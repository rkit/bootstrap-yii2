<?php

namespace app\tests\unit\models;

use app\models\User;
use app\models\AuthItem;

class AuthItemTest extends \Codeception\Test\Unit
{
    public function testIsSuperUser()
    {
        $authItem = new AuthItem();
        expect_not($authItem->isSuperUser());

        $authItem->name = User::ROLE_SUPERUSER;
        expect_that($authItem->isSuperUser());
    }
}
