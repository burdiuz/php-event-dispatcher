<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\events{

  use \PHPUnit_Framework_TestCase as TestCase;

  class ValueEventTest extends TestCase {
    public function testAccessors() {
      $event = new ValueEvent('newType', true);
      $this->assertTrue($event->getValue());
    }

    public function testProperties() {
      $event = new ValueEvent('newType', true);
      $this->assertTrue($event->value);
    }
  }
}
