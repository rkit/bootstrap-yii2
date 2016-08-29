<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\News;

/**
 * NewsSearch represents the model behind the search form about `app\models\News`.
 */
class NewsSearch extends News
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var int
     */
    public $typeId;
    /**
     * @var timestamp
     */
    public $datePub;
    /**
     * @var int
     */
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'string'],
            ['typeId', 'integer'],
            ['datePub', 'date', 'format' => 'yyyy-mm-dd'],

            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(News::getStatuses())],
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
        $query = News::find()->joinWith(['type']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_pub' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $dataProvider->sort->attributes['typeId'] = [
            'asc'  => ['news_type.title' => SORT_ASC],
            'desc' => ['news_type.title' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'type_id' => $this->typeId,
            'status' => $this->status,
            'DATE(date_pub)' => $this->datePub
        ]);

        $query->andFilterWhere(['like', 'news.title', $this->title]);

        return $dataProvider;
    }
}
