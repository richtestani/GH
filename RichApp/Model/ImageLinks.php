<?php
namespace RichApp\Model;
use RichApp\Core;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class ImageLinks extends Core\Model {
    
    protected $id;
    protected $image_id;
	protected $page_id;
	protected $item_id;
    
    protected $_table;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('imagelinks');
    }
	
	public function insertLinks($images=array(), $item_id, $type)
	{
		//remove
		$this->deleteWhere(
			array('where'=>array(
					array('field'=>'item_id', 'op'=>'=', 'value'=>$item_id),
					array('field'=>'type', 'op'=>'=', 'value'=>$type, 'andor'=>'AND')
				)
			)
		);
		foreach($images as $id)
		{			
			//$sql = "INSERT INTO imagelinks SET page_id=".$page.", image_id=".$id;
			$link = array(
				'item_id' => $item_id,
				'image_id' => $id,
				'type' => $type,
				'created_on' => $this->created_on()
			);
			$this->create($link);
		}
	}
	
	public function getImageByLink($item_id, $type)
	{
		$args = array(
			'where' => array(
				array('field'=>'item_id', 'op'=>'=', 'value'=>$item_id),
				array('field'=>'type', 'op'=>'=', 'value'=>$type, 'andor'=>'AND')
			),
			'join' => array('table'=>'images', 'on'=>'imagelinks.image_id=images.uid')
		);

		return $this->getAll($args);
	}
	
}