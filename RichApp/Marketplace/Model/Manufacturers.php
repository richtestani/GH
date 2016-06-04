<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Manufacturers extends Model {
	
	protected $id;
	protected $page_id;
	protected $url;
	protected $rating;
	protected $website;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('manufacturers');
        $this->properties = array(
            'id',
            'manufacturer',
			'description',
			'website',
			'created_on',
			'created_by',
			'modified_on',
			'modified_by',
			'image',
			'slug'
        );
    }
	
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}