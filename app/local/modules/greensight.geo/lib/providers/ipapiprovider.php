<?php

namespace Greensight\Geo\Providers;

use Bitrix\Main\Data\Cache;

class IpApiProvider implements ProviderInterface
{
    public function search($ip, $params = [])
    {
        if (empty($params['cache'])) {
            return $this->searchWithoutCache($ip, $params);
        }

        $data = [];
        $cacheManager = Cache::createInstance();

        $ttl = 60 * $params['cache'];
        if ($cacheManager->initCache($ttl, $ip, '/greensight.geo/ip_api_data')) {
            $data = $cacheManager->getVars();
        } elseif ($cacheManager->startDataCache()) {
            $data = $this->searchWithoutCache($ip, $params);
            $cacheManager->endDataCache($data);
        }

        return $data;
    }

    public function searchWithoutCache($ip, $params = [])
    {
        $timeout = (int) $params['timeout'];
        $timeout = $timeout ?: 10;
        $ctx = stream_context_create(['http' => ['timeout' => $timeout]]);
        $content = @file_get_contents("http://ip-api.com/php/{$ip}?lang=ru", false, $ctx);
        if (!$content) {
            return [];
        }
        
        return $this->normalizeSearchResult(unserialize($content));
    }
    
    private function normalizeSearchResult($result)
    {
        return [
            'id' => null,
            'city_id' => $result['city'], // идентификатора города нам не отдали, но он нам нужен, поэтому просто заполним названием города.
            'country_name' => $result['country'],
            'city_name' => $result['city'],
            'region_name' => $result['regionName'],
            'district_name' => null,
            'country_code' => $result['countryCode'],
            'city_code' => null,
            'region_code' => $result['region'],
            'lat' => (string) $result['lat'],
            'lon' => (string) $result['lon'],
            'zip' => $result['zip'],
            'isp' => $result['isp'],
            'timezone' => $result['timezone'],
        ];
    }
    
    public function updateDatabase($disablePhpLimits = true)
    {
        return;
    }
}
