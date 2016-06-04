<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core;

use Slim\Slim;
use RichApp\Core;

/**
 * Description of App
 *
 * @author richardtestani
 */
class App extends Slim {
    
    use RouterTrait;
    
    public function __construct($setup, $config)
    {
                
        //default timezone
        date_default_timezone_set('America/New_York');
        
        $this->app = new Slim();
        
        //apply configuration
        foreach($config as $k => $v)
        {
            $this->app->config($k, $v); 
        }
        
        /*
         * Application Services
         */
        Services::appendService('request', $this->app->request);
        Services::appendService('response', $this->app->response);
        Services::appendService('app', $this->app);

        
        //apply routing
        $this->routes($setup);
        
        if(!$setup)
        {
            //run installer
            $this->app->get('(/)', function()
            {
                $this->app->config('setup', false);
                $this->app->redirect('/installer/welcome');
            });
        }
        
        $this->app->run();
  
    }
    
    public function addConfig($name, $value)
    {
        $this->app->config($name, $value);
    }
    
    public function getConfig($name)
    {
        return $this->app->config($name);
    }
    
    public function boot()
    {
       
        
        //execute events
    }
    
}
