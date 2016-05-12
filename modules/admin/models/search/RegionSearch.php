<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Region;

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
    public $country_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string'],

            ['country_id', 'integer'],
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
        $query = Region::find()->with('country');

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
            'country_id' => $this->country_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
