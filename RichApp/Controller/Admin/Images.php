<?php
namespace RichApp\Controller\Admin;
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
 * Description of Pages
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Images extends Controller\Admin {
    
    protected $images_model;
    
    public function __construct()
    {
        parent::__construct();
        $this->images_model = new Model\Images();
        $this->html_listing = new Library\HTML\HTMLListing();
        $this->image_transform = new Library\ImageTransform();
    }
    
   public function index($page=1)
   {

	    $all = $this->images_model->getAll(array('select'=>'id, public_path, filename, ext'));
		
		$numrecords = $this->images_model->count();
		
		$page_settings = $this->system->get('pages');
		
		$this->pagination->setTotalNumPages($numrecords, $page_settings['num_posts_per_page'])
			->setBaseLink('/admin/images')->setBasePageLink('page')->currentPageNum($page);
		$pagination = $this->pagination->get();
		
		$this->setData('pagination', $pagination);
		
		$this->setData('baselink', '/admin/images');
        $this->setData('page_title', 'Images');
        $this->setData('listing', $all);
        $this->render('listing');
   }
   
   public function create()
    {
       $message = '';
        if (!extension_loaded('gd')) {
            $message = '<div class="alert alert-warning">You do not have the GD library installed, and will not be able to resize your uploads</div>';
        }
        $form = <<<FORM
           $message
           <form action="/admin/images/save/" method="post" enctype="multipart/form-data">
                <div class="form-group upload-box">
                <label>Choose a File</label>
                <input type="file" name="image_upload[]" class="form-control" multiple />
                </div>
				<label>Caption</label>
				<input type="text" name="caption" id="caption" class="form-control" />
                <button type="submit" class="btn btn-primary">Upload</button>
                </form>
FORM;

        $this->setData('page_title', 'New Image');
        $this->setData('form', $form);
        $this->render('add-edit');
    }

    
    public function delete($id=0)
    {
        $id = (int)$id;
		$theme = $this->system->get('theme');
		$image = $this->images_model->getOne('id', $id);
		$uid = $image->uid;
		$filename = $image->filename;
		$sizes = './public' . DS . trim($image->public_path, '/');
		$orig = './public' . DS . trim($theme['theme_path'], '/') . DS . trim($theme['current_theme'], '/') . DS . 'images' . DS . $filename;
		
		$this->filesystem->deleteDir($sizes);
		$this->filesystem->delete($orig);
        $this->images_model->delete($id);
        $this->app->redirect('/admin/images');
    }
	
	private function process($data)
	{
		
	}
    
    public function save($id=0)
    {
        $id = (int)$id;
        $key = key($_FILES);
        $uploader = new Library\Upload($key);
        $date = date('Y-d-m');
        $image_sizes = $this->system->get('image');
        $theme = $this->system->get('theme');
        $image_sizes['thumbnail'] = 65;

        $theme_path = rtrim($theme['theme_path'], '/') . DS . $theme['current_theme'] . DS . 'images';

        //save them
        if($id == 0)
        {
			$theme['theme_path'];
            $uploader->setUploadPath($theme_path);
            $data = $uploader->uploadFile();
            
            
            //write to db
            foreach($data as $img)
            {
				list($width, $height, $type, $attr) = getImageSize($_SERVER['DOCUMENT_ROOT'].'/'.$img['public_path'].'/'.$img['filename']);
				$img['size'] = $img['file_size'];
				$img['mime'] = $img['mime_type'];
				$img['ext'] = $img['file_ext'];
				unset($img['file_size']);
                unset($img['tmp_name']);
                unset($img['use_random']);
                unset($img['upload_path']);
				unset($img['mime_type']);
				unset($img['file_ext']);
				
                //assign a uinque id to each upload
                $imageuid = uniqid();
				$img['caption'] = $this->request->post('caption');
                $img['created_on'] = $this->images_model->created_on();
                $img['uid'] = $imageuid;
                $img['public_path'] = '/images/' . $date . '/' . $imageuid;
                //$img['original_name'] = $img['name'][0];
                //save data to db
				$img['created_by'] = $this->user['id'];
				$img['width'] = $width;
				$img['height'] = $height;
				$img['dimensions'] = $width.' x '.$height;
                $this->images_model->create($img);
                if (!extension_loaded('gd')) 
                {
                    continue;    
                }
                //resize each image to the sizes specified in settings
                foreach($image_sizes as $size => $dim)
                {
                    $source = PUBPATH . '/' . $theme_path . '/' . $img['filename'];
                    if(!file_exists(PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/'))
                    {
                        mkdir(PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/', 0777, true);
                    }
                    if($size == 'thumbnail')
                    {
                        $destination = PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/'.$img['filename'];
                        $this->image_transform->scaleAndCrop($source, $destination, 55, 55);   
                    }
                    else
                    {
                        $destination = PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/'.$img['filename'];
                        $this->image_transform->scaleImage($source, $destination, $dim);   
                    }
                                     
                }
            }
        }
 
        $this->app->redirect('/admin/images');
    }
	
	
	public function saveImageFromURL()
	{
		$url = $_POST['src'];
		$date = date('Y-d-m');
        $image_sizes = $this->system->get('image');
        $theme = $this->system->get('theme');
		$theme_path = PUBPATH.DS.rtrim($theme['theme_path'], '/') . DS . $theme['current_theme'] . DS . 'images'.DS;
        $image_sizes['thumbnail'] = 65;
		$parsed = parse_url($url);
		$path = $parsed['path'];
		$sections = explode('/', $path);
		$file = $sections[count($sections)-1];
		echo $theme_path.$file;
		
		
		
			$fp = fopen ($theme_path.$file, 'w+');              // open file handle

		    $ch = curl_init($url);
		    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
		    curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
		    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
		    // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
		    curl_exec($ch);

		    curl_close($ch);                              // closing curl handle
		    fclose($fp); 
			list($width, $height, $type, $attr) = getImageSize($theme_path.$file);
			$img = array();
			//$img['size'] = $img['file_size'];
			//$img['mime'] = $img['mime_type'];
			//$img['ext'] = $img['file_ext'];
			$imageuid = uniqid();
	        $img['created_on'] = $this->images_model->created_on();
	        $img['uid'] = $imageuid;
	        $img['public_path'] = '/images/' . $date . '/' . $imageuid;
			$img['created_by'] = $this->user['id'];
			$img['width'] = $width;
			$img['height'] = $height;
			$img['dimensions'] = $width.' x '.$height;
			$img['filename'] = $file;
			echo 'creating new image<br>';
			print_r($img);
			$this->images_model->create($img);
            foreach($image_sizes as $size => $dim)
            {
                $source = $theme_path.$file;
                if(!file_exists(PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/'))
                {
                    mkdir(PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/', 0777, true);
                }
                if($size == 'thumbnail')
                {
                    $destination = PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/'.$img['filename'];
                    $this->image_transform->scaleAndCrop($source, $destination, 55, 55);   
                }
                else
                {
                    $destination = PUBPATH . '/images/'.$date.'/'.$imageuid.'/'.$size.'/'.$img['filename'];
                    $this->image_transform->scaleImage($source, $destination, $dim);   
                }
                                 
            }
	}
   
   public function json_panel()
   {
       $all = $this->images_model->getAll(array('limit'=>9999));
       $image_sizes = $this->system->get('image');
	   $domain = 'http://'.trim($_SERVER['HTTP_HOST'], '/').DS;
       $output = '';

       $counter = 0;
       foreach($all as $k => $img)
       {
           $src = $img['public_path'].'/thumbnail/'.$img['filename'];
           $output .= '<div id="'.$img['uid'].'" data-image-domain="'.$domain.'" class="thumbnail panel-item-image" data-image-path="'.$img['public_path'].'" data-image-filename="'.$img['filename'].'"><img src="'.$src.'" class="img-thumbnail" />';
		   $output .= '<div class="insert-image-links">';
           foreach($image_sizes as $size => $dim)
           {
               $output .= '<a class="insert-image-link" href="#" data-image-src="'.$img['public_path'].'/'.$size.'/'.$img['filename'].'" data-image-size="'.$size.'" data-image-width="'.$dim.'">Insert '.$size.'</a>';
           }
		   $output .= '</div>';
           $output .= '</div>';
       }
       
       $images['images'] = $output;
       echo json_encode($images, JSON_FORCE_OBJECT);
   }
   
   public function imageGrid($class="default")
   {
       $all = $this->images_model->getAll(array('limit'=>50));
       $listing = '<div id="image-grid" class="grid-listing"><div id="image-grid-listing">';
       $listing .= '<div class="close-grid"><a href="#"><span class="fa fa-times btn btn-default"></span></a></div>';
       foreach($all as $k => $v)
       {
           $listing .= '<div class="thumbnail image-grid">';
           $listing .= '<img src="'.$v['public_path'].DS.'small'.DS.$v['filename'].'" class="'.$class.'" />';
           $listing .= '<a href="" class="set-'.$class.'-image" data-uid="'.$v['uid'].'" data-image-src="'.$v['public_path'].DS.'thumbnail'.DS.$v['filename'].'">Set Image</a>';
           $listing .= '</div>';
       }
       
       $listing .= '</div></div>';
       
       echo $listing;
       
   }
}
