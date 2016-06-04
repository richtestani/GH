<?php
namespace Marketplace\Model;
use RichApp\Core\Model;

class Sellers extends Model {
	
	protected $id;
	protected $user_id;
	protected $address1;
	protected $rating;
	
	public function __construct()
    {
		parent::__construct();
        $this->setTable('sellers');
        $this->properties = array(
            'id',
			'user_id',
			'address1',
			'city',
			'state',
			'zip',
			'rating',
			'connect_access_code',
			'connected'
        );
    }
	
	public function getUser($user_id)
	{
		$sql = "SELECT * FROM users WHERE id=?";
		$user = $this->getAll($sql, [$user_id]);
		return $user[0];
	}
	
	public function findAllSellers()
	{
		$sql = "SELECT * FROM users";
		$users = $this->getAll($sql);
		return $users;
	}
	
	
	public function getProperties()
    {
        return $this->properties;
    }
	
}