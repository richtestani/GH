<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Listings extends Model {
	
	protected $id;
	protected $page_id;
	protected $url;
	protected $rating;
	protected $website;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('listings');
        $this->properties = array(
            'id',
            'seller_id',
			'created_on',
			'modified_on',
			'qty',
			'price',
			'views',
			'created_by',
			'modified_by',
			'page_id',
			'sold'
        );
    }
	
	public function findOneListing($id)
	{
		$sql = "SELECT
			pages.id,
			pages.title,
			pages.slug,
			pages.created_on,
			pages.created_by,
			pages.default_image,
			pages.published_on,
			pages.views,
			listings.id as listing_id,
			listings.price,
			listings.views,
			listings.qty,
			users.id as user_id,
			users.slug as user_slug,
			users.username
			FROM pages
			LEFT JOIN listings ON listings.page_id=pages.id
			LEFT JOIN users ON users.id=pages.created_by
			WHERE pages.published=1
			AND pages.slug=$id";

			return $this->getAll($sql);
	}
	
	public function findAllListings()
	{
		$sql = "SELECT p.id, p.title, l.price, p.created_on, p.published_on FROM listings l LEFT JOIN pages p ON l.page_id=p.id WHERE p.type='listing' ORDER BY p.created_on";
		return $this->getAll($sql);
	}
	
	public function listingsByUser($id)
	{
		
		$sql = "SELECT  FROM listings l LEFT JOIN pages p ON  WHERE p.type='listing'  AND p.created_by = ? AND l.sold=0 ORDER BY p.created_on";
		$args = array(
			'select' => 'p.id, p.slug, p.title, l.price, p.created_on, p.published_on, l.sold',
			'from' => 'listings l',
			'join' => ['table'=>'pages p', 'on'=> 'l.page_id=p.id'],
			'where' => [
				['field'=>'p.pagetype', 'op'=>'=', 'value'=>'listing'],
				['field'=>'p.created_by', 'op'=>'=', 'value'=>$id, 'andor'=>'AND'],
				['field'=>'l.sold', 'op'=>'=', 'value'=>0, 'andor'=>'AND']
			],
				'order' => ['by'=>'p.created_on']
		);
		$result = $this->getAll($args);
		return $result;
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}