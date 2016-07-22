<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $email;
    /**
     * @var timestamp
     */
    public $date_create;
    /**
     * @var string
     */
    public $ip;
    /**
     * @var string
     */
    public $role;
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
            [
                ['username', 'email', 'ip', 'role'], 'string'
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
        $query = User::find()->with('roles');

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

        $dataProvider->getPagination()->setPageSize(Yii::$app->request->get('pageSize'), true);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ip' => !empty($this->ip) ? ip2long($this->ip) : null,
            'status' => $this->status,
            'role' => $this->role,
            'DATE(date_create)' => $this->date_create
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username]);
        $query->andFilterWhere(['like', 'user.email', $this->email]);

        return $dataProvider;
    }
}
