<?php

namespace app\models\entity;

use Yii;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $user_id
 * @property string $full_name
 * @property string $photo
 * 
 * @property User $user
 * @property File $photoFile
 */
class UserProfile extends \yii\db\ActiveRecord
{
    public function __construct($config = [])
    {
        $this->attachBehavior(
            'fileManager',
            require Yii::getAlias('@app/config/behaviors/user-profile/filemanager.php')
        );
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User'),
            'full_name' => Yii::t('app', 'Your name'),
            'photo' => Yii::t('app', 'Photo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoFile()
    {
        return $this
            ->hasOne(File::class, ['id' => 'file_id'])
            ->viaTable('{{%user_profile_to_file}}', ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \query\UserProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\UserProfileQuery(get_called_class());
    }

    /**
     * Get thumb for photo
     *
     * @param string $size Preset Name
     * @return string
     */
    public function photo($size)
    {
        $default = '/img/userpic-default.jpg';
        if (empty($this->photo)) {
            return $default;
        }

        return $this->thumbUrl('photo', $size);
    }
}
