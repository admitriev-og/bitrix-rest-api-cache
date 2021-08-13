<?php
return array(
    'cache' => array(
        'value' => array(
            'type' => [
                'class_name' => 'BitrixRestApiCache\Redis\RedisCacheEngine',
                'required_file' => '/local/vendor/bitrix-rest-api-cache/src/Redis/RedisCacheEngine.php'
            ],
            'redis' => array(
                'host' => 'redis',
                'port' => '6379',
            ),
            'sid' => $_SERVER["DOCUMENT_ROOT"] . "#01"
        ),
    ),
);
?>
