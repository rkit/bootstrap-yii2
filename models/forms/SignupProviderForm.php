<?php

namespace app\models\forms;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidParamException;
use app\models\File;
use app\models\User;
use app\models\UserProfile;

class SignupProviderForm extends \yii\base\Model
{
    public $email;
    public $provider;
    public $profile;
    public $token;
    
    private $user = null;
    private $verified = false;
    
    /**
     * Creates a form model given a provider.
     *
     * @param array $provider
     * @param array $config Name-value pairs that will be used to initialize the object properties.
     * @throws \yii\base\InvalidParamException If token is empty or not valid.
     */
    public function __construct($data, $config = [])
    {
        if (!isset($data['provider']) || !isset($data['profile']) || !isset($data['token'])) {
            throw new InvalidParamException(Yii::t('app', 'Incorrect data, please try again'));
        }
        
        $this->provider = $data['provider'];
        $this->email = ArrayHelper::getValue($data['profile'], 'email');
        $this->prepareProfile($data['profile']);
        $this->prepareToken($data['token']);
 
        if (ArrayHelper::getValue($data['profile'], 'verified') && !empty($this->email)) {
            $this->verified = true;
            $this->user = User::findByEmail($this->email);
            
            if ($this->user) {
                if (!$this->user->isActive()) {
                    throw new InvalidParamException(Yii::t('app', $this->user->getStatusDescription()));
                } 
            } else {
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
                
                if (!empty($profile->photo)) {
                    $file = File::createFromUrl($profile->photo, File::OWNER_TYPE_USER_PHOTO);
                    if ($file) {
                        $profile->photo = $file->id;
                    }
                }
                
                $this->user->populateRelation('profile', $profile);
            }
       
            if ($this->user->save()) {
                if ($this->user->saveProvider(User::getProviders($this->provider), [
                    'profileId' => $this->profile['profileId'],
                    'profileUrl' => $this->profile['profileUrl'],
                    'accessToken' => $this->token['accessToken'], 
                    'accessTokenSecret' => $this->token['accessTokenSecret']
                ])
                ) {
                    if ($this->user->authorize(true)) {
                        return $this->user;
                    }
                }
            }
        }
        
        return null;
    }
    
    /**
     * Prepore token.
     *
     * @param array $data Token from social network.
     */
    private function prepareToken($data)
    {
        $token = [];
        switch ($this->provider) {
            case 'facebook': 
                $token['accessToken'] = $data['access_token'];
                $token['accessTokenSecret'] = '';
                break;
                
            case 'vkontakte': 
                $token['accessToken'] = $data['access_token'];
                $token['accessTokenSecret'] = '';
                break;
                
            case 'twitter': 
                $token['accessToken'] = $data['oauth_token'];
                $token['accessTokenSecret'] = $data['oauth_token_secret'];
                break;
        }
        
        $this->token = $token; 
    }
    
    /**
     * Prepore profile.
     *
     * @param array $data Data from social network.
     */
    private function prepareProfile($data)
    {
        $profile = [];
        switch ($this->provider) {
            case 'facebook': 
                $profile['profileId']  = $data['id'];
                $profile['profileUrl'] = $data['link'];
                $profile['firstName']  = $data['first_name'];
                $profile['lastName']   = $data['last_name'];
                $profile['birthDay']   = date_format(date_create_from_format('m/d/Y', $data['birthday']), 'Y-m-d');
                break;
                
            case 'vkontakte': 
                $profile['profileId']  = $data['id'];
                $profile['profileUrl'] = 'https://vk.com/id' . $data['id'];
                $profile['firstName']  = $data['first_name'];
                $profile['lastName']   = $data['last_name'];
                $profile['birthDay']   = date_format(date_create_from_format('d.m.Y', $data['bdate']), 'Y-m-d');
                $profile['photo']      = str_replace('_50', '_400', $data['photo']);
                break;
                
            case 'twitter': 
                $profile['profileId']  = $data['id'];
                $profile['profileUrl'] = 'https://twitter.com/' . $data['screen_name'];
                $profile['firstName']  = $data['name'];
                $profile['photo']      = str_replace('_normal', '_400x400', $data['profile_image_url']);
                break;
        }

        $this->profile = $profile;
    }
    
    /**
     * Sends an email with a link, for confirm the email.
     *
     * @return boolean Whether the email was send
     */
    public function sendEmail()
    {
        if ($this->user) {
            if (!User::isTokenValid($this->user->emailConfirmToken)) {
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
