<?php
namespace RichApp\Core;

use League\Plates;
use League\Plates\Engine;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
use RichApp\Model;
use RichApp\Library;
use rcastera\Browser\Session\Session;


/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Controller {
    
    protected $data = array();
    protected $app;
    protected $request;
    protected $reponse;
    protected $filesystem;
    protected $theme;
	protected $script;
	protected $css;
    protected $package_config = array();
	protected $user;
	protected $events;
    
    use URLTrait;
	use Library\Events;

    
    public function __construct($setup=true)
    {
		
        $this->date = new \DateTime();
		$this->session = new Session();

        /*
         * update admin login
         */
        Auth::initialize();
        /*
         * setup services provided for all controllers
         */
        $services = Services::importServices();
		
        
        if( $this->system = new \RichApp\Core\Settings($setup) )
        {
            
            foreach($services as $n => $s)
            {
                $this->{$n} = $s;
            }
			
            $theme_settings = $this->system->get('theme');

            //Are we in the admin
            if( $this->isAdmin() )
            {
				$nav = $this->app->config('Controllers');
				
				$default_classes = [
							'app',
							'theme',
							'image',
							'pages'
						];
				
						foreach($default_classes as $c)
						{
							$nav['settings']['actions'][$c] = array(
								'icon' => 'fa fa-gear',
								'route' => '/admin/settings/edit/'.$c
							);
						}
				$this->setData('nav', $nav);
				$this->setData('dashboard', $this->app->config('Dashabord'));
                $path = APPPATH . DS . 'Admin' . DS . 'Themes' . DS . 'default';
            }
            else
            {
                $package = $theme_settings['current_theme'];
                $path = PUBPATH . DS . 'themes' . DS . $package;
				
				//we are logged in and on the login page
				$user_settings = $this->system->get('users');
				//check if there is a user session stored
				if( array_key_exists($user_settings['user_session'], $_SESSION) && Auth::isAuthenticated())
				{
					Auth::updateActivity();
					$this->user = unserialize( Auth::get($user_settings['user_session']) );
					$this->setData('userdata', $this->user);
				}
				else
				{
					//create a default guest
					$this->user = array(
						'id' => '999999999999999999',
						'username' => 'guest',
						'role' => 100
					);
					Auth::set( $user_settings['user_session'], serialize($this->user) );
					$this->setData('userdata', $this->user);
				}
            }
        }
		
        
        //Are we installing
        if( $this->isInstaller() )
        {
            $path = APPPATH . DS . 'Installer' . DS . 'views';
        }
		
        $this->filesystem = new Filesystem(new Adapter(APPROOT));
        $this->theme = new Theme($path);
        $app_settings = $this->system->get('app');
        
        $package_path = APPPATH . DS . $app_settings['package'];
  
        if(file_exists($package_path))
        {
            if(file_exists($package_path . DS . 'Config.php'))
            {
                $this->package_config = require_once $package_path . DS . 'Config.php';                
            }
        }
        
		$this->setLinkBasePath();
		$this->setRedirectLink();
        
    }
    
    public function setData($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function render($file)
    {
        $data = $this->getData();
		$data['script'] = $this->getAssets('script');
		$data['css'] = $this->getAssets('css');
        echo $this->theme->render($file, $data);
    }
	
	public function addAsset($type, $path)
	{
		
		$this->{$type}[]=$path;
	}
	
	private function getAssets($ass)
	{
		return $this->{$ass};
	}
	
}