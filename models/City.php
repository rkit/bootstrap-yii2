<?php

namespace app\models;

use app\components\BaseActive;
use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $city_id
 * @property integer $country_id
 * @property integer $important
 * @property integer $region_id
 * @property string $title
 * @property string $area
 *
 * @property Country $country
 * @property Region $region
 */
class City extends BaseActive
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['country_id', 'required'],
            ['country_id', 'integer'],

            ['region_id', 'integer'],
            ['region_id', 'default', 'value' => null],

            ['area', 'string', 'max' => 150],
            
            ['important', 'integer'],

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
            'city_id' => Yii::t('app', 'City'),
            'country_id' => Yii::t('app', 'Country'),
            'important' => Yii::t('app', 'Big city'),
            'region_id' => Yii::t('app', 'Region'),
            'title' => Yii::t('app', 'Title'),
            'area' => Yii::t('app', 'District'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['region_id' => 'region_id']);
    }
    
    /**
     * Return: City, Region, Country
     *
     * @return string
     */
    public function getFullLocation()
    {
        $full  = $this->title;
        $full .= $this->region ? ', '.$this->region->title : '';
        $full .= $this->country ? ', '.$this->country->title : '';
        
        return $full;
    }
}
