<?php

namespace app\modules\auth\models\forms;

use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use app\models\entity\User;
use app\modules\auth\services\Tokenizer;

class ResetPasswordForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $password;
    /**
     * @var \app\models\entity\User
     */
    private $user;
    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\BadRequestHttpException
     */
    public function __construct($token, Tokenizer $tokenizer, $config = [])
    {
        $this->tokenizer = $tokenizer;

        if (empty($token) || !is_string($token) || !$this->tokenizer->validate($token)) {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid link for reset password'));
        }

        $this->user = User::find()->passwordResetToken($token)->one();

        if (!$this->user) {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid link for reset password'));
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
            throw new Exception(Yii::t('app', 'An error occurred while saving user'));
        }

        Yii::$app->user->login($this->user, 3600 * 24 * 30);
    }
}
