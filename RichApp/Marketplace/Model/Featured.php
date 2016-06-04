<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Featured extends Model {
	
	protected $id;
	protected $image_id;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('featured');
        $this->properties = array(
            'id',
            'image_id',
			'description',
			'link',
			'created_on',
			'created_by',
			'modified_on',
			'modified_by',
			'buttonposition',
			'buttontext',
			'startdate',
			'enddate'
			
        );
    }
	
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}