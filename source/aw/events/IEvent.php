<?php
/**
 * Created by Oleg Galaburda on 04.12.15.
 */


namespace aw\events;


interface IEvent {
  public function getType():string;
  public function getTarget():IEventDispatcher;
}