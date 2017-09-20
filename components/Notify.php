<?php

namespace app\components;

use Yii;

/**
 * Component for notify user
 */
class Notify extends \yii\base\Component
{
    /**
     * @var object
     */
    private $params = null;

    public function init()
    {
        parent::init();

        $this->params = Yii::$app->settings;
    }

    /**
     * Send message
     *
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     * @param string[] $attach
     * @return bool
     */
    public function sendMessage(string $to, string $subject, string $view, array $params = [], array $attach = []): bool
    {
        $message = \Yii::$app->getMailer()->compose($view, $params);

        if (count($attach)) {
            foreach ($attach as $file) {
                $message->attach($file);
            }
        }

        if (strlen($this->params->emailMain) && strlen($this->params->emailName)) {
            $message->setFrom([$this->params->emailMain => $this->params->emailName]);
        }

        $to = explode(',', $to);
        $message->setTo($to);

        if (strlen($this->params->emailPrefix)) {
            $message->setSubject($this->params->emailPrefix . ': ' . $subject);
        } else {
            $message->setSubject($subject);
        }

        try {
            return $message->send();
        } catch (\Throwable $e) {
            Yii::error($e);
            return false;
        }
    }
}
