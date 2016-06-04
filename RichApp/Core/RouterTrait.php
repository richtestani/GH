<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core;
use \RichApp\Controller;

/**
 * Description of Router
 *
 * @author richardtestani
 */
trait RouterTrait {
    
    public function packageRoutes()
    {
        //add your routes

    }
    
    public function routes($setup)
    {
        $this->packageRoutes();
        //installer routes
        $this->app->get('/installer/welcome', function()
        {
           $controller = new \RichApp\Installer\Installer();
           $controller->welcome();
           
        });
        
        $this->app->get('/installer/step1', function()
        {
            $controller = new \RichApp\Installer\Installer();
           $controller->step1();
        });
        
        $this->app->post('/installer/step2', function()
        {
            $controller = new \RichApp\Installer\Installer();
            $controller->step2();
        });
        
        $this->app->get('/installer/step2', function()
        {
            $controller = new \RichApp\Installer\Installer();
            $controller->step2();
        });
        
        $this->app->post('/installer/step3', function()
        {
            $controller = new \RichApp\Installer\Installer();
            $controller->step3();
        });
        
        $this->app->post('/installer/finalStep', function()
        {
            $controller = new \RichApp\Installer\Installer();
            $controller->finalStep();
        });
        
        //admin group
        $this->app->group('/admin', function()
        {
            //admin home
            $this->app->get('/', function()
            {
                $controller = new Controller\Admin();
                $controller->index();
                
            });
            
            //admin login
            $this->app->get('/login', function()
            {
                if(isset($_SESSION['LOGIN_ACTIVE']))
                {
                    $this->app->redirect('/admin');
                }
                $controller = new Controller\Admin();
                
                $controller->login();
                //$data = $controller->getData();
            });
			
            $this->app->get('/logout', function()
            {
                if(isset($_SESSION['LOGIN_ACTIVE']))
                {
                    $this->app->redirect('/admin');
                }
                $controller = new Controller\Admin();
                
                $controller->logout();
                //$data = $controller->getData();
            });
            
            $this->app->post('/logincheck', function()
            {
                
                $username = $this->app->request->post('username');
                $password = $this->app->request->post('password');
                
                $controller = new Controller\Admin();
                if( $controller->checkLogin($username, $password) )
                {
                    $this->app->redirect('/admin');
                }
                else
                {
                    $this->app->redirect('/admin/login');
                }
            });
			
			$this->app->get('/lostpass', function()
			{
                $controller = new Controller\Admin();
				$controller->lostPassword();
			});
			
			$this->app->post('/passwordreset(/new)', function()
			{
				echo 'here';
				unset($_SESSION['action']);
				print_r($_POST).'<br>';
                $controller = new Controller\Admin();
				$controller->lostPassword();
			});
			
            
            /* 
             * Admin CRUD
             */
            $this->app->get('/:controller(/)', function($controller)
            {
                $class = "\RichApp\Controller\Admin\\".ucwords($controller);
                $Controller = new $class();
                $Controller->index();
            });
            
            
            $this->app->get('/pages/create', function()
            {
                $controller = new Controller\Admin\Pages();
                $controller->create();
            });
            
            $this->app->get('/pages/edit/:id', function($id)
            {
                $controller = new Controller\Admin\Pages();
                $controller->edit($id);
            });
            
            $this->app->post('/pages/save(/:id)', function($id=0)
            {
                $controller = new Controller\Admin\Pages();
                $controller->save($id);
            });
			
            
            $this->app->get('/:controller(/:method(/(:args)))', function($controller, $method, $args=array())
            {
                $class = "\RichApp\Controller\Admin\\".ucwords($controller);
                $Controller = new $class();
                $Controller->$method($args);
            });
            
            $this->app->post('/:controller/save(/(:id))', function($controller, $id=0)
            {
                $class = "\RichApp\Controller\Admin\\".ucwords($controller);
                $Controller = new $class();
                $Controller->save($id);
            });
            
            /* Panel data */
            $this->app->post('/:item/panel(/:id)', function($item, $id=0)
            {
                $className = '\RichApp\Controller\Admin\\'.ucwords($item);
                $controller = new $className();
                $controller->json_panel($id);
            });
            
            
        });
        
        //public routes
        if($setup)
        {
            $this->app->get('/', function()
            {
                $pages = new \RichApp\Controller\Pages();
                $pages->index();

            });
        }
		
		$this->app->get('/user/register(/)', function()
		{
            $controller = new Controller\Users();
			$controller->register();
           
		});
		
		$this->app->post('/user/save', function()
		{
            $controller = new Controller\Users();
			$controller->save();
           
		});
		
		$this->app->post('/user/login', function()
		{
            $controller = new Controller\Users();
			$controller->login();
           
		});
        
		$this->app->get('/tags/:tag(/)', function($tag)
		{
            $controller = new Controller\Tags();
            $controller->index($tag);
		});
		
		$this->app->get('/categories/:type/:slug', function($type, $slug)
		{
            $controller = new Controller\Categories();
            $controller->index($type, $slug);
		});
		
		$this->app->get('/user/(profile/):username', function($username){

            $controller = new Controller\Users();
			$controller->profile($username);
			
		});
        
        $this->app->get('/:page', function($page)
        {
            $pages = new \RichApp\Controller\Pages();
            $pages->single($page);
        });
    }
    
}
