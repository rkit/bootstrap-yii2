<?php

namespace app\models\forms;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidParamException;
use app\models\User;
use app\models\UserProfile;

class SignupProviderForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var array
     */
    public $provider;
    /**
     * @var array
     */
    public $profile;
    /**
     * @var string
     */
    public $token;
    /**
     * @var \app\models\User
     */
    private $user = null;
    /**
     * @var bool
     */
    private $verified = false;

    /**
     * Form for social auth.
     *
     * @param array $data
     * @param array $config
     */
    public function __construct($data, $config = [])
    {
        $this->provider = $data['provider'];
        $this->email = ArrayHelper::getValue($data['profile'], 'email');
        $this->prepareAttributes($data);

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
                'message' => Yii::t('app', 'This email address has already been taken')
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
     * @return User
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
     * Signs user up.
     *
     * @return User|null The saved model or null if saving fails.
     */
    public function signup($validate = true)
    {
        if ($this->validate($validate ? null : [])) {
            if ($this->user->isNewRecord) {
                $this->user->email = $this->email;

                $profile = new UserProfile();
                $profile->load($this->profile, '');
                $photo = $profile->photo;
                $this->user->populateRelation('profile', $profile);
            }

            if ($this->user->save()) {
                if (!empty($photo)) {
                    $this->savePhoto($this->user->profile, $photo);
                }
                if ($this->user->saveProvider(
                    User::getProviders($this->provider),
                    $this->profile['profile_id'],
                    $this->profile['profile_url'],
                    $this->token['access_token'],
                    $this->token['access_token_secret']
                )) {
                    if ($this->user->authorize(true)) {
                        return $this->user;
                    }
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
     * Prepare profile and token.
     *
     * @param array $data Data from social network.
     */
    private function prepareAttributes($data)
    {
        switch ($this->provider) {
            case 'facebook':
                $attributes = $this->prepareFacebook($data);
                break;

            case 'vkontakte':
                $attributes = $this->prepareVkontakte($data);
                break;

            case 'twitter':
                $attributes = $this->prepareTwitter($data);
                break;
        }

        $this->profile = $attributes['profile'];
        $this->token = $attributes['token'];
    }

    /**
     * Prepare Facebook attributes.
     *
     * @return array
     */
    private function prepareFacebook($data)
    {
        return [
            'profile' => [
                'profile_id' => $data['profile']['id'],
                'profile_url' => $data['profile']['link'],
                'full_name' => trim($data['profile']['first_name'] . ' ' . $data['profile']['last_name'])
            ],
            'token' => [
                'access_token' => $data['token']['access_token'],
                'access_token_secret' => ''
            ]
        ];
    }

    /**
     * Prepare Vkontakte attributes.
     *
     * @return array
     */
    private function prepareVkontakte($data)
    {
        return [
            'profile' => [
                'profile_id' => $data['profile']['id'],
                'profile_url' => 'https://vk.com/id' . $data['profile']['id'],
                'full_name' => trim($data['profile']['first_name'] . ' ' . $data['profile']['last_name']),
                'birth_day' => date_format(date_create_from_format('d.m.Y', $data['profile']['bdate']), 'Y-m-d'),
                'photo' => str_replace('_50', '_400', $data['profile']['photo'])
            ],
            'token' => [
                'access_token' => $data['token']['access_token'],
                'access_token_secret' => ''
            ]
        ];
    }

    /**
     * Prepare Twitter attributes.
     *
     * @return array
     */
    private function prepareTwitter($data)
    {
        return [
            'profile' => [
                'profile_id' => $data['profile']['id'],
                'profile_url' => 'https://twitter.com/' . $data['profile']['screen_name'],
                'full_name' => $data['profile']['name'],
                'photo' => str_replace('_normal', '_400x400', $data['profile']['profile_image_url'])
            ],
            'token' => [
                'access_token' => $data['token']['oauth_token'],
                'access_token_secret' => $data['token']['oauth_token_secret']
            ]
        ];
    }

    /**
     * Sends an email with a link, for confirm the email.
     *
     * @return boolean Whether the email was send
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
                    Yii::t('app', 'Activate Your Account'),
                    'emailConfirmToken',
                    ['user' => $this->user]
                );
            }
        }

        return false;
    }
}
