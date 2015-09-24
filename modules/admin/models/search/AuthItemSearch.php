<?php

namespace app\modules\admin\models\search;

use app\models\AuthItem;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * AuthItemSearch represents the model behind the search form about `app\models\AuthItem`.
 */
class AuthItemSearch extends AuthItem
{
    /**
     * @var string
     */
    public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string'],
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
        $query = AuthItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'pageSizeLimit' => [50, 100],
            ],
        ]);

        $dataProvider->getPagination()->setPageSize(Yii::$app->request->get('pageSize'), true);

        $query->andFilterWhere(['type' => \yii\rbac\Item::TYPE_ROLE]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
