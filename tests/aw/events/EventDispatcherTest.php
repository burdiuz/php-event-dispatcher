<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */

namespace {
  function _EventDispatcherTest_eventHandler($arg) {
    return $arg;
  }
}

namespace aw\events {

  use \PHPUnit_Framework_TestCase as TestCase;
  use Prophecy\Argument;
  use Prophecy\Prophecy\MethodProphecy;

  class _EventDispatcherTest_EventDispatcherTargetMock {
    public function firstHandler($event) {
    }

    public function secondHandler($event) {
    }

    public function thirdHandler($event) {
    }
  }

  class EventDispatcherTest extends TestCase {
    /**
     * @var IEventDispatcher
     */
    public $parent;
    /**
     * @var EventDispatcher
     */
    public $target;
    public $handlerSpies;
    public $handlers;
    public $handler1;
    public $handler2;
    public $handler3;

    public function setUp() {
      $this->parent = $this->prophesize();
      $this->parent->willImplement('\\aw\\events\\IEventDispatcher');
      $this->target = new EventDispatcher($this->parent->reveal());
      $this->handlerSpies = $this->prophesize('\\aw\\events\\_EventDispatcherTest_EventDispatcherTargetMock');
      $this->handlerSpies->firstHandler(Argument::type('\\aw\\events\\IEvent'))->willReturnArgument(0);
      $this->handlerSpies->secondHandler(Argument::type('\\aw\\events\\IEvent'))->willReturnArgument(0);
      $this->handlerSpies->thirdHandler(Argument::type('\\aw\\events\\IEvent'))->willReturnArgument(0);
      $this->handlers = $this->handlerSpies->reveal();
      $this->handler1 = function () {
        return 1;
      };
      $this->handler2 = function () {
        return 2;
      };
      $this->handler3 = function () {
        return 3;
      };
    }

    public function tearDown() {
      unset($this->parent);
      unset($this->target);
      unset($this->handlerSpies);
      unset($this->handlers);
      unset($this->handler1);
      unset($this->handler2);
      unset($this->handler3);
      // force destructor execution
      gc_collect_cycles();
    }

    public function testAddListener() {
      $this->target->addEventListener('event1', $this->handler1);
      $this->target->addEventListener('event2', $this->handler2, 100);
      $this->target->addEventListener('event2', $this->handler3, -100);
    }

    public function testAddListenerMethod() {
      $listener = $this->target->addEventListener('event1', array($this->handlers, 'first'));
      $this->assertInstanceOf('\\aw\\callbacks\\MethodCallback', $listener);
    }

    public function testAddListenerFunction() {
      $listener = $this->target->addEventListener('event1', '_EventDispatcherTest_eventHandler');
      $this->assertInstanceOf('\\aw\\callbacks\\FunctionCallback', $listener);
    }

    /**
     * @depends testAddListener
     */
    public function testAddDuplicateListener() {
      $this->testAddListener();
      $this->target->addEventListener('event1', $this->handler1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddListenerErrorPriority() {
      $listener = $this->target->addEventListener('event1', function () {
      }, PHP_INT_MAX);
    }

    /**
     * @depends testAddListener
     */
    public function testHasListener() {
      $this->testAddListener();
      $this->assertTrue($this->target->hasEventListener('event1'));
      $this->assertTrue($this->target->hasEventListener('event2'));
      $this->assertFalse($this->target->hasEventListener('event3'));
    }

    /**
     * @depends testAddListener
     */
    public function testRemoveListener() {
      $this->testAddListener();
      $this->target->removeEventListener('event1', $this->handler1);
      $this->assertFalse($this->target->hasEventListener('event1'));
      $this->target->removeEventListener('event2', $this->handler2);
      $this->assertTrue($this->target->hasEventListener('event2'));
      $this->target->removeEventListener('event2', $this->handler3);
      $this->assertFalse($this->target->hasEventListener('event2'));
    }

    /**
     * @depends testAddListener
     */
    public function testRemoveNotExistentListener() {
      $this->testAddListener();
      $this->target->removeEventListener('event3', $this->handler1);
      $this->target->removeEventListener('event3', $this->handler2);
      $this->target->removeEventListener('event1', $this->handler2);
      $this->target->removeEventListener('event2', $this->handler1);
      $this->assertTrue($this->target->hasEventListener('event1'));
      $this->assertTrue($this->target->hasEventListener('event2'));
    }

    /**
     * @depends testAddListener
     */
    public function testRemoveListeners() {
      $this->testAddListener();
      EventDispatcher::removeListeners($this->target, 'event1');
      $this->assertFalse($this->target->hasEventListener('event1'));
      $this->assertTrue($this->target->hasEventListener('event2'));
      EventDispatcher::removeListeners($this->target, 'event2');
      $this->assertFalse($this->target->hasEventListener('event1'));
      $this->assertFalse($this->target->hasEventListener('event2'));
    }

    /**
     * @depends testAddListener
     */
    public function testRemoveAllListeners() {
      $this->testAddListener();
      EventDispatcher::removeAllListeners($this->target);
      $this->assertFalse($this->target->hasEventListener('event1'));
      $this->assertFalse($this->target->hasEventListener('event2'));

    }

    private function addSpyListeners(){
      $this->target->addEventListener('event1', [$this->handlers, 'firstHandler']);
      $this->target->addEventListener('event2', [$this->handlers, 'secondHandler']);
      $this->target->addEventListener('event2', [$this->handlers, 'thirdHandler'], -100);
    }

    public function testDispatchEventObject() {
      $this->addSpyListeners();
      $event = new Event('event1');
      $this->handlerSpies->firstHandler(Argument::type('\\aw\\events\\IEvent'))->shouldBeCalledTimes(1);
      $this->handlerSpies->secondHandler(Argument::type('\\aw\\events\\IEvent'))->shouldNotBeCalled();
      $this->handlerSpies->thirdHandler(Argument::type('\\aw\\events\\IEvent'))->shouldNotBeCalled();
      $this->assertTrue($this->target->dispatchEvent($event));
      $this->handlerSpies->checkProphecyMethodsPredictions();
    }
    
    public function testDispatchUnknownEvent() {
      $this->addSpyListeners();
      $event = new Event('event-unknown');
      $this->handlerSpies->firstHandler(Argument::type('\\aw\\events\\IEvent'))->shouldNotBeCalled();
      $this->handlerSpies->secondHandler(Argument::type('\\aw\\events\\IEvent'))->shouldNotBeCalled();
      $this->handlerSpies->thirdHandler(Argument::type('\\aw\\events\\IEvent'))->shouldNotBeCalled();
      $this->assertFalse($this->target->dispatchEvent($event));
      $this->handlerSpies->checkProphecyMethodsPredictions();
    }

    public function testDispatchEventTypeString() {
      $this->addSpyListeners();
      $this->handlerSpies->firstHandler(Argument::type('\\aw\\events\\IEvent'))->shouldBeCalledTimes(1);
      $this->assertTrue($this->target->dispatchEvent('event1'));
      $this->handlerSpies->checkProphecyMethodsPredictions();
    }

    public function testDispatchedEventTargetWhenHasListeners() {
      $this->addSpyListeners();
      $event = new Event('event1');
      $this->assertTrue($this->target->dispatchEvent($event));
      $this->assertInstanceOf('\\aw\\events\\EventDispatcher', $event->getTarget());
    }

    public function testDispatchedEventTargetWhenDoesNotHaveListeners() {
      $this->addSpyListeners();
      $event = new Event('else');
      $this->assertFalse($this->target->dispatchEvent($event));
      $this->assertFalse($event->hasTarget());
    }
  }
}
