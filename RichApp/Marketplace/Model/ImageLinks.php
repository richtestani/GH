<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class ImageLinks extends Model {
	
	protected $id;
	protected $page_id;
	protected $website;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('imagelinks');
        $this->properties = array(
            'id',
            'item_id',
			'image_id',
			'type'
        );
    }
	
	public function deleteWhere($field=array(), $value=array())
	{
		$fields = array();
		foreach($field as $v => $f)
		{
			$fields[] = $f.'="'.$value[$v].'"';
		}
		$fields = implode(' AND ', $fields);
		$sql = "DELETE FROM imagelinks WHERE ".$fields;
		$this->query($sql);
	}
	
	public function getImageByLink($itemid, $type)
	{
		$data = [];
		$data = [':item_id' => $itemid, ':type'=>$type];
		$sql = "SELECT * FROM imagelinks LEFT JOIN images ON imagelinks.image_id=images.uid WHERE imagelinks.item_id=$itemid AND imagelinks.type='".$type."'";

		return $this->query($sql);
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}