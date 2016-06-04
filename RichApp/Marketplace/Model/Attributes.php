<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Attributes extends Model {
	
	protected $id;
	protected $page_id;
	protected $url;
	protected $rating;
	protected $website;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('attributes');
        $this->properties = array(
            'id',
			'name',
			'value',
			'product_id',
			'type'
        );
    }
	
	public function setAttributes($data)
	{
		$attribuets = '<div id="attribute-listing"></div>';
		foreach($data as $att)
		{
			$attribtues .= '<div class="form-group">';
			$attribtues .= '<label>Name</label><input type="text" name="attribute-name[]" value="'.$att->name.'" />';
			$attribtues .= '<label>Name</label><input type="text" name="attribute-value[]" value="'.$att->value.'" />';
			$attributes .= '</div>';
		}
		$attributes .= '</div>';
		return $attributes;
	}
	
	public function getItemAttributes($type, $item_id)
	{
		//$sql = "SELECT * FROM attributes WHERE type='$type' AND item_id=$item_id";
		$args = array(
			'where' => array(
				array('field'=>'item_id', 'op'=>'=', 'value'=>$item_id),
				array('field'=>'type', 'op'=>'=', 'value'=>$type, 'andor'=>'AND')
			)
		);
		
		return $this->getAll($args);
	}
	
	public function addAttributes($data=array(), $item_id, $type)
	{
		$arg = array(
			'where' => array(
				array('field'=>'item_id', 'op'=>'=', 'value'=>$item_id),
				array('field'=>'type', 'op'=>'=', 'value'=>$type, 'andor'=>'AND')
			)
		);
		
		$this->deleteWhere($arg);
		
		foreach($data as $n => $v) {
			$att['name'] = $n;
			$att['value'] = $v;
			$att['item_id'] = $item_id;
			$att['type'] = $type;
			$this->create($att);
		}
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}