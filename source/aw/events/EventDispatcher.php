<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */


namespace aw\events {

  use aw\callbacks\FunctionCallback;
  use aw\callbacks\MethodCallback;
  use \InvalidArgumentException;
  use aw\Object;

  class EventDispatcher extends Object implements IEventDispatcher {
    const MAX_PRIORITY = (PHP_INT_MAX / 2) >> 0;
    private $_listeners;
    private $_target;

    public function __construct(IEventDispatcher $target = null) {
      $this->_target = $target ?: $this;
      $this->_listeners = new EventListeners();
    }


    public function addEventListener(string $eventType, callable $handler, int $priority = 0):callable {
      if (is_array($handler)) {
        $handler = new MethodCallback(...$handler);
      } else if (is_string($handler)) {
        $handler = new FunctionCallback($handler);
      }
      if (abs($priority) > self::MAX_PRIORITY) {
        throw new InvalidArgumentException('Priority value should be less or equal to ' . self::MAX_PRIORITY . '.');
      }
      $this->_listeners->add($eventType, $priority + self::MAX_PRIORITY, $handler);
      return $handler;
    }

    public function removeEventListener(string $eventType, callable $handler) {
      $this->_listeners->remove($eventType, $handler);
    }

    public function hasEventListener(string $eventType):bool {
      return $this->_listeners->has($eventType);
    }

    public function dispatchEvent($event):bool {
      $result = false;
      if ($event instanceof IEvent) {
        $eventType = $event->getType();
      } else {
        $eventType = (string)$event;
        $event = new Event($eventType);
      }
      if ($this->hasEventListener($eventType)) {
        foreach ($this->_listeners->getEventIterator($eventType) as $listener) {
          $listener($event);
          $result = true;
        }
      }
      return $result;
    }

    static public function removeListeners(EventDispatcher $dispatcher, string $eventType) {
      $dispatcher->_listeners->removeType($eventType);
    }

    static public function removeAllListeners(EventDispatcher $dispatcher) {
      $dispatcher->_listeners->clear();
    }
  }
}
