<?php

namespace app\models\forms;

use Yii;
use app\models\User;
use app\models\UserProfile;

class SignupForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $fullName;
    /**
     * @var \app\models\User
     */
    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['fullName', 'required'],
            ['fullName', 'string', 'max' => 40],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => '\app\models\User',
                'message' => Yii::t('app.validators', 'This email address has already been taken')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge((new User())->attributeLabels(), ['fullName' => Yii::t('app', 'Full Name')]);
    }

    /**
     * Signs user up
     *
     * @return \app\models\User
     */
    public function signup()
    {
        if ($this->validate()) {
            $this->user = new User();
            $this->user->email = $this->email;
            $this->user->setPassword($this->password);
            $this->user->addProfile(['full_name' => $this->fullName]);
            if ($this->user->save()) {
                if ($this->user->authorize(true)) {
                    return $this->user;
                }
            } // @codeCoverageIgnore
        } // @codeCoverageIgnore

        return false;
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @return boolean
     */
    public function sendEmail()
    {
        if (!User::isTokenValid($this->user->email_confirm_token)) {
            $this->user->generateEmailConfirmToken();
            $this->user->updateAttributes([
                'email_confirm_token' => $this->user->email_confirm_token,
                'date_confirm' => $this->user->date_confirm,
            ]);
        }

        return Yii::$app->notify->sendMessage(
            $this->email,
            Yii::t('app', 'Activate Your Account'),
            'emailConfirmToken',
            ['user' => $this->user]
        );
    }
}
