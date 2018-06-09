<?php

namespace app\components;

use yii\base\BootstrapInterface;
use yii\base\Event;

class Bootstrap implements BootstrapInterface
{
    /**
     * @var array
     */
    public $events;

    public function bootstrap($app)
    {
        $this->attachEventHandlers();
    }

    private function attachEventHandlers()
    {
        foreach ($this->events as $className => $events) {
            foreach ($events as $eventName => $handlers) {
                foreach ($handlers as $handlerClass) {
                    Event::on($className, $eventName, function ($event) use ($handlerClass) { 
                        (new $handlerClass)($event);
                    });
                }
            }
        }
    }
}
