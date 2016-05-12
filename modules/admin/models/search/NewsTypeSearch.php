<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NewsType;

/**
 * NewsTypeSearch represents the model behind the search form about `app\models\NewsType`.
 */
class NewsTypeSearch extends NewsType
{
    /**
     * @var string
     */
    public $title;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string'],
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
        $query = NewsType::find();

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

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
