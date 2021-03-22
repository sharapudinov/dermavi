<?php

namespace Greensight\Geo\Providers;

use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Web\HttpClient;
use CBXArchive;
use RuntimeException;

class GeoLite2Provider implements ProviderInterface
{
    /**
     * @param string $ip
     * @param array $params
     * @return array
     */
    public function search($ip, $params = [])
    {
        if (empty($params['cache'])) {
            return $this->searchWithoutCache($ip, $params);
        }
        $data = array();
        $cacheManager = Cache::createInstance();

        $ttl = 60 * $params['cache'];
        if ($cacheManager->initCache($ttl, $ip, '/greensight.geo/geolite2_data')) {
            $data = $cacheManager->getVars();
        } elseif ($cacheManager->startDataCache()) {
            $data = $this->searchWithoutCache($ip, $params);
            $cacheManager->endDataCache($data);
        }

        return $data;
    }
    
    /**
     * @param string $ip
     * @param array $params
     * @return array
     * @throws RuntimeException
     */
    public function searchWithoutCache($ip, $params = [])
    {
        $longIp = ip2long($ip);
        if ($longIp === false) {
            return [];
        }

        $sql = "
            SELECT
              b.id, c.country_name, b.city_id, c.city, c.region, b.lat, b.lng, c.time_zone, b.postal_code, c.country_iso_code
            FROM
              greensight_geo_geolite2_base b
            LEFT OUTER JOIN greensight_geo_geolite2_cities c
            ON b.city_id = c.city_id
            WHERE
              long_ip1<='$longIp' AND long_ip2>='$longIp'
            LIMIT 1
        ";
        $geoData = Application::getConnection()->query($sql)->fetch();

        return $this->normalizeSearchResult($geoData);
    }
    
    
    private function geobasePath($path = '')
    {
        return realpath(__DIR__ . '/../../geobase/') . '/' . $path;
    }

    private function cidrFilePath()
    {
        return $this->geobasePath('GeoLite2-City-Blocks-IPv4.csv');
    }
    
    private function citiesFilePath()
    {
        return $this->geobasePath('GeoLite2-City-Locations-ru.csv');
    }
    
    /**
     * Обновляет в БД таблицы geoip беря информацию из файлов.
     *
     * @param bool $disablePhpLimits
     */
    public function updateDatabase($disablePhpLimits = true)
    {
        if ($disablePhpLimits) {
            @set_time_limit(0);
            @ignore_user_abort(true);
            ini_set('memory_limit', '4G');
        }

        $this->deleteDatabaseFiles();
    
        $this->downloadDatabaseFiles();

        $this->updateCidrDataFromFile($this->cidrFilePath());
        $this->updateCitiesFromFile($this->citiesFilePath());

        // once again
        $this->deleteDatabaseFiles();
    }

    protected function downloadDatabaseFiles()
    {
        $url = "http://geolite.maxmind.com/download/geoip/database/GeoLite2-City-CSV.zip";
        $httpTools = new HttpClient();
        $archiveDestination = $this->geobasePath("GeoLite2-City-CSV.zip");
        $res = $httpTools->download($url, $archiveDestination);
        if (!$res) {
            throw new RuntimeException("Cannot download file " . $url);
        }

        $archive = CBXArchive::GetArchive($archiveDestination, "ZIP");
        if (!$archive->Extract([
            "remove_all_path" => true,
            "by_preg" => "/(GeoLite2-City-Blocks-IPv4|GeoLite2-City-Locations-ru)/",
            "add_path" => $this->geobasePath()
        ])) {
            throw new RuntimeException("Cannot extract archive " . $archiveDestination);
        }
    }

    public function updateCidrDataFromFile($src)
    {
        if (!file_exists($src)) {
            throw new RuntimeException('Geo: file ' . $src . ' does not exist');
        }

        $connection = Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $connection->truncateTable('greensight_geo_geolite2_base');

        foreach ($this->readFile($src) as $i => $row) {
            if (!$i) { 
                $keys = $row;
                continue;
            }

            $row = array_combine($keys, $row);
            $arIP = $this->cidrconv($row['network']);
    
            foreach ($row as $field => $value) {
                $row[$field] = $sqlHelper->forSql($value);
            }

            try {
                $connection->query("
                    INSERT INTO `greensight_geo_geolite2_base` 
                    (`long_ip1`, `long_ip2`, `ip1`, `ip2`, `country`, `city_id`, `network`, `lat`, `lng`, `postal_code`, `accuracy_radius`) 
                    VALUES(
                        '{$arIP['longip']['start']}', 
                        '{$arIP['longip']['end']}', 
                        '{$arIP['ip']['start']}', 
                        '{$arIP['ip']['end']}', 
                        '{$row['registered_country_geoname_id']}', 
                        '{$row['geoname_id']}', 
                        '{$row['network']}', 
                        '{$row['latitude']}', 
                        '{$row['longitude']}', 
                        '{$row['postal_code']}', 
                        '{$row['accuracy_radius']}'
                    )");
            } catch (\Exception $e) {
                echo $e->getMessage();
                continue;
            }
        }
    }

    private function cidrconv($net) {
        $start = strtok($net,"/");
        $n = 3 - substr_count($net, ".");
        if ($n > 0)
        {
            for ($i = $n;$i > 0; $i--)
                $start .= ".0";
        }
        $bits1 = str_pad(decbin(ip2long($start)), 32, "0", STR_PAD_LEFT);
        $net = (1 << (32 - substr(strstr($net, "/"), 1))) - 1;
        $bits2 = str_pad(decbin($net), 32, "0", STR_PAD_LEFT);
        $final = "";
        for ($i = 0; $i < 32; $i++)
        {
            if ($bits1[$i] == $bits2[$i]) $final .= $bits1[$i];
            if ($bits1[$i] == 1 and $bits2[$i] == 0) $final .= $bits1[$i];
            if ($bits1[$i] == 0 and $bits2[$i] == 1) $final .= $bits2[$i];
        }
        $end = bindec($final);

        return [
            'ip' => ['start' => $start, 'end' => long2ip($end)],
            'longip' => ['start' => ip2long($start), 'end' => $end],
        ];
    }

    public function updateCitiesFromFile($src)
    {
        if (!file_exists($src)) {
            throw new RuntimeException('Geo: file ' . $src . ' does not exist');
        }

        $connection = Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $connection->truncateTable('greensight_geo_geolite2_cities');

        foreach ($this->readFile($src) as $i => $row) {
            if (!$i) { 
                $keys = $row;
                continue;
            }

            $row = array_combine($keys, $row);
            foreach ($row as $field => $value) {
                $row[$field] = $sqlHelper->forSql($value);
            }

            try {
                $connection->query("
                    INSERT INTO `greensight_geo_geolite2_cities` 
                    (`city_id`, `city`, `region`, `locale_code`, `continent_code`, `continent_name`, `country_iso_code`,`country_name`,`time_zone`) 
                    VALUES(
                        '{$row['geoname_id']}',
                        '{$row['city_name']}', 
                        '{$row['subdivision_1_name']}', 
                        '{$row['locale_code']}', 
                        '{$row['continent_code']}', 
                        '{$row['continent_name']}', 
                        '{$row['country_iso_code']}', 
                        '{$row['country_name']}', 
                        '{$row['time_zone']}'
                    )");
            } catch (\Exception $e) {
                echo $e->getMessage();
                continue;
            }
        }
    }

    public function readFile($path)
    {
        if (!file_exists($path)) {
            throw new RuntimeException('Geo: file ' . $path . ' does not exist');
        }
        
        $fp = fopen($path, 'rb');
        
        while(($line = fgetcsv($fp)) !== false) {
            yield $line;
        }
    
        fclose($fp);
    }
    
    private function deleteDatabaseFiles()
    {
        @unlink($this->cidrFilePath());
        @unlink($this->citiesFilePath());
        @unlink($this->geobasePath("GeoLite2-City-CSV.zip"));

        if (file_exists($this->cidrFilePath()) || file_exists($this->citiesFilePath()) || file_exists($this->geobasePath("GeoLite2-City-CSV.zip"))) {
            throw new RuntimeException("Cannot delete database source files");
        }
    }

    private function normalizeSearchResult($result)
    {
        return [
            'id' => $result['id'],
            'city_id' => $result['city_id'],
            'country_name' => $result['country_name'],
            'city_name' => $result['city'],
            'region_name' => $result['region'],
            'district_name' => '',
            'country_code' => $result['country_iso_code'],
            'city_code' => null,
            'region_code' => null,
            'lat' => (string) $result['lat'],
            'lon' => (string) $result['lng'],
            'zip' => $result['postal_code'],
            'isp' => null,
            'timezone' => $result['time_zone'],
        ];
    }
}
