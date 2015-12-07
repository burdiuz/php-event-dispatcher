<?php
/**
 * Created by Oleg Galaburda on 06.12.15.
 */


namespace aw\events {

  use \PHPUnit_Framework_TestCase as TestCase;

  class EventCollectionTest extends TestCase {
    public $parent;

    public function setUp() {
      $this->parent = new EventCollection(null, function () {
      }, null);
    }

    public function testCreateWithNumericKey() {
      $collection = new EventCollection(321, function () {
      }, $this->parent);
      $this->assertEquals(321, $collection->getType());
      $this->assertSame($this->parent, $collection->getParent());
    }

    public function testCreateWithStringKey() {
      $collection = new EventCollection('key!11', function () {
      }, $this->parent);
      $this->assertEquals('key!11', $collection->getType());
      $this->assertSame($this->parent, $collection->getParent());
    }

    public function testCreateWithoutParent() {

    }

    public function testEmptyCallbackOnRemove() {

    }

    public function testEmptyCallbackOnRemoveAll() {

    }
  }
}
