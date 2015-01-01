<?php

namespace app\models;

use app\components\BaseActive;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "newsType".
 *
 * @property integer $id
 * @property string $title
 */
class NewsType extends BaseActive
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'newsType';
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
        ];
    }
}
