<?php

namespace aw\callbacks {

  use aw\events\Event;
  use aw\events\IEvent;

  class EventCallback extends AbstractEventCallback {
    private $_event;
    protected function generateEvent($args):IEvent {
      if(!$this->_event){
        $this->_event = new Event($this->_eventType);
      }
      return $this->_event;
    }
  }
}