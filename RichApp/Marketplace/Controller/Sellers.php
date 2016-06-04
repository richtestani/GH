<?php
	
namespace Marketplace\Controller;
use RichApp\Controller;
use RichApp\Core\Auth;
use RichApp\Model;

class Sellers extends Controller\Users {
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new \Marketplace\Library\Marketplace_API();
		$this->listings_model = new\Marketplace\Model\Listings();
		$this->seller_model = new \Marketplace\Model\Sellers();
		$this->setData('api', $this->api);
	}
	
	public function index()
	{
		
		$this->render('listing');
	}
	
	public function usersellers()
	{
		$sellers = $this->seller_model->getAll();
		$this->setData('sellers', $sellers);
		$this->render('listing');
	}
	
	public function profile($username)
	{
		$oneuser = $this->users_model->getOne('username', $username);
		$listings = $this->listings_model->listingsByUser($oneuser->id);
		$seller = $this->seller_model->getOne('user_id', $oneuser->id);
		$this->setData('sellerdata', $seller);
		$this->setData('listings', $listings);
		parent::profile($username);
	}
	
    public function single($slug)
	{
		$this->setData('slug', $slug);
		$this->setData('api', $this->api);
		$this->render('single');
	}
	
}
?>