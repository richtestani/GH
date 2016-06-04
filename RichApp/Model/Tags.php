<?php
namespace RichApp\Model;
use RichApp\Core;
use RichApp\Core\DB;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Tags extends Core\Model {
    
    protected $id;
    protected $tags;
    protected $slug;
    protected $date_created;
	protected $author_id;
	protected $page_id;
    
    protected $_table;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('tags');
    }
	
	public function findAllListing($order = 'date_created')
	{
		$db = $this->getDB();
		return $db::findAll('tags', array(), $order.' LIMIT 25');
	}
	
	public function findPageTags($id)
	{
		$sql = "SELECT * FROM tags LEFT JOIN taglinks ON tags.id=taglinks.tag_id WHERE taglinks.item_id=".$id;
		$records = $this->getAll($sql);
		return $records;
	}
	
	public function insertTags($data, $date, $user)
	{
		$ids = array();
		foreach($data as $t)
		{
			$tag = trim($t);
			$slug = strtolower(str_replace(' ', '-', $tag));
			$item = $this->findOne('slug', $slug);
			$tag_data = array(
				'tag' => $tag,
				'slug' => $slug,
				'date_created' => $date,
				'author_id' => $user
			);
			if(empty($item))
			{
				$record = $this->create('tags', $tag_data);
			}
			else
			{
				$record = $item;
			}
			
			$ids[] = $record->id;
		}
		
		return $ids;
	}
        
        public function getPageTags($id)
    {
		//$sql = "SELECT * FROM tags LEFT JOIN taglinks ON tags.id=taglinks.tag_id WHERE taglinks.item_id=".$id;
		$args = array(
			'select' => '*',
			'from' => 'tags',
			'join' => array('table'=>'taglinks', 'type'=>'left', 'on'=>'tags.id=taglinks.tag_id'),
			'where' => array(
				array('field' => 'taglinks.item_id', 'op'=>'=', 'value' => $id)
			),
			'limit' => 100
		);
		$results = $this->getAll($args);
		return $results;
    }
	
	//return array of ids
	public function addTags($data='', $item_id, $user)
	{
		$tags = [];
		echo $data.'<br>';
		if(empty($data))
		{
			return $tags;
		}
		
		$data = (!is_array($data)) ? explode(",", $data) : $data;
		print_r($data);
	  foreach($data as $t)
	  {
		  if(empty(trim($t)))
		  {
			  continue;
		  }

		  $tag = array(
			  'tag' => trim($t),
			  'created_on' => $this->created_on(),
			  'slug' => $this->slug($t),
			  'created_by' => $user
		  );
		  
		  $args = array(
			  'select' => 'id, tag',
			  'where' => array(
				  array('field' => 'tag', 'op'=>'=', 'value'=>trim($t))
			  )
		  );
		  
		 
		  if(!$this->exists(array('field'=>'tag', 'value'=>trim($t))))
		  {
			  echo $t.' does not exist, add this one<br>';
		  	 $tag_id = $this->create($tag);
			 $tags[] = $tag_id;
		  }
		  else
		  {
			  echo 'looks like '.$t.' exists, get the id<br>';
			  $find = $this->getOne('tag', trim($t));
			  print_r($find);
			  $tag_id = $find['id'];
			  $tags[] = $tag_id;
		  }
		  
		  /*
		  $taglink = array(
			  'item_id' => $record,
			  'tag_id' => $tag_id
		  );
		  
		  $this->create( $taglink);
		  */
		  
	  }
	  
	  return $tags;
	  
	}
	
}