<?php

namespace app\models\forms;

use Yii;
use yii\base\InvalidParamException;
use app\models\User;

class ResetPasswordForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $password;
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
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return (new User())->attributeLabels();
    }

    /**
     * Validate token.
     *
     * @param string $token
     * @return boolean
     */
    public function validateToken($token)
    {
        if (empty($token) || !is_string($token)) {
            return false;
        }

        $this->user = User::findByPasswordResetToken($token);

        if (!$this->user) {
            return false;
        }

        return true;
    }

    /**
     * Resets password.
     *
     * @return boolean If password was reset.
     */
    public function resetPassword()
    {
        $this->user->setPassword($this->password);
        $this->user->removePasswordResetToken();
        $this->user->authorize(true);

        return $this->user->save(false);
    }
}
