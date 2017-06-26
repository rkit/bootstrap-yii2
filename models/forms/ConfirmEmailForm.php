<?php

namespace app\models\forms;

use Yii;
use app\models\User;
use app\services\Tokenizer;

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
    public function validateToken(string $token): bool
    {
        $tokenizer = new Tokenizer();
        if (empty($token) || !is_string($token) || !$tokenizer->validate($token)) {
            return false;
        }

        $this->user = User::find()->emailConfirmToken($token)->one();

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
    public function confirmEmail(): bool
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
    public function sendEmail(User $user): bool
    {
        $tokenizer = new Tokenizer();
        if (!$tokenizer->validate($user->email_confirm_token)) {
            $user->setEmailConfirmToken($tokenizer->generate());
            $user->updateAttributes([
                'email_confirm_token' => $user->email_confirm_token,
                'date_confirm' => $user->date_confirm,
            ]);
        }

        return Yii::$app->notify->sendMessage(
            $user->email,
            Yii::t('app', 'Activate Your Account'),
            'emailConfirmToken',
            ['user' => $user]
        );
    }
}
