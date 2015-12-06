<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\events {

  use \PHPUnit_Framework_TestCase as TestCase;

  class EventTest extends TestCase {
    public function testAccessors() {
      $event = new Event('newType');
      $this->assertEquals('newType', $event->getType());
    }

    public function testProperties() {
      $event = new Event('newType');
      $this->assertEquals('newType', $event->type);
    }

    public function testPreventDefault() {
      $event = new Event('newType');
      $this->assertFalse($event->isDefaultPrevented());
      $event->preventDefault();
      $this->assertTrue($event->isDefaultPrevented());
    }

    public function testReset() {
      $event = new Event('newType');
      $target = $this->getMock('\\aw\\events\\EventDispatcher');
      $event->preventDefault();
      Event::reset($event, $target);
      $this->assertFalse($event->isDefaultPrevented());
      $this->assertSame($target, $event->getTarget());
      $this->assertSame($target, $event->target);
    }
  }
}
