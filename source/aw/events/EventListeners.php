<?php
/**
 * Created by Oleg Galaburda on 03.12.15.
 */


namespace aw\events {

  use aw\Object;

  class EventListeners extends Object implements \IteratorAggregate {

    protected $_hash = array();
    protected $_emptyCallback;

    public function __construct() {
      $this->_emptyCallback = function (EventCollection $collection) {
        $key = $collection->getType();
        if (is_string($key)) {
          $this->removeType($key);
        } else {
          $this->removePriority($collection->getParent()->getType(), $key);
        }
      };
    }

    public function add(string $eventType, int $priority, callable $callable) {
      $collection = $this->getCollection($eventType, $priority);
      $collection[] = $callable;
    }

    public function has(string $eventType):bool {
      return isset($this->_hash[$eventType]) && $this->_hash[$eventType]->getCount();
    }

    public function get(string $eventType) {
      return $this->_hash[$eventType];
    }

    public function remove(string $eventType, callable $handler) {
      $types = $this->_hash[$eventType];
      if ($types) {
        foreach ($types as /** @var EventCollection */
                 $priorities) {
          $priorities->removeItem($handler);
        }
      }
    }

    public function removeType(string $eventType) {
      unset($this->_hash[$eventType]);
    }

    public function removePriority(string $eventType, int $priority) {
      $types = $this->_hash[$eventType];
      if ($types) {
        $types->removeItemAt($priority);
      }
    }

    public function clear() {
      $this->_hash = array();
    }

    public function getIterator() {
      foreach ($this->_hash as $eventType => $priorities) {
        foreach ($priorities as $priority => $listeners) {
          foreach ($listeners as $index => $listener) {
            yield $eventType => $listener;
          }
        }
      }
    }

    public function __destruct() {
      unset($this->_hash);
    }

    private function getCollection(string $eventType, int $priority) {
      if (!isset($this->_hash[$eventType])) {
        $types = new EventCollection($eventType, $this->_emptyCallback);
        $this->_hash[$eventType] = $types;
      } else {
        $types = $this->_hash[$eventType];
      }
      $priorities = $types->getItemAt($priority);
      if (!$priorities) {
        $priorities = new EventCollection($eventType, $this->_emptyCallback, $types);
        $types->setItem($priority, $priorities);
      }
      return $priorities;
    }
  }
}