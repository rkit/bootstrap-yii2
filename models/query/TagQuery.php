<?php

namespace app\models\query;

use yii\db\ActiveQuery;

/**
 * ActiveQuery for Tag
 */
class TagQuery extends ActiveQuery
{
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
