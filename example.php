<?php

class Broadcaster {
  const EVENT_FIRST = 'eventFirst';
  const EVENT_SECOND = 'eventSecond';
  const EVENT_THIRD = 'eventThird';
  /**
   * @var \aw\events\EventDispatcher
   */
  private $_dispatcher;

  //TODO add dispatcher target test
  public function __construct() {
    $this->_dispatcher = new \aw\events\EventDispatcher();
  }

  public function addHandler(string $eventType, callable $handler) {
    $this->_dispatcher->addEventListener($eventType, $handler);
  }

  public function doFirst() {
    $this->_dispatcher->dispatchEvent(self::EVENT_FIRST);
  }

  public function doSecond() {
    $this->_dispatcher->dispatchEvent(self::EVENT_SECOND);
  }

  public function doThird() {
    $this->_dispatcher->dispatchEvent(self::EVENT_THIRD);
  }
}