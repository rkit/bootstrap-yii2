<?php 

namespace app\components;

use yii\db\ActiveQuery;
use Yii;

/**
 * Base ActiveQuery.
 */
class BaseQuery extends ActiveQuery
{
    public function active()
    {    
        $this->andWhere(['status' => true]);
        return $this;
    }

    public function like($title, $field = 'title')
    {
        $this->andWhere(['like', $field, $title]);
        return $this;
    }
}
