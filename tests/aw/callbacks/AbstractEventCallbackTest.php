<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\callbacks {

  use aw\events\Event;
  use aw\events\IEvent;
  use aw\events\IEventDispatcher;
  use \PHPUnit_Framework_TestCase as TestCase;
  use Prophecy\Argument;

  class AbstractEventCallbackTest_AbstractEventCallback extends AbstractEventCallback {
    public function getEventType() {
      return $this->_eventType;
    }

    public function generateEvent($args):IEvent {
      return new Event($this->_eventType);
    }
  }

  class AbstractEventCallbackTest extends TestCase {
    public $targetSpy;
    public $target;

    public function setUp() {
      $this->targetSpy = $this->prophesize('\\aw\\events\\EventDispatcher');
      $this->target = $this->targetSpy->reveal();
    }

    public function testCreateWithEventType() {
      $callback = new AbstractEventCallbackTest_AbstractEventCallback($this->target, 'commit');
      $this->assertEquals('commit', $callback->getEventType());
    }

    public function testCreateWithoutEventType() {
      $callback = new AbstractEventCallbackTest_AbstractEventCallback($this->target);
      $this->assertEquals(AbstractEventCallback::EVENT_TYPE, $callback->getEventType());
    }

    public function testCall() {
      $callback = new AbstractEventCallbackTest_AbstractEventCallback($this->target, 'submit');
      /**
       * @var \Prophecy\Prophecy\MethodProphecy
       */
      $this->targetSpy->dispatchEvent(Argument::any())->willReturn(true);
      $this->assertInternalType('bool', $callback());
    }
  }

}