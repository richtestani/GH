<?php
namespace Core\Observer\Notifications;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of Notifications
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class NotifySubject extends \Core\Observer\Subject {
    
    protected $successMessage;
    protected $errorMessage;
    protected $warningMessage;
    protected $messages = array();
    protected $observers = array();
    protected $more = array();
    
    public function registerObserver(\Core\Observer\Observer $o)
    {
        $this->observers[] = $o;
    }
    
    public function removeObserver(\Core\Observer\Observer $o)
    {
        echo 'removing...';
    }
    
    public function notifyObserver()
    {
        foreach($this->observers as $obs)
        {
            $o = $obs;
            $o->update($this->messages, $this->more);
        }
    }
    
    public function displayAll()
    {
        $all = array();
        foreach($this->observers as $obs)
        {
            $all[] = $obs->getNotification();
        }
        
        return $all;
    }
    
    public function setMessage($message, $type, $more)
    {
        $this->messages[$type] = $message;
        $messageType = $type.'Message';
        $this->{$messageType} = $message;
        $this->more[] = $more;
        $this->notifyObserver();
    }
}
