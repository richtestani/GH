<?php
namespace RichApp\Model;
use RichApp\Interfaces;
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
class Pages extends Model {
    
    protected $id;
    protected $title;
    protected $body;
    protected $categories;
    protected $slug;
    protected $date_modified;
    protected $date_created;
    protected $status;
    protected $template;
    protected $author;
    protected $author_id;
    protected $images;
    
    protected $_table;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('pages');
		$this->properties = array(
			'id',
			'title',
			'body',
			'category',
			'subtitle',
			'default_image',
			'slug',
			'template',
			'published',
			'published_on',
			'status',
			'pagetype',
			'created_on',
			'modified_on',
			'created_by',
			'modified_by',
			'views'
		);
    }

	
	public function findPages($order='date_created', $limit=10)
	{
		$sql = "select * from pages where type='page' AND status=1 ORDER BY ".$order." LIMIT ".$limit;
		$result = \R::getAll($sql);
		
		$result = \R::convertToBeans('pages', $result);
		return $result;
        }
		
	public function getProperties()
	{
		return $this->properties;
	}
}
