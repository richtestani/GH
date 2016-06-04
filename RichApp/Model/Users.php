<?php
namespace RichApp\Model;
use RichApp\Core\Model;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Users
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Users extends Model {
    
    protected $id;
    protected $username;
    protected $email;
    protected $role;
    protected $avatar;
    protected $password;
	protected $properties;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('users');
		$this->properties = array(
			'id',
			'username',
			'email',
			'first',
			'last',
			'role',
			'last_login',
			'created_on',
			'modified_on',
			'created_by',
			'modified_by',
			'active',
			'slug',
			'activation'
		);
    }
	
	public function searchUser($data)
	{
		extract($data);
		$sql = "SELECT * FROM users WHERE email='$email' OR username=?";
		return $this->query($sql, [$username]);
		
	}
	
	public function getOneUser($username)
	{
		
		$sql = "SELECT id, username, first, last, email FROM users WHERE username=?";
		$result = $this->query($sql, [$username]);
		return $result;
		
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
	
    
    
}
