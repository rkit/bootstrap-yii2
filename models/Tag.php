<?php

namespace app\models;

use Yii;
use app\components\BaseActive;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $title
 * @property integer $count
 */
class Tag extends BaseActive
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'trim'],
            ['title', 'required'],
            ['title', 'unique'],
            ['title', 'string', 'max' => 255],

            ['count', 'integer'],
            ['count', 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'count' => Yii::t('app', 'Amount'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if (!Yii::$app instanceof \yii\console\Application) {
                    $this->user_id = Yii::$app->user->id;
                }
            }

            return true;
        }

        return false;
    }
}
