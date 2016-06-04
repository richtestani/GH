<?php
namespace RichApp\Controller;
use RichApp\Interfaces;
use RichApp\Library;
use RichApp\Core\Controller;
use RichApp\Core\Auth;
use RichApp\Model;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Admin extends Controller implements Interfaces\iController {
    
    protected $user;
    protected $logged_in;
	
    public function __construct()
    {
        parent::__construct();

        $this->users = new Model\Users();
		$this->pagination = new Library\Pagination();
		$this->user = null;
		$this->setData('action', null);
		
		$this->pagination->setNumPagesShown(10);
		
		$this->setData('panels', '');
		
		//we are logged in and on the login page
		if(array_key_exists('ra_user', $_SESSION) && Auth::isAuthenticated())
		{
			Auth::updateActivity();
			$this->user = Auth::get('ra_user');
			if($this->isLogin())
			{
				$this->app->redirect('/admin');
			}
		}
		else
		{
			
			if(!$this->isLogin())
			{
				
				if (filter_input(INPUT_SERVER, 'QUERY_STRING') == '/admin/lostpass') {
					Auth::set('action', 'reset');
				}
				
				if (filter_input(INPUT_SERVER, 'QUERY_STRING') == '/admin/passwordreset') {
					$email = $this->request->post('email');
					$user = $this->users->getOne('email', $email);

					if(!empty($user)) {
						Auth::set('updateuser', $email);
						Auth::set('action', 'passwordform');
					} else {
						Auth::set('action', 'reset');
					}				
				}
				
				if (filter_input(INPUT_SERVER, 'QUERY_STRING') == '/admin/passwordreset/new') {
					//update user password
					$email = Auth::get('updateuser');
					$user = $this->users->getOne('email', $email);
					$password = Auth::generatePasswordHash($this->request->post('newpass'));
					$u = [];
					foreach($user as $k => $v) {
						$u[$k] = $v;
					}
					$u['password'] = $password;
					$this->users->update($user);
				}
				
				$this->app->redirect('/admin/login');
				
			}
			
		}
		
		$this->setData('package_config', $this->package_config);
    }
    
    public function index()
    {
	    $widgets = array();
		if(array_key_exists('Dashboard', $this->package_config))
		{
			$dashboard = (array_key_exists('modules', $this->package_config['Dashboard'])) ? $this->package_config['Dashboard']['modules'] : array();
			
			foreach($dashboard as $k => $m)
			{
				$path = 'RichApp\\'.str_replace("/", "\\", key($m));
				$method = $m[key($m)];
				$widgets[$k] = $this->loadDashboard($path, $method);
			}
		}
	   $this->setData('dashboardWidgets', $widgets);
       $this->setData('page_title', 'Dashboard');
       
       $this->render('index');
    }

    
    public function login()
    {
		$action = Auth::get('action');
		$this->setData('action', $action);
		$this->render('login');
    }
    
    public function checkLogin($username, $password)
    {
		
        $authenticated = false;
		
        //find a user
        $user = $this->users->getOne('username', $username);

        //new auth
        if(!is_null($user) && $user->role == 1)
        {
             $authenticated = Auth::authenticate($password, $user->password);
        }
        //authenticate user
        if($authenticated && $username == $user->username)
		{
            $userdata = array();
            foreach($user as $k =>$v)
            {
                $userdata[$k] = $v;
            }
			$last['last_login'] = $this->users->created_on();
			$last['id'] = $user['id'];
			$this->users->update($last);
            unset($userdata['password']);
			Auth::set('ra_user', $userdata);
		}
		else
		{
			$this->logout();
		}
    }
	
	public function loadDashboard($namespace, $method)
	{
		$class = $namespace;
		$item = new $class();
		
		return function() use ($item, $method)
		{
			return $item->$method();
		};
	}
	
	public function logout()
	{
		Auth::destroy();
		Auth::set('ra_user', array());
		$this->app->redirect('/admin/login');
	}
	
	public function lostPassword()
	{
		$this->render('login');
	}
}