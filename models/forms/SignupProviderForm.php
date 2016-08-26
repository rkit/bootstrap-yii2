<?php

namespace app\models\forms;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\DynamicModel;
use app\helpers\Util;
use app\models\User;
use app\models\UserProfile;
use app\models\UserProvider;

class SignupProviderForm extends \yii\base\Model
{
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
    private $verifiedEmail = false;
    /**
     * @var array
     */
    private $provider = null;

    public function __construct($provider)
    {
        $this->provider = $provider;
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
                'message' => Yii::t('app.validators', 'This email address has already been taken')
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
     * Is verified?
     *
     * @return bool
     */
    public function isVerifiedEmail()
    {
        return $this->verifiedEmail;
    }

    /**
     * Prepare user
     */
    public function prepareUser()
    {
        $profile = $this->provider['profile'];
        $this->email = ArrayHelper::getValue($profile, 'email');
        $isVerifiedEmail = ArrayHelper::getValue($profile, 'verified');

        if ($isVerifiedEmail && !empty($this->email)) {
            $this->verifiedEmail = true;
            $this->user = User::findByEmail($this->email);

            if (!$this->user) {
                $this->user = new User();
                $this->user->setConfirmed();
            }
        }

        if ($this->user === null) {
            $this->user = new User();
        }
    }

    /**
     * Parse provider
     *
     * @return array
     */
    public function parseProvider()
    {
        $provider = [];
        switch ($this->provider['type']) {
            case UserProvider::TYPE_FACEBOOK:
                $provider = $this->parseProviderFacebook();
                break;

            case UserProvider::TYPE_VKONTAKTE:
                $provider = $this->parseProviderVkontakte();
                break;

            case UserProvider::TYPE_TWITTER:
                $provider = $this->parseProviderTwitter();
                break;
        }
        $provider['type'] = $this->provider['type'];
        return $provider;
    }

    /**
     * Parse profile
     *
     * @return array
     */
    public function parseProfile()
    {
        $profile = [];
        switch ($this->provider['type']) {
            case UserProvider::TYPE_FACEBOOK:
                $profile = $this->parseProfileFacebook();
                break;

            case UserProvider::TYPE_VKONTAKTE:
                $profile = $this->parseProfileVkontakte();
                break;

            case UserProvider::TYPE_TWITTER:
                $profile = $this->parseProfileTwitter();
                break;
        }
        $profile['type'] = $this->provider['type'];
        return $profile;
    }

    /**
     * Prepare provider attributes for facebook
     *
     * @return array
     */
    private function parseProviderFacebook()
    {
        $profile = $this->provider['profile'];
        return [
            'profile_id' => $profile['id'],
            'profile_url' => $profile['link'],
            'access_token' => $this->provider['token']['access_token'],
            'access_token_secret' => ''
        ];
    }

    /**
     * Prepare provider attributes for vkontakte
     *
     * @return array
     */
    private function parseProviderVkontakte()
    {
        $profile = $this->provider['profile'];
        return [
            'profile_id' => $profile['id'],
            'profile_url' => 'https://vk.com/id' . $profile['id'],
            'access_token' => $this->provider['token']['access_token'],
            'access_token_secret' => ''
        ];
    }

    /**
     * Prepare provider attributes for twitter
     *
     * @return array
     */
    private function parseProviderTwitter()
    {
        $profile = $this->provider['profile'];
        return [
            'profile_id' => $profile['id'],
            'profile_url' => 'https://twitter.com/' . $profile['screen_name'],
            'access_token' => $this->provider['token']['oauth_token'],
            'access_token_secret' => $this->provider['token']['oauth_token_secret']
        ];
    }

    /**
     * Prepare profile attributes for facebook
     *
     * @return array
     */
    private function parseProfileFacebook()
    {
        $profile = $this->provider['profile'];
        return [
            'full_name' => trim($profile['name']),
            'birth_day' => '—',
            'photo' => ArrayHelper::getValue($profile, 'picture.data.url', '')
        ];
    }

    /**
     * Prepare profile attributes for vkontakte
     *
     * @return array
     */
    private function parseProfileVkontakte()
    {
        $profile = $this->provider['profile'];
        return [
            'full_name' => trim($profile['first_name'] . ' ' . $profile['last_name']),
            'birth_day' => date_format(
                date_create_from_format('d.m.Y', $profile['bdate']),
                'Y-m-d'
            ),
            'photo' => str_replace('_50', '_400', $profile['photo'])
        ];
    }

    /**
     * Prepare profile attributes for twitter
     *
     * @param array $data Data from social network
     * @return array
     */
    private function parseProfileTwitter()
    {
        $profile = $this->provider['profile'];
        return [
            'full_name' => $profile['name'],
            'photo' => str_replace('_normal', '_400x400', $profile['profile_image_url'])
        ];
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
        $file = Util::makeUploadedFile('file', $photo);

        $model = new DynamicModel(compact('file'));
        $model->addRule('file', 'image', $profile->getFileRules('photo', true))->validate();

        if (!$model->hasErrors()) {
            $file = $profile->createFile('photo', $file->tempName, 'photo', true);
            $profile->photo = $file->id;
            $profile->save();
        }
    }

    /**
     * Signs user up
     *
     * @param bool $checkExistEmail
     * @return \app\models\User
     */
    public function signup($checkExistEmail = true)
    {
        if ($this->validate($checkExistEmail ? null : [])) {
            if ($this->user->isNewRecord) {
                $this->user->email = $this->email;
                $this->user->addProfile($this->parseProfile());
                $photo = $this->user->profile->photo;
            }
            $this->user->addProvider($this->parseProvider());

            if ($this->user->save()) {
                if (isset($photo) && !empty($photo)) {
                    $this->savePhoto($this->user->profile, $photo);
                }

                if ($this->user->authorize(true)) {
                    return $this->user;
                }
            } // @codeCoverageIgnore
        } // @codeCoverageIgnore

        return false;
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @return boolean
     */
    public function sendEmail()
    {
        if (!User::isTokenValid($this->user->email_confirm_token)) {
            $this->user->generateEmailConfirmToken();
            $this->user->updateAttributes([
                'email_confirm_token' => $this->user->email_confirm_token,
                'date_confirm' => $this->user->date_confirm,
            ]);
        }

        return Yii::$app->notify->sendMessage(
            $this->email,
            Yii::t('app', 'Activate Your Account'),
            'emailConfirmToken',
            ['user' => $this->user]
        );
    }
}
