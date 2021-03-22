<?php

namespace Greensight\Geo\Providers;

use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Web\HttpClient;
use CBXArchive;
use RuntimeException;

class IpGeoBaseProvider implements ProviderInterface
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
        if ($cacheManager->initCache($ttl, $ip, '/greensight.geo/ip_geobase_data')) {
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
              b.id, b.country, b.city_id, c.city, c.region, c.district, c.lat, c.lng
            FROM
              greensight_geo_ipgeobase_base b
            LEFT OUTER JOIN greensight_geo_ipgeobase_cities c
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
        return $this->geobasePath('cidr_optim.txt');
    }
    
    private function citiesFilePath()
    {
        return $this->geobasePath('cities.txt');
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
        $url =  "http://ipgeobase.ru/files/db/Main/geo_files.zip";
        
        $httpTools = new HttpClient();
        $archiveDestination = $this->geobasePath("geo_files.zip");
        $res = $httpTools->download($url, $archiveDestination);
        if (!$res) {
            throw new RuntimeException("Cannot download file " . $url);
        }

        $archive = CBXArchive::GetArchive($archiveDestination, "ZIP");
        if (!$archive->Extract(["by_name" => ['cidr_optim.txt', 'cities.txt'], "add_path" => $this->geobasePath()])) {
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
        $connection->truncateTable('greensight_geo_ipgeobase_base');

        $pattern = '#(\d+)\s+(\d+)\s+(\d+\.\d+\.\d+\.\d+)\s+-\s+(\d+\.\d+\.\d+\.\d+)\s+(\w+)\s+(\d+|-)#';
        foreach ($this->readFile($src) as $row) {
            $row = iconv('windows-1251', 'utf-8', $row);
            if (preg_match($pattern, $row, $out)) {
                foreach ($out as $index => $value) {
                    $out[$index] = $sqlHelper->forSql($value);
                }
                $connection->query("INSERT INTO `greensight_geo_ipgeobase_base` (`long_ip1`, `long_ip2`, `ip1`, `ip2`, `country`, `city_id`) VALUES('$out[1]', '$out[2]', '$out[3]', '$out[4]', '$out[5]', '$out[6]')");
            }
        }
    }

    public function updateCitiesFromFile($src)
    {
        if (!file_exists($src)) {
            throw new RuntimeException('Geo: file ' . $src . ' does not exist');
        }

        $connection = Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $connection->truncateTable('greensight_geo_ipgeobase_cities');

        $pattern = '#(\d+)\s+(.*?)\t+(.*?)\t+(.*?)\t+(.*?)\s+(.*)#';
        foreach ($this->readFile($src) as $row) {
            $row = iconv('windows-1251', 'utf-8', $row);
            if (preg_match($pattern, $row, $out)) {
                foreach ($out as $index => $value) {
                    $out[$index] = $sqlHelper->forSql($value);
                }
                $connection->query("INSERT INTO `greensight_geo_ipgeobase_cities` (`city_id`, `city`, `region`, `district`, `lat`, `lng`) VALUES('$out[1]', '$out[2]', '$out[3]', '$out[4]', '$out[5]', '$out[6]')");
            }
        }
    }

    public function readFile($path)
    {
        if (!file_exists($path)) {
            throw new RuntimeException('Geo: file ' . $path . ' does not exist');
        }
        
        $fp = fopen($path, 'rb');
        
        while(($line = fgets($fp)) !== false) {
            yield rtrim($line, "\r\n");
        }
    
        fclose($fp);
    }
    
    private function deleteDatabaseFiles()
    {
        @unlink($this->cidrFilePath());
        @unlink($this->citiesFilePath());
        @unlink($this->geobasePath("geo_files.zip"));

        if (file_exists($this->cidrFilePath()) || file_exists($this->citiesFilePath()) || file_exists($this->geobasePath("geo_files.zip"))) {
            throw new RuntimeException("Cannot delete database source files");
        }
    }

    private function normalizeSearchResult($result)
    {
        return [
            'id' => $result['id'],
            'city_id' => $result['city_id'],
            'country_name' => null,
            'city_name' => $result['city'],
            'region_name' => $result['region'],
            'district_name' => $result['district'],
            'country_code' => $result['country'],
            'city_code' => null,
            'region_code' => null,
            'lat' => (string) $result['lat'],
            'lon' => (string) $result['lng'],
            'zip' => null,
            'isp' => null,
            'timezone' => null,
        ];
    }
}
