<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RichApp\Core\Events;
use League\Event\ListenerInterface;
use League\Event\EventInterface;
/**
 * Description of EventListener
 *
 * @author richardtestani
 */
class EventListener implements ListenerInterface {
    
    public function isListener($listener)
    {
        return $listener === $this;
    }

    public function handle(EventInterface $event)
    {
        // Handle the event.
        echo 'handling event';
    }
}
