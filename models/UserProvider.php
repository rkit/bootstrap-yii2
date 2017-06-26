<?php

namespace app\models;

use app\models\User;
use app\models\query\UserProviderQuery;

/**
 * This is the model class for table "user_provider"
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $profile_id
 * @property string $profile_url
 * @property string $access_token
 * @property string $access_token_secret
 */
class UserProvider extends \yii\db\ActiveRecord
{
    const TYPE_TWITTER   = 1;
    const TYPE_FACEBOOK  = 2;
    const TYPE_VKONTAKTE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['type', 'profile_id', 'profile_url', 'access_token', 'access_token_secret'], 'safe'
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return UserProviderQuery
     */
    public static function find()
    {
        return new UserProviderQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get types
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_TWITTER => 'twitter',
            self::TYPE_FACEBOOK => 'facebook',
            self::TYPE_VKONTAKTE => 'vkontakte',
        ];
    }

    /**
     * Get type by id
     *
     * @return string $name
     * @return int
     */
    public static function getTypeByName(string $name): int
    {
        $types = array_flip(self::getTypes());
        return isset($types[$name]) ? $types[$name] : false;
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function getTypeName(): string
    {
        $types = self::getTypes();
        return isset($types[$this->type]) ? $types[$this->type] : false;
    }
}
