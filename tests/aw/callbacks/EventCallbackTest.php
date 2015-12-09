<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\callbacks {

  use \PHPUnit_Framework_TestCase as TestCase;
  use \Prophecy\Argument;
  use \Prophecy\Argument\ArgumentsWildcard;
  use \Prophecy\Prophecy\MethodProphecy;

  class EventCallbackTest extends TestCase {
    public $target;
    public $callback;
    public $eventType;

    public function setUp() {
      /**
       * @var \Prophecy\Prophecy\ObjectProphecy
       */
      $this->target = $this->prophesize('\\aw\\events\\EventDispatcher');
      $this->eventType = Argument::type('\\aw\\events\\Event');
      $this->target->dispatchEvent($this->eventType)->willReturn(true);
      $this->callback = new EventCallback($this->target->reveal());
    }

    public function tearDown() {
      unset($this->target);
      unset($this->eventType);
      unset($this->target);
      unset($this->callback);
    }

    public function testCall() {
      $callback = $this->callback;
      $spy = $this->target->dispatchEvent($this->eventType)->shouldBeCalledTimes(1);
      $this->assertTrue($callback());
      $spy->checkPrediction();
    }

    public function testEventObjectCaching() {
      $callback = $this->callback;
      /**
       * @var MethodProphecy
       */
      $spy = $this->target->dispatchEvent($this->eventType)->shouldBeCalledTimes(2);
      $this->assertTrue($callback());
      $event1 = $this->getDispatchEventArg(0);
      $callback();
      $event2 = $this->getDispatchEventArg(1);
      $this->assertSame($event1, $event2);
      $spy->checkPrediction();
    }
    private function getDispatchEventArg($callIndex=0){
      return $this->target->dispatchEvent($this->eventType)->getObjectProphecy()->findProphecyMethodCalls('dispatchEvent', new ArgumentsWildcard([Argument::any()]))[$callIndex]->getArguments()[0];
    }
  }
}