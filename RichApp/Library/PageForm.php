<?php
namespace RichApp\Library;

class PageForm {
	protected $open;
	protected $title;
	protected $body;
	protected $date_created;
	protected $date_modified;
	protected $file_input;
	protected $images;
	protected $categories;
	protected $tags;
	protected $template;
	protected $page_type;
	protected $id;
	protected $slug;
	protected $byline;
	protected $subtitle;
	protected $author_id;
	protected $status;
	protected $submit;
	protected $close;
	
	protected $form;
	protected $grid = 12;
	
	public function __construct($data = array())
	{
		
		extract($data);
		//default template
		$this->form = array(
			
			'open' => $this->open(),
			
			'maindata' => array(
						'title' => $this->title($title),
						'subtitle' => $this->subtitle($subtitle)
						
					),
			'mainbody' => array('body' => $this->body($body)),
			'publish_info' => array(
				'publish' => array('date_created'=>$this->date_created($date_created), 'status'=>$this->status($status),
				'categories' => $this->categories($cat_select),
				'tags' => $this->tags($tags)
			),
			
				'images' => array(
					'file_input' => $this->file_input(),
					'current_images' => $this->images($images)
				)
			),
			
			'template' => array( 'template' => $this->template($template_select) ),
			
			'submit' => array( 'submit' => $this->submit() ),
				
			'close' => $this->close()
			
		);
	}
	
	public function open($action='/admin/pages/save')
	{
		$this->open = '<form action="'.$action.'" method="post" id="add_page" class="clearfix" enctype="multipart/form-data">';
		if(!empty($action))
		{
			$this->form['open'] = $this->open;
		}
		return $this->open;
	}
	
	public function title( $title = '')
	{
		$this->title = <<<TITLE
			<div class="control-group">
			<label>Title</label>
			<input type="text" name="title" class="form-control" value="$title" />
			</div>
TITLE;

		return $this->title;
	}
	
	public function subtitle( $subtitle = '')
	{
		$this->subtitle = <<<TITLE
			<div class="control-group">
			<label>SubTitle</label>
			<input type="text" name="subtitle" class="form-control" value="$subtitle" />
			</div>
TITLE;
		return $this->subtitle;
	}
	
	public function byline($byline='')
	{
		$this->byline = <<<BYLINE
			<div class="control-group">
			<label>By Line</label>
			<input type="text" name="byline" class="control-group" value="$byline" />
			</div>
BYLINE;
		
		return $this->byline;	
	}
	
	public function body($body = '')
	{
		$this->body = <<<BODY
			<div class="control-group">
			<label>Body</label>
			<textarea name="body" class="form-control">$body</textarea>
			</div>
BODY;
		return $this->body;
	}
	
	public function date_created($date_input='')
	{
		$this->date_created = <<<DATE
			<div class="control-group">
				<label>Publish Date</label>
				<input type="text" name="date_created" class="form-control" value="$date_input" />
			</div>
DATE;
	return $this->date_created;
	}
	
	public function categories($cat_select)
	{
		$this->categories = <<<CATEGORIES
			<div class="control-group">
				<label>Categories</label>
				$cat_select
			</div>
CATEGORIES;
	return $this->categories;
	}
	
	public function images($images)
	{
		$this->images = <<<IMAGES
			<div class="control-group">
				<h3>Page Images</h3>
				<div id="current-images">$images</div>
			</div>
IMAGES;
			return $this->images;
	}
	
	public function tags($tags)
	{
		$this->tags = <<<TAGS
			<div class="control-group">
				<label>Page Tags</label>
				<input type="text" name="tags" value="$tags" />
			</div>
TAGS;
			return $this->tags;
	}
	
	public function file_input()
	{
		$this->file_input = <<<FILE
			<div class="control-group">
				<h3>Image Uploader</h3>
				<div id="response"></div>
				<input type="file" multiple name="images[]" id="images" />
			</div>
FILE;
		return $this->file_input;
	}
	
	public function template($template_select)
	{
		$this->template = <<<TEMPLATE
			<div class="control-group">
			<h2>Template</h2>
			$template_select
			</div>
TEMPLATE;
			return $this->template;
	}
	
	public function status($status)
	{
		$this->status = <<<STATUS
			<div class="control-group checkbox">
				<input name="status" type="checkbox" value="1" $status />
				<label>Publish This</label>
			</div>
STATUS;
			return $this->status;
	}
	
	public function hidden($name, $value)
	{
		$this->hidden[] = '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
		
	}
	
	public function submit()
	{
		$this->submit = '<button class="btn btn-primary" id="submit_button" role="button">Save</button>';
		return $this->submit;
	}
	
	public function close()
	{
		$this->close = '</form>';
		return $this->form;
	}
	
	public function render()
	{

		//insert hidden fields
		$this->insertBeforeItem('submit', implode('', $this->hidden));
		
		$rendering = '';
		foreach($this->form as $k => $v)
		{
			$rendering .= '<div class="row" id="'.$k.'">';
			if(is_array($v))
			{
				$rendering .=$this->parseArray($v);
			}
			else
			{
				$rendering .= $v;
			}
			$rendering .= '</div>';
		}
		
		return $rendering;
	}
	
	private function parseArray($array)
	{
		$html = '';
		$cols = count($array);
		$num = ($this->grid / $cols);
		$classes = "col-lg-".$num." col-md-".$num." col-sm-".$num." col-xs-12";
		foreach($array as $k => $v)
		{
			if(is_array($v))
			{
				$html .= $this->parseArray($v);
			}
			else
			{
				$html .= '<div class="'.$classes.'" id="'.$k.'">';
				$html .= $v;
				$html .= '</div>';
			}
		}
		
		return $html;
		
	}
	
	public function makeRow($id)
	{
		
	}
	
	public function makeColumn()
	{
		
	}
	
	public function insertAfterItem($item, $data)
	{
		
		$form = array();
		foreach($this->form as $k => $v)
		{
			$form[$k] = $v;
			if($k == $item)
			{
				foreach($data as $l => $m)
				{
					$form[$l] = $m;
				}
			}
		}
		
		$this->form = $form;
	}
	
	public function insertIntoItem($item, $data)
	{
		foreach($data as $k => $v)
		{
			$this->form[$item][$k] = $v;
		}
	}
	
	public function insertBeforeItem($item, $data)
	{
		array_push($this->form[$item], $data);
	}
	
	public function overrideItem($item, $newItem)
	{
		
	}
	
	public function hideItem($item)
	{
		
	}
	
	public function insertRowAfter($row, $data)
	{
		
	}
	
	public function appendToRow($row, $data)
	{
		
	}
}	

?>