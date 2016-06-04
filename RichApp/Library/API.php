<?php
	
namespace RichApp\Library;

use RichApp;
use RichApp\Model;

class API {
	
	public function __construct()
	{
		$this->categories = new Model\Categories();
		$this->images = new Model\Images();
		$this->pages = new Model\Pages();
		$this->users = new Model\Users();
		$this->tags = new Model\Tags();
	}
	
	/*
	*	UI Elements API
	*/
	public function showSearchBox()
	{
		$search = <<<SEARCH
			<form method="post" action="/search">
			<input type="text" name="q" placeholder="Search Site" />	
			</form>
SEARCH;
		return $search;
	}
	
	/*
	*	Pages API
	*/
	public function getPage($slug)
	{
		$page = $this->pages->getOne('slug', $slug);
		return $page;
	}
	
	public function getPages($args=array())
	{
		$pages = $this->pages->getAll($args);
		return $pages;
	}
	
	public function getPagesByCategory($cat)
	{
		
	}
	
	public function getPagesByTag($tag)
	{
		
	}
	
	public function getPagesPublishedByDateRange($begin, $end)
	{
		
	}
	
	public function updateViews($id)
	{
		//$sql = "UPDATE pages SET views=views+1 WHERE id=$id";
		$args = array(
			'where' => array(
				array('field'=>'views', 'op'=>'=', 'views+1')
			)
		);
		$this->pages->update($args, $id);
	}
	
	public function nextPage($id)
	{
		$args = array(
				'limit' => 1,
				'where' => 
					array(
						array('field' => 'published', 'op'=>'=', 'value'=>1),
						array('field'=>'id', 'op'=>'<', 'value'=>$id, 'andor'=>'AND')
					),
					'order'=> array('by'=>'published_on', 'dir'=>'DESC')
			);

		$pages = $this->pages->getAll($args);

		return $pages;
	}
	
	public function prevPage($id)
	{
		$args = array(
				'limit' => 1,
				'where' => 
					array(
						array('field' => 'published', 'op'=>'=', 'value'=>1),
						array('field'=>'id', 'op'=>'>', 'value'=>$id, 'andor'=>'AND')
					),
					'order'=> array('by'=>'published_on', 'dir'=>'ASC')
			);
		
		$pages = $this->pages->getAll($args);
		return $pages;
	}
	
	
	/*
	*	Pagination API
	*/
	public function getPagination()
	{
		
	}
	
	/*
	*	Images API
	*/
	public function getImage($id)
	{
		$img = $this->images->getOne('uid', $id);
		return $img;
	}
	
	public function resizeImage($w)
	{
		
	}
	
	public function getPageImages($id)
	{
		
	}
	
	public function getPageDefaultImage($id)
	{
		
	}
	
	public function showUploadForm($options=array())
	{
		
	}

	
	/*
	*	Categories API
	*/
	public function getPageCategory($category)
	{
		return $this->categories->getOne('id', $category, false);
	}
	public function currentCategory()
	{
		
	}
	
	public function getCategoryChildren($parent)
	{
		
	}
	
	public function getCategoryParent($child)
	{
		
	}
	
	public function getAllCategories($hierarchal=false)
	{
		if(!$hierarchal)
		{
			return $this->categories->findAll();
		}
		else
		{
			return $this->categories->categoryHierachy();
		}
		
	}
	
	/*
	*	Tags API
	*/
	public function getPageTags($id)
	{
		$tags = $this->tags->getPageTags($id);
		return $tags;
	}
	
	public function getCategoryTags($category)
	{
	}
	
	public function pageHasTag($slug)
	{
		
	}
	
	/*
	*
	*/
	public function getOneUser($username)
	{
		return $this->users->getOneUser($username);
	}
	
	public function getUsername($id)
	{
		
	}
	
	public function getUserEmail($id)
	{
		
	}
	
	public function userCan($action, $user)
	{
		
	}
	
}
?>