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

class Pages extends Core\Controller {
    
    protected $pages_model;
    protected $page_slug;
    protected $page_exists = false;
    protected $template;
    protected $page_params;
    
    public function __construct()
    {
	parent::__construct();
        $this->pages_model = new Model\Pages();
		$this->api = new Library\API();
		$this->setData('api', $this->api);
       
    }

    public function index($page = '')
    {
		$args = array(
			'limit' => 20,
			'where' =>
				array(
					array('field'=>'published', 'op'=>'=', 'value'=>1)
				),
				'order'=>array('by'=>'published_on', 'dir'=>'DESC')
		);
        $all = $this->pages_model->getAll($args);
		
        $this->setData('pages', $all);
        $this->render('home');
    }
    
    public function single($page)
    {
		$this->setData('slug', $page);
        $this->render('single');
    }
    
	public function listingByTag($tag)
	{
		$args = array(
			'select' => 'pages.id, pages.title, pages.body, pages.category, pages.subtitle, pages.slug, tags.tag, tags.slug as tagslug',
			'limit' => 20,
			'where' =>
				array(
					array('field'=>'published', 'op'=>'=', 'value'=>1),
					array('field'=>'tag', 'op'=>'=', 'value'=>$tag, 'andor'=>'AND')
				),
				'join'=>array(
					array( 'table' => 'taglinks', 'on'=>'taglinks.item_id=pages.id'),
					array( 'table'=>'tags', 'on'=>'tags.id=taglinks.tag_id' )
				),
				'order'=>array('by'=>'published_on', 'dir'=>'DESC')
		);

		$pages = $this->pages_model->getAll($args, false);
		$this->setData('page_title', 'Page with tag: '.$tag);
		$this->setData('pages', $pages);
		$this->render('listing');
	}
	
	public function listingByCategory($category)
	{
		$args = array(
			'select' => 'pages.id, pages.title, pages.body, pages.category, pages.subtitle, pages.slug, categories.category as categoryname, categories.slug as categoryslug',
			'limit' => 20,
			'where' =>
				array(
					array('field'=>'published', 'op'=>'=', 'value'=>1),
					array('field'=>'categories.category', 'op'=>'=', 'value'=>$category, 'andor'=>'AND')
				),
				'join'=>array(
					array( 'table'=>'categories', 'on'=>'pages.category=categories.id' )
				),
				'order'=>array('by'=>'published_on', 'dir'=>'DESC')
		);

		$pages = $this->pages_model->getAll($args, false);
		$this->setData('page_title', 'Categories: '.$category);
		$this->setData('pages', $pages);
		$this->render('listing');
	}
    
    
}