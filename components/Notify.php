<?php

namespace app\components;

use Yii;

/**
 * Component for notify user
 */
class Notify extends \yii\base\Component
{
    /**
     * Send message.
     *
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     * @return bool
     */
    public function sendMessage($to, $subject, $view, $params = [])
    {
        $message = \Yii::$app->mailer->compose($view, $params);
        
        if (!empty(Yii::$app->settings->get('emailMain')) && 
            !empty(Yii::$app->settings->get('emailName'))) {
            $message->setFrom([
                Yii::$app->settings->emailMain => Yii::$app->settings->emailName
            ]);
        }
        
        $message->setTo($to);
        
        if (empty(Yii::$app->settings->get('emailPrefix'))) {
            $message->setSubject($subject);
        } else {
            $message->setSubject(Yii::$app->settings->emailPrefix . ': ' . $subject);
        }
        
        return $message->send();
    }
}
