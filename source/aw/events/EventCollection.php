<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */

namespace aw\events {

  use aw\CallableCollection;

  /**
   * @private
   */
  class EventCollection extends CallableCollection {
    private $_emptyCallback;
    private $_parent;
    private $_type;

    public function __construct($type, callable $emptyCallback, EventCollection $parent = null) {
      $this->_type = $type;
      $this->_parent = $parent;
      $this->_emptyCallback = $emptyCallback;
    }

    public function getType() {
      return $this->_type;
    }

    public function getParent() {
      return $this->_parent;
    }

    public function removeItemAt($index):bool {
      $result = parent::removeItemAt($index);
      $this->checkEmpty();
      return $result;
    }

    public function removeAll() {
      parent::removeAll();
      $this->checkEmpty();
    }

    protected function checkEmpty() {
      if (!$this->getCount()) {
        $empty = $this->_emptyCallback;
        $empty($this);
      }
    }

    public function __invoke(...$args) {
      foreach ($this->_items as $value) {
        $value(...$args);
      }
    }

    public function __destruct() {
      unset($this->_emptyCallback);
      unset($this->_parent);
      parent::__destruct();
    }
  }
}
