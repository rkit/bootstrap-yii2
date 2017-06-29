<?php

namespace app\models;

use Yii;
use app\models\File;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property string $full_name
 * @property string $photo
 * @property string $birth_day
 */
class UserProfile extends \yii\db\ActiveRecord
{
    public function __construct($config = [])
    {
        $this->attachBehavior(
            'fileManager',
            require Yii::getAlias('@app/models/behaviors/user-profile/filemanager.php')
        );
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User'),
            'full_name' => Yii::t('app', 'Full Name'),
            'photo' => Yii::t('app', 'Photo'),
            'birth_day' => Yii::t('app', 'Birth Day'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getFiles($callable = null)
    {
        return $this
            ->hasMany(File::class, ['id' => 'file_id'])
            ->viaTable('user_profiles_files', ['user_id' => 'user_id'], $callable);
    }
}
