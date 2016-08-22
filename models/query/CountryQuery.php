<?php

namespace app\models\query;

use Yii;
use yii\db\ActiveQuery;

/**
 * ActiveQuery for Country
 */
class CountryQuery extends ActiveQuery
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
