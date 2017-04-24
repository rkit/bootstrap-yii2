<?php

use app\models\User;

class m161026_103039_add_superuser extends app\migrations\Migration
{
    public function safeUp()
    {
        $user = new User();
        $user->username = 'editor';
        $user->setPassword('fghfgh');
        $user->role = User::ROLE_SUPERUSER;
        $user->status = User::STATUS_ACTIVE;
        $user->setConfirmed();
        $user->save(false);

        $auth = Yii::$app->authManager;
        $role = $auth->createRole($user->role);
        $role->description = Yii::t('app', 'SuperUser');
        $auth->add($role);
        $auth->assign($role, $user->id);
    }

    public function safeDown()
    {
    }
}
