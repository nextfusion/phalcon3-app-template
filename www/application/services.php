<?php

use Phalcon\Mvc\Url as UrlManager,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter,
    Phalcon\Session\Adapter\Files as SessionAdapter,
    Phalcon\Logger\Adapter\File as LogFile,
    Phalcon\Mvc\View\Engine\Volt as VoltEngine,
    Phalcon\Mvc\View,
    Phalcon\Mvc\Dispatcher;

/* ==================================================
 * กำหนด Url เบื้องต้น
 * ================================================== */

$config = $this->config;

$manager->set('url', function () use ($config) {
    $url = new UrlManager();
    $url->setBaseUri($config->application->baseUri);
    return $url;
}, true);
       
$manager->set('dispatcher', function () use ($manager) {
    $eventsManager = $manager->getShared('eventsManager');
    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;
}); 

/* ==================================================
 * ตั้งค่าการเชื่อมต่อฐานข้อมูล
 * ================================================== */

$manager->set('db', function () use ($config) {
    return new DbAdapter([
        'host'      => $config->database->host,
        'username'  => $config->database->username,
        'password'  => $config->database->password,
        'dbname'    => $config->database->dbname,
        'options'   => [ PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $config->database->charset ]
    ]);
});

$manager->set('view', function () use ($config) {
            
    $view = new View();
    $view->setViewsDir(__DIR__ . '/views/');
    $view->setLayoutsDir(sprintf('%s/%s/', $config->theme->themesDir, $config->theme->themeDefault));
    $view->setTemplateAfter('layouts/' . $config->theme->layoutDefault);

    /* สร้างโฟล์เดอร์เก็บไฟล์ cache */
    // $cacheDir = sprintf('%s/%s/', RUNTIME_PATH, $config->application->cacheDir);
    // if (!is_dir($cacheDir)) { mkdir($cacheDir); }

    $view->registerEngines([
        '.phtml' => function ($view, $di){
            $volt = new VoltEngine($view, $di);
            $volt->setOptions([
                'compiledPath' => sprintf('%s/%s/caches/', RUNTIME_PATH, $config->application->cacheDir),
                'compiledSeparator' => '_'
            ]);
            return $volt;
        },
    ]);

    return $view;

});
        
/* ==================================================
 * ตั้งค่าการเปิดใช้งาน Session
 * ================================================== */

$manager->set('session', function () {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});

/* ==================================================
 * กำหนดค่ามาตรฐาน
 * ================================================== */

/* ดึงข้อมูล Config */
$manager->set('config', function () {
    return $this->config;
}, true);
$manager->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/* เปิดใช้งานระบบเก็บข้อมูล Log */
$manager->set('logger', function () use ($config) {
    $monthNow = date('Y-m-d',time());
    $pathLog = sprintf('%s/%s/%s.log', APPLICATION_PATH, $config->application->logsDir, $monthNow);
    $logger = new LogFile($pathLog);
    return $logger;
});
        
/* ==================================================
 * ลงทะเบียน Component & Librarys ที่เราสร้างขึ้นเอง
 * ================================================== */

/* ดึงข้อมูลหลัก เช่น ข้อมูลการตั้งค่าต่าง ๆ */
$manager->set('base', function(){
    return new CBaseSystem(); // ex. $this->base->xxxx;
});
