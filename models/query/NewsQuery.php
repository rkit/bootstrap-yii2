<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\News;

/**
 * ActiveQuery for News
 */
class NewsQuery extends ActiveQuery
{
    /**
     * Select only active
     *
     * @return yii\db\ActiveQuery
     */
    public function active(): ActiveQuery
    {
        $this->andWhere(['status' => News::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * Select only blocked
     *
     * @return yii\db\ActiveQuery
     */
    public function blocked(): ActiveQuery
    {
        $this->andWhere(['status' => News::STATUS_BLOCKED]);
        return $this;
    }
}
