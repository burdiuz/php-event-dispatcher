##PHP-Events

Events allow easy setup and use for communication between components in application.
Event can notify about state changes and pass data of any kind.
To use events you don't need to implement any interfaces, just create `EventDispatcher`
and register some listeners for events.
```php
class Broadcaster {
  const EVENT_FIRST = 'eventFirst';
  const EVENT_SECOND = 'eventSecond';
  const EVENT_THIRD = 'eventThird';
  /**
   * @var \aw\events\EventDispatcher
   */
  private $_dispatcher;

  //TODO add dispatcher target test
  public function __construct() {
    $this->_dispatcher = new \aw\events\EventDispatcher();
  }

  public function addHandler(string $eventType, callable $handler) {
    $this->_dispatcher->addEventListener($eventType, $handler);
  }

  public function doFirst() {
    echo 'do first and tell ';
    $this->_dispatcher->dispatchEvent(self::EVENT_FIRST);
  }

  public function doSecond() {
    echo 'do second and tell ';
    $this->_dispatcher->dispatchEvent(new \aw\events\ValueEvent(self::EVENT_SECOND, 'pass some data'));
  }

  public function doThird() {
    echo 'do third and tell ';
    $this->_dispatcher->dispatchEvent(self::EVENT_THIRD);
  }
}
```
After `EventDispatcher` is instantiated, you can add listeners and dispatch events.
```php
$target = new Broadcaster();
// register event handlers
$target->addHandler(Broadcaster::EVENT_FIRST, function ($event) {
  echo $event->type . PHP_EOL;
});

function secondHandler($event) {
  echo 'handler was called with data: '.$event->value.PHP_EOL;
}

$target->addHandler(Broadcaster::EVENT_SECOND, 'secondHandler');
$target->addHandler(Broadcaster::EVENT_THIRD, [new class () {
  public function eventHandler($event) {
    echo 'event has target: '.json_encode(isset($event->target)).PHP_EOL;
  }
}, 'eventHandler']);
// broadcast events
$target->doThird(); // do third and tell event has target: true
$target->doSecond(); // do second and tell handler was called with data: pass some data
$target->doFirst(); // do first and tell eventFirst
```