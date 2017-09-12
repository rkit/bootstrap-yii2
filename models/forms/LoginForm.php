<?php

namespace app\models\forms;

use Yii;
use app\models\entity\User;

class LoginForm extends \yii\base\Model
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
     * @var bool
     */
    public $rememberMe = true;
    /**
     * @var \app\models\entity\User
     */
    private $user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                ['email', 'password'], 'required'
            ],

            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return (new User())->attributeLabels() + [
            'rememberMe' => Yii::t('app', 'Remember me')
        ];
    }

    /**
     * Validates the password
     * This method serves as the inline validation for password
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('app.msg', 'Incorrect email or password'));
            } elseif ($user && !$user->isActive()) {
                $this->addError('password', $user->getStatusDescription());
            }
        }
    }

    /**
     * Logs in a user using the provided email and password
     *
     * @return boolean
     */
    public function login(): bool
    {
        if ($this->validate()) {
            $this->getUser()->updateDateLogin();
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Get user
     *
     * @return \app\models\entity\User|null
     */
    public function getUser(): ?User
    {
        if ($this->user === false) {
            $this->user = User::find()->email($this->email)->one();
        }

        return $this->user;
    }
}
