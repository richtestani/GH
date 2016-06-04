<?php

namespace Marketplace\Controller\Admin;
use RichApp\Controller;
use RichApp\Library;

class Sellers extends Controller\Admin {
	
	public function __construct()
	{
		parent::__construct();
		$this->sellers_model = new \Marketplace\Model\Sellers();
		$this->html_listing = new Library\HTML\HTMLListing();
	}
	
	public function index()
	{

		$args = [
			'select' => 'users.id as user_id, sellers.id, users.username, sellers.connected, users.email',
			'from' => 'sellers',
			'join'=>[
				['table'=>'users', 'on'=>'sellers.user_id=users.id']
			]
		];
		$sellers = $this->sellers_model->getAll($args);
		$this->setData('listing', $sellers);
		$this->setData('page_title', 'Sellers');
		$this->render('listing');
	}
	
	public function find()
	{
		$form = <<<FORM
			<form>
		<input type="text" name="usersearch" placeholder="Find a User" id="usersearch" class="form-control" />
		<div class="row">
			<div id="selecteduser" class="xs-col-6"></div>
			<div id="foundusers" class="xs-col-6"></div>
		</div>
		<button id="addseller" class="btn btn-primary disabled">Add</button>
		</form>
FORM;
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		$this->setData('form', $form);
		$this->setData('page_title', 'Find & Add Seller');
		$this->render('add-edit');
	}
	
	public function search($format = array())
	{
		$query = $this->request->post('query');
		$args = [
			'select' => 'CONCAT(first, last) as name, id, username',
			'from' => 'users',
			'where'=>[
				['field'=>'username', 'op'=>'LIKE', 'value'=>'%'.$query.'%'],
				['field'=>'CONCAT(first, last)', 'op'=>'LIKE', 'value'=>'%'.$query.'%', 'andor'=>'OR']
			]
		];

		$results = $this->sellers_model->getAll($args);
		if($format == 'json') {
			echo json_encode($results);
		}
	}
	
	public function create()
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/seller/save', 'post');
        $this->getForm();
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add seller');
		$this->render('add-edit');
	}
	
	public function edit($id=0)
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/seller/save/'.$id, 'post');
		$data = $this->sellers_model->findOne('id', $id);
        $this->getForm($data);
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add seller');
		$this->render('add-edit');
	}
	
	public function saveUser()
	{
		$user = $this->request->post('user');
		$seller = [
			'user_id' => $user,
			'rating'=>0,
			'created_on' => $this->sellers_model->created_on()
		];
		
		$this->sellers_model->create($seller);
	}
	
	public function save($id=0)
	{
		
		$seller = [];
		$seller['seller'] = $_POST['seller'];
		$seller['about'] = $_POST['about'];
		$seller['slug'] = $this->sellers_model->slug($_POST['seller']);
		
		if( $id == 0 )
		{
			$seller['created_on'] = $this->sellers_model->created_on();
			$id = $this->sellers_model->create($seller);
			
		}
		else
		{
			$seller['modified_on'] = $this->sellers_model->created_on();
			$this->sellers_model->update('seller', $seller, $id);
		}
		
		$this->app->redirect('/admin/marketplace/seller/edit/'.$id);
	}
	
	public function delete($id=0)
	{
		$this->sellers_model->delete($id);
		$this->app->redirect('/admin/marketplace/sellers');
	}
	
	public function getForm($data=null)
	{
        $values = new \stdClass();
        $properties = $this->seller_model->getProperties();
		
        foreach($properties as $prop)
        {
            $values->{$prop} = (!is_null($data)) ? $data->$prop : '';
        }
		
        $this->forms->insertRow('data');
        $this->forms->insertInputLast('text', 'seller', $values->seller, array('class'=>'form-control'));
        $this->forms->setLabel('Name', 'name');
		$this->forms->insertInput('textarea', 'about', $values->about, array('class'=>'form-control'));
		$this->forms->setLabel('About', 'about');
		$this->forms->endRow();
	}
}