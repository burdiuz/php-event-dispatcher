<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


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
    public $handler1;
    public $handler2;
    public $handler3;

    public function setUp() {
      $this->parent = $this->prophesize();
      $this->parent->willImplement('\\aw\\events\\IEventDispatcher');
      $this->target = new EventDispatcher($this->parent->reveal());
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

    }
    public function testAddListenerFunction() {

    }

    public function testAddDuplicateListener() {
      $this->target->addEventListener('event1', $this->handler1);
    }

    public function testAddListenerErrorPriority() {

    }

    public function testHasListener() {

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
