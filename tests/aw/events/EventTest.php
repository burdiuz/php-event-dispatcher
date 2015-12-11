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
      Event::reset($event, new EventDispatcher());
      $this->assertNotNull($event->getTarget());
    }

    public function testHasTarget() {
      $event = new Event('newType');
      $this->assertFalse($event->hasTarget());
      Event::reset($event, new EventDispatcher());
      $this->assertTrue($event->hasTarget());
    }

    public function testProperties() {
      $event = new Event('newType');
      $this->assertEquals('newType', $event->type);
      Event::reset($event, new EventDispatcher());
      $this->assertNotNull($event->target);
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
