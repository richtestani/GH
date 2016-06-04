<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library\HTML;

/**
 * Description of Attributes
 *
 * @author richardtestani
 */
class Attributes {
    
    protected $attributes;
    
    public function setAttribute($name, $value='')
    {
        if(is_array($name))
        {
            foreach($name as $n => $v)
            {
                $this->attributes[$n] = $v;
            }
        }
        else
        {
            $this->attributes[$name] = $value;
        }
        
    }
    
    public function __toString()
    {
        $atts = $this->getOutput();
        print_r($atts);
        return '';
    }
    
    public function getOutput()
    {
        print_r($this->attributes);
        if(empty($this->attributes))
        {
            return '';
        }
        
        $atts = '';
        foreach($this->attributes as $k => $v)
        {
            echo $k.'=>'.$v;
            $atts .= $k.'="'.$v.'" ';
        }
        
        return '';
    }
    
}
