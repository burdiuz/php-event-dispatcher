<?php

namespace aw\callbacks {

  use aw\events\Event;
  use aw\events\IEvent;
  use aw\events\IEventDispatcher;

  abstract class AbstractEventCallback extends Callback {
  static
    const EVENT_TYPE = 'callbackNotification';
    /**
     *
     * @var IEventDispatcher
     */
    protected $_dispatcher;
    protected $_baseEvent;

    public function __construct(IEventDispatcher $dispatcher, $event = null) {
      parent::__construct($dispatcher);
      $this->setBaseEvent($event ? $event : self::EVENT_TYPE);
    }

    protected function setBaseEvent($event) {
      if (is_string($event)) {
        $this->_baseEvent = new Event($event);
      } else if ($event instanceof IEvent) {
        $this->_baseEvent = $event;
      } else {
        throw new \InvalidArgumentException('Base event should be of string or \\aw\\events\\IEvent type.');
      }
    }
  }
}
