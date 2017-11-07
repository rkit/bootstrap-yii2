<?php

namespace app\modules\admin\models\search;

use yii\data\ActiveDataProvider;
use app\models\entity\User;

/**
 * UserSearch represents the model behind the search form about `app\models\entity\User`.
 */
class UserSearch extends User
{
    public $date_create_start;
    public $date_create_end;
    public $date_login_start;
    public $date_login_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'role_name'], 'string'],

            ['id', 'number'],

            [
                [
                    'date_create_start',
                    'date_create_end',
                    'date_login_start',
                    'date_login_end'
                ], 'date', 'format' => 'yyyy-mm-dd'
            ],

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
            'id' => $this->id,
            'status' => $this->status,
            'role_name' => $this->role_name,
        ]);

        $query->andFilterWhere(['between', 'DATE(date_create)', $this->date_create_start, $this->date_create_end]);
        $query->andFilterWhere(['between', 'DATE(date_login)', $this->date_login_start, $this->date_login_end]);

        $query->andFilterWhere(['like', 'user.email', $this->email]);

        return $dataProvider;
    }
}
