<?php
namespace RichApp\Core;
use RichApp\Core\DB\DBQuery;
use RichApp\Library;

/* 
 * Author: Richard Testani
 * Designed For RichApp
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 * 
 * Base CRUD Model
 * @version 0.6
 */

class Model {
    
    protected $created_on;
    protected $modified_on;
    protected $created_by;
    protected $modified_by;
    
    protected $date;
    private $table;
	private $properties =array();
	private $query;
	
	public function __construct()
	{
		 $this->date = new \DateTime();
		 $this->created_on = $this->date->format('Y-m-d H:i:s');
		 if(file_exists(APPPATH.'/RAConfig.php') )
		 {
		 	$config = require APPPATH.'/RAConfig.php';
			$config = $config['db'];
            $dsn = 'mysql:host='.$config['host'].';dbname='.$config['dbname'];
            $user = $config['dbuser'];
            $password = $config['dbpass'];
		
			$config = array(
				'dsn' => $dsn,
				'username' => $user,
				'password' => $password
			);
			$this->query = new Library\Query($config);
			$this->setTable(get_called_class());
		 }
	}
	
	public function setDefaults($config)
	{
		$pages_per = $config['pages']['num_posts_per_page'];
		$this->query->setDefaultLimit($pages_per);
	}
	
	public function setTable($table)
	{
		$path = explode("\\", $table);
		$table = $path[count($path)-1];
		$this->table = $table;
	}
	
	public function created_on()
	{
		return $this->created_on;
	}
    public function slug($string, $delimiter = '-')
    {
        $remove = array('!', '@', '$', '%', '?', '&', '*', '(', ')', '=', '{', '}', '[', ']', '|', '/', '<', '>', ',', ':', ';', '"');
        $replace = array('-', '.', '_', '+', '\'', ' ');
        
        $string = str_replace($remove, '', trim($string));
        $string = trim(strtolower(str_replace($replace, $delimiter, $string)));
        
        return $string;
    }
	
	public function query($query, $bindings)
	{
		return $this->query->query($query, $bindings);
	}
	
	public function getAll($args=array(), $convert = true)
	{
		if(is_string($args))
		{
			$this->table = $args;
			$args = array();
		}
		$args['from'] = (!array_key_exists('from', $args)) ? $this->table : $args['from'];
		
		$query = $this->query->getAll($args);
		
		$results = $this->query->getResults($query, $convert);

		return $results;
	}
	
	public function getOne($field, $value, $convert = true)
	{
		$field = (is_array($field)) ? implode(',', $field) : $field;
		$where = array( 
					array('field'=>$field, 'op'=>'=', 'value'=>$value) 
				);
	
		$args['select'] = $field;
		$args['from'] = $this->table;
		$args['where'] = $where;
		$args['value'] = $value;

		$result = $this->query->getOne($args);

		return $result;
	}
	
	public function deleteWhere($args)
	{
		$this->query->deleteWhere($this->table, $args);
	}
    
	public function getProperties()
    {
        return $this->properties;
    }
	
	/*
		CRUD
	*/
	public function update($data)
	{
		if(!array_key_exists('id', $data))
		{
			return null;
		}
		$id = $data['id'];
		unset($data['id']);
		$this->query->update($this->table, $data, $id);
	}
	
	public function create($data)
	{
		return $this->query->create($this->table, $data);
	}
	
	public function delete($id)
	{
		if($this->exists(array('field'=>'id', 'value'=>$id)))
		{
			$data = array(
				'where' => array(
					array('field'=>'id', 'op'=>'=', 'value'=>$id)
				)
			);
			
			$this->query->delete($this->table, $data);
		}
		
	}
	
	public function save($data)
	{
		
		if(!array_key_exists('id', $data))
		{
			$this->create($data);
		}
		else
		{
			$this->update($data);
		}
	}
	
	public function exists($data)
	{
		extract($data);
		$record = $this->getOne($field, $value);
		
		$exists = (!empty($record)) ? true : false;
		return $exists;
	}
	
	public function count($args=array())
	{
		return $this->query->count($this->table, $args);
	}
	
	public function exec($query)
	{
		$this->query->exec($query);
	}
}