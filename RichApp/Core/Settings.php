<?php
namespace RichApp\Core;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Settings extends Model {
    
    protected $all_settings = array();
	protected $properties = array(
		'id',
		'name',
		'value',
		'created_on',
		'modified_on',
		'created_by',
		'modified_by',
		'inputtype',
		'inputvalues',
		'class',
		'description'
	);
	
	static protected $ignoreDir = array(
		'Admin',
		'Controller',
		'Core',
		'Installer',
		'Interfaces',
		'Library',
		'Model',
		'Plugins',
		'vendor'
	);
	
	static protected $filesystem;
    
    public function __construct($setup = false)
    {   
        if( $setup )
        {
            parent::__construct();
			$this->setTable('settings');
			self::$filesystem = new Filesystem(new Adapter(APPROOT));
			
            $all_settings = $this->getAll(array('limit'=>9999));
			
            foreach( $all_settings as $s )
            {
                $class = $s['class'];
                $name = $s['name'];
                $val = $s['value'];

                $this->all_settings[$class][$name] = $val;
            }

			$this->setDefaults($this->all_settings);
        }
		
    }
    
    
    
    public function get( $name )
    {
        if( array_key_exists($name, $this->all_settings) )
        {
            return $this->all_settings[$name];
        }
        
        return false;
    }
    
    
    public function set( $setting )
    {
        extract($setting);
        //check if property exists in db
        $settings = array(
            'class' => $class,
            'name' => $name
        );
        $this->setTable('settings');
        $result = '';
        
        
        if(!empty($result))
        {
           //update setting
           $id = $result->id;
           $settings['value'] = $value;
           $this->update($settings, $id);
        }
        else
        {
            //write setting
            $settings['value'] = $value;
			$settings['created_on'] = $this->created_on();
            $this->create($settings);
        }
    }
    
    public function __get($name)
    {
        echo 'getting::'.$name.'<br>';
    }
	
	public function getProperties()
	{
		return $this->properties;
	}
	
	static public function menuCallback($path = '', $filter = array())
	{
		
		$ignore = self::$ignoreDir;
		
		$allDirs = self::$filesystem->listContents($path);
		
		$func = function() use ($path, $ignore, $allDirs, $filter)
		{
			$values = array(array('name'=>'default', 'value'=>'default'));
			
			$names = ['default'];
			
			foreach($allDirs as $f)
			{
				$filename = $f['filename'];
				$type = $f['type'];
				
				if(empty($filename) || $type == 'file')
				{
					continue;
				}

				if(!in_array($filename, $names))
				{
					$values[] = [
						'name' => $filename,
						'value' => $filename
					];
				}
				
				$names[] = $filename;
			}

			return $values;
		};
		
		return $func;
	}
	
	static public function pathsMenu($path)
	{
		
		$allDirs = self::$filesystem->listContents($path);
		
		$func = function() use ($allDirs)
		{
			//$values = array(array('name'=>'default', 'value'=>'default'));
			
			foreach($allDirs as $f)
			{
				$filename = $f['filename'];
				$type = $f['type'];
				
				if(empty($filename) || $type == 'file')
				{
					continue;
				}
				
				$values[] = [
					'name' => $filename,
					'value' => $filename
				];
			}
			
			return $values;
		};
		
		return $func;
	}
    
}