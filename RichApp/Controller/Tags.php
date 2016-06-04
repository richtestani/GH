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

class Tags extends Core\Controller {
    
    protected $tags;
    
    public function __construct()
    {
	parent::__construct();
    $this->pages_model = new Model\Pages();
	$this->tags_model = new Model\Tags();
	$this->api = new Library\API();
    }

    public function index($tag)
    {

		$args = array(
			'select' => 'pages.id, pages.slug, pages.category, pages.subtitle, pages.created_on, pages.published, pages.body, pages.title, tags.slug as tagslug, tags.tag, taglinks.item_id, taglinks.tag_id',
			'from' => 'pages',
			'join' => array(
				 array('table'=>'taglinks', 'on'=>'taglinks.item_id=pages.id'),
				 array('table'=>'tags', 'on'=>'tags.id=taglinks.tag_id')
			),
			'where' => array(
				array('field'=>'tags.slug', 'op'=>'=', 'value'=>$tag),
				array('field'=>'pages.published', 'op'=>'=', 'value'=>1, 'andor'=>'AND')
			),
			'order' => array('by'=>'pages.created_on', 'dir'=>'DESC')
		);
		
        $all = $this->pages_model->getAll($args);
		
		$this->setData('page_title', 'Tags: '.$tag);
        $this->setData('pages', $all);
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