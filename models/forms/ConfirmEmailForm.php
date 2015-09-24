<?php

namespace app\models\forms;

use Yii;
use yii\base\InvalidParamException;
use app\models\User;

class ConfirmEmailForm extends \yii\base\Model
{
    /**
     * @var \app\models\User
     */
    private $user;

    /**
     * Validate token.
     *
     * @param string $token
     * @return boolean
     */
    public function validateToken($token)
    {
        if (empty($token) || !is_string($token)) {
            return false;
        }

        $this->user = User::findByEmailConfirmToken($token);

        if (!$this->user) {
            return false;
        }

        return true;
    }

    /**
     * Confirm email.
     *
     * @return boolean if email was confirm.
     */
    public function confirmEmail()
    {
        $this->user->setConfirmed();
        return $this->user->save(false);
    }
}
