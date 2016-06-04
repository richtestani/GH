<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library\HTML;

/**
 * Description of Element
 * 
 * Represents a single element which consists of
 * open tag
 * attributes
 * content
 * closing tag or self closing tag
 *
 * @author richardtestani
 */
class Element {
    
    protected $element;
    protected $attribtues;
    
    public function __construct($name, $attributes = array())
    {
        $this->element = $name;
        if(!empty($attributes))
        {
            $this->setAttributes($attributes);
        }
    }
    
    public function setAttributes($attributes = array())
    {
        $this->attributes = new RichApp\Library\Attributes();
        $this->attributes->setAttributes($attributes);
        
    }
}
