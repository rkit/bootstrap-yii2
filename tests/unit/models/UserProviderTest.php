<?php

namespace app\tests\unit\models;

use app\models\UserProvider;

class UserProviderTest extends \Codeception\Test\Unit
{
    public function testGetTypes()
    {
        $types = UserProvider::getTypes();
        expect_that(is_array($types));
        expect(count($types))->equals(3);
    }

    public function testGetTypeByName()
    {
        $type = UserProvider::getTypeByName('twitter');
        expect($type)->equals(UserProvider::TYPE_TWITTER);
    }

    public function testGetTypeName()
    {
        $userProvider = new UserProvider();
        $userProvider->type = UserProvider::TYPE_TWITTER;

        $type = $userProvider->getTypeName();
        expect($type)->equals('twitter');
    }
}
