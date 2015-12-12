<?php
/**
 * Created by Oleg Galaburda on 12.12.15.
 */


namespace aw\events{

  interface IEventListeners {
    /**
     * @param string $eventType
     * @param callable|array $handler
     * @param int $priority
     * @return callable
     */
    public function addEventListener(string $eventType, callable $handler, int $priority = 0):callable;

    public function removeEventListener(string $eventType, callable $handler);

    public function hasEventListener(string $eventType):bool;

  }

}
