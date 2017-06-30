<?php

namespace app\models\forms;

use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
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
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\BadRequestHttpException
     */
    public function __construct($token, $config = [])
    {
        $tokenizer = new Tokenizer();
        if (empty($token) || !is_string($token) || !$tokenizer->validate($token)) {
            throw new BadRequestHttpException(Yii::t('app.msg', 'Invalid link for reset password'));
        }

        $this->user = User::find()->passwordResetToken($token)->one();

        if (!$this->user) {
            throw new BadRequestHttpException(Yii::t('app.msg', 'Invalid link for reset password'));
        }

        parent::__construct($config);
    }

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
        return [
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Resets password
     *
     * @throws Exception
     */
    public function resetPassword(): void
    {
        $this->user->setPassword($this->password);
        $this->user->removePasswordResetToken();
        $this->user->updateDateLogin();

        if (!$this->user->save()) {
            throw new Exception(Yii::t('app.msg', 'An error occurred while saving user'));
        }

        Yii::$app->user->login($this->user, 3600 * 24 * 30);
    }
}
