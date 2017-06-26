<?php

namespace app\models\forms;

use Yii;
use app\models\User;
use app\services\Tokenizer;

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
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return (new User())->attributeLabels();
    }

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

        $this->user = User::find()->passwordResetToken($token)->one();

        if (!$this->user) {
            return false;
        }

        return true;
    }

    /**
     * Resets password
     *
     * @return boolean
     */
    public function resetPassword(): bool
    {
        $this->user->setPassword($this->password);
        $this->user->removePasswordResetToken();
        $this->user->updateDateLogin();

        Yii::$app->user->login($this->user, 3600 * 24 * 30);

        return $this->user->save(false);
    }
}
