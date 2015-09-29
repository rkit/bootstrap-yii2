<?php

namespace app\components;

use Yii;

/**
 * Base class for ActiveRecord
 */
class BaseActive extends \yii\db\ActiveRecord
{
    /**
     * Check owner
     *
     * @return bool
     */
    public function isOwner()
    {
        $isSuperUser = !Yii::$app->getUser()->getIsGuest() && Yii::$app->getUser()->getIdentity()->isSuperUser();
        return $isSuperUser || Yii::$app->getUser()->id === $this->user_id;
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
