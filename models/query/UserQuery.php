<?php

namespace app\models\query;

use yii\db\ActiveQuery;
use app\models\entity\User;

/**
 * This is the ActiveQuery class for [[\entity\User]]
 *
 * @see \entity\User
 */
class UserQuery extends ActiveQuery
{
    /**
     * Select only active
     *
     * @return yii\db\ActiveQuery
     */
    public function active(): ActiveQuery
    {
        $this->andWhere(['status' => User::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * Select only blocked
     *
     * @return yii\db\ActiveQuery
     */
    public function blocked(): ActiveQuery
    {
        $this->andWhere(['status' => User::STATUS_BLOCKED]);
        return $this;
    }

    /**
     * Select only deleted
     *
     * @return yii\db\ActiveQuery
     */
    public function deleted(): ActiveQuery
    {
        $this->andWhere(['status' => User::STATUS_DELETED]);
        return $this;
    }

    /**
     * `Like` search for value in the field
     *
     * @param string $field
     * @param string $value
     * @return yii\db\ActiveQuery
     */
    public function like(string $field, string $value): ActiveQuery
    {
        $this->andWhere(['like', $field, $value]);
        return $this;
    }

    /**
     * Select by password reset token
     *
     * @param string $token Password reset token
     * @return yii\db\ActiveQuery
     */
    public function passwordResetToken(string $token): ActiveQuery
    {
        $this->andWhere([
            'password_reset_token' => $token,
            'status' => User::STATUS_ACTIVE,
        ]);
        return $this;
    }

    /**
     * Select by confirm email token
     *
     * @param string $token Confirm email token
     * @return yii\db\ActiveQuery
     */
    public function emailConfirmToken(string $token): ActiveQuery
    {
        $this->andWhere([
            'email_confirm_token' => $token,
            'status' => User::STATUS_ACTIVE,
        ]);
        return $this;
    }

    /**
     * Select by email
     *
     * @param string $email
     * @return yii\db\ActiveQuery
     */
    public function email(string $email): ActiveQuery
    {
        $this->andWhere(['email' => $email]);
        return $this;
    }
}
