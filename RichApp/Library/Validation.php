<?php

/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Validation
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class Validation {
    
    protected $fields = array();
    protected $errors = array();
    protected $valid = false;
    
    public function register_field($field, $type)
    {
        $this->fields[$field] = $value;
    }
    
    public function validate()
    {
        foreach($this->fields as $k => $v)
        {
            if( array_key_exists($k, $_POST))
            {
                $validation = 'is_'.$v;
                if( ! $validation() )
                {
                    $this->errors[$k] = $this->error;
                }
            }
        }
    }
    
    private function is_number($field, $value)
    {
        $this->error = 'Tthis field needs to be a number';
    }
    
    private function is_string($field, $value)
    {
        $this->error = 'This field: needs to be a string';
    }
    
    private function is_empty($field, $value)
    {
        $this->error = 'This field is empty';
    }
    
}
