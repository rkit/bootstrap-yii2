<?php

namespace app\modules\admin\models\search;

use app\models\City;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * CitySearch represents the model behind the search form about `app\models\City`.
 */
class CitySearch extends City
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var int
     */
    public $countryId;
    /**
     * @var int
     */
    public $regionId;
        
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string'],
            
            ['countryId', 'integer'],

            ['regionId', 'integer'],
        ];
    }
    
    /**
     * Search by request criteria.
     *
     * @param array|null Filter params.
     * @return ActiveDataProvider Data provider.
     */
    public function search($params)
    {
        $query = City::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'pageSizeLimit' => [50, 100],
            ],
        ]);
        
        $dataProvider->getPagination()->setPageSize(Yii::$app->request->get('pageSize'), true);
            
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'countryId' => $this->countryId,
            'regionId' => $this->regionId
        ]);
        
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
