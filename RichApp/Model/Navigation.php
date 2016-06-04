<?php
namespace RichApp\Model;
use RichApp\Core\Model;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Navigation extends Model {
    
    protected $id;
    protected $name;
    protected $href;
    protected $class;
    protected $parent;
    protected $lft;
    protected $rgt;
    protected $date_created;
    protected $created_on;
    protected $modified_on;
    protected $created_by;
    protected $modified_by;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('navigation');
    }
	
	
}