<?php

namespace Marketplace\Controller\Admin;
use RichApp\Controller;
use RichApp\Library;

class Series extends Controller\Admin {
	
	public function __construct()
	{
		parent::__construct();
		$this->series_model = new \Marketplace\Model\Series();
		$this->html_listing = new Library\HTML\HTMLListing();
	}
	
	public function index()
	{
		$all = $this->series_model->getAll();
		$this->setData('page_title', 'All Series');
		$this->setData('listing', $all);
		$this->render('listing');
	}
	
	public function create()
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/series/save', 'post');
        $this->getForm();
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add Series');
		$this->render('add-edit');
	}
	
	public function edit($id=0)
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/series/save/'.$id, 'post');
		$data = $this->series_model->getOne('id', $id);
        $this->getForm($data);
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add Series');
		$this->render('add-edit');
	}
	
	public function save($id=0)
	{
		
		$series = [];
		$series['series'] = $_POST['series'];
		$series['about'] = $_POST['about'];
		$series['slug'] = $this->series_model->slug($_POST['series']);
		
		if( $id == 0 )
		{
			$series['created_on'] = $this->series_model->created_on();
			$id = $this->series_model->create($series);
			
		}
		else
		{
			$series['modified_on'] = $this->series_model->created_on();
			$series['id'] = $id;
			$this->series_model->update($series, $id);
		}
		
		$this->app->redirect('/admin/marketplace/series/edit/'.$id);
	}
	
	public function delete($id=0)
	{
		$this->series_model->delete($id);
		$this->app->redirect('/admin/marketplace/series');
	}
	
	public function getForm($data=null)
	{
        $values = new \stdClass();
        $properties = $this->series_model->getProperties();
		
        foreach($properties as $prop)
        {
            $values->{$prop} = (!is_null($data)) ? $data->$prop : '';
        }
		
		$this->forms->insertDiv('seriesname', 'form-group');
        $this->forms->insertInputLast('text', 'series', $values->series, array('class'=>'form-control'));
        $this->forms->setLabel('Name', 'name');
		$this->forms->endDiv();
		$this->forms->insertInput('textarea', 'about', $values->about, array('class'=>'form-control'));
		$this->forms->setLabel('About', 'about');

	}
}