<?php

namespace Marketplace\Controller\Admin;
use RichApp\Controller;
use RichApp\Library;
use RichApp\Model;
use Marketplace\Model as MModel;
use Marketplace\Library as MLibrary;

class Featured extends Controller\Admin {
	
	public function __construct()
	{
		parent::__construct();
		$this->featured_model = new MModel\Featured();
		$this->img_model = new Model\Images();
		$this->img_links = new MModel\ImageLinks();
		$this->html_listing = new Library\HTML\HTMLListing();
	}
	
	public function index()
	{
		$all = $this->featured_model->getAll(array('select'=>'id, buttontext, startdate, enddate'));
		$this->setData('page_title', 'All Features');
		$this->setData('listing', $all);
		$this->render('listing');
	}
	
	public function create()
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/featured/save', 'post');
        $this->getForm();
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add Featured');
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		$this->render('add-edit');
	}
	
	public function edit($id=0)
	{
		$this->forms = new Library\HTML\Form('/admin/marketplace/featured/save/'.$id, 'post');
		$data = $this->featured_model->getOne('id', $id);
        $this->getForm($data);
		$this->setData('form', $this->forms->get());
		$this->setData('page_title', 'Add Featured');
		$this->addAsset('script', '/assets/admin/js/marketplace.js');
		$this->render('add-edit');
	}
	
	public function save($id=0)
	{
		$featured = [];
		print_r($_POST);
		$featured['startdate'] = $this->request->post('publish_on');
		$featured['enddate'] = $this->request->post('expire_on');
		$featured['buttontext'] = $this->request->post('buttontext');
		$featured['buttonposition'] = $this->request->post('buttonposition');
		$featured['link'] = $_POST['link'];
		$f_image = (array_key_exists('featured-image', $_POST)) ? $this->request->post('featured-image') : '';
		$featured['image_id'] = $f_image;
		
		if($id == 0)
		{
			//insert
			$featured['created_on'] = $this->featured_model->created_on();
			$featured['created_by'] = $this->user['id'];
			$id = $this->featured_model->create($featured);
		}
		else
		{
			$featured['id'] = $id;
			$this->featured_model->update($featured);
		}
		//handle product images
		//$this->img_links->deleteWhere(array('item_id', 'type'), [$f_image, 'featured']);
		
		
		
		$this->app->redirect('/admin/marketplace/featured/edit/'.$id);
	}
	
	public function delete($id=0)
	{
		$this->featured_model->delete($id);
		$this->app->redirect('/admin/marketplace/featured');
	}
	
	public function getForm($data=null)
	{
        $values = new \stdClass();
        $properties = $this->featured_model->getProperties();
		
        foreach($properties as $prop)
        {
            $values->{$prop} = (!is_null($data)) ? $data->$prop : '';
        }
		
        $this->forms->insertDiv('data');
        $this->forms->insertInputLast('text', 'link', $values->link, array('class'=>'form-control'));
        $this->forms->setLabel('Link', 'link');
		$this->forms->endDiv();
		
		//start & end dates
        $this->forms->insertDiv('startdate');
        $this->forms->insertInputLast('text', 'publish_on', $values->startdate, array('class'=>'form-control datepicker start-datepicker'));
        $this->forms->setLabel('Start Date', 'publish_on');
		$this->forms->endDiv();
		
        $this->forms->insertDiv('enddate');
        $this->forms->insertInputLast('text', 'expire_on', $values->enddate, array('class'=>'form-control datepicker end-datepicker'));
        $this->forms->setLabel('End Date', 'expire_on');
		$this->forms->endDiv();
		
        $this->forms->insertDiv('buttontext');
        $this->forms->insertInputLast('text', 'buttontext', $values->buttontext, array('class'=>'form-control'));
        $this->forms->setLabel('Button Text', 'buttontext');
		$this->forms->endDiv();
		
		$pos = array(
			array('name'=>'Top Left', 'value'=>'topleft'),
			array('name'=>'Top Right', 'value'=>'topright'),
			array('name'=>'Bottom Left', 'value'=>'bottomleft'),
			array('name'=>'Bottom Right', 'value'=>'bottomright')
		);

        $this->forms->insertDiv('buttonposition');
        $this->forms->insertInput('select', 'buttonposition', $values->buttonposition, array('class'=>'form-control'), $pos);
        $this->forms->setLabel('Button Position', 'buttonposition');
		$this->forms->endDiv();
		
		//image
		$this->forms->insertDiv('featured-image');
		$this->forms->insertDiv('image-link');
		$this->forms->insertHTML('<div class="form-group"><h3>Featured Image (900 x 300)</h3><a class="btn btn-default add-featured-image" href="#">Add Featured Image</a></div>');
		if(isset($data->image_id))
		{
			$img = $this->img_model->getOne('uid', $data->image_id);
			$this->forms->insertDiv('featured_images_show');
			$src = $img['public_path'].'/thumbnail/'.$img['filename'];
			$this->forms->insertHTML('<div class="featured-image">');
			$this->forms->insertHTML('<img src="'.$src.'">');
			$this->forms->insertHTML('<a href="#" class="remove-featured-image btn btn-danger fa fa-times"></a>');
			$this->forms->insertInput('hidden', 'featured-image', $img['uid']);
			$this->forms->insertHTML('</div>');
			$this->forms->endDiv();
		}
		else
		{
			$this->forms->insertHTML('<div id="featured_images_show"></div>');
		}
		
		$this->forms->endDiv();
		$this->forms->endDiv();
	}
}