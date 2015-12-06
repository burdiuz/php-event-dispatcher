<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\callbacks {

  use \PHPUnit_Framework_TestCase as TestCase;

  class EventCallbackTest extends TestCase {
    public $target;
    public $callback;

    public function setUp() {
      $this->target = $this->getMock('\\aw\\events\\EventDispatcher');
      $this->callback = new EventCallback();
    }

    public function testCall() {

    }

    public function testEventObjectCaching() {

    }
  }
}