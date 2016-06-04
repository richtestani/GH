<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Library\HTML;
use RichApp\Library\HTML\Element;
use RichApp\Library\HTML\Forms;
use RichApp\Library\HTML\Input;

/**
 * Description of Form Class
 * @package HTML_Form
 * @author richtestani
 * @version 0.5
 */
class Form  {
    
    protected $action;
    protected $method;
    protected $form;
    protected $input;
	protected $form_index;
    
    /**
    * @param string $action this is where the form will be submitted to
    * @param string $method this is how the form will be submitted
    * @return void
    */
    public function __construct($action , $method='post')
    {
        $this->action = $action;
        $this->method = $method;
        $this->input = null;
		$this->form_index = array();
    }
    
    /**
    * @param string $id applies an id for this row
    * @return void
    */
    public function insertRow($id)
    {
        $rowStart = '<div class="row form-row" id="'.$id.'">'."\n";
        $this->form[] = $rowStart;
		$this->addToIndex('row');
    }
    /**
    * @param string $class applies a class for the div wrapper
    * @param string $id applies an id for this row
    * @return void
    */
    public function insertDiv($id='', $class='')
    {
        $class = (!empty($class)) ? ' class="'.$class.'"' : '';
        $id = (!empty($id)) ? ' id="'.$id.'"' : '';
        $div = '<div'.$class.$id.'>';
        $this->form[] = $div;
		$this->addToIndex('div');
    }
    
    /*
    * Allows static HTML to be inserted in between for elements. 
    * Can be used for descriptions or other pieces of non-form elements
    */
    public function insertHTML($html)
    {
        $this->form[] = $html;
		$this->addToIndex('html');
    }
    
    public function endDiv()
    {
        $this->form[] = '</div>'."\n";
		$this->addToIndex('endDiv');
    }
    
    /*
    * The basic input insertion. Adds new element on the bottom of the stack
    */
    public function insertInput($type, $name, $value, $attributes=array(), $selectOption = array())
    {
        $this->input = new Input($type, $name, $value, $attributes, $selectOption);
        $this->form[] = $this->input;
		$this->addToIndex($name);
    }
    
    public function endRow()
    {
        $this->form[] = '</div>';
		$this->addToIndex('endRow');
    }
    
    public function insertInputFirst($type, $name, $attributes=array())
    {
        $this->input = new Input($type, $name, $attributes);
        array_unshift($this->form, $this->input);
		$this->addToIndex($name);
    }
    
    public function insertBefore($type, $name, $attributes)
    {
		$this->input = new Input($type, $name, $attributes, $li);
        $this->splitStack($index, 'before');
    }
    
    public function insertInputAfter($index, $type, $name, $attribtues=array())
    {
		$this->input = new Input($type, $name, $attributes);
        $split = $this->splitStack($index, 'after');
		array_push($split['top'], $this->input);
		$this->form = array_merge($split['top'], $split['bottom']);
    }
    
    public function insertInputLast($type, $name, $value='', $attributes = array(), $selectOption = array())
    {
        $this->input = new Input($type, $name, $value,  $attributes, $selectOption);
        array_push($this->form, $this->input);
		$this->addToIndex($name);
    }
    
    public function setLabel($label, $name, $location='before')
    {
        array_push($this->form, $this->input->label($label, array('for'=>$name)));
		$this->addToIndex($name);
    }
    
    public function get()
    {
        $this->insertDiv('page-buttons');
        $this->form[] = new Input('button', 'submit', 'Submit', array('class'=>'btn btn-primary'));
		$this->addToIndex('submit');
        $this->endDiv();
        $form = '<form action="'.$this->action.'" method="'.$this->method.'">';
        foreach ($this->form as $input) {
            if(is_object($input))
            {
                $form .= $input->get();
            } else {
                $form .= $input;
            }
        }
        
        $form .= '</form>';

        return $form;
    }
	
	private function addToIndex($name)
	{
		$this->form_index[] = $name;
	}
	
	private function splitStack($index, $where)
	{
		$orig = $this->form;
		$copy = $this->form;
		
		$index = ($where == 'after') ? $index : $index-1;
		
		$top = array_splice($orig, 0, $index);
		$bottom = array_splice($copy, $index);
		
		return ['top'=>$top, 'bottom'=>$bottom];
	}
    
}
