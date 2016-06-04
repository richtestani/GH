<?php
namespace Model;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Files extends \Core\Model {
    
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'files';
    }
    
}