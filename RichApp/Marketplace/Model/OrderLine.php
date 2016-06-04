<?php
namespace Marketplace\Model;
use RichApp\Core\Model;
use RichApp\Model as Models;

class OrderLine extends Model {

	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('orderline');
        $this->properties = array(
            'id',
			'listing_id',
			'item',
			'order_id',
			'qty',
			'transaction_id'
        );
    }
	
	
	public function deleteAll($transaction_id)
	{
		$sql = "DELETE FROM orderline WHERE transaction_id='$transaction_id'";
		$this->exec($sql);
	}
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}