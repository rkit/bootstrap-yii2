<?php

namespace app\modules\admin\models\forms;

use Yii;
use app\models\User;

class LoginForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;
    /**
     * @var bool
     */
    public $rememberMe = true;
    /**
     * @var \app\models\User
     */
    private $user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                ['username', 'password'], 'required'
            ],

            ['username', 'string', 'min' => 3, 'max' => 40],

            ['rememberMe', 'boolean'],

            ['password', 'validatePassword'],
            ['password', 'string', 'min' => 6],
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
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('app.validators', 'Incorrect username or password'));
            } elseif ($user && !$user->isActive()) {
                $this->addError('password', $user->getStatusDescription());
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean Whether the user is logged in successfully.
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
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->user === false) {
            $this->user = User::find()->username($this->username)->one();
        }

        return $this->user;
    }
}
