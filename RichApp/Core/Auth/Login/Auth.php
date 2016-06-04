<?php
namespace RichApp\Core\Auth;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Auth
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Login_Auth {
    
    protected $status;
    protected $is_logged_in;
    protected $email;
    protected $password;
    
    public function __construct($email, $password)
    {
        
    }
    
    public function authenticate($email, $password, $stored)
    {
        
    }
    
}
