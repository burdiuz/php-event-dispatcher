<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */


namespace aw\events {
  interface IEventDispatcher extends IEventListeners {

    /**
     * @param string|IEvent $event
     * @return boolean
     */
    public function dispatchEvent($event):bool;
  }
}