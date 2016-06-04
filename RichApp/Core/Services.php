<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core;

/**
 * Description of Services
 *
 * @author richardtestani
 */
class Services {
    
    static protected $services = array();
    
    //put your code here
    static public function importServices()
    {
        require_once APPPATH . DS . 'Core' . DS . 'DB' . DS . 'RedBean' . DS . 'rb.php';
        return self::$services;
    }
    
    static public function appendService($name, $service)
    {
        self::$services[$name] = $service;
    }
}
