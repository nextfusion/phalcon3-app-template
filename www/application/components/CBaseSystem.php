<?php

use Phalcon\Mvc\User\Component;

class CBaseSystem extends Component {
    
    /* ===========================================================
     * Web Site
     * =========================================================== */
    
    public static $version = '1.0.0';
    public static $lastUpdate = '2018-08-15 16:48';
    public static $pageTitle = 'Web Application | Phalcon Framework 3.4.4';
    
    public static $baseUrl  = 'http://localhost';
    public static $urlImage = 'http://localhost/images/';
    
    /* ===========================================================
     * Access Control List (ACL)
     * =========================================================== */
    
    public $securityStart    = false;
    public $securityRealtime = false;
    
}