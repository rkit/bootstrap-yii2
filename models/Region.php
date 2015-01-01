<?php

namespace app\models;

use app\components\BaseActive;
use Yii;

/**
 * This is the model class for table "region".
 *
 * @property integer $regionId
 * @property integer $countryId
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
            ['countryId', 'required'],
            ['countryId', 'integer'],
            
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
            'regionId' => Yii::t('app', 'Region'),
            'countryId' => Yii::t('app', 'Country'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasMany(City::className(), ['regionId' => 'regionId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['countryId' => 'countryId']);
    }
}
