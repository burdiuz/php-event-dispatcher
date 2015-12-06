<?php

namespace aw\callbacks {

  use aw\events\IEvent;
  use aw\events\IEventDispatcher;

  abstract class AbstractEventCallback extends Callback {
    const EVENT_TYPE = 'callbackNotification';
    /**
     *
     * @var IEventDispatcher
     */
    private $_dispatcher;
    protected $_eventType;

    public function __construct(IEventDispatcher $dispatcher, string $eventType = null) {
      parent::__construct($dispatcher);
      $this->_eventType = is_null($eventType) ? self::EVENT_TYPE : $eventType;
    }

    abstract protected function generateEvent($args):IEvent;

    public function call(array $args = array()) {
      $this->_dispatcher->dispatchEvent($this->generateEvent($args));
    }
  }
}
