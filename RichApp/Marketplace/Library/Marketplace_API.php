<?php
	
namespace Marketplace\Library;
use RichApp\Library as RALib;
use \RichApp\Core\Model;
use Marketplace\Model as MPModel;

class Marketplace_API extends RALib\API {
	
	public function __construct()
	{
		parent::__construct();
		$this->listings = new MPModel\ProductListings();
		$this->lines = new MPModel\OrderLine();
		$this->transaction = new MPModel\Orders();
		$this->bundles = new MPModel\Bundles();
		$this->attributes = new MPModel\Attributes();
		$this->featured = new MPModel\Featured();
		//$this->brands = new MPModel\Brands();
	}
	
	public function getFeatured()
	{
		$featured = $this->featured->getAll();
		return $featured;
	}
	
	//products API
	public function recentListings($limit=3)
	{
		$listings = $this->listings->recentListings();
		return $listings;
	}
	
	public function popularProducts($limit=5)
	{
		
	}
	
	public function listing($slug)
	{
		$result = $this->listings->oneListing($slug);
		$result['attributes'] = $this->attributes->getItemAttributes('listing', $result['id']);
		
		if(empty($result['default_image']) AND $result['default_image'] != 0)
		{
			$img = $this->getImage($result['default_image']);
			$result['default_image'] = $img;
		}
		
		$products = $this->getListingProducts($result['listing_id']);
		$result['products'] = $products;
		$images = $this->getListingProductImages($products);
		$result['images'] = $images;
		return $result;
	}
	
	public function getListingProducts($id)
	{
		$bundle = $this->bundles->findBundle($id);
		$products = array();
		foreach($bundle as $b)
		{
			$prod = $this->listings->oneProduct($b['product_id']);
			$prod['attributes'] = $this->attributes->getItemAttributes('product', $prod['id']);
			$products[] = $prod;
		}
		
		return $products;
	}
	
	public function getProductImages($prod_id)
	{
		$imgs = $this->listings->getProductImages($prod_id);
		return $imgs;
	}
	
	public function getListingProductImages($products)
	{
		
		$images = [];
		foreach($products as $p)
		{
			$img = $this->getProductImages($p['prod_id']);
			$images[] = $img;
		}
		
		return $images;
	}
	
	public function productsListing($limit=10)
	{
		
	}
	
	//user API
	public function getProfile($slug)
	{
		
	}
	
	//order API
	public function cartItems($transaction_id)
	{
		$lsql = "SELECT * FROM orderline WHERE transaction_id=?";
		$args = array(
			'select' => '*',
			'where' => array(
				array('field'=>'transaction_id', 'op'=>'=', 'value'=>$transaction_id)
			)
		);
		$lines = $this->lines->getAll($args);
		
		$order = $this->transaction->getOne('transaction_id', $transaction_id);
		$order->lines = $lines;
		return $order;
	}
	public function getOrderHistory($userid)
	{
		$args = array(
			'select' => '*',
			'from' => 'orders',
			'where' => array(
				array('field'=>'created_by', 'op'=>'=', 'value'=>$userid),
				array('field'=>'status', 'op'=>'=', 'value'=>1, 'andor'=>'AND')
			),
			'order'=>array('by'=>'completed_on')
		);
		$orders = $this->transaction->getAll($args);
		return $orders;
	}
	
}
?>