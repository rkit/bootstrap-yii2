<?php

namespace app\models\query;

use yii\db\ActiveQuery;

/**
 * ActiveQuery for UserProvider
 */
class UserProviderQuery extends ActiveQuery
{
    /**
     * Select by type of provider
     *
     * @param int $type
     * @param int $profileId
     * @return yii\db\ActiveQuery
     */
    public function provider(int $type, int $profileId): ActiveQuery
    {
        $this->andWhere(['type' => $type, 'profile_id' => $profileId]);
        return $this;
    }
}
