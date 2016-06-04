<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core\Events;
use League\Event\Dispatcher;
use League\Event\Emitter;

/**
 * Description of Events
 *
 * @author richardtestani
 */
class Dispatcher extends Event {
    
    protected $event_name;
    protected $emitter;
    
    public function __construct(Emitter $emitter = null)
    {
        if(!is_null($emitter))
        {
            $this->setEmitter($emitter);
        }
    }
    
}
