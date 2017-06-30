<?php

namespace app\models\forms;

use Yii;
use yii\base\Exception;
use yii\base\UserException;
use app\models\User;
use app\services\Tokenizer;

class PasswordResetRequestForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('app.msg', 'There is no user with such email')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password
     *
     * @throws Exception
     * @throws UserException
     */
    public function sendEmail(): void
    {
        /* @var $user User */
        $user = User::find()->email($this->email)->one();
        if (!$user) {
            throw new UserException(Yii::t('app.msg', 'User not found'));
        }

        $tokenizer = new Tokenizer();
        if (!$tokenizer->validate($user->password_reset_token)) {
            $user->setPasswordResetToken($tokenizer->generate());
            if (!$user->save()) {
                throw new Exception(Yii::t('app.msg', 'An error occurred while saving user'));
            }
        }

        $sent = Yii::$app->notify->sendMessage(
            $user->email,
            Yii::t('app', 'Password Reset'),
            'passwordResetToken',
            ['user' => $user]
        );

        if (!$sent) {
            throw new UserException(Yii::t('app.msg', 'An error occurred while sending a message to reset password'));
        }
    }
}
