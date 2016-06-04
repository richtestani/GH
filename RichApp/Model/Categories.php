<?php
namespace RichApp\Model;
use RichApp\Core\Model;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Categories extends Model {
    
    protected $id;
    protected $category;
    protected $type;
	protected $slug;
    protected $created_on;
	protected $modified_on;
	protected $created_b;
	protected $modified_by;
	protected $lft;
	protected $rgt;
    
    protected $_table;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTable('categories');
    }
    public function categoryHierachy($indented = false)
    {
		$indented = (!$indented) ? 'node.category, (COUNT(parent.category) - 1) as depth' : "CONCAT( REPEAT( '&nbsp;&nbsp;', (COUNT(parent.category) - 1) ), node.category) as name";
            $sql = "SELECT node.id, node.slug, node.categorytype, node.created_on, $indented FROM categories AS node, categories AS parent WHERE node.lft BETWEEN parent.lft  AND parent.rgt AND node.lft > 0 GROUP BY node.category ORDER BY node.lft;";
            return $this->query($sql, []);
    }
    public function getChildren($node)
	{
		$sql = "SELECT node.category, node.slug, node.id, (COUNT(parent.category) - (sub_tree.depth + 1)) AS depth FROM categories AS node, categories AS parent, categories AS sub_parent, ( SELECT node.category, (COUNT(parent.category) - 1) AS depth FROM categories AS node, categories AS parent  WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.slug = ? GROUP BY node.category ORDER BY node.lft )AS sub_tree WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt AND sub_parent.category = sub_tree.category AND node.lft > 0 GROUP BY node.category ORDER BY node.lft;";
		return DB\DBQuery::getAll($sql, [$node]);
	}
    public function getPageCategories($id)
    {
        $sql = "Select * FROM pages WHERE id = ?";
		$result = $this->getAll( array(
									'select'=>'*',
									'where'=> array(
										array('field'=>'id','op'=>'=','value'=>$id)
									)
								)
							);
        //return DB\DBQuery::getAll($sql, array($id));
		return $result;
    }
	
	public function addNode($category, $parent)
	{
		$id = $this->create($category);
		
		$sql = 'update categories set lft='.($parent->lft+1).', rgt='.($parent->lft+2).' WHERE id='.$id;
		$this->query($sql, []);
		
		$record = $this->getOne('id', $id);
		
		if($record->lft > $parent->lft)
		{
			//update nodes
			$left = 'update categories set lft=lft+2 WHERE lft>='.$record->lft.' AND lft > 0 AND id !='.$record->id;
			$this->query($left, []);
		
			$right = 'update categories set rgt=rgt+2 WHERE rgt >= '.$record->lft.' AND id !=' .$record->id;
			$this->query($right, []);

		}

	}
	
	public function editNode($category, $id, $parent)
	{
		$before = $this->getOne('id', $id);
		$this->update($category, $id);
		if($before->parent != $parent->id)
		{
			//move node
			$sql = 'update categories set lft='.($parent->lft+1).', rgt='.($parent->rgt+2).' WHERE id='.$id;
			$this->query($sql, []);
			
			$record = $this->getOne('id', $id);
			
			//update nodes
		
			$right = 'update categories set rgt=rgt+2 WHERE rgt >= '.$before->lft.' AND lft <= '.$before->rgt.' id !=' .$record->id;
			$this->query($right, []);
		}
		
	}

}