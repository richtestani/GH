<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class Orders extends Model {

	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('orders');
        $this->properties = array(
            'id',
			'transaction_id',
			'user_id',
			'total',
			'status',
			'created_on',
			'created_by',
			'modified_on',
			'modified_by',
			'completed_on'
        );
    }

	public function getOrder($session)
	{
		
	}
	
	public function initialize($session, $user)
	{
		
		$uuid = $this->gen_uuid();
		$order['transaction_id'] = $uuid;
		$order['session_id'] = $session;
		$order['created_on'] = $this->created_on();
		$order['created_by'] = $user['id'];
		$this->create($order);
		return $order;
	}
	
	public function fetch($session)
	{
		
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
	private function gen_uuid() {
		
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
	
}