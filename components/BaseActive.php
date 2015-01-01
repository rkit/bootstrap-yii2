<?php

namespace app\components;

use Yii;

/**
 * Base class for ActiveRecord.
 */
class BaseActive extends \yii\db\ActiveRecord
{
    /**
     * Check owner.
     *
     * @return bool
     */
    public function isOwner()
    {
        $isSuperUser = !user()->isGuest && user()->identity->isSuperUser();
        return $isSuperUser || user()->id === $this->userId;
    }
    
    /**
     * @inheritdoc
     * @return BaseQuery
     */
    public static function find()
    {
        return new BaseQuery(get_called_class());
    }
}
