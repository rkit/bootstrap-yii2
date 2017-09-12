<?php

namespace app\models\entity;

/**
 * This is the model class for table "{{%user_provider}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $profile_id
 * @property string $profile_url
 * @property string $access_token
 * @property string $access_token_secret
 *
 * @property User $user
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
        return '{{%user_provider}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \query\UserProviderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\UserProviderQuery(get_called_class());
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
