<?php

namespace app\models\forms;

use Yii;
use app\models\User;
use app\models\UserProfile;

class SignupForm extends \yii\base\Model
{
    public $email;
    public $password;
    public $fullName;
    
    private $user;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['fullName', 'required'],
            ['fullName', 'string', 'max' => 40],
            
            ['password', 'required'],
            ['password', 'string', 'min' => 6],

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
        return array_merge((new User())->attributeLabels(), ['fullName' => Yii::t('app', 'Full Name')]);
    }
    
    /**
     * Signs user up.
     *
     * @return User|null The saved model or null if saving fails.
     */
    public function signup()
    {
        if ($this->validate()) {
            $this->user = new User();
            $this->user->email = $this->email;
            $this->user->setPassword($this->password);
            $this->user->generateEmailConfirmToken();
            
            $profile = new UserProfile();
            $profile->fullName = $this->fullName;
            $this->user->populateRelation('profile', $profile);
            
            if ($this->user->save()) {
                if ($this->user->authorize(true)) {
                    return $this->user;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Sends an email with a link, for confirm the email.
     *
     * @return boolean Whether the email was send.
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
