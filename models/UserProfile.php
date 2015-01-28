<?php

namespace app\models;

use Yii;
use app\components\BaseActive;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property string $full_name
 * @property string $photo
 * @property string $birth_day
 *
 * @property User $user
 */
class UserProfile extends BaseActive
{
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
    public function rules()
    {
        return [
            ['birth_day', 'safe'],
            ['full_name', 'string', 'max' => 40],
            ['photo', 'string', 'max' => 255]
        ];
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'app\behaviors\FileBehavior',
                'attributes' => [
                    'photo' => [
                        'ownerType' => File::OWNER_TYPE_USER_PHOTO,
                        'savePath' => true, // save 'path' in current model
                        'rules' => [
                            'imageSize'  => ['minWidth' => 300, 'minHeight' => 300],
                            'mimeTypes'  => ['image/png', 'image/jpg', 'image/jpeg'],
                            'extensions' => ['jpg', 'jpeg', 'png'],
                            'maxSize'    => 1024 * 1024 * 1, // 1 MB
                        ]
                    ],
                ]
            ]
        ];
    } 

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
