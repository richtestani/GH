<?php

/*
 * Verifies user existance and setups a session
 * It also helps maintain a user session
 */

namespace RichApp\Core;
use rcastera\Browser\Session\Session;
/**
 * Description of Auth
 *
 * @author richardtestani
 */
class Auth {
    static protected $status;
    static protected $login_length = 30; //minutes
    static protected $login_timer = 0;
    static protected $last_login = 0;
    static protected $login_activity;
    static protected $authenticated;
    static protected $date;
	static protected $session;
    
    public static function initialize()
    {
		Auth::$session = new Session();
		//Auth::updateActivity();
    }
    
    static public function authenticate($password, $stored)
    {
        
        if( password_verify($password, $stored) )
        {
            Auth::$session->register(300);
			Auth::$authenticated = true;
            return true;
        }
        else {
            return false;
        }
                
    }
	
	public static function set($name, $value)
	{
		Auth::$session->set( $name, $value );
	}
	
	public static function get($name)
	{
		$value = array();
		if(array_key_exists($name, $_SESSION))
		{
			$value = Auth::$session->get($name);
		}
		
		return $value;
		
	}
    
    public static function loginStatus()
    {
        return Auth::$authenticated;
    }
    
    
    static public function updateActivity()
    {
        if( Auth::$session->isRegistered() )
		{
			if(!Auth::$session->isExpired())
			{
				Auth::$authenticated = true;
				Auth::$session->renew();
			}
			else
			{
				Auth::$session->end();
			}
		}
    }
	
	static public function destroy()
	{
		Auth::$session->end();
	}
	
	static public function isAuthenticated()
	{
		return Auth::$session->isRegistered();
	}
    
    static public function login_timer()
    {
        return $_SESSION['LOGIN_TIMER'];
    }
	
	static public function generatePasswordHash($password)
	{
		$hash = password_hash($password, PASSWORD_BCRYPT);
		return $hash;
	}
}
