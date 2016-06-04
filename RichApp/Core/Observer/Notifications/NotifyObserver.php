<?php
namespace Core\Observer\Notifications;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of NotifyObserver
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class NotifyObserver extends \Core\Observer\Observer {
    
    protected $subject;
    protected $display;
    protected $message;
    protected $notification;
    
    public function __construct($subject, $display)
    {
        $this->subject = $this;
        $this->display = $display;
        $subject->registerObserver($this);
    }
    
    public function setDisplay($message)
    {
        $this->message = $message;
        $this->display->setDisplay($this->message);
   
    }
    
    public function update($message = '', $more = array())
    {
        if(is_array($message) AND array_key_exists('success', $message))
        {
            $message = $message['success'];
        }
        else if( is_string($message) )
        {
            $message = $message;
        }
        else
        {
            $message = null;
        }

        $this->setDisplay($message);
        $this->notification =  $this->display->display();

    }
    
    public function getNotification()
    {
        return $this->notification;
    }
    
    public function getMessage(){
        return $this->message;
    }
}
