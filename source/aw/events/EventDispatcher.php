<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */


namespace aw\events{

  use aw\callbacks\FunctionCallback;
  use aw\callbacks\MethodCallback;
  use \InvalidArgumentException;
  use aw\Object;

  class EventDispatcher extends Object implements IEventDispatcher {
    private $_listeners;
    private $_target;

    public function __construct(IEventDispatcher $target = null) {
      $this->_target = $target ?: $this;
      $this->_listeners = new EventListeners();
    }


    public function addEventListener(string $eventType, $handler, int $priority = 0):callable {
      if (is_array($handler)) {
        $handler = new MethodCallback(...$handler);
      } else if (is_string($handler)) {
        $handler = new FunctionCallback($handler);
      } else if (!is_callable($handler)) {
        throw new InvalidArgumentException('Handler must be a valid callable.');
      }
      $this->_listeners->add($eventType, $priority, $handler);
      return $handler;
    }

    public function removeEventListener(string $eventType, callable $handler) {

    }

    public function hasEventListener(string $eventType):bool {
      return false;
    }

    public function dispatchEvent($event):bool {
      return true;
    }

    static public function removeListeners(IEventDispatcher $dispatcher, string $eventType) {

    }

    static public function removeAllListeners(IEventDispatcher $dispatcher) {

    }
  }
}
