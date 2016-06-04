<?php

namespace Marketplace\Library\Payments\Stripe;
use Marketplace\Interfaces\Payment;
use Stripe\Stripe;

class StripePayment implements Payment {
	
	protected $customer;
	protected $token;
	protected $charges = array();
	
	public function __construct()
	{
		Stripe::setApiKey("sk_0Ij2vXX9P0I0KEoqIS7xfuXzbVYIK");
	}
	
	public function process($sales, $total, $customer, $shipping)
	{
		$token = $_POST['stripeToken'];
		
		setlocale(LC_MONETARY, 'en_US.UTF-8');
		$stripeCustomer = $this->createCustomer($token, $this->customer->get('email'));
		
		foreach($sales as $account => $sum)
		{
			$amount = array_sum($sum);
			$this->createToken($account, 'customer', $stripeCustomer->id);
			$this->createCharge($account, $token, $amount);
		}
		
		return $this->charges;
		
	}
	
	private function createToken($account, $type, $id)
	{
		$this->token = \Stripe\Token::create(
			  array($type => $id),
			  array("stripe_account" => $t) // id of the connected account
			);
	}
	
	private function createCustomer($token, $email)
	{
		$this->customer = \Stripe\Customer::create( array(
			  "description" => "Customer for ".$email,
			  "source" => $token // obtained with Stripe.js
			));
	}
	
	private function createCharge($customer, $token, $total)
	{
		
		try {
  		  $charge = \Stripe\Charge::create(array(
  		    "amount" => $this->formatMoney($total), // amount in cents, again
  		    "currency" => "usd",
  		    "source" => $this->token->id,
  			'receipt_email' => $_POST['email'],
  		    "description" => "GH Collectables",
  			"metadata" => array('order_id' => $_SESSION['transaction_id']),
			'application_fee' => '10',
  			"shipping" => array(
  					'name' => $_POST['first'].' '.$_POST['last'], 
  					'carrier' => 'USPS', 
  					'address'=>array(
  						'city'=>$_POST['city'], 
  						'postal_code'=>$_POST['zip'], 
  						'state'=>$_POST['state'],
  						'line1' => $_POST['line1']
  						)
  					)
  		    ), array('stripe_account'=>$t));
			
			array_push($this->charges, $charge);

		}
		catch(\Stripe\Error\Card $e) {
			$this->charges[]['error'][] =  $e->getMessage();
		}
	}
	
	public function formatMoney($amount)
	{
		return str_replace(array('$','.'),'',$amount);
	}
}
	
?>