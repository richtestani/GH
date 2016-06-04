<?php
namespace RichApp\Controller\Admin;
use RichApp\Controller;
use RichApp\Model;
use RichApp\Library;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Pages
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Categories extends Controller\Admin {
    
    protected $categories_model;
    
    public function __construct()
    {
        parent::__construct();
        $this->categories_model = new Model\Categories();
        $this->html_listing = new Library\HTML\HTMLListing();
 

    }
    
    public function index()
    {
		$all = $this->categories_model->categoryHierachy(true);
		//$all = \R::convertToBeans( 'categories', $all );
		$this->setData('baselink', '/admin/categories/');
        $this->setData('page_title', 'Categories Listing');
        $this->setData('listing', $all);
        $this->render('listing');
                
    }
	
    
    public function delete($id=0)
    {
        $id = (int)$id;
        if($id > 0)
        {
            $this->categories_model->delete('categories', $id);
        }
        
        $this->app->redirect('/admin/categories');
    }
    public function create()
    {
        $default = array(array('value'=>1, 'name'=>'None'));
        //$all = $this->categories_model->getAll("SELECT id as value, name FROM categories ORDER BY type ASC, lft ASC");
		$h = $this->categories_model->categoryHierachy(true);
		$list = array();
		foreach($h as $k => $v)
		{
			$list[] = ['name'=>$v['name'], 'value' => $v['id']];
		}

        $types = array(
			array('name'=>'Pages', 'value'=>'pages'),
            array('name'=>'Images', 'value'=>'images')            
            );
        
        $this->forms = new Library\HTML\Form('/admin/categories/save', 'post');
        
        //build form
        $this->forms->insertDiv('category-input');
        $this->forms->insertInput('text', 'name', '', array('class'=>'form-control', 'id'=>'category-name'));
        $this->forms->setLabel('Category Name', 'name');
        $this->forms->insertInput('select', 'type', '', array('class'=>'form-control', 'id'=>'type-category'), $types);
        $this->forms->setLabel('Category Type', 'type');
        $this->forms->endDiv();
        
        $this->forms->insertDiv('category-form');
        $this->forms->insertInput('select', 'parent', '', array('class'=>'form-control', 'id'=>'parent-category'), array_merge($default, $list));
        $this->forms->setLabel('Parent Category', 'parent');
        $this->forms->endDiv();
		
        $this->forms->insertDiv('category-description');
        $this->forms->insertInput('textarea', 'description', '', array('class'=>'form-control', 'id'=>'category-description'));
        $this->forms->setLabel('Description', 'description');
        $this->forms->endDiv();
		
		$this->forms->insertDiv('image-link');
		$this->forms->insertHTML('<div class="form-group"><label>Default Image</label><div class="thumbnail"></div><a id="set-default-image" href="#">Set Default Image</a><div id="default_image_show"></div></div>');
		$this->forms->insertHTML('<div id="default_image_show"></div>');
		$this->forms->endDiv();
        
        $this->setData('form', $this->forms->get());
		$this->setData('script', '<script src="/assets/admin/js/categories.js"></script>');
        $this->setData('page_title', 'New Category');
        $this->render('add-edit');
    }
    
    public function edit($id)
    {
        $default = array(array('value'=>1, 'name'=>'None'));
        $category = $this->categories_model->getOne('id', $id);
        //$all = $this->categories_model->getAll("SELECT id as value, name FROM categories ORDER BY type ASC, lft ASC");
		$h = $this->categories_model->categoryHierachy(true);
		$list = array();

		foreach($h as $k => $v)
		{
			$list[] = ['name'=>$v['name'], 'value' => $v['id']];
		}
        $types = array(
            array('name'=>'Images', 'value'=>'images'),
            array('name'=>'Pages', 'value'=>'pages', 'selected'=>'selected')
            );
        
        $this->forms = new Library\HTML\Form('/admin/categories/save/'.$id, 'post');
        
        //build form
        $this->forms->insertRow('category-input');
        $this->forms->insertInput('text', 'name', $category->category, array('class'=>'form-control', 'id'=>'category-name'));
        $this->forms->setLabel('Category Name', 'name');
        $this->forms->insertInput('select', 'type', $category->inputtype, array('class'=>'form-control', 'id'=>'type-category'), $types);
        $this->forms->setLabel('Category Type', 'type');
        $this->forms->endRow();
        
        $this->forms->insertRow('category-form');
        $this->forms->insertInput('select', 'parent', $category->parent, array('class'=>'form-control', 'id'=>'parent-category'), array_merge($default, $list));
        $this->forms->setLabel('Parent Category', 'parent');
        $this->forms->endRow();
		
        $this->forms->insertRow('category-description');
        $this->forms->insertInput('textarea', 'description', $category->description, array('class'=>'form-control', 'id'=>'category-description'));
        $this->forms->setLabel('Description', 'description');
        $this->forms->endRow();
        
        $this->setData('form', $this->forms->get());
        $this->setData('page_title', 'Edit Category');
        $this->render('add-edit');
    }
    
    public function save($id=0)
    {
        $id = (int)$id;
        
        if(empty($_POST))
        {
            return false;
        }
        
        $category = array(
          'category'=> $this->request->post('name'),
          'slug' => $this->categories_model->slug($this->request->post('name')),
          'parent' => $this->request->post('parent'),
          'categorytype'=> $this->request->post('type'),
		  'description' => $this->request->post('description'),
		  'created_on' => $this->categories_model->created_on()
        );

		//parent record
		$parent = $this->categories_model->getOne('id', $this->request->post('parent'));
        
        if($id > 0)
		{
			//preupdate record
			$this->categories_model->editNode($category, $id, $parent);
		}
		else
		{
			$this->categories_model->addNode($category, $parent);
		}
		//exit();
        $this->app->redirect('/admin/categories');
    }
}
