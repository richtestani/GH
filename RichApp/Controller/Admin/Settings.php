<?php
namespace RichApp\Controller\Admin;
use RichApp\Core;
use RichApp\Controller;
use RichApp\Model;
use RichApp\Library;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Settings
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Settings extends Controller\Admin {
    
    protected $all_settings;
	protected $default_classes = array();
    
    public function __construct()
    {
        parent::__construct();
        
		$this->default_classes = [
			'app',
			'theme',
			'image',
			'pages'
		];
		
        $themes = $this->system->get('theme');
		
        $package = $themes['current_theme'];
        $theme_path = PUBPATH . DS . 'themes' . DS . $package;
        if(!file_exists($theme_path))
        {
            $theme_path = PUBPATH . DS . 'themes' . DS . 'default';
        }
        $this->current_theme = new Core\Theme($theme_path);
        $this->current_theme->setThemeName($package);
        
    }
    
    public function index($page=1)
    {
		$classes = "'".implode("', '", $this->default_classes)."'";
		
		$all = $this->system->getAll(array(
			'offset'=>$page,
										'select'=>'id, name, value, class, inputtype'
									)
								);
		$page_settings = $this->system->get('pages');
		$numrecords = $this->system->count();
		$this->pagination->setTotalNumPages($numrecords, $page_settings['num_posts_per_page'])
			->setBaseLink('/admin/settings')->setBasePageLink('page')->currentPageNum($page);
		$pagination = $this->pagination->get();
		
		$this->setData('pagination', $pagination);
       $this->setData('baselink', '/admin/settings/'); 
        $this->setData('page_title', 'Settings Listing');
        $this->setData('listing', $all);
        $this->render('settings');
        
    }
    public function delete($id=0)
    {
        $id = (int)$id;
        if($id > 0)
        {
            $this->system->delete($id);
        }
        
        $this->app->redirect('/admin/settings');
    }
    public function create()
    {
        
		$this->buildForm(array('action'=>'/admin/settings/save', 'record'=>array(), 'mode'=>'create'));
        $this->setData('page_title', 'New Setting');
        $this->setData('form', $this->forms->get());
        $this->render('add-edit');
    }
    
    public function edit($id)
    {
		if(is_numeric($id))
		{
			$field = 'id';
			$mode = 'modify';
		}
		else
		{
			$field = 'class';
			$mode = 'edit';
		}
		
		$args = array(
			'where' => array(
				array('field'=>$field, 'op'=>'=', 'value'=>$id)
			)
		);

        $setting = $this->system->getAll($args);
        $this->buildForm(array('action'=>'/admin/settings/save/'.$id, 'record'=>$setting, 'mode'=>$mode));
        $this->setData('page_title', $id.' Settings');
        $this->setData('form', $this->forms->get());
        $this->render('add-edit');
    }
    
    public function save($id=0)
    {
		
		/*
			if id is a number and class is null, then we update a single entry.
			otherwise if id is null and class is string, we update many values
		*/
		
        $class = null;
		if(is_numeric($id))
		{
			$id = $id;
			$page = $id;
		}
		else
		{
			$class = $id;
			$id = null;
			$page = $class;
		}
		
        if(is_null($id) && !empty($class))
		{
			
			
			/*
			[id] => 1
            [name] => x-small
            [value] => 100
            [created_on] => 2016-01-20 00:00:00
            [modified_on] => 2016-01-20 12:48:31
            [created_by] => 1
            [modified_by] => 1
            [inputtype] => text
            [class] => image
            [inputvalues] => 
            [description] => 
			*/
			$args = array(
				'where' => array(
					array('field'=>'class', 'op'=>'=', 'value'=>$class)
				)
			);
			
			$records = $this->system->getAll($args);
			
			foreach($records as $k => $v)
			{
				$name = $v['name'];
				$value = $this->request->post($name);
				$setting_id = $v['id'];
				$setting = [
					'id' => $setting_id,
					'value' => $value,
					'modified_on' => $this->system->created_on()
				];
				$this->system->update($setting);
				
			}
			
			
		}
		else
		{
			
			$record = $this->system->getOne('id', $id);
			
			$setting =[
				'id' => $id,
				'name' => $this->request->post('name'),
				'value' => $this->request->post('value'),
				'inputtype' => $this->request->post('inputtype'),
				'inputvalues' => $this->request->post('inputvalues'),
				'class' => $this->request->post('class'),
				'description' => $this->request->post('description'),
				'modified_by' => $this->user['id']
			];
			
			if(!empty($record))
			{
				$this->system->update($setting);
			}
			else
			{
				$setting =[
					'name' => $this->request->post('name'),
					'value' => $this->request->post('value'),
					'inputtype' => $this->request->post('inputtype'),
					'inputvalues' => $this->request->post('inputvalues'),
					'class' => $this->request->post('class'),
					'description' => $this->request->post('description'),
					'modified_by' => $this->user['id']
				];
				
				$page = $this->system->create($setting);
			}
		}
		
		
		$this->app->redirect('/admin/settings/edit/'.$page);
    }
	
	public function buildForm($data)
	{
		extract($data);

		$this->forms = new Library\HTML\Form($action, 'post');
		$prop = $this->system->getProperties();
		$settings = array();
		$inputs = array(
			array('name'=>'Text', 'value'=>'text'),
			array('name'=>'Select', 'value'=>'select'),
			array('name'=>'Radio', 'value'=>'radio'),
			array('name'=>'Checkbox', 'value'=>'checkbox'),
			array('name'=>'Select', 'value'=>'select'),
			array('name'=>'Textarea', 'value'=>'textarea')
		);
		
		if($mode == 'edit')
		{
			foreach($record as $k => $v)
			{
	 		   $pattern = "/(?<callback>[\\\\\\w:_]+)(\\((?<args>[\\w,\\s\\d\\/\'\"]+)\\)){0,}/";
	 		   preg_match($pattern, $v['inputvalues'], $matches);
			   
			   //echo $v['inputvalues'];
			   $inputValues = $v['inputvalues'];
			   if(array_key_exists('callback', $matches))
			   {
				   //$test = Core\Settings::menuCallback();
				   //print_r($matches);
				   $args = (array_key_exists('args', $matches)) ? $matches['args'] : '';
				   
				   $classPath = $matches['callback'];
				   $func = \RichApp\Core\Settings::menuCallback($args);

				   //$func = $classPath($args);
				   $inputValues = $func();
				   
			   }
			   
				$this->forms->insertInput($v['inputtype'], $v['name'], $v['value'], array('class'=>'form-control'), $inputValues);
				$this->forms->setLabel(ucwords(str_replace('_', ' ', $v['name'])), $v['name']);
			}
		}
		else
		{
			//get first and only record
			if(!empty($record))
			{
				$record = $record[0];
			}
			foreach($prop as $p)
			{
				$settings[$p] = (array_key_exists($p, $record)) ? $record[$p] : '';
			}
			
			$this->forms->insertDiv('form-els','control-group');
		
			$this->forms->insertInput('text', 'name', $settings['name'], array('class'=>'form-control'));
			$this->forms->setLabel('Name', 'name');
			$this->forms->insertInput('text', 'value', $settings['value'], array('class'=>'form-control'));
			$this->forms->setLabel('Value', 'value');
		
			$this->forms->insertInput('text', 'class', $settings['class'], array('class'=>'form-control'));
			$this->forms->setLabel('Class', 'class');
			$this->forms->insertInput('select', 'inputtype', $settings['inputtype'], array('class'=>'form-control'), $inputs);
			$this->forms->setLabel('Input Type', 'inputtype');
			$this->forms->insertInput('text', 'inputvalues', $settings['inputvalues'], array('class'=>'form-control'));
			$this->forms->setLabel('Input Values', 'inputvalues');
			$this->forms->insertInput('text', 'description', $settings['description'], array('class'=>'form-control'));
			$this->forms->setLabel('Description', 'description');
		}
		

		
	}
    
    
    public function json_panel($page_id=0)
   {
	   $categories = new Model\Categories();
	   $pages = new Model\Pages();
	   $images = new Model\Images();
	   
	   $settings = [];
	   $templateName = '';
       $selected = 0;
       $status = 0;
       $image = '';
       $imageUID = '';
	   $catid = 0;
	   $default = array(array('value'=>0, 'name'=>'None'));
	   
	   $args = array(
		   'limit' => 9999,
		   'select' => 'id as value, category as name',
		   'where' => array(
			   array('field'=>'categorytype', 'op'=>'!=', 'value'=>'root')
		   )
	   );
	   $all_categories = $categories->getAll($args);
	   
	   
	   //get page values
	   if($page_id > 0)
	   {
	   	$page = $pages->getOne('id', $page_id);
		$catid = $page->category;
		$status = $page->status;
		$findImage = $images->getOne('uid', $page->default_image);
		if (!empty($findImage)) {
			$image = '<img src="'.$findImage->public_path.DS.'thumbnail'.DS.$findImage->filename.'" />';
		}
	   }
	   
	   
       $themes = $this->system->get('theme');
       $theme_path = PUBPATH . DS . 'themes' . DS . $themes['current_theme'];
       $templates = $this->current_theme->getAvailableTemplates($this->filesystem);
       foreach($templates as $k => $v)
       {
		   
           $templates[$k]['name'] = $v['Template'];
           $templates[$k]['value'] = $v['filename'];
       }
	   
       $templateInput = new Library\HTML\Input('select', 'template', $templateName, array('class'=>'form-control panel-item-changable'), $templates);
       $templateInput->label('Templates');
	   $catSelect = new Library\HTML\Input('select', 'category', $catid, array('class'=>'form-control panel-item-changable', 'id'=>'category'), array_merge($default, $all_categories));
	   $catSelect->label('Category');
	   
	   $settings=['templates'=>$templateInput->get(), 'categories'=>$catSelect->get()];
	   $pubSelect = new Library\HTML\Input('select', 'published', $status, array('class'=>'form-control panel-item-changable', 'id'=>'published'), array(array('name'=>'Draft', 'value'=>0), array('name'=>'Published', 'value'=>1)));
	   $pubSelect->label('Publish');
	   $settings['published'] = $pubSelect->get();
	   
	   $def_image = '<a href="javascript:;" class="add-image-default add-image" data-add-image-type="default">Set Default Image</a><div id="default_image_show">'.$image.'</div>';
	   $settings['default_iamge'] = $def_image;
	   echo json_encode($settings, JSON_FORCE_OBJECT);
   }
}
