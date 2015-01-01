<?php

namespace app\modules\admin\models\search;

use app\models\Region;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * RegionSearch represents the model behind the search form about `app\models\Region`.
 */
class RegionSearch extends Region
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string'],

            ['countryId', 'integer'],
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
        $query = Region::find();

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
        ]);
        
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
