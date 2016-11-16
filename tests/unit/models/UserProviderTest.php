<?php

namespace app\tests\unit\models;

use app\tests\fixtures\User as UserFixture;
use app\tests\fixtures\UserProvider as UserProviderFixture;
use app\models\UserProvider;

class UserProviderTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::className(),
             'provider' => UserProviderFixture::className(),
        ]);
    }

    public function testGetUser()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        expect($user->providers[0]->user)->isInstanceOf('app\models\User');
    }

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

    public function testFindByProvider()
    {
        expect_that($provider = UserProvider::findByProvider(UserProvider::TYPE_TWITTER, 'twitter-id'));
        expect($provider->user_id)->equals(2);

        expect_not(UserProvider::findByProvider(UserProvider::TYPE_TWITTER, 'wrong-id'));
    }
}
