<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library;
use Intervention\Image\ImageManager;
use Intervention\Image;
//require_once APPPATH . '/Library/PHPThumb/ThumbLib.inc.php';

/**
 * Description of ImageTransform
 *
 * @author richardtestani
 */
class ImageTransform {
	
	private $image_manager;
	
	public function __construct()
	{
		$this->image_manager = new ImageManager();
	}

    
    public function scaleImage($source, $destination, $size)
    {
		/*
        $thumb = \PhpThumbFactory::create($source);
        $thumb->resize($size);
        $thumb->save($destination);
		*/
		
		
		$img = $this->image_manager->make($source);
		$img->resize($size, null, function($constraint){
			$constraint->aspectRatio();
		})->save($destination);
		$img->destroy();
    }
    
    public function scaleAndCrop($source, $destination, $w, $h)
    {
		/*
        $thumb = \PhpThumbFactory::create($source);
        $thumb->adaptiveResize($w, $h);
        $thumb->save($destination);
		*/
		$img = $this->image_manager->make($source);
		$img->resize($w, $h)->save($destination);
		$img->destroy();
    }
}
