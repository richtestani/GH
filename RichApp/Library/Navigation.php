<?php
namespace Library;
/* 
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

class Navigation {
    
    public $navigation = array();
    public $name;
    public $id;
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->id = str_replace(" ", "-", strtolower($name));
    }
    
    public function addNavItem($name, $uri, $atts=array(), $more = '')
    {
        $this->navigation[] = array(
            'name' => $name,
            'uri' => $uri,
            'atts' => $atts,
            'more' => $more
        );
        
        return $this;
    }
    
    public function nav($line='li', $wrap='ul', $atts = array() )
    {
        $attributes = '';
        foreach($atts as $k => $v)
        {
            $attributes .= $k.'='.$v.' ';
        }
        
        $nav = '<'.$wrap.' id="'.$this->id.'" '.trim($attributes).'>';
        foreach($this->navigation as $item)
        {
            $nav .= '<'.$line.'><a href="'.strtolower($item['uri']).'">'.ucwords($item['name']);
            if(!empty($item['more']))
            {
                $nav .= '<br>'.$item['more'];
            }
            $nav .= '</a></'.$line.'>';
        }
        $nav .= '</'.$wrap.'>';
        
        return $nav;
    }
    
}