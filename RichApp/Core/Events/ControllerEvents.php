<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core\Events;
use League\Event\Emitter;
use League\Event\EmitterInterface;
use RichApp\Core\Events\EventListener;

/**
 * Description of ControllerEvents
 *
 * @author richardtestani
 */
class ControllerEvents extends Emitter {

     public function __construct($event)
     {
         $this->listener = new EventListener();
         $this->event_name = 'controller.'.$event;
         $this->emitter = new Emitter();
         $this->addEvent();
     }
     
     public function addEvent()
     {
         $this->emitter->addListener( $this->event_name, $this->listener);
     }
     
     public function removeAllListeners($event) {
         
     }
     
     public function emit($event)
     {
         echo 'emitting';
         $this->emitter->emit($this->event_name);
     }
    
}
