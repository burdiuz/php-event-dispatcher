<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */


namespace aw\events;


use aw\Object;

class Event extends Object implements IEvent {
  private $_type;
  private $_target;
  private $_defaultPrevented = false;

  public function __construct(string $type) {
    $this->_type = $type;
  }

  public function getType():string {
    return $this->_type;
  }

  public function hasTarget():bool {
    return !is_null($this->_target);
  }

  public function getTarget():IEventDispatcher {
    return $this->_target;
  }

  public function preventDefault() {
    $this->_defaultPrevented = true;
  }

  public function isDefaultPrevented() {
    return $this->_defaultPrevented;
  }

  public function __destruct() {
    unset($this->_target);
  }

  static public function reset(Event $event, IEventDispatcher $target = null) {
    $event->_target = $target;
    $event->_defaultPrevented = false;
  }

}