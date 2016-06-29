<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AuthItem;

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
                'pageSize' => 50,
            ],
        ]);

        $query->andFilterWhere(['type' => \yii\rbac\Item::TYPE_ROLE]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
