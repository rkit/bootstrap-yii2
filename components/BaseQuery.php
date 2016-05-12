<?php

namespace app\components;

use Yii;
use yii\db\ActiveQuery;

/**
 * Base ActiveQuery
 */
class BaseQuery extends ActiveQuery
{
    /**
     * Select only active recording
     *
     * @param bool $active
     * @return yii\db\ActiveQuery
     */
    public function active($active = true)
    {
        $this->andWhere(['status' => $active]);
        return $this;
    }

    /**
     * `Like` search for value in the field
     *
     * @param string $value
     * @param string $field
     * @return yii\db\ActiveQuery
     */
    public function like($value, $field = 'title')
    {
        $this->andWhere(['like', $field, $value]);
        return $this;
    }
}
