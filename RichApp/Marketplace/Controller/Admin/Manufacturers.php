<?php

namespace Marketplace\Controller\Admin;
use RichApp\Controller;
use RichApp\Library;
use Marketplace\Model as MModel;

class Manufacturers extends Controller\Admin {
	
	public function __construct()
	{
		parent::__construct();
		$this->man_model = new MModel\Manufacturers();
		$this->html_listing = new Library\HTML\HTMLListing();
	}
	
	public function index()
	{

		$all = $this->man_model->getAll(array('select'=>'id, manufacturer, created_on'));
		$this->setData('page_title', 'All Manufacturers');
		$this->setData('listing', $all);
		$this->render('listing');
	}
	
	public function create()
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/manufacturers/save', 'post');
        $this->getForm();
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add Manufacturer');
		$this->render('add-edit');
	}
	
	public function edit($id=0)
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/manufacturers/save/'.$id, 'post');
		$data = $this->man_model->getOne('id', $id);
        $this->getForm($data);
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add Manufacturer');
		$this->render('add-edit');
	}
	
	public function save($id=0)
	{
		
		$manufacturer = [];
		$manufacturer['manufacturer'] = $_POST['name'];
		$manufacturer['description'] = $_POST['description'];
		$manufacturer['website'] = $_POST['website'];
		$manufacturer['slug'] = $this->man_model->slug($_POST['name']);
		
		if( $id == 0 )
		{
			$manufacturer['created_by'] = $this->user['id'];
			$manufacturer['created_on'] = $this->man_model->created_on();
			$id = $this->man_model->create($manufacturer);
			
		}
		else
		{
			$manufacturer['modified_by'] = $this->user['id'];
			$manufacturer['modified_on'] = $this->man_model->created_on();
			$this->man_model->update($manufacturer, $id);
		}
		
		$this->app->redirect('/admin/marketplace/manufacturers/edit/'.$id);
	}
	
	public function delete($id=0)
	{
		$this->man_model->delete($id);
		$this->app->redirect('/admin/marketplace/manufacturers');
	}
	
	public function getForm($data=null)
	{
        $values = new \stdClass();
        $properties = $this->man_model->getProperties();
		
        foreach($properties as $prop)
        {
            $values->{$prop} = (!is_null($data)) ? $data->$prop : '';
        }
		
		$this->forms->insertDiv('mantext', 'form-group');
        $this->forms->insertInputLast('text', 'name', $values->manufacturer, array('class'=>'form-control'));
        $this->forms->setLabel('Name', 'name');
		$this->forms->endDiv();
        $this->forms->insertInputLast('text', 'website', $values->website, array('class'=>'form-control'));
        $this->forms->setLabel('Website', 'website');
		$this->forms->insertInput('textarea', 'description', $values->description, array('class'=>'form-control'));
		$this->forms->setLabel('Description', 'description');
	}
}