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
     *
     * @param string $attribute The attribute currently being validated.
     * @param array $params The additional name-value pairs given in the rule.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('app.messages', 'Incorrect username or password'));
            } elseif ($user && !$user->isActive()) {
                $this->addError('password', $user->getStatusDescription());
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean Whether the user is logged in successfully.
     */
    public function login()
    {
        if ($this->validate()) {
            return $this->getUser()->authorize($this->rememberMe);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            $this->user = User::findByUsername($this->username);
        }

        return $this->user;
    }
}
