<?php

namespace app\services;

use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\BadRequestHttpException;
use app\models\User;
use app\services\Tokenizer;

class ConfirmEmail
{
    /**
     * Confirm email
     *
     * @param string $token
     * @throws BadRequestHttpException
     */
    public function setConfirmed(string $token): void
    {
        $tokenizer = new Tokenizer();

        if ($tokenizer->validate($token)) {
            $user = User::find()->emailConfirmToken($token)->one();

            if ($user) {
                $user->setConfirmed();
                if (!$user->save()) {
                    throw new Exception(Yii::t('app.msg', 'An error occurred while confirming'));
                }
                return;
            }
        }

        throw new BadRequestHttpException(Yii::t('app.msg', 'Invalid token for activate account'));
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @throws UserException
     */
    public function sendEmail(User $user): void
    {
        $tokenizer = new Tokenizer();
        if (!$tokenizer->validate($user->email_confirm_token)) {
            $user->setEmailConfirmToken($tokenizer->generate());
            $user->updateAttributes([
                'email_confirm_token' => $user->email_confirm_token,
                'date_confirm' => $user->date_confirm,
            ]);
        }

        $sent = Yii::$app->notify->sendMessage(
            $user->email,
            Yii::t('app', 'Activate Your Account'),
            'emailConfirmToken',
            ['user' => $user]
        );

        if (!$sent) {
            throw new UserException(
                Yii::t('app.msg', 'An error occurred while sending a message to activate account')
            );
        }
    }
}
