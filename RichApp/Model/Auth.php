<?php
namespace Model;
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
class Auth extends \Core\Model {
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('users');
    }
}
