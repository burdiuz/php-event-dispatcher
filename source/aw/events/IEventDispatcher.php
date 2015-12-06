<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */


namespace aw\events {
  interface IEventDispatcher {
    /**
     * @param string $eventType
     * @param callable|array $handler
     * @param int $priority
     * @return callable
     */
    public function addEventListener(string $eventType, $handler, int $priority = 0):callable;

    public function removeEventListener(string $eventType, callable $handler);

    public function hasEventListener(string $eventType):bool;

    /**
     * @param string|IEvent $event
     * @return boolean
     */
    public function dispatchEvent($event):bool;
  }
}