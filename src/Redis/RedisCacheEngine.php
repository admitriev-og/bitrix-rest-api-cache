<?php

namespace BitrixRestApiCache\Redis;

use Bitrix\Main\Config;
use Bitrix\Main\Data\CacheEngineRedis as BCacheEngineRedis;
use BitrixRestApi\Responser\Response\AbstractResponse;
use Predis\Client;

class RedisCacheEngine extends BCacheEngineRedis
{
    private static $baseDirVersion = [];

    /**
     * Redis client
     *
     * @var Client
     */
    private $client;

    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param array|null $options
     */
    public function __construct($options = null)
    {
        $v = Config\Configuration::getValue("cache");

        if (!$options && isset($v["redis"])) {
            $options = $v["redis"];
        }

        $this->client = new Client($options);

        parent::__construct($options);
    }

    public function clean($baseDir, $initDir = false, $filename = false)
    {
        if ($baseDir == '/bitrix/cache/' && !$initDir) {
            $key = '*';
            $keyList = $this->getClient()->keys($key);
            if (!empty($keyList)) {
                $this->getClient()->del($keyList);
            }
        }

        if ($initDir) {
            $r = $this->getClient()->get($initDir);
            if ($r) {
                $this->getClient()->del([$initDir]);
            }
        }

        parent::clean($baseDir, $initDir, $filename);
    }

    public function write($arAllVars, $baseDir, $initDir, $filename, $TTL)
    {
        $response = $arAllVars['VARS']['result'];
        if (is_a($response, AbstractResponse::class)) {
            $cacheId = $arAllVars['VARS']['result']->cacheId;

            if ($cacheId) {
                $this->getClient()->set($cacheId, serialize(json_encode($response->jsonSerialize())));
                $this->getClient()->expire($cacheId, $TTL);
            }
        }

        parent::write($arAllVars, $baseDir, $initDir, $filename, $TTL);
    }
}
