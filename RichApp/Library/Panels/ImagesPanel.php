<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library\Panels;
use RichApp\Interfaces;
use RichApp\Model;

/**
 * Description of ImagesPanel
 *
 * @author richardtestani
 */
class ImagesPanel implements Interfaces\iPanel  {
    
    public function __construct()
    {
        $this->images_model = new \RichApp\Model\Images();
    }
    
    public function configure()
    {
        $data = array(
            'id' => 'images',
            'module_name' => 'Images',
            'icon_class' => 'fa fa-image',
			'script' => 'Images.js'
        );
        
        return $data;
    }
    
    public function json_panel()
   {
       $images = array();
       $all = $this->images_model->findAll();
       $counter = 0;
       foreach($all as $k => $v)
       {
           foreach($v as $n => $i)
           {
               $images[$k][$n] = $i;
           }
       }
       echo json_encode($images, JSON_FORCE_OBJECT);
   }
}
