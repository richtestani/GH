<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Controller\Admin;
use RichApp\Controller;
use RichApp\Model;
use RichApp\Library;

/**
 * Description of Tags
 *
 * @author richardtestani
 */
class Tags extends Controller\Admin {
    
    protected $tags_model;
    
    public function __construct()
    {
        parent::__construct();
        $this->tags_model = new Model\Tags();
        $this->html_listing = new Library\HTML\HTMLListing();
 

    }
    
    public function index($page=1)
    {
	$all = $this->tags_model->getAll(array('select'=>'id, tag, slug'));
        
	$this->setData('baselink', '/admin/tags');
        $this->setData('page_title', 'Tag Listing');
        $this->setData('listing', $all);
        $this->render('listing');
                
    }
    
    
    public function delete($id=0)
    {
        $id = (int)$id;
        if($id > 0)
        {
            $this->tags_model->delete('tags', $id);
        }
        
        $this->app->redirect('/admin/tags');
    }
    public function create()
    {
        $form = <<<FORM
           <form action="/admin/tags/save" method="post">
                <div class="form-group">
                <label>Tag</label>
                <input type="text" name="name" class="form-control" />
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" />
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
FORM;
        $this->setData('page_title', 'New Tag');
        $this->setData('form', $form);
        $this->render('add-edit');
    }
    
    public function edit($id)
    {
        $tag = $this->tags_model->findOne('id', $id);
        $form = <<<FORM
           <form action="/admin/tags/save/$id" method="post">
                <div class="form-group">
                <label>Tag</label>
                <input type="text" name="name" class="form-control" value="$tag->name" />
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="$tag->slug" />
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                </form>
FORM;
        $this->setData('page_title', 'New Tag');
        $this->setData('form', $form);
        $this->render('add-edit');
    }
    
    public function save($id=0)
    {
        $id = (int)$id;
        
        if(empty($_POST))
        {
            return false;
        }
        
        $tag = array(
          'tag'=> $_POST['name'],
           'slug' => $_POST['slug']
        );
        
        if($id > 0)
            {
                //update
                
                $this->tags_model->update( $tag, $id);
            }
            else
            {
                $tag['created_on'] = $this->tags_model->created_on();
				$tag['created_by'] = $this->user['id'];
                $this->tags_model->create($tag);
            }
            
            
            $this->app->redirect('/admin/tags');
    }
    public function json_panel($page_id = 0)
   {
        $tags = array();
        
       if($page_id != 0)
       {
           $tagData = $this->tags_model->getPageTags($page_id);
		   foreach($tagData as $t)
		   {
			   $tags[] = $t['tag'];
		   }
           
       }
       
	   $tags = implode(', ', $tags);
       $tagForm = new Library\HTML\Input('text', 'tags', $tags, array('class'=>'form-control panel-item-tags'));
       $tagForm->label('Tags');
       
       $tags = array('tags' => $tagForm->get());
       echo json_encode($tags, JSON_FORCE_OBJECT);
   }
}
