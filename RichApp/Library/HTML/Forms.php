<?php

/*
 * HTML Form Builder
 * Building a form is very quick, easy and flexible
 * 
 * $form = new Forms();
 * $form->input('text', 'email', '', array('class'=>'form-control', 'placeholder'=>'Your Email');
 * $form->input('checkbox', 'subscribe'
 */

namespace RichApp\Library\HTML;

/**
 * Description of Forms
 *
 * @author richardtestani
 */
class Forms implements iHTML {
    
    
    
    
    public function open($action, $method='post', $attributes = array())
    {
        
    }
    
    public function input($type, $name, $value='', $atts = array(), $wrapper = null)
    {
        if(is_array($type))
        {
            foreach($type as $inputs)
            {
                extract($inputs);
                $input = new Input($type, $name, $atts, $value);
                $this->form[$name] = $input;
            }
        }
        else
        {
            $input = new Input($type, $name, $atts, $value);
            $this->form[$name] = $input;
        }
        
    }
    
    
    
    public function get()
    {
        return $this->form;
    }
}
