<?php
namespace RichApp\Controller;
use RichApp\Library;
use RichApp\Model;
use RichApp\Core;

/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Categories extends Core\Controller {
    
    protected $category;
    
    public function __construct()
    {
	parent::__construct();
    $this->pages_model = new Model\Pages();
	$this->category_model = new Model\Categories();
	$this->api = new Library\API();
    }

    public function index($type='page', $slug = 'page')
    {
		$category = $this->category_model->findOne('slug', $slug);
        $all = $this->category_model->getAll("SELECT * FROM $type LEFT JOIN pages ON pages.id=$type.page_id AND pages.published=1 ORDER BY pages.created_on");
		
		$this->setData('title', $category->category);
        $this->setData('listings', $all);
		$this->setData('api', $this->api);
        $this->render('listing');
    }
    
    public function single($page)
    {
        $single = $this->pages_model->findOne('slug', $page);
		$this->setData('api', $this->api);
        $this->setData('listing', $single);
        $this->render($single->template);
    }
    
    
    
}