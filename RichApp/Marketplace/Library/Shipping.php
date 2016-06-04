<?php
namespace Marketplace\Library;

class Shipping {
	
	private $amount;
	private $first;
	private $last;
	private $address1;
	private $address2;
	private $city;
	private $state;
	private $zip;
	private $country;
	private $account;
	
	public function __construct($customer = array())
	{
		if(!empty($customer))
		{
			foreach($customer as $k => $c)
			{
				$this->{$k} = $c;
			}
		}
	}
	
	public function set($name, $value='')
	{
		$this->{$name} = $value;
	}
	
	public function get($name)
	{
		return $this->{$name};
	}
}
?>