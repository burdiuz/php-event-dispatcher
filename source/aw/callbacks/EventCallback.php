<?php

/**
 * Диспатчит ValueEvent, когда приходит новое значение/ия

$callback = new EventCallback();
$callback->addEventListener(ValueEvent::VALUE_COMMIT, new FunctionHandler('handler'));
function handler(ValueEvent $event){
	echo $event->value();
}
call_user_func($callback->caller(), 'value');

 * @author Oleg
 *
 */
class EventCallback extends AbstractEventCallback implements IEventDispatcher{
	protected $_value;
	public function getData(){
		return $this->_value;
	}
	public function __callback($value){
		$this->_value = $value;
		$this->_dispatcher->dispatchEvent(new ValueEvent(ValueEvent::VALUE_COMMIT, false, $value));
	}
}

?>