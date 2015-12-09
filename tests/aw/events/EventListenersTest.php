<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\events {

  use \PHPUnit_Framework_TestCase as TestCase;

  class EventListenersTest extends TestCase {
    /**
     * @var \aw\events\EventListeners
     */
    public $target;
    public $handler1;
    public $handler2;
    public $handler3;

    public function setUp() {
      $this->target = new EventListeners();
      $this->handler1 = function(){
        return 1;
      };
      $this->handler2 = function(){
        return 2;
      };
      $this->handler3 = function(){
        return 3;
      };
      $this->target->add('event1', 0, $this->handler1);
      $this->target->add('event1', 0, $this->handler2);
      $this->target->add('event2', 100, $this->handler3);
    }

    public function tearDown() {
      unset($this->target);
      unset($this->handler1);
      unset($this->handler2);
      unset($this->handler3);
      // force destructor execution
      gc_collect_cycles();
    }

    public function testAddListeners() {
      $this->assertFalse($this->target->has('event3'));
      $this->assertTrue($this->target->add('event3', 0, $this->handler1));
      $this->assertTrue($this->target->add('event3', 0, $this->handler2));
      $this->assertTrue($this->target->add('event3', 0, $this->handler3));
      $this->assertFalse($this->target->add('event3', 0, $this->handler1));
      $this->assertTrue($this->target->add('event3', 1, $this->handler1));
      $this->assertTrue($this->target->add('event3', 1, $this->handler3));
      $this->assertFalse($this->target->add('event3', 1, $this->handler3));
    }

    public function testHasListeners() {
      $this->assertTrue($this->target->has('event1'));
      $this->assertTrue($this->target->has('event2'));
      $this->assertFalse($this->target->has('event3'));
    }

    public function testGetListenersByEventType() {
      $priorities = $this->target->get('event1');
      $this->assertEquals(1, $priorities->getCount());
      $this->assertEquals(2, $priorities->getItemAt(0)->getCount());
      $this->assertNull($this->target->get('event3'));
      $priorities = $this->target->get('event2');
      $this->assertEquals(1, $priorities->getCount());
      $this->assertEquals(null, $priorities->getItemAt(0));
      $this->assertEquals(1, $priorities->getItemAt(100)->getCount());
    }

    public function testRemoveListener() {
      $this->assertFalse($this->target->remove('event2', $this->handler1));
      $this->assertTrue($this->target->remove('event2', $this->handler3));
      $this->assertTrue($this->target->remove('event1', $this->handler1));
      $this->assertTrue($this->target->remove('event1', $this->handler2));
    }

    public function testRemoveListenersOfEventType() {
      $this->assertTrue($this->target->has('event1'));
      $this->assertTrue($this->target->has('event2'));
      $this->target->removeType('event2');
      $this->assertTrue($this->target->has('event1'));
      $this->assertFalse($this->target->has('event2'));
      $this->target->removeType('event1');
      $this->assertFalse($this->target->has('event1'));
      $this->assertFalse($this->target->has('event2'));
    }

    public function testRemoveListenerOfEventPriority() {
      $this->assertTrue($this->target->has('event1'));
      $this->assertTrue($this->target->has('event2'));
      $this->target->removePriority('event2', 0);
      $this->assertTrue($this->target->has('event1'));
      $this->assertTrue($this->target->has('event2'));
      $this->target->removePriority('event2', 100);
      $this->assertTrue($this->target->has('event1'));
      $this->assertFalse($this->target->has('event2'));
      $this->target->removePriority('event1', 0);
      $this->assertFalse($this->target->has('event1'));
      $this->assertFalse($this->target->has('event2'));
    }

    public function testClear() {
      $this->assertTrue($this->target->has('event1'));
      $this->assertTrue($this->target->has('event2'));
      $this->target->clear();
      $this->assertFalse($this->target->has('event1'));
      $this->assertFalse($this->target->has('event2'));
    }

    public function testIterator() {
      $types = array();
      $listeners = array();
      foreach($this->target as $eventType => $listener){
        $types[] = $eventType;
        $listeners[$eventType] = $listener;
      }
      $this->assertCount(3, $types);
      $this->assertContains('event1', $types);
      $this->assertContains('event2', $types);
      $this->assertSame($this->handler2, $listeners['event1']);
      $this->assertSame($this->handler3, $listeners['event2']);
    }
  }
}
