<?php

namespace app\models\entity;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 * @property integer $ip
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     * @internal
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * @inheritdoc
     * @internal
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @internal
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if (!Yii::$app instanceof \yii\console\Application) {
                    $this->user_id = Yii::$app->user->isGuest ? 0 : Yii::$app->user->id;
                    $this->ip = ip2long(Yii::$app->request->getUserIP());
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     * @return \query\FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\FileQuery(get_called_class());
    }
}
