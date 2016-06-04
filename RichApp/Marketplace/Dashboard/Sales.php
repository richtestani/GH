<?php
namespace Marketplace\Dashboard;
use RichApp\Core\Model;

class Sales {
	
	protected $user;
	
	public function __construct()
	{
		$this->model = new Marketplace\Model\Orders();
		$this->users = new Marketplace\Model\Sellers();
	}
	
	public function recentSales()
	{
		//print_r($_SESSION);
		//$userdata = $this->users->findOne('username', $username);
		$data = [
			'title' => 'My Recent Sales',
			'content' => 'list of sales'
		];
		
		return $data;
	}
	
	public function topSales()
	{
		$data = [
			'title' => 'Top Sales',
			'content' => 'lots of sales'
		];
		
		return $data;
	}
}
?>