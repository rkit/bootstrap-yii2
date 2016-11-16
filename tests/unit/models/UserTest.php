<?php

namespace app\tests\unit\models;

use Yii;
use app\tests\fixtures\AuthItem as AuthItemFixture;
use app\tests\fixtures\AuthAssignment as AuthAssignmentFixture;
use app\tests\fixtures\User as UserFixture;
use app\tests\fixtures\UserProfile as UserProfileFixture;
use app\tests\fixtures\UserProvider as UserProviderFixture;
use app\models\User;
use app\models\UserProvider;
use app\models\AuthItem;

class UserTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'authItem' => AuthItemFixture::className(),
             'authAssignment' => AuthAssignmentFixture::className(),
             'user' => UserFixture::className(),
             'profile' => UserProfileFixture::className(),
             'provider' => UserProviderFixture::className(),
        ]);
    }

    public function testGetProfile()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        expect($user->profile)->isInstanceOf('app\models\UserProfile');
    }

    public function testSetProfile()
    {
        $user = new User();
        $user->setProfile(['full_name' => 'Test']);

        expect_that($user->save());
        expect($user->profile->full_name)->equals('Test');
    }

    public function testGetProviders()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        expect($user->providers[0]->user)->isInstanceOf('app\models\User');
    }

    public function testSetProviders()
    {
        $user = new User();
        $user->setProviders(['type' => UserProvider::TYPE_TWITTER]);

        expect_that($user->save());
        expect($user->providers[0]->type)->equals(UserProvider::TYPE_TWITTER);
    }

    public function testGetRoles()
    {
        $user = $this->tester->grabFixture('user', 'user-1');
        expect($user->roles)->isInstanceOf('app\models\AuthItem');
    }

    public function testGetStatuses()
    {
        $user = new User();
        $statuses = $user->getStatuses();

        expect_that(is_array($statuses));
        expect(count($statuses))->equals(3);
    }

    public function testGetStatusName()
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE;

        $status = $user->getStatusName();
        expect($status)->equals('Active');
    }

    public function testStatusUnconfirmed()
    {
        $user = $this->tester->grabFixture('user', 'user-2');

        expect_not($user->isConfirmed());
        expect($user->email_confirm_token)->notEmpty();
    }

    public function testStatusConfirmed()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        $user->setConfirmed();
        expect_that($user->save());

        $user = User::findByEmail($user->email);
        expect_that($user->isConfirmed());
        expect($user->email_confirm_token)->isEmpty();
    }

    public function testStatusDeleted()
    {
        $user = new User();

        $user->status = User::STATUS_DELETED;
        expect_that($user->isDeleted());
        expect($user->getStatusName())->equals('Deleted');
        expect($user->getStatusDescription())->equals('Your account has been deleted');
    }

    public function testStatusActive()
    {
        $user = new User();

        $user->status = User::STATUS_ACTIVE;
        expect_that($user->isActive());
        expect($user->getStatusName())->equals('Active');
        expect($user->getStatusDescription())->equals('Your account is activated');
    }

    public function testStatusBlocked()
    {
        $user = new User();

        $user->status = User::STATUS_BLOCKED;
        expect_that($user->isBlocked());
        expect($user->getStatusName())->equals('Locked');
        expect($user->getStatusDescription())->equals('Your account has been suspended');
    }

    public function testIsSuperUser()
    {
        $user = new User();
        $user->role = User::ROLE_SUPERUSER;
        expect_that($user->isSuperUser());
    }

    public function testFindByPasswordResetToken()
    {
        $user = $this->tester->grabFixture('user', 'user-1');
        expect_that($user = User::findByPasswordResetToken($user->password_reset_token));
        expect($user->username)->equals('superuser');

        expect_not(User::findByPasswordResetToken(999));
    }

    public function testFindByEmailConfirmToken()
    {
        $user = $this->tester->grabFixture('user', 'user-1');
        expect_that($user = User::findByEmailConfirmToken($user->email_confirm_token));
        expect($user->username)->equals('superuser');

        expect_not(User::findByEmailConfirmToken(999));
    }

    public function testFindIdentity()
    {
        expect_that($user = User::findIdentity(1));
        expect($user->username)->equals('superuser');

        expect_not(User::findIdentity(999));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage findIdentityByAccessToken is not implemented.
     */
    public function testFindIdentityByAccessToken()
    {
        expect_not(User::findIdentityByAccessToken('test_token'));
    }

    public function testFindByEmail()
    {
        expect_that($user = User::findByEmail('superuser@example.com'));
        expect($user->username)->equals('superuser');

        expect_not(User::findByEmail('test@test.com'));
    }

    public function testFindByUsername()
    {
        expect_that($user = User::findByUsername('superuser'));
        expect($user->username)->equals('superuser');

        expect_not(User::findByUsername('test_user'));
    }

    public function testValidateAuthKey()
    {
        $user = $this->tester->grabFixture('user', 'user-1');
        expect_that($user->validateAuthKey('dKz8PzyduJUDyrrhAC05-Mn53IvaXvoA'));
        expect_not($user->validateAuthKey('test_password'));
    }

    public function testCreate()
    {
        $user = new User();
        $user->username = 'test';
        $user->email = 'test@test.com';
        $user->generateEmailConfirmToken();
        $user->setPassword('test_password');

        expect_that($user->save());
        expect($user)->isInstanceOf('app\models\User');
        expect_that($user->isActive());
        expect_that($user->validatePassword('test_password'));

        return $user;
    }

    public function testChangePassword()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        $user->passwordNew = 'test_new_password';

        expect_that($user->save());
        expect_that($user->validatePassword('test_new_password'));
    }

    public function testAssignRole()
    {
        $user = $this->tester->grabFixture('user', 'user-2');
        $role = new AuthItem([
            'type' => \yii\rbac\Item::TYPE_ROLE,
            'name' => 'test',
            'description' => 'test_description'
        ]);

        expect_that($role->save());

        $user->role = $role->name;
        expect_that($user->save());

        $user = User::findByEmail($user->email);

        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole($user->role), $user->id);

        expect_that($user->roles->name === $role->name);
        expect($user->role)->equals($role->name);
        expect(key($auth->getRolesByUser($user->id)))->equals($role->name);

        $role->delete();
    }

    public function testGenerateAuthKey()
    {
        $user = new User();
        $user->auth_key = $user->generateAuthKey();
        expect_that($user->validateAuthKey($user->getAuthKey()));
    }

    public function testUserValidatePassword()
    {
        $user = new User();
        expect_not($user->validatePassword(''));

        $user = User::findByEmail('user-2@example.com');
        expect_that($user->validatePassword('123123'));
        expect_not($user->validatePassword('test_password'));
    }
}
