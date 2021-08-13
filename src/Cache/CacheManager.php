<?php

namespace BitrixRestApiCache\Cache;

use Symfony\Component\HttpFoundation\Request;

class CacheManager
{
    const SHORT_CACHE_TIME = 60;
    const DEFAULT_CACHE_TIME = 3600 * 24;

    public static function getCacheId(Request $request)
    {
        $getParams = $request->query->all();
        ksort($getParams);

        // JSON_NUMERIC_CHECK
        $cacheId = md5(json_encode($getParams, JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT));

        $schemeAndHttpHost = $request->getSchemeAndHttpHost();
        if (isset($_ENV['HOST']) && isset($_ENV['SCHEME']) && $_ENV['HOST'] && $_ENV['SCHEME']) {
            $schemeAndHttpHost = $_ENV['SCHEME'] . "://" . $_ENV['HOST'];
        }
        //$cacheId = $request->getSchemeAndHttpHost().$request->getPathInfo()."_".$cacheId;
        $cacheId = $schemeAndHttpHost . $request->getPathInfo() . "_" . $cacheId;
        $cacheId = str_replace("@", "_", $cacheId);
        $cacheId = str_replace(",", "_", $cacheId);
        $cacheId = str_replace(";", "_", $cacheId);
        $cacheId = str_replace(".", "_", $cacheId);
        $cacheId = str_replace(":", "_", $cacheId);
        $cacheId = str_replace("/", "_", $cacheId);

        return $cacheId;
    }

    public static function start($path = '/')
    {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->StartTagCache($path);
    }

    public static function end()
    {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->EndTagCache();
    }

    public static function addTag($tag)
    {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->RegisterTag($tag);
    }

    public static function clearByTag($tag)
    {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag($tag);
    }
}
