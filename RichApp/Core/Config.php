<?php
namespace Core;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Config {
    
    /*
     * 
     * Configure db settings here
     * 
     */
    static private $db_user = 'root';
    static private $db_pass = 'root';
    static private $db_name = 'richapp';
    static private $db_host = 'localhost';
    
    static public $config   = array();
    
    static public $config_instance = null;
    
    private function __construct( ){}
    
    static private function load()
    {
        self::$config['db']['db_user'] = self::$db_user;
        self::$config['db']['db_pass'] = self::$db_pass;
        self::$config['db']['db_name'] = self::$db_name;
        self::$config['db']['db_host'] = self::$db_host;
        
        if( is_null( self::$config_instance ) )
        {
            self::$config_instance = new \Core\Config();
        }
    }
    
    static public function instance()
    {
        self::load();
        return self::$config_instance;
    }
    
    static public function configItem( $item, $sub = '' )
    {
        if( array_key_exists( $item, self::$config) )
        {
            return self::$config[$item];
        }
    }
    
    static public function addConfigItem($name, $value = '', $class='config')
    {
        self::$config[$class][$name] = $value;
    }
       
}