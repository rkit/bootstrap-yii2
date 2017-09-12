<?php

namespace app\modules\admin\models\search;

use yii\data\ActiveDataProvider;
use app\models\entity\User;

/**
 * UserSearch represents the model behind the search form about `app\models\entity\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['username', 'email', 'ip', 'role_name'], 'string'
            ],

            ['date_create', 'date', 'format' => 'yyyy-mm-dd'],

            ['status', 'integer'],
            ['status', 'in', 'range' => array_keys(User::getStatuses())],
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
        $query = User::find()->with('role');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_create' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ip' => !empty($this->ip) ? ip2long($this->ip) : null,
            'status' => $this->status,
            'role_name' => $this->role_name,
            'DATE(date_create)' => $this->date_create
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username]);
        $query->andFilterWhere(['like', 'user.email', $this->email]);

        return $dataProvider;
    }
}
