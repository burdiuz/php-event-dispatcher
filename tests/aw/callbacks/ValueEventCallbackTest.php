<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\callbacks {

  use \aw\events\ValueEvent;
  use \PHPUnit_Framework_TestCase as TestCase;
  use \Prophecy\Argument;
  use \Prophecy\Argument\ArgumentsWildcard;

  class ValueEventCallbackTest extends TestCase {
    public $target;
    public $callback;
    public $eventType;

    public function setUp() {
      /**
       * @var \Prophecy\Prophecy\ObjectProphecy
       */
      $this->target = $this->prophesize('\\aw\\events\\EventDispatcher');
      $this->eventType = Argument::type('\\aw\\events\\ValueEvent');
      $this->target->dispatchEvent($this->eventType)->willReturn(true);
      $this->callback = new ValueEventCallback($this->target->reveal());
    }

    public function tearDown() {
      /**
       * @var \Prophecy\Prophecy\ObjectProphecy
       */
      unset($this->target);
      unset($this->eventType);
      unset($this->target);
      unset($this->callback);
    }

    public function testCall() {
      $callback = $this->callback;
      $spy = $this->target->dispatchEvent($this->eventType)->shouldBeCalledTimes(1);
      $this->assertTrue($callback(true));
      $spy->checkPrediction();
    }

    public function testDispatchValueEvent() {
      $callback = $this->callback;
      $callback('value');
      /**
       * @var ValueEvent
       */
      $event = $this->getDispatchEventArg(0);
      $this->assertEquals('value', $event->getValue());
    }

    public function testValueUseFirstArg() {
      $callback = $this->callback;
      $callback('arg1', 'arg2', 3);
      /**
       * @var ValueEvent
       */
      $event = $this->getDispatchEventArg(0);
      $this->assertEquals('arg1', $event->getValue());
    }

    public function testUseCurrentValue() {
      $callback = $this->callback;
      $callback('value1');
      /**
       * @var ValueEvent
       */
      $event = $this->getDispatchEventArg(0);
      $this->assertEquals('value1', $event->getValue());
      $callback('value2');
      $event = $this->getDispatchEventArg(1);
      $this->assertEquals('value2', $event->getValue());
    }

    private function getDispatchEventArg($callIndex=0){
      return $this->target->dispatchEvent($this->eventType)->getObjectProphecy()->findProphecyMethodCalls('dispatchEvent', new ArgumentsWildcard([Argument::any()]))[$callIndex]->getArguments()[0];
    }
  }
}