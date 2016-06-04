<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Products extends Model {
	
	protected $id;
	protected $page_id;
	protected $url;
	protected $rating;
	protected $website;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('products');
        $this->properties = array(
            'id',
			'sku',
			'inventory',
			'slug',
			'created_on',
			'modified_on',
			'created_by',
			'modified_by',
			'series_id',
			'num_in_series',
			'page_id',
			'manufacturer_id'
        );
    }
	
	public function findAllProducts($args = array())
	{
		//$sql = "SELECT pages.title, pages.id, pages.type, pages.created_on, pages.published, pages.published_on FROM products LEFT JOIN pages ON products.page_id=pages.id WHERE pages.type=? ORDER BY pages.created_on";
		$books = $this->getAll($args);
		return $books;
	}
	
	public function recent($num=3)
	{
		$sql = "SELECT pages.title, pages.slug, books.level, pages.id, pages.type, pages.created_on, pages.published_on FROM books LEFT JOIN pages ON books.page_id=pages.id WHERE pages.type=? AND pages.published=1 ORDER BY pages.created_on DESC LIMIT $num";
		$books = $this->getAll($sql, 'product');
		return $books;
	}
	
	function oneProduct($slug)
	{
		$sql = "SELECT 
			pages.title, 
			pages.slug, 
			books.level, 
			pages.id, 
			pages.type, 
			pages.created_on, 
			pages.published,
			pages.published_on, 
			pages.default_image,
			pages.body,
			products.id as prod_id,
			products.inventory,
			products.sku,
			products.page_id,
			categories.name,
			categories.slug as cat_slug
			FROM products 
			LEFT JOIN pages ON products.page_id=pages.id
			LEFT JOIN categories ON pages.category=categories.id
			WHERE pages.type='product' AND pages.slug=:slug";

		$product = $this->getAll($sql, [':slug'=>$slug]);
		$product[0]['image'] = $this->getProductImage($product[0]['default_image']);
		return $books[0];
	}
	
	public function getProductImage($id)
	{
		$image = $this->getAll("SELECT * FROM images WHERE uid=:uid", [':uid'=>$id]);
		return $image[0];
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
	public function getListing()
	{
		$sql = "SELECT p.id, p.title, p.slug, pr.id as prod_id, pr.num_in_series, pr.inventory FROM products pr LEFT JOIN pages p ON p.id=pr.page_id WHERE p.published=1 ORDER BY p.title LIMIT 20";
		$args = array(
			'select' => 'p.id, p.title, p.slug, pr.id as prod_id, pr.num_in_series, pr.inventory',
			'from'=>'products pr',
			'join' => array('table'=>'pages p', 'on'=>'p.id=pr.page_id'),
			'where' => array(
				array('field'=>'p.published', 'op'=>'=', 'value'=>1)
			),
			'order'=>array('by'=>'p.title'),
			'limit' => 100
		);
		return $this->getALL($args);
	}
	
}