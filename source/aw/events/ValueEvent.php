<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */


namespace aw\events{

  class ValueEvent extends Event {
    const VALUE_COMMIT = 'valueCommit';
    private $_value;
    public function __construct(string $type, $value) {
      parent::__construct($type);
      $this->_value = $value;
    }
    public function getValue(){
      return $this->_value;
    }

    public function __destruct() {
      parent::__destruct();
      unset($this->_value);
    }
  }
}