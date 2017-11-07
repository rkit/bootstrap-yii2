<?php

use app\migrations\Migration;
use app\models\entity\User;

class m141230_075228_add_superuser extends Migration
{
    public function safeUp()
    {
        $user = new User();
        $user->email = 'editor@example.com';
        $user->setPassword('fghfgh');
        $user->role_name = User::ROLE_SUPERUSER;
        $user->status = User::STATUS_ACTIVE;
        $user->setConfirmed();
        $user->save(false);

        $auth = Yii::$app->authManager;
        $role = $auth->createRole($user->role_name);
        $role->description = Yii::t('app', 'SuperUser');
        $auth->add($role);
        $auth->assign($role, $user->id);
    }

    public function safeDown()
    {
        $role = Yii::$app->authManager->getRole(User::ROLE_SUPERUSER);
        Yii::$app->authManager->remove($role);
    }
}
