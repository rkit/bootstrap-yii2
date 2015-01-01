<?php

namespace app\models;

use yii\base\NotSupportedException;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use app\components\BaseActive;
use app\models\UserProfile;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $passwordResetToken
 * @property string $emailConfirmToken
 * @property string $authKey
 * @property string $dateConfirm
 * @property string $dateCreate
 * @property string $dateUpdate
 * @property string $dateLogin
 * @property integer $ip
 * @property string $role
 * @property integer $status
 */
class User extends BaseActive implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE  = 1;
    const STATUS_BLOCKED = 2;
    
    const PROVIDER_TWITTER   = 1;
    const PROVIDER_FACEBOOK  = 2;
    const PROVIDER_VKONTAKTE = 3;
    
    const ROLE_SUPERUSER = 'SuperUser';
    
    public $passwordNew;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {                    
        return [
            ['username', 'string', 'min' => 3, 'max' => 40],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'unique'],
            ['username', 'default', 'value' => null],

            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],
            
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatuses())],
            
            //
            // scenario: admin-edit
            //
            ['role', 'string', 'on' => 'admin-edit'],
            
            ['username', 'required', 'when' => function($model) {
                return empty($model->email);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#user-email').val().length
            }", 'message' => Yii::t('app', 'You must fill in username or email'), 'on' => 'admin-edit'],
            
            ['passwordNew', 'string', 'min' => 6, 'on' => 'admin-edit'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'username'   => Yii::t('app', 'Username'),
            'email'      => Yii::t('app', 'Email'),
            'password'   => Yii::t('app', 'Password'),
            'dateCreate' => Yii::t('app', 'Date create'),
            'dateUpdate' => Yii::t('app', 'Date update'),
            'dateLogin'  => Yii::t('app', 'Last login'),
            'ip'         => Yii::t('app', 'IP'),
            'role'       => Yii::t('app', 'Role'),
            'status'     => Yii::t('app', 'Status'),
            
            'passwordNew' => Yii::t('app', 'New password'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreate',
                'updatedAtAttribute' => 'dateUpdate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }    
    
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['userId' => 'id']);
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
                $this->ip = !isset(Yii::$app->enableCoreCommands) ? ip2long(Yii::$app->request->getUserIP()) : 0;
                
                if ($this->profile === null) {
                    $this->populateRelation('profile', new UserProfile());
                }
            }
        
            if (!empty($this->passwordNew)) {
                $this->setPassword($this->passwordNew);
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if ($this->profile !== null) {
            $this->link('profile', $this->profile);
        }
    }
    
    /**
     * Get providers.
     *
     * @param string $provider
     * @param array|int
     */
    public static function getProviders($provider = null)
    {
        $providers = [
            self::PROVIDER_TWITTER => 'twitter',
            self::PROVIDER_FACEBOOK => 'facebook',
            self::PROVIDER_VKONTAKTE => 'vkontakte',
        ];
        
        if ($provider) {
            return array_flip($providers)[$provider];
        }
        
        return $providers;
    }
    
    /**
     * Get all statuses.
     *
     * @param array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_DELETED => Yii::t('app', 'Deleted'),
            self::STATUS_BLOCKED => Yii::t('app', 'Locked'),
            self::STATUS_ACTIVE  => Yii::t('app', 'Active'),
        ];
    }
    
    /**
     * Get statuse name
     *
     * @return string
     */  
    public function getStatusName()
    {
        $statuses = $this->getStatuses();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }
    
    /**
     * Is it deleted?
     *
     * @param bool
     */
    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
    }
    
    /**
     * Is it blocked?
     *
     * @param bool
     */
    public function isBlocked()
    {
        return $this->status == self::STATUS_BLOCKED;
    }
    
    /**
     * Is it active?
     *
     * @param bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }
    
    /**
     * Is it confirmed?
     *
     * @param bool
     */
    public function isConfirmed()
    {
        return $this->dateConfirm > 0;
    }
    
    /**
     * Set confirmed.
     */
    public function setConfirmed()
    {
        $this->emailConfirmToken = '';
        $this->dateConfirm = new \yii\db\Expression('NOW()');
    }
    
    /**
     * Get status description.
     *
     * @return string
     */
    public function getStatusDescription()
    {
        if ($this->status == self::STATUS_BLOCKED) {
            return Yii::t('app', 'Your account has been suspended');
        } elseif ($this->status == self::STATUS_DELETED) {
            return Yii::t('app', 'Your account has been deleted');
        } else {
            return Yii::t('app', 'Your account is activated');
        }
    }
    
    public function isSuperUser()
    {
        return $this->role === self::ROLE_SUPERUSER;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (empty($this->password)) {
            return false;
        }
        
        return Yii::$app->security->validatePassword($password, $this->password);
    }
        
    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Generate new token.
     */
    public function generateToken()
    {
        return Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Authorize user.
     *
     * @param bool $rememberMe
     * @return bool
     */
    public function authorize($rememberMe = false)
    {
        $this->updateAttributes([
            'dateLogin' => new \yii\db\Expression('NOW()'),
            'ip' => ip2long(Yii::$app->request->getUserIP())
        ]);

        return user()->login($this, $rememberMe ? 3600 * 24 * 30 : 0);
    }     
    
    /**
     * Finds out if token is valid.
     *
     * @param string $token
     * @return boolean
     */
    public static function isTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        
        $expire = Yii::$app->params['user.tokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        
        return $timestamp + $expire >= time();
    }
    
    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->passwordResetToken = self::generateToken();
    }
    
    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->passwordResetToken = null;
    }
    
    /**
     * Finds user by password reset token.
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isTokenValid($token)) {
            return null;
        }
        
        return static::findOne([
            'passwordResetToken' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
    
    /**
     * Generates new confirm email token.
     */
    public function generateEmailConfirmToken()
    {
        $this->emailConfirmToken = self::generateToken();
        $this->dateConfirm = 0;
    }
    
    /**
     * Finds user by confirm email token.
     *
     * @param string $token confirm email token
     * @return static|null
     */
    public static function findByEmailConfirmToken($token)
    {
        if (!static::isTokenValid($token)) {
            return null;
        }
        
        return static::findOne([
            'emailConfirmToken' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne([$id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }
    
    /**
     * Finds user by username.
     *
     * @param string $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    
    /**
     * Finds user by email.
     *
     * @param string $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }
    
    /**
     * Finds user by provider and profileId.
     *
     * @param int $provider
     * @param int $profileId
     * @return User|null
     */
    public static function findByProvider($provider, $profileId)
    {
        $exist = (new Query())
            ->select('*')
            ->from('userToProvider')
            ->where([
                'provider'  => $provider,
                'profileId' => $profileId
            ])
            ->one();
 
        return $exist ? static::findOne($exist['userId']) : null;
    }
    
    /**
     * Get all connected providers.
     *
     * @return array
     */
    public function providers()
    {
        return (new Query())
            ->select('*')
            ->from('userToProvider')
            ->where(['userId' => $this->id])
            ->all();
    }
    
    /**
     * Save provider.
     *
     * @param int $provider
     * @param array $params
     * @return int Number of rows affected.
     */
    public function saveProvider($provider, $params)
    {
        $params['userId'] = $this->id;
        $params['provider'] = $provider;
        
        $exist = (new Query())
            ->select('*')
            ->from('userToProvider')
            ->where(['userId' => $this->id, 'provider' => $provider])
            ->one();
        
        if ($exist) {
            return Yii::$app->db
                ->createCommand()
                ->update('userToProvider', $params, 'id = :id', ['id' => $exist['id']])
                ->execute();
            
        } else {
            return Yii::$app->db
                ->createCommand()
                ->insert('userToProvider', $params)
                ->execute();
        }
    }
    
    /**
     * @return bool
     */
    public function beforeDelete()
    {
        Yii::$app->authManager->revokeAll($this->id);
        $this->profile->delete();
        
        return true;
    }
}
