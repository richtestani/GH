<?php
namespace RichApp\Model;
use RichApp\Core\Model;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class TagLinks extends Model {
    
    protected $id;
    protected $tag_id;
	protected $page_id;
    
    protected $_table;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('taglinks');
    }
	
	public function addLinks($links=array(), $item_id)
	{
		$l = [];
		if(!is_array($l))
		{
			return $l;
		}
  	  	
		$this->deleteWhere(
			array('where'=>array(
					array('field'=>'item_id', 'op'=>'=', 'value'=>$item_id)
				)
			)
		);
		
		foreach($links as $tag_id)
		{
	    	  $taglink = array(
	    		  'item_id' => $item_id,
	    		  'tag_id' => $tag_id
	    	  );
			$this->create( $taglink);
		}
		
	  
	  
	  
	}

}