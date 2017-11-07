<?php

namespace app\components;

use yii\base\BootstrapInterface;

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
        foreach ($this->events as $eventClass) {
            (new $eventClass)->attachEventHandler();
        }
    }
}
