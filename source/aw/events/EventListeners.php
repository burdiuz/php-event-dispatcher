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

    public function add(string $eventType, int $priority, callable $callable):bool {
      $result = false;
      $collection = $this->getCollection($eventType, $priority);
      if(!$collection->hasItem($callable)){
        $collection[] = $callable;
        $result = true;
      }
      return $result;
    }

    public function has(string $eventType):bool {
      return isset($this->_hash[$eventType]) && $this->_hash[$eventType]->getCount();
    }

    public function get(string $eventType) {
      return isset($this->_hash[$eventType]) ? $this->_hash[$eventType] : null;
    }

    public function remove(string $eventType, callable $handler):bool {
      $result = false;
      if (isset($this->_hash[$eventType])) {
        $types = $this->_hash[$eventType];
        foreach ($types as
          /**
           * @var EventCollection
           */
                 $priorities) {
          $result = $priorities->removeItem($handler) || $result;
        }
      }
      return $result;
    }

    public function removeType(string $eventType):bool {
      $result = isset($this->_hash[$eventType]);
      if ($result) {
        unset($this->_hash[$eventType]);
      }
      return $result;
    }

    public function removePriority(string $eventType, int $priority):bool {
      $result = false;
      if (isset($this->_hash[$eventType])) {
        $types = $this->_hash[$eventType];
        $result = $types->removeItemAt($priority);
      }
      return $result;
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

    public function getEventIterator(string $eventType) {
      if(isset($this->_hash[$eventType])){
        $priorities = $this->_hash[$eventType];
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