<?php

namespace app\models\forms;

use Yii;
use app\models\User;
use app\models\UserProfile;
use app\services\Tokenizer;

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
    public function signup(): ?User
    {
        if ($this->validate()) {
            $this->user = new User();
            $this->user->email = $this->email;
            $this->user->setPassword($this->password);
            $this->user->setProfile(['full_name' => $this->fullName]);
            if ($this->user->save()) {
                $this->user->updateDateLogin();

                if (Yii::$app->user->login($this->user, 3600 * 24 * 30)) {
                    return $this->user;
                }
            } // @codeCoverageIgnore
        } // @codeCoverageIgnore

        return null;
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @return boolean
     */
    public function sendEmail(): bool
    {
        $tokenizer = new Tokenizer();
        if (!$tokenizer->validate($this->user->email_confirm_token)) {
            $this->user->setEmailConfirmToken($tokenizer->generate());
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
