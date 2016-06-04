<?php
namespace Core\Observer\Notifications;
/*
 * Author: Richard Testani
 * Designed For
 * Contact me at: richtestani@mac.com or @sandman25
 * See more about what I've done: http://www.richtestani.com
 */

/**
 * Description of NotifyDisplay
 *
 * @author Richard Testani <richtestani@mac.com>
 */
class NotifyDisplay extends \Core\Observer\Display {
    
    protected $display;
    protected $status;
    
    public function __construct($status)
    {
        $this->status = $status;
    }
    
    public function setDisplay($display, $more = array())
    {
        $this->display = '';
        if(! is_null($display) OR ! empty($display))
        {
            $this->display = '<div class="alert alert-'.$this->status.' '.$this->status.'" role="alert">'.$display.'</div>';
        }
    }
    
    public function display()
    {
        return $this->display;
    }
}
