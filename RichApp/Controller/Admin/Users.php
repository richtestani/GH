<?php
	
namespace RichApp\Controller\Admin;
use RichApp\Controller;
use RichApp\Library;
use rcastera\Browser\Session\Session;

class Users extends Controller\Admin {
	
	public function __construct()
	{
		parent::__construct();
		$this->users_model = new \RichApp\Model\Users();
		$this->html_listing = new \RichApp\Library\HTML\HTMLListing();
		$this->session = new Session();
		$this->forms = null;
		$this->roles = array(
			1 => 'admin',
			10 => 'manager',
			40 => 'reviewer',
			100 => 'member'
		);

	}
	
	public function index($page=1)
	{

		$all = $this->users_model->getAll(array('select'=>'id, username, email, active, role, last_login, created_on'));
		
		$page_settings = $this->system->get('pages');
		$numrecords = $this->users_model->count();
		$this->pagination->setTotalNumPages($numrecords, $page_settings['num_posts_per_page'])
			->setBaseLink('/admin/users')->setBasePageLink('page')->currentPageNum($page);
		$pagination = $this->pagination->get();
		
		$this->setData('pagination', $pagination);
		
		$this->setData('baselink', '/admin/users/');
		$this->setData('page_title', 'All Listings');
		$this->setData('script', '<script src="/assets/admin/js/marketplace.js"></script>');
		$this->setData('listing', $all);
		$this->render('listing');
	}
	
	public function create()
	{
		$data = null;
		$session = (array_key_exists('user_form_data', $_SESSION)) ? $this->session->get('user_form_data') : array();
		if(!empty($session))
		{
			$data = (object)$session;
			$this->setData('error_message', 'Your password was empty');
		}
		$this->forms = new Library\HTML\Form('/admin/users/save', 'post');
		$this->setUserForm($data);
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'New User');
		$this->render('add-edit');
	}
	
	public function edit($id=0)
	{
		
		$user = $this->users_model->getOne('id', $id);
		$this->forms = new Library\HTML\Form('/admin/users/save/'.$id, 'post');
		$this->setUserForm($user);
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', $user->username);
		$this->render('add-edit');
	}
	
	public function save($id=0)
	{
		$user['username'] = $this->request->post('username');
		$user['email'] = $this->request->post('email');
		$user['first'] = $this->request->post('first');
		$user['last'] = $this->request->post('last');
		$user['role'] = $this->request->post('role');
		
		
		$lookup = $this->users_model->searchUser($user);
		
		if($id > 0)
		{
			$user['modified_on'] = $this->users_model->created_on();
			$user['modified_by'] = $this->user['id'];
			$user['id'] = $id;
			$this->users_model->update($user);
		}
		else
		{
			if(empty($_POST['password']))
			{
				$this->session->set('user_form_data', $_POST);
				$this->app->redirect('/admin/users/create');
			}
			if(empty($lookup))
			{
				$user['created_on'] = $this->users_model->created_on();
				$user['created_by'] = $this->user['id'];
				$user['modified_by'] = $this->user['id'];
				$this->session->set('user_form_data', '');
				$id = $this->users_model->create($user);
			}
			else
			{
				echo 'duplicate email or username';
				exit();
			}
		}
		$this->app->redirect('/admin/users/edit/'.$id);
		
	}
	
	public function delete($id=0)
	{
		$this->users_model->delete($id);
		$this->app->redirect('/admin/users');
	}
	
    private function setUserForm($data = null)
    {
        $values = new \stdClass();
        $properties = $this->users_model->getProperties();
        foreach($properties as $prop)
        {
            if( is_object($data) )
			{
				$values->{$prop} =  $data->$prop;
				
			}
			else
			{
				$values->{$prop} = '';
			}
        }
		$roles = array();
		foreach($this->roles as $id => $role)
		{
			$roles[] = array('name' => $role, 'value'=>$id);
		}
		
        $this->forms->insertDiv('userinfo');
        $this->forms->insertInput('text', 'username', $values->username, array('class'=>'form-control', 'id'=>'username'));
		$this->forms->setLabel('Username', 'username');
        $this->forms->endDiv();
        $this->forms->insertDiv('email');
        $this->forms->insertInput('text', 'email', $values->email, array('class'=>'form-control', 'id'=>'email'));
		$this->forms->setLabel('Email', 'email');
        $this->forms->endDiv();
        $this->forms->insertDiv('name');
        $this->forms->insertInput('text', 'first', $values->first, array('class'=>'form-control', 'id'=>'first'));
		$this->forms->setLabel('First', 'first');
        $this->forms->insertInput('text', 'last', $values->last, array('class'=>'form-control', 'id'=>'last'));
		$this->forms->setLabel('Last', 'last');
        $this->forms->endDiv();
		if(is_null($data) OR empty($data->password))
		{
	        $this->forms->insertDiv('password');
	        $this->forms->insertInput('text', 'password', '', array('class'=>'form-control', 'id'=>'password'));
			$this->forms->setLabel('Password', 'password');
	        $this->forms->endDiv();
		}
        $this->forms->insertDiv('role');
        $this->forms->insertInput('select', 'role', $values->role, array('class'=>'form-control', 'id'=>'roles'), $roles);
		$this->forms->setLabel('Role', 'role');
        $this->forms->endDiv();
		if( !is_null($data) AND !empty($data->password) )
		{
			$this->forms->insertDiv('passwordreset');
			$this->forms->insertDiv('resetpassword');
			$this->forms->insertHTML('<a href="/admin/users/resetpassword">Reset Password</a>');
			$this->forms->endDiv();
			$this->forms->endDiv();
		}
		
        
    }
	
}
?>