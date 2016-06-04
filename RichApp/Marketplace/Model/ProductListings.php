<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class ProductListings extends Model {

	
	public function __construct()
    {
		parent::__construct();
        $this->table = 'products';
        $this->properties = array(
            'id',
			'sku',
			'inventory',
			'slug',
			'created_on',
			'modified_on',
			'available',
			'created_by',
			'modified_by',
			'series_id',
			'num_in_series',
        );
    }
	
	public function recentListings()
	{
		$sql = "SELECT
					pages.title,
					pages.slug,
					pages.body,
					pages.created_on,
					pages.created_by,
					pages.published_on,
					pages.default_image,
					pages.id,
					users.username,
					users.slug as userslug,
					listings.id as list_id,
					listings.price
					FROM pages
					LEFT JOIN listings ON listings.page_id=pages.id
					LEFT JOIN users ON pages.created_by=users.id
					WHERE pages.published=1
					AND pages.pagetype='listing'";
					
					return $this->query($sql, array());
	}
	
	public function oneListing($id)
	{
		
		if( (int)$id > 0)
		{
			$where = " listings.id = ?";
		}
		else
		{
			$where = " pages.slug=?";
		}
		
		$sql = "SELECT
					pages.id,
					pages.title,
					pages.slug,
					pages.created_on,
					pages.created_by,
					pages.default_image,
					pages.published_on,
					pages.views,
					pages.body,
					listings.id as listing_id,
					listings.price,
					listings.views,
					listings.qty,
					users.id as user_id,
					users.slug as user_slug,
					users.username
					FROM pages
					LEFT JOIN listings ON listings.page_id=pages.id
					LEFT JOIN users ON pages.created_by=users.id
					WHERE pages.published=1
					AND pages.pagetype='listing'
					AND $where";
					
					$result = $this->query($sql, [$id]);
					
					return $result[0];
	}
	
	public function findAllProducts()
	{
		$sql = "SELECT pages.title, pages.id, pages.type, pages.created_on, pages.published_on FROM products LEFT JOIN pages ON products.page_id=pages.id WHERE pages.pagetype=? ORDER BY pages.published_on DESC";
		$books = $this->getAll($sql, 'product');
		return $books;
	}
	
	public function recent($num=3)
	{
		$sql = "SELECT pages.title, pages.slug, books.level, pages.id, pages.type, pages.created_on, pages.published_on FROM books LEFT JOIN pages ON books.page_id=pages.id WHERE pages.pagetype=? AND pages.published=1 ORDER BY pages.created_on DESC LIMIT $num";
		$books = $this->query($sql, ['product']);
		return $books;
	}
	
	function oneProduct($id)
	{
		if( (int)$id > 0)
		{
			$where = " products.id = ?";
			$binding = [$id];
		}
		else
		{
			$where = " pages.slug=?";
			$binding = [$id];
		}
		$sql = "SELECT 
			pages.title, 
			pages.slug,
			pages.id, 
			pages.pagetype, 
			pages.created_on, 
			pages.published_on, 
			pages.default_image,
			pages.body,
			products.id as prod_id,
			products.inventory,
			products.sku,
			products.page_id
			FROM products 
			LEFT JOIN pages ON products.page_id=pages.id
			WHERE pages.pagetype='product' AND $where";
			
			
			$product = $this->query($sql, $binding);
			return $product[0];
	}
	
	public function getProductAttributes($product_id)
	{
		$sql = "SELECT * FROM attributes where item_id=$product_id AND pagetype='product'";
		return $this->query($sql);
	}
	
	public function getProductImage($id)
	{
		echo $id.',';
		$image = $this->query("SELECT * FROM images WHERE uid=:uid", [':uid'=>$id]);
		return $image[0];
	}
	
	public function getProductImages($id)
	{
		$sql = "SELECT * FROM images LEFT JOIN imagelinks ON images.uid=imagelinks.image_id WHERE imagelinks.item_id=? AND imagelinks.type=?";
		return $this->query($sql, [$id, 'product']);
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
	public function getListing()
	{
		$sql = "SELECT p.id, p.title, p.slug, pr.id as prod_id, pr.num_in_series, pr.inventory FROM products pr LEFT JOIN pages p ON p.id=pr.page_id ORDER BY p.title LIMIT 20";
		return $this->query($sql);
	}
	
}