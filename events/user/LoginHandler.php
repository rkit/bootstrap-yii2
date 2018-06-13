<?php

namespace app\events\user;

use Yii;

/**
 * Handler for EVENT_BEFORE_LOGIN
 * 
 * @see config/web.php
 */
class LoginHandler
{
    public function __invoke(\yii\base\Event $event) 
    {
        Yii::info(['userId' => $event->identity->id], 'app.login'); 
    }
}
