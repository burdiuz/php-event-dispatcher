<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */

namespace {
  function _EventDispatcherTest_eventHandler($arg){
    return $arg;
  }
}

namespace aw\events {

  use \PHPUnit_Framework_TestCase as TestCase;

  class EventDispatcherTest extends TestCase {
    /**
     * @var IEventDispatcher
     */
    public $parent;
    /**
     * @var EventDispatcher
     */
    public $target;
    public $handlers;
    public $handler1;
    public $handler2;
    public $handler3;

    public function setUp() {
      $this->parent = $this->prophesize();
      $this->parent->willImplement('\\aw\\events\\IEventDispatcher');
      $this->target = new EventDispatcher($this->parent->reveal());
      $this->handlers = $this->getMockBuilder('stdClass')->setMethods(array('first', 'second', 'third'))->getMock();
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

    public function testAddDuplicateListener() {
      $this->testAddListener();
      $this->target->addEventListener('event1', $this->handler1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddListenerErrorPriority() {
      $listener = $this->target->addEventListener('event1', function(){}, PHP_INT_MAX);
    }

    /**
     * @ depends testAddListener
     */
    public function testHasListener() {
      $this->testAddListener();
      $this->assertTrue($this->target->hasEventListener('event1'));
      $this->assertTrue($this->target->hasEventListener('event2'));
      $this->assertFalse($this->target->hasEventListener('event3'));
    }

    public function testRemoveListener() {

    }

    public function testRemoveNotExistentListener() {

    }

    public function testRemoveListeners() {

    }

    public function testRemoveAllListeners() {

    }
  }
}
