<?php

namespace app\models\query;

use Yii;
use yii\db\ActiveQuery;
use app\models\User;

/**
 * ActiveQuery for User
 */
class UserQuery extends ActiveQuery
{
    /**
     * Select only active
     *
     * @return yii\db\ActiveQuery
     */
    public function active()
    {
        $this->andWhere(['status' => User::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * Select only blocked
     *
     * @return yii\db\ActiveQuery
     */
    public function blocked()
    {
        $this->andWhere(['status' => User::STATUS_BLOCKED]);
        return $this;
    }

    /**
     * Select only deleted
     *
     * @return yii\db\ActiveQuery
     */
    public function deleted()
    {
        $this->andWhere(['status' => User::STATUS_DELETED]);
        return $this;
    }

    /**
     * `Like` search for value in the field
     *
     * @param string $field
     * @param string $value
     * @return yii\db\ActiveQuery
     */
    public function like($field, $value)
    {
        $this->andWhere(['like', $field, $value]);
        return $this;
    }
}
