<?php

/* ============================================================
 * Environment
 * ============================================================ */

define('Dev_Mode', true);
define('Phalcon_Debug', true);

if (Dev_Mode){
    error_reporting(-1);
    ini_set('display_errors', 1);
} else {
    error_reporting(0); 
    ini_set('display_errors','On');
}

date_default_timezone_set('Asia/Bangkok');

use Phalcon\Mvc\Application as WebApplication,
    Phalcon\DI\FactoryDefault as ApplicationManager,
    Phalcon\Config\Adapter\Ini as ConfigIni,
    Phalcon\Debug as ModeDebug;

/* ============================================================
 * Application
 * ============================================================ */

class Application extends WebApplication {
    
    private $config;
    private $manager;
    
    public function __construct() {
        $this->config = new ConfigIni(APPLICATION_PATH . '/config/main.ini'); // Read the configuration
    }
    
    private function _registerServices(){
        $debug = new ModeDebug();
        $debug->listen(Phalcon_Debug);
        $this->manager = new ApplicationManager();
        $this->include_file('autoloader.php');      // Autoload Service
        $this->include_file('routers.php');         // Read Router
        $this->include_file('services.php');        // Read services
        $this->setDI($this->manager);
    }
    
    public function run() {
        try { 
            $this->_registerServices(); 
            echo $this->handle()->getContent();
        } catch(\Phalcon\Exception $e) {
            echo 'PhalconException: ' . $e->getMessage(); exit();
        } catch (\Exception $e) {
            echo 'PhpException: ' . $e->getMessage(); exit();
        }
    }
    
    public function include_file($file = null){
        $pathFile = sprintf('%s/%s', APPLICATION_PATH, $file);
        if (!empty($pathFile) && file_exists($pathFile)){
            $manager = $this->manager;
            include_once $pathFile;
            $this->manager = $manager;
        }
        return false;
    }
    
}

/* ============================================================
 * Default
 * ============================================================ */

// Path Root
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)));
    define('RUNTIME_PATH', ROOT_PATH . '/runtime');
    define('APPLICATION_PATH', ROOT_PATH . '/application');
    define('IMAGE_PATH', ROOT_PATH . '/public/images');
}

/* ============================================================
 * Run Application
 * ============================================================ */

$application = new Application();
$application->run();