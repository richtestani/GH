<?php
namespace RichApp\Controller\Admin;
use RichApp\Controller;
use RichApp\Model;
use RichApp\Library;
use RichApp\Core;
use RichApp\Interfaces;
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
class Pages extends Controller\Admin implements Interfaces\iPageController {
    
    protected $pages_model;
    protected $form_data = array();
	protected $type = null;
	private $pagetype;
	protected $pagination;
    
    public function __construct()
    {
        parent::__construct();
        $this->pages_model 		= new Model\Pages();
		$this->tags_model 		= new Model\Tags();
		$this->taglinks_model 	= new Model\TagLinks();
		
		$this->setPageType('page');
		
		$this->loadPanels();
		
    }
	
	public function setPageType($type)
	{
		$this->pagetype = $type;
	}

    	
    public function index($page=1)
    {
        
		/*
		* Configure page listing query
		*/
        $args = array(
			'select' => 'id, title, created_on, published, published_on',
			'order' => array('by'=>'created_on', 'dir'=>'desc'),
			'where' => array(
				array('field'=>'pagetype', 'op'=>'=', 'value' =>'page')
			)
        );
		$listing = $this->pages_model->getAll($args);
		
		$numrecords = $this->pages_model->count($args);
		$page_settings = $this->system->get('pages');
		
		$this->pagination->setTotalNumPages($numrecords, $page_settings['num_posts_per_page'])
			->setBaseLink('/admin/pages')->setBasePageLink('page')->currentPageNum($page);
		$pagination = $this->pagination->get();
		
		$this->setData('pagination', $pagination);
		
		/*
		* Fetch page records
		*/
		
        $this->setData('page_title', 'Pages Listing');
        $this->setData('listing', $listing);
        $this->render('listing');
        
    }
    public function create()
    {
        $this->buildForm(array('action'=>'/admin/pages/save'));
        $this->setData('form', $this->forms->get());
		$this->setData('page_title', 'New Page');
        echo $this->render('add-edit');
    }

	
	public function edit($id=null)
	{
		$id = (int)$id;
		if($id < 1 || is_null($id)) {
			return false;
		}
		
        $page = $this->pages_model->getOne('id', $id);
        $this->forms = new Library\HTML\Form('/admin/pages/save/'.$id, 'post');
        
        $this->buildForm(array('action'=>'/admin/pages/save/'.$id, 'pagedata'=>$page));
        
        $this->setData('form', $this->forms->get());
        $this->setData('page_id', $page->id);
		
        $this->render('add-edit');
	}
        
    public function delete($id=null)
    {
        if($id > 0 && !is_null($id))
        {
            $this->pages_model->delete($id);
        }
        
		$this->afterDelete([$id]);
        $this->app->redirect('/admin/pages');
    }
	
	public function save($id=null)
	{
		$this->beforeSave([$id]);
		
		$page = [];
		$properties = $this->pages_model->getProperties();
		foreach($properties as $k)
		{
			$page[$k] = (is_null($this->request->post($k))) ? 0 : $this->request->post($k);
		}
		
		$page['id'] = $id;
		$page['pagetype'] = $this->pagetype;
		$page['slug'] = $this->pages_model->slug($this->request->post('title'));
		$page['category'] = $this->request->post('page_category');
		
		switch( $this->request->post('publish_status') )
		{
			case 1:
			$page['status'] = 1;
			$page['published'] = 1;
			$page['published_on'] = $this->pages_model->created_on();
			break;
			
			default:
			$page['status'] = 0;
			$page['published'] = 0;
		}
		
		if(!is_null($id) && !$this->pages_model->exists(['field'=>'id', 'value'=>$id]))
		{
			$page['created_on'] = $this->pages_model->created_on();
			$page['created_by'] = $this->user['id'];
			$id = $this->pages_model->create($page);
		}
		else
		{
			$page['modified_on'] = $this->pages_model->created_on();
			$page['modified_by'] = $this->user['id'];
			$this->pages_model->update($page);
		}
		
		$tags = $this->tags_model->addTags($this->request->post('tags'), $id, $this->user['id']);
		$this->taglinks_model->addLinks($tags, $id, $this->user['id']);

		$this->afterSave(['id'=>$id]);
		
		$this->app->redirect($this->data['redirectlink']);
		
	}
	
	public function buildForm($data=array())
	{
		
		$data = $this->beforeForm($data);
		extract($data);
		$this->forms = new Library\HTML\Form($action, 'post');
		$props = $this->pages_model->getProperties();
		$properties = array();
		$page_title = (!isset($pagedata)) ? 'Untitled Page' : $pagedata->title;
		$tags = array();
		$this->setData('page_title', $page_title);
		
		//build form data
        if(isset($pagedata))
        {
            foreach($pagedata as $k => $p)
            {
                $properties[$k] = $p;
            }
        }
		else
		{
            foreach($props as $p)
            {
                $properties[$p] = '';
            }
		}
		
		//set id to 0 if were not filling in the form
		if(!isset($pagedata))
		{
			$properties['id'] = 0;
		}
		
		//fill in tag info
		if(isset($pagedata))
		{
			$tagData = $this->tags_model->getPageTags($pagedata->id);
  		   foreach($tagData as $t)
  		   {
  			   $tags[] = $t['tag'];
  		   }
		}
		
		//format tags
		$tags = (empty($tagData)) ? '' : implode(', ', $tags);
		$properties['tags'] = $tags;
		
		//the form construction
        $this->forms->insertDiv('main-body');
		$this->forms->insertInput('text', 'title',$properties['title'], array('class'=>'form-control', 'id'=>'title', 'placeholder'=>'Title'));
        $this->forms->insertInput('textarea', 'body',$properties['body'], array('class'=>'form-control', 'id'=>'body', 'contenteditable'=>''));
        $this->forms->endDiv();
        $this->forms->insertDiv('smalltext');
        $this->forms->insertInput('text', 'subtitle',$properties['subtitle'], array('class'=>'form-control', 'id'=>'subtitle', 'placeholder'=>'Subtitle'));
        $this->forms->endDiv();
        //page meta
        $this->forms->insertDiv('meta');
		$this->forms->insertInput('hidden', 'created_on', $properties['created_on'], array('id'=>'created_on'));
        $this->forms->insertInput('hidden', 'page_category', $properties['category'], array('id'=>'page_category'));
        $this->forms->insertInput('hidden', 'publish_status', $properties['published'], array('id'=>'publish_status'));
        $this->forms->insertInput('hidden', 'tags', $properties['tags'], array('id'=>'tags-values'));
        $this->forms->insertInput('hidden', 'default_image', $properties['default_image'], array('id'=>'default_image'));
        $this->forms->insertInput('hidden', 'template', $properties['template'], array('id'=>'page_template'));
        $this->forms->insertInput('hidden', 'id', $properties['id'], array('id'=>'page_id'));
        $this->forms->insertInput('hidden', 'published_on', $properties['published_on'], array('id'=>'published_on'));
        $this->forms->endDiv();
		
		$this->afterForm($data);
	}
	
	public function upload()
	{
		$error = $_FILES['images']['error'][0];
		$theme = $this->theme->themeName();

		$uploader = new \Library\Upload('images');
		$uploader->useRandomFileName(true);
		$uploader->setUploadPath($_SERVER['DOCUMENT_ROOT'] . '/themes/'.$theme.'/images/');
		$images = $uploader->uploadFile();
		$img_data = array();
		if($error != 4)
		{
			foreach($images as $i)
			{
			
				$dim = getimagesize($i['upload_path'].'/'.$i['filename'].'.'.$i['file_ext']);
				$width = $dim[0];
				$height = $dim[1];
			
				$image = array(
					'width' => $width,
					'height' => $height,
					'mime' => $i['mime_type'],
					'filename' => $i['filename'],
					'path' => $i['public_path'],
					'ext' => '.'.$i['file_ext'],
					'size' => $i['file_size']
				);
				$image = $this->images->create('images', $image);
				$img_data[] = $image;
			}
		}
		
		return $img_data;
	}
	
	protected function getScript()
	{
		$script = <<<SCRIPT
			<script>
		(function($)
		{
			var input = document.getElementById('images'),
			formdata = false;
			
			
			images = [];
			
			if (input.addEventListener)
			{
				input.addEventListener("change", function (evt) {
					var i = 0, len = this.files.length, img, reader, file;
					document.getElementById("response").innerHTML = "Uploading . . ."
					for(i=0; i<len;i++)
					{
						file = this.files[i];
						images.push(file);
						
		  				reader = new FileReader();
		  			 	reader.onloadend = function (e) {
		  			    	showCurrentImages(e.target.result);
		  			  	};
						reader.readAsDataURL(file);
					}
					
				  }, false);
				  
			}
			
			
			
		})(jQuery);
		
		function showCurrentImages(source)
		{
			var list = document.getElementById("current-images"),
			      li   = document.createElement("li"),
			      img  = document.createElement("img");
				  $(img).attr('width', '100');
				  img.src = source;
				  li.appendChild(img);
				  list.appendChild(li);
		}
		
		function removeImage(id)
		{
			img = $('img[data-image-id="'+id+'"]');
			$(img).closest('.thumbs').remove();
			$('input[value="'+id+'"]').remove();
		}
		
		$('.thumbs img').click(function()
		{
			var imgid = $(this).data('image-id');
			removeImage(imgid);
		})
		
		$('#submit_button').click(function()
		{
			var input = document.getElementById('images'),
			formdata = new FormData();
			for(i=0; i<images.length; i++)
			{
				formdata.append("images[]", images[i]);
				input.files[i] = images[i];
			}
			
		})
		
		
			</script>
SCRIPT;

return $script;
	}
	
	public function loadPanels()
	{
		$this->panels = new Core\Panel();
       
        //setup and build panels
        $panelPaths = array(
                'RichApp' . DS . 'Library' . DS . 'Panels'
              );

        foreach($panelPaths as $p)
        {
            $files = $this->filesystem->listContents($p);
            foreach ($files as $object) {
				
                if($object['type'] == 'file' && $object['extension'] == 'php')
                {
					$scriptName = substr($object['filename'], 0, strlen($object['filename'])-strlen('panel'));
					$this->addAsset('script', '/assets/admin/js/panels/'.$scriptName.'.js');
                    $className = "\RichApp\Library\Panels\\".$object['filename'];
                    $this->panels->register(new $className());
                }
            }
        }
		 $this->panels->setup();
		$this->setData('panels', $this->panels->get());
	}
    
}
