<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Bundles extends Model {
	
	protected $id;
	protected $page_id;
	protected $url;
	protected $rating;
	protected $website;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('bundles');
        $this->properties = array(
            'id',
			'listing_id',
			'product_id',
			'qty'
        );
    }

	public function findBundle($listing_id)
	{
		$args = array(
			'where' => array(
				array('field'=>'listing_id', 'op'=>'=', 'value'=>$listing_id)
			)
		);
		
		return $this->getAll($args);
	}
	
	public function findListingProductsBundle($listing_id)
	{
				$sql = "SELECT * FROM bundles LEFT JOIN products ON bundles.product_id=products.id LEFT JOIN pages ON products.page_id=pages.id WHERE listing_id=?";
				
				return $this->getAll($sql, [$listing_id]);
	}
	
	public function findProductBundle($prod_id)
	{
		$sql = "SELECT * FROM bundles LEFT JOIN products ON bundles.product_id=products.id LEFT JOIN pages ON products.page_id=pages.id WHERE products.id=?";
		
		$args = array(
			'where' => array(
				array('field'=>'products.id', 'op'=>'=', 'value'=>$prod_id)
			),
			'join' => array(
				array('table'=>'products', 'on'=>'bundles.product_id=products.id'),
				array('table'=>'pages', 'on'=>'pages.id=products.page_id')
			)
		);
		return $this->getAll($args);
	}
	
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}