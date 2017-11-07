<?php

namespace app\modules\auth\models\forms;

use Yii;
use yii\base\{Exception, UserException};
use app\models\entity\User;
use app\modules\auth\services\Tokenizer;

class PasswordResetRequestForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var Tokenizer
     */
    private $tokenizer;
    
    public function __construct(Tokenizer $tokenizer, $config = [])
    {
        $this->tokenizer = $tokenizer;

        parent::__construct($config);
    }

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
                'targetClass' => '\app\models\entity\User',
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

        if (!$this->tokenizer->validate($user->password_reset_token)) {
            $user->setPasswordResetToken($this->tokenizer->generate());
            if (!$user->save()) {
                throw new Exception(Yii::t('app.msg', 'An error occurred while saving user'));
            }
        }

        $sent = Yii::$app->mailer
            ->compose('passwordResetToken', ['user' => $user])
            ->setTo($user->email)
            ->setSubject(Yii::t('app', 'Password Reset'))
            ->send();

        if (!$sent) {
            throw new UserException(Yii::t('app.msg', 'An error occurred while sending a message to reset password'));
        }
    }
}
