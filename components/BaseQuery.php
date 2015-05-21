<?php

namespace app\components;

use yii\db\ActiveQuery;
use Yii;

/**
 * Base ActiveQuery.
 */
class BaseQuery extends ActiveQuery
{
    public function active($active = true)
    {
        $this->andWhere(['status' => $active]);
        return $this;
    }

    public function like($title, $field = 'title')
    {
        $this->andWhere(['like', $field, $title]);
        return $this;
    }
}
