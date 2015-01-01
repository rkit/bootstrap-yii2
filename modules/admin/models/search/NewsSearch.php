<?php

namespace app\modules\admin\models\search;

use app\models\News;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

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
     * @var string
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
                    'datePub' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSizeLimit' => [50, 100],
            ],
        ]);
        
        $dataProvider->getPagination()->setPageSize(Yii::$app->request->get('pageSize'), true);
       
        $dataProvider->sort->attributes['typeId'] = [
            'asc'  => ['newsType.title' => SORT_ASC],
            'desc' => ['newsType.title' => SORT_DESC],
        ];
            
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'typeId' => $this->typeId,
            'status' => $this->status,
            'DATE(datePub)' => $this->datePub
        ]);
        
        $query->andFilterWhere(['like', 'news.title', $this->title]);

        return $dataProvider;
    }
}
