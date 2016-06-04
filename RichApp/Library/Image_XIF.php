<?php

/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Image_XIF
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Image_XIF {
    
    protected $exif_data;
    protected $cameraType;
    
    private $image_types = array(
            'IMAGETYPE_GIF',
            'IMAGETYPE_JPEG',
            'IMAGETYPE_PNG',
            'IMAGETYPE_PSD',
            'IMAGETYPE_BMP'
    );
    
    public function getExif( $source )
    {
        
    }
    
    public function cameraType()
    {
        return $this->cameraType;
    }
    
    private function determineCamera()
    {
        
    }
}
