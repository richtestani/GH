<?php
namespace RichApp\Core;

use League\Plates;
use League\Plates\Engine;

/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Theme
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Theme {
    
    protected $name;
    protected $pacakge;
    protected $path;
    protected $css;
    protected $js;
    protected $img;
    protected $template;
    protected $header;
    protected $footer;
    protected $partial;
    protected $theme_settings;


    public function __construct($template_path = '/template')
    {
        $this->template_path = $template_path;
        $this->template = new \League\Plates\Engine($this->template_path);
    }
    
 
    public function getAvailableTemplates($filesystem)
    {
            $path = '/public/themes/'.$this->name;
            $listing = $filesystem->listContents($path);

            $template_meta = array();
            $count = 0;

            foreach($listing as $l)
            {
                    if($l['type'] == 'file' && $l['extension'] == 'php')
                    {

                            $exists = $filesystem->has($l['path']);
                            if($exists)
                            {
                                    $contents = $filesystem->read($l['path']);
                                    preg_match('/(\/\*)(.*?)(\*\/)/s', $contents, $matches);

                                    $meta = array_key_exists(2, $matches) ? explode("\n", $matches[2]) : array();

                                    foreach($meta as $m)
                                    {
                                            if(!empty($m))
                                            {
                                                    $line = explode(':', $m);
                                                    $template_meta[$count]['filename'] = $l['filename'];
                                                    $template_meta[$count]['path'] = $l['path'];
                                                    $template_meta[$count][trim($line[0])] = trim($line[1]);
                                            }

                                    }
                            }
                            $count++;
                    }
            }

            $this->template_meta = $template_meta;
            return $this->template_meta;
    }

    public function render($template, $data = array())
    {
            return $this->template->render($template, $data);
    }
    
    public function setThemeName($name)
    {
        $this->name = $name;
    }
    public function themeName()
    {
            return $this->name;
    }

    public function themePath()
    {
            return $this->theme_path;
    }

    public function adminThemeName()
    {
            return $this->admin_name;
    }

    public function adminThemePath()
    {
            return $this->admin_theme_path;
    }
    
}
