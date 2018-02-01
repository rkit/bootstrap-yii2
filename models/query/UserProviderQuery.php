<?php

namespace app\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\entity\UserProvider]]
 *
 * @see \entity\UserProvider
 */
class UserProviderQuery extends ActiveQuery
{
    /**
     * Select by type of provider
     *
     * @param int $type
     * @param string $profileId
     * @return yii\db\ActiveQuery
     */
    public function provider(int $type, string $profileId): ActiveQuery
    {
        $this->andWhere(['type' => $type, 'profile_id' => $profileId]);
        return $this;
    }
}
