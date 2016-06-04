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
 * Description of Pages
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Images extends Model {
    
    protected $id;
    protected $filename;
    protected $caption;
    protected $width;
    protected $height;
    protected $mime;
    protected $path;
    
    protected $_table;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('images');
    }
	
    public function findPageImages($id)
    {echo $id;
        $sql = "SELECT * FROM images LEFT JOIN imagelinks ON images.id=imagelinks.image_id WHERE imagelinks.page_id=".$id;
        $results = $this->getAll($sql);

        return $results;
    }
	
    
}
