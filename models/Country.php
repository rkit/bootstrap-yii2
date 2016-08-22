<?php

namespace app\models;

use Yii;
use app\models\query\CountryQuery;

/**
 * This is the model class for table "country".
 *
 * @property integer $country_id
 * @property string $title
 *
 * @property City[] $city
 * @property Region[] $regions
 */
class Country extends \yii\db\ActiveRecord
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
     * @inheritdoc
     * @return CountryQuery
     */
    public static function find()
    {
        return new CountryQuery(get_called_class());
    }
}
