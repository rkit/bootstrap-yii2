<?php

namespace app\modules\auth\services;

use Yii;
use yii\base\{Exception, UserException};
use yii\web\BadRequestHttpException;
use app\models\entity\User;

class ConfirmEmail
{
    private $tokenizer;

    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * Confirm email
     *
     * @param string $token
     * @throws BadRequestHttpException
     */
    public function setConfirmed(string $token): void
    {
        if ($this->tokenizer->validate($token)) {
            $user = User::find()->emailConfirmToken($token)->one();

            if ($user) {
                $user->setConfirmed();
                if (!$user->save()) {
                    throw new Exception(Yii::t('app', 'An error occurred while confirming'));
                }
                return;
            }
        }

        throw new BadRequestHttpException(Yii::t('app', 'Invalid token for activate account'));
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @throws UserException
     */
    public function sendEmail(User $user): void
    {
        if (!$this->tokenizer->validate($user->email_confirm_token)) {
            $user->setEmailConfirmToken($this->tokenizer->generate());
            $user->updateAttributes([
                'email_confirm_token' => $user->email_confirm_token,
                'date_confirm' => $user->date_confirm,
            ]);
        }

        $sent = Yii::$app->mailer
            ->compose('emailConfirmToken', ['user' => $user])
            ->setTo($user->email)
            ->setSubject(Yii::t('app', 'Activate Your Account'))
            ->send();

        if (!$sent) {
            throw new UserException(
                Yii::t('app', 'An error occurred while sending a message to activate account')
            );
        }
    }
}
