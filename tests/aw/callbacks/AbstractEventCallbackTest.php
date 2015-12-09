<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\callbacks {

  use aw\events\Event;
  use aw\events\IEvent;
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
      /**
       * @var \Prophecy\Prophecy\ObjectProphecy
       */
      $this->targetSpy = $this->prophesize('\\aw\\events\\EventDispatcher');
      $this->target = $this->targetSpy->reveal();
    }

    public function tearDown() {
      unset($this->targetSpy);
      unset($this->target);
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
      $spy = $this->targetSpy->dispatchEvent(Argument::type('\\aw\events\\IEvent'))->willReturn(true);
      $spy->shouldBeCalledTimes(1);
      $this->assertInternalType('bool', $callback());
      $spy->checkPrediction();
    }
  }

}