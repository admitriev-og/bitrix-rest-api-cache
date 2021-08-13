<?php

namespace BitrixRestApiCache\Redis;

use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Data\CacheEngineNone;

class ExtendCache extends Cache
{
    public static function createCacheEngine($params = [])
    {
        $cacheEngine = parent::createCacheEngine();
        $isNone = ($cacheEngine instanceof CacheEngineNone);
        if (!$isNone) {
            return $cacheEngine;
        }

        $config = Configuration::getValue('cache');
        $cacheType = $config['type'];
        if ($cacheType == 'redis') {
            return new RedisCacheEngine();
        }

        return $cacheEngine;
    }
}
