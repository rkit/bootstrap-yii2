<?php

namespace app\models;

use app\components\BaseActive;
use Yii;

/**
 * This is the model class for table "country".
 *
 * @property integer $countryId
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
            'countryId' => Yii::t('app', 'Country'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasMany(City::className(), ['countryId' => 'countryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasMany(Region::className(), ['countryId' => 'countryId']);
    }
}
