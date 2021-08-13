# Установка либы
Для подключения кеша редиса к битриксу нужно: 

0. Установить зависимость composer require vsavritsky/bitrix-rest-api-cache
1. В файл bitrix/php_interface/dbconn.php подключить автолоадер
   require($_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/vendor/autoload.php");
2. Добавить настройки подключения редиса в файл bitrix/.settings_extra.php (пример в папке examples)
3. Вызов кеша в контроллере
``` 
$cache = new PhpCache($this->getRequest());
$cacheResult = $cache->init();
if (!$cacheResult) {
    // кешируемый вызов
    // $result = ['test' => 1];
    if ($result) {
        $cache->addTag('test');
        $cacheResult = new TestResponse();
        $cache->cache($result);
    }
}
```