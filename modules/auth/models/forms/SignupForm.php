<?php

namespace app\modules\auth\models\forms;

use Yii;
use yii\base\{Exception, UserException};
use app\models\entity\User;
use app\modules\auth\services\Tokenizer;

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
     * @var \app\models\entity\User
     */
    private $user;
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
            ['fullName', 'required'],
            ['fullName', 'string', 'max' => 40],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => '\app\models\entity\User',
                'message' => Yii::t('app', 'This email address has already been taken')
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
            'password' => Yii::t('app', 'Password'),
            'fullName' => Yii::t('app', 'Your name'),
        ];
    }

    /**
     * Signs user up
     *
     * @throws Exception
     * @return \app\models\entity\User
     */
    public function signup(): User
    {
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->setProfile([
            'full_name' => $this->fullName,
        ]);
        $user->status = User::STATUS_ACTIVE;

        if (!$user->save()) {
            throw new Exception(Yii::t('app', 'An error occurred while saving user'));
        }

        $this->user = $user;

        $user->updateDateLogin();
        Yii::$app->user->login($user, 3600 * 24 * 30);

        $this->sendEmail();

        return $this->user;
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @throws UserException
     */
    private function sendEmail(): void
    {
        if (!$this->tokenizer->validate($this->user->email_confirm_token)) {
            $this->user->setEmailConfirmToken($this->tokenizer->generate());
            $this->user->updateAttributes([
                'email_confirm_token' => $this->user->email_confirm_token,
                'date_confirm' => $this->user->date_confirm,
            ]);
        }

        $sent = Yii::$app->mailer
            ->compose('registration', ['user' => $this->user])
            ->setTo($this->email)
            ->setSubject(Yii::t('app', 'Registration'))
            ->send();

        if (!$sent) {
            throw new UserException(Yii::t('app', 'An error occurred while sending a message to activate account'));
        }
    }
}
