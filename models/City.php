<?php

namespace app\models;

use app\components\BaseActive;
use Yii;

/**
 * This is the model class for table "City".
 *
 * @property integer $cityId
 * @property integer $countryId
 * @property integer $important
 * @property integer $regionId
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
            ['countryId', 'required'],
            ['countryId', 'integer'],

            ['regionId', 'integer'],
            ['regionId', 'default', 'value' => null],

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
            'cityId' => Yii::t('app', 'City'),
            'countryId' => Yii::t('app', 'Country'),
            'important' => Yii::t('app', 'Big city'),
            'regionId' => Yii::t('app', 'Region'),
            'title' => Yii::t('app', 'Title'),
            'area' => Yii::t('app', 'District'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['countryId' => 'countryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['regionId' => 'regionId']);
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
