<?php

/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

namespace Core\Observer\Notifications;

/**
 * Description of ErrorNotify
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class ErrorNotify extends \Core\Observer\Observer {
    
    protected $subject;
    protected $display;
    protected $error;
    protected $solution = 'No solution provided';
    
    public function __construct($subject, $display)
    {
        $this->subject = $this;
        $this->display = $display;
        $subject->registerObserver($this);
    }
    
    public function setError($error)
    {
        $this->error = $error;
        $this->display->setDisplay($this->error);
    }
    
    public function update($message = '', $solution = array())
    {
        if(is_array($message))
        {
            
            $message = array_key_exists('error', $message) ? $message['error'] : '';
            
            
            
        }
        
        
        if(array_key_exists('solution', $solution))
        {
            $this->solution = $solution;
        }
        else
        {
            $this->solution = '';
        }
        
        
        if(!empty($this->solution))
        {
            $message .= '<br>To fix this: '.$this->solution;
        }
        
        $this->setError($message);
        $this->notification =  $this->display->display();
    }
    
    public function setSolution($solution)
    {
        $this->solution = $solution;
    }
    
    public function getNotification()
    {
        return $this->notification;
    }
    
    public function getMessage()
    {
        return $this->error;
    }
}
