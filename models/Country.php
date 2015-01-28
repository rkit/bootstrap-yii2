<?php

namespace app\models;

use app\components\BaseActive;
use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $country_id
 * @property string $title
 *
 * @property City[] $city
 * @property Region[] $regions
 */
class Country extends BaseActive
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string', 'max' => 60],
            ['title', 'unique'],
            ['title', 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => Yii::t('app', 'Country'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasMany(City::className(), ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasMany(Region::className(), ['country_id' => 'country_id']);
    }
}
