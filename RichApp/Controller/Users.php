<?php
	
namespace RichApp\Controller;
use RichApp\Library;
use RichApp\Core;
use RichApp\Model;
use rcastera\Browser\Session\Session;
use \RichApp\Core\Auth;

class Users extends Core\Controller {
	
	protected $invalid = true;
	
	public function __construct()
	{
		parent::__construct();
		$this->users_model = new Model\Users();
		$this->api = new Library\API();
		$this->roles = array(
			1 => 'admin',
			10 => 'manager',
			40 => 'reviewer',
			100 => 'member'
		);
		$this->setData('api', $this->api);
	}
	
	public function index()
	{
	}
	
	public function profile($username)
	{
		
		if($this->user['username'] == $username)
		{
			//get private
			$data = $this->getPrivateProfile($username);
			$this->setData('userdata', $data);
			
		}
		else
		{
			//get public
			$profile = $this->getPublicProfile($username);
		}
		$this->setData('username', $username);
		$this->setData('title', $username);
		$this->render('profile');
	}
	
	private function getPublicProfile($id)
	{
		$user = $this->users_model->getOne('username', $id);
		if(empty($user))
		{
			//return guest
		}
	}
	
	public function getPrivateProfile($id)
	{
		//user data
		$userdata = $this->users_model->getOne('username', $id);
		$userdata['password'] = '';
		return $userdata;
	}
	
	public function register()
	{
		if(array_key_exists('user_create_error', $_SESSION))
		{
			$error = $_SESSION['user_create_error'];
			unset($_SESSION['user_create_error']);
			$this->setData('errorMessage', $error);
			$this->setData('userData', unserialize($this->session->get('user_create_data')));
			unset($_SESSION['user_create_data']);
		}
		$this->setData('showForm', 'register');
		$this->render('register-login');
	}
	
	public function login()
	{
		if(array_key_exists('username', $_POST) && array_key_exists('password', $_POST))
		{
			$loggedin = $this->checkLogin();
			if($loggedin)
			{
				$this->app->redirect('/user/profile/'.$_POST['username']);
			}
			else
			{
				//so we have a get request
				$this->app->redirect('/user/login');
			}
		}
		$this->setData('showForm', 'login');
		$this->render('register-login');
	}
	
	private function checkLogin()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$user = $this->users_model->getOne('username', $username);
		$stored = $user->password;


		if(empty($user))
		{
			return false;
		}
		
		$result = Auth::authenticate($password, $stored);
		if($result)
		{
			$this->user = $this->api->getOneUser($username);
			$settings = $this->system->get('users');
			Auth::set($settings['user_session'], serialize($this->user[0]));
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	public function save($id = 0)
	{
		if($id == 0)
		{
			$error = $this->create();
			if( $this->invalid )
			{
				$_SESSION['user_create_error'] = $error;
				$_SESSION['user_create_data'] = serialize($_POST);
				$this->app->redirect('/user/register');
			}
		}
	}
	
	protected function create()
	{
		if( $this->isValidUser() )
		{
			$username = $this->users_model->getOne('username', $_POST['username']);
			//check username
			if(empty($username))
			{
				
				$this->invalid = false;
			}
			else
			{
				$this->invalid = true;
				return 'Username not available';
			}
			
			//check password
			if(strlen($_POST['password']) >= 8 && ctype_alnum($_POST['password']))
			{
				$this->invalid = false;
			}
			else
			{
				$this->invalid = true;
				return 'Your password should be 8 characters and include letters and numbers only';
			}

			
			//create the user
			$user = array(
				'first' => $_POST['first'],
				'last' => $_POST['last'],
				'email' => $_POST['email'],
				'username' => $_POST['username'],
				'password' => password_hash($this->request->post('password'), PASSWORD_BCRYPT),
				'created_on' => $this->users_model->created_on(),
				'slug' => $this->users_model->slug($_POST['username']),
				'role' => 100,
				'activation' => session_id()
			);
			
			$this->users_model->create($user);
			$this->app->redirect('/user/'.$_POST['username']);
		}
		else
		{
			$this->invalid = true;
			return 'All fields must be filled out';
		}
	}
	
	protected function saveUser($id = 0)
	{
		if($id == 0)
		{
			$new_user = array();
			$new_user['role'] = 100;
			$new_user['first'] = $_POST['first'];
			$new_user['last'] = $_POST['last'];
			$new_user['username'] = $_POST['username'];
			$new_user['password'] = password_hash($this->request->post('password'), PASSWORD_BCRYPT);
			$new_user['activation'] = $this->session->get('session_id');
		}
	}
	
	protected function isValidUser()
	{
		$properties = $this->users_model->getProperties();
		foreach($_POST as $k => $p)
		{
			if(in_array($k, $properties))
			{
				if(empty($_POST[$k]))
				{
					return false;
				}
			}
		}
		
		return true;
		
	}
	
	protected function userForm()
	{
		
	}
	
}
?>