<?php
	
namespace Marketplace\Controller;
use RichApp\Core\Controller;
use RichApp\Core\Auth;
use RichApp\Model;

class Listings extends Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new \Marketplace\Library\Marketplace_API();
	}
	
	public function index()
	{
		$this->setData('api', $this->api);
		$this->render('home');
	}
	
    public function single($slug)
	{
		$this->setData('slug', $slug);
		$this->setData('api', $this->api);
		$this->render('single');
	}
	
}
?>