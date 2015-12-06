<?php
/**
 * Created by Oleg Galaburda on 05.12.15.
 */


namespace aw\callbacks{

  use aw\events\IEvent;
  use aw\events\ValueEvent;

  class ValueEventCallback extends AbstractEventCallback {
    protected function generateEvent($args):IEvent {
      return new ValueEvent($this->_eventType, $args[0]);
    }
  }
}