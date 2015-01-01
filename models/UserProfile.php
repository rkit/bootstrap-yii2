<?php

namespace app\models;

use Yii;
use app\components\BaseActive;

/**
 * This is the model class for table "userProfile".
 *
 * @property integer $userId
 * @property string $firstName
 * @property string $lastName
 * @property string $photo
 * @property string $birthDay
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
        return 'userProfile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['birthDay'], 'safe'],
            [['firstName', 'lastName'], 'string', 'max' => 40],
            [['photo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User'),
            'firstName' => Yii::t('app', 'First Name'),
            'lastName' => Yii::t('app', 'Last Name'),
            'photo' => Yii::t('app', 'Photo'),
            'birthDay' => Yii::t('app', 'Birth Day'),
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
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
    
    /**
     * @return string
     */
    public function fullName()
    {
        return $this->firstName . ' '. $this->lastName;
    }
}
