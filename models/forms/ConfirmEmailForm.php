<?php

namespace app\models\forms;

use Yii;
use app\models\User;

class ConfirmEmailForm extends \yii\base\Model
{
    /**
     * @var \app\models\User
     */
    private $user;

    /**
     * Validate token
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
     * Confirm email
     *
     * @return boolean
     */
    public function confirmEmail()
    {
        $this->user->setConfirmed();
        return $this->user->save(false);
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @param User $user
     * @return boolean
     */
    public function sendEmail($user)
    {
        if (!User::isTokenValid($user->email_confirm_token)) {
            $user->generateEmailConfirmToken();
            $user->updateAttributes([
                'email_confirm_token' => $user->email_confirm_token,
                'date_confirm' => $user->date_confirm,
            ]);
        }

        return Yii::$app->notify->sendMessage(
            $user->email,
            Yii::t('app.messages', 'Activate Your Account'),
            'emailConfirmToken',
            ['user' => $user]
        );
    }
}
