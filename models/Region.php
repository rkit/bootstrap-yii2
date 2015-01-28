<?php

namespace app\models;

use app\components\BaseActive;
use Yii;

/**
 * This is the model class for table "region".
 *
 * @property integer $region_id
 * @property integer $country_id
 * @property string $title
 *
 * @property City[] $city
 * @property Country $country
 */
class Region extends BaseActive
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['country_id', 'required'],
            ['country_id', 'integer'],
            
            ['title', 'string', 'max' => 150],
            ['title', 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'region_id' => Yii::t('app', 'Region'),
            'country_id' => Yii::t('app', 'Country'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasMany(City::className(), ['region_id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }
}
