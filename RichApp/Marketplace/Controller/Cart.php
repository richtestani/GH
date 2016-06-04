<?php
namespace Marketplace\Controller;
use RichApp\Core\Controller;
use RichApp\Core\Auth;
use Library\Payments;

class Cart extends Controller {
	
	protected $items = [];
	private $cart_session = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new \Marketplace\Library\Marketplace_API();
		$this->orders = new \Marketplace\Model\Orders();
		$this->orderline = new \Marketplace\Model\OrderLine();
        $this->users = new \RichApp\Model\Users();
		$this->sellers = new \Marketplace\Model\Sellers();
		$this->customer = new \Marketplace\Library\Customer();
		$this->shipping = new \Marketplace\Library\Shipping();
		
		$this->settings = $this->system->get('marketplace');
		$payment_class = 'Marketplace\Library\Payments\\'.$this->settings['payment'];
		$this->payment = new $payment_class();

		if($this->session->isRegistered() && array_key_exists('cart_session', $_SESSION))
		{
			$this->session->renew();

			$this->cart_session = $this->session->get('cart_session');
			

			if(is_null($this->user) || empty($this->user))
			{
				$this->user = array(
					'id' => '999999999999999999',
					'username' => 'marketplace_guest'
				);
				$this->session->set('marketplace_user', $this->user);
				if(empty($this->cart_session))
				{
					$order = $this->orders->initialize($this->session->get('session_id'), $this->user);
					$this->session->set('cart_session', $order);
					$this->cart_session = $this->session->get('cart_session');
				}
			}
			else
			{
				if(empty($this->cart_session))
				{
					$order = $this->orders->initialize($this->session->get('session_id'), $this->user);
					$this->session->set('cart_session', $order);
					$this->cart_session = $this->session->get('cart_session');
				}
			}
		}
		else
		{
			
			$this->session->register(10000);
			if(is_null($this->user) || empty($this->user))
			{
				
				$this->user = array(
					'id' => '999999999999999999',
					'username' => 'marketplace_guest'
				);
				$this->session->set('marketplace_user', serialize($this->user));
			}
			else
			{
				if(array_key_exists('marketplace_user', $_SESSION))
				{
					$this->user = unserialize($this->session->get('marketplace_user'));
				}
				
				$this->user = array(
					'id' => '999999999999999999',
					'username' => 'marketplace_guest'
				);
				$this->session->set('marketplace_user', serialize($this->user));
				
			}
			
			$order = $this->orders->initialize($this->session->get('session_id'), $this->user);
			$this->session->set('cart_session', $order);
			$this->cart_session = $this->session->get('cart_session');
		}

	}
	
	public function add()
	{
		
		$item = $this->api->listing($_POST['listing_id']);
		$price = $item['price'] * $_POST['qty'];
		$transaction_id = $this->cart_session['transaction_id'];
		$order = $this->orders->getOne('transaction_id', $transaction_id);
		$total = ($order->total + $price);
		
		
		$line = array(
			'listing_id' => $item['listing_id'],
			'qty' => $_POST['qty'],
			'transaction_id' => $this->cart_session['transaction_id'],
			'item' => serialize($item)
		);
		
		$items = (array_key_exists('items', $this->cart_session)) ? $this->cart_session['items'] : array();

		
		foreach($items as $k => $i)
		{
			if($i['qty'] == 0)
			{
				unset($items['items'][$k]);
			}
			
			if($i['listing_id'] == $line['listing_id'])
			{
				
				$items[$i['listing_id']]['qty'] = ($i['qty'] + $line['qty']);
				$line = $items[$i['listing_id']];
				continue;
			}
		}
		
		if(!array_key_exists($line['listing_id'], $items))
		{
			$items[$line['listing_id']] = $line;
		}
		
		$this->cart_session['items'] = $items;
		$this->orderline->deleteAll($transaction_id);
		$this->session->set('cart_session', $this->cart_session);
		$this->orderline->create($line);
		$this->orders->exec("UPDATE orders SET total=$total WHERE transaction_id='$transaction_id'");
		
		$this->app->redirect('/cart');
	}
	
	public function update($id = 0)
	{
		$transaction_id = $this->cart_session['transaction_id'];
		$order = $this->orders->getOne('transaction_id', $transaction_id);
		$items = (array_key_exists('items', $this->cart_session)) ? $this->cart_session['items'] : array();
		$ids = $this->request->post('item_id');
		$qty = $this->request->post('qty');
		$count = 0;
		$total = 0;
		$this->orderline->deleteAll($transaction_id);
		foreach ($ids as $i) {
			
			$item = $this->api->listing($i);
			$line = array(
				'listing_id' => $item['listing_id'],
				'qty' => $qty[$count],
				'transaction_id' => $this->cart_session['transaction_id'],
				'item' => serialize($item)
			);
			$total = $total + ($line['qty'] * $item['price']);
			$this->orderline->create($line);
			$items[$i] = $line;
			$count++;
		}
		
		$this->cart_session['items'] = $items;
		$this->session->set('cart_session', $this->cart_session);
		$this->orders->exec("UPDATE orders SET total=$total WHERE transaction_id='$transaction_id'");
		$this->app->redirect('/cart');
	}
	
	public function remove($key, $id)
	{
		//unset($this->cart_session['items'][$key]]);
	}
	
	public function index()
	{
		$this->setData('transaction_id', $this->cart_session['transaction_id']);
		$this->setData('api', $this->api);
		$this->render('cart');
	}
	
	public function checkout()
	{
		
		$amount = 0;
		if(array_key_exists('transaction_id', $this->cart_session))
		{
			$transaction_id = $this->cart_session['transaction_id'];
			$order = $this->api->cartItems($transaction_id);
			$amount = $order->total;
			
		}
		$this->setData('cart_settings', $this->system->get('marketplace'));
		$seller = $this->sellers->getOne('user_id', $this->user['id']);
		$this->setData('sellerData', $seller);
		$this->setData('transaction_id', $this->cart_session['transaction_id']);
		$this->setData('api', $this->api);
		$this->render('checkout');
	}
	
	public function connect($auth=true)
	{
		$code = $this->request->get('code');
		if(!is_null($code))
		{
			$connect_client_id = 'ca_7t8Okz94hsxygtBpLZFEAbHcy7KmJteq';
			$csecret_key = 'sk_0Ij2vXX9P0I0KEoqIS7xfuXzbVYIK';
			//Stripe::setApiKey("sk_0Ij2vXX9P0I0KEoqIS7xfuXzbVYIK");
			$oauth_uri = 'https://connect.stripe.com/oauth/authorize?response_type='.$code.'&client_id='.$connect_client_id.'&scope=read_write';
			
			$token_request_body = array(
			    'grant_type' => 'authorization_code',
			    'client_id' => $connect_client_id,
			    'code' => $code,
			    'client_secret' => $csecret_key
			  );
			
				$req = curl_init('https://connect.stripe.com/oauth/token');
			  curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
			  curl_setopt($req, CURLOPT_POST, true );
			  curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
			  
			  $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
			  $resp = json_decode(curl_exec($req), true);
			  curl_close($req);
			
			  print_r($resp);
			$seller = $this->sellers->getOne('user_id', $this->user['id']);
			$seller_update = array('connect_access_code' => $resp['access_token'], 'connected'=>1, 'connect_refresh_code'=>$resp['refresh_token'], 'connect_user_id'=>$resp['stripe_user_id']);
			$this->sellers->update('sellers', $seller_update, $seller->id);
			$this->app->redirect('/user/profile/'.$this->user['username']);
		}
		
	}
	
	public function process()
	{
		/***
		* get the cart from the session
		**/
		$cart = $this->api->cartItems($this->cart_session['transaction_id']);
		
		/***
		* total up the cart with shipping
		**/
		$total = ($cart->total + $this->settings['shipping']);
		
		//build customer
		foreach($_POST as $k => $v)
		{
			if(property_exists($k, $this->customer))
			{
				$this->customer->set($k, $v);
			}
			if(property_exists($k, $this->shipping))
			{
				$this->customer->set($k, $v);
			}
		} 
		
		$this->shipping->set('amount', $this->settings['amount']);
		
		
		$sales_by_seller = array();
		
		foreach($cart->lines as $line)
		{

			$item = unserialize($line['item']);
			$seller = $this->sellers->getOne('user_id', $item['created_by']);
			if(property_exists('connect_user_id', $seller))
			{
				$connect_token = $seller->connect_user_id;
			}
			else
			{
				$error_message = 'Seller cannot sell this item';
				return false;
			}
			$connect_token = $connect_token;
			$sales_by_seller[$connect_token][] = $item['price'];
		}
		
		
		//process orders
		$result = $this->payment->process($sales_by_seller, $total, $this->customer, $this->shipping);
	}
	
	public function thanks()
	{
		$this->setData('api', $this->api);
		$this->render('completed');
	}
	
	public function emptyCart()
	{
		$transaction_id = $this->cart_session['transaction_id'];
		$sql = "DELETE FROM orderline WHERE transaction_id='$transaction_id'";
		$order = $this->orders->getOne('transaction_id', $transaction_id);
		$this->orderline->exec($sql);
		$order->total = 0.00;
		$this->orders->update('orders', $order, $order->id);
		$this->app->redirect('/cart');
	}
	
	public function logout()
	{
		$this->session->end();
		$this->app->redirect('/');
	}
	
}