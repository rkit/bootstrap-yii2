<?php

namespace app\models\forms;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\UserProfile;
use app\models\UserProvider;

class SignupProviderForm extends \yii\base\Model
{
    /**
     * @var int
     */
    public $type;
    /**
     * @var string
     */
    public $email;
    /**
     * @var \app\models\User
     */
    private $user = null;
    /**
     * @var bool
     */
    private $verified = false;
    /**
     * @var array
     */
    private $data = [];

    /**
     * Form for social auth
     *
     * @param array $data
     * @param array $config
     */
    public function __construct($data, $config = [])
    {
        $this->email = ArrayHelper::getValue($data['profile'], 'email');
        $this->type = $data['type'];
        $this->data = $data;

        if (ArrayHelper::getValue($data['profile'], 'verified') && !empty($this->email)) {
            $this->verified = true;
            $this->user = User::findByEmail($this->email);

            if (!$this->user) {
                $this->user = new User();
                $this->user->setConfirmed();
            }
        }

        if ($this->user === null) {
            $this->user = new User();
        }

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
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => '\app\models\User',
                'message' => Yii::t('app.messages', 'This email address has already been taken')
            ],
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
     * Get User
     *
     * @return \app\models\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Is verified?
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified;
    }

    /**
     * Signs user up
     *
     * @param bool $validate
     * @return \app\models\User|null The saved model or null if saving fails.
     */
    public function signup($validate = true)
    {
        if ($this->validate($validate ? null : [])) {
            if ($this->user->isNewRecord) {
                $this->user->email = $this->email;
                $this->user->addProfile(UserProvider::parseProfile($this->type, $this->data));
                $photo = $this->user->profile->photo;
            }
            $this->user->addProvider(UserProvider::parseProvider($this->type, $this->data));

            if ($this->user->save()) {
                if (isset($photo) && !empty($photo)) {
                    $this->savePhoto($this->user->profile, $photo);
                }

                if ($this->user->authorize(true)) {
                    return $this->user;
                }
            }
        }

        return null;
    }

    /**
     * Save photo
     *
     * @param \app\models\UserProfile $profile
     * @param string $photo
     * @return void
     */
    private function savePhoto($profile, $photo)
    {
        $file = Yii::$app->fileManager->create($photo, $this->user->id, $profile->getFileOwnerType('photo'), true);
        if ($file) {
            $profile->updateAttributes(['photo' => $file->path()]);
        }
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @return boolean
     */
    public function sendEmail()
    {
        if ($this->user) {
            if (!User::isTokenValid($this->user->email_confirm_token)) {
                $this->user->generateEmailConfirmToken();
            }

            if ($this->user->save(false)) {
                return Yii::$app->notify->sendMessage(
                    $this->email,
                    Yii::t('app.messages', 'Activate Your Account'),
                    'emailConfirmToken',
                    ['user' => $this->user]
                );
            }
        }

        return false;
    }
}
