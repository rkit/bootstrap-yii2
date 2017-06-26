<?php

namespace app\tests\unit\models;

use Yii;
use app\tests\fixtures\AuthItemFixture;
use app\tests\fixtures\UserFixture;
use app\tests\fixtures\UserProfileFixture;
use app\tests\fixtures\UserProviderFixture;
use app\models\User;
use app\models\UserProvider;
use app\models\AuthItem;
use app\services\Tokenizer;

class UserTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'authItem' => AuthItemFixture::class,
             'user' => UserFixture::class,
             'profile' => UserProfileFixture::class,
             'provider' => UserProviderFixture::class,
        ]);
    }

    public function testSetProfile()
    {
        $user = new User();
        $user->email = 'test@test.ru';
        $user->setProfile(['full_name' => 'Test']);

        expect_that($user->save());
        expect($user->profile->full_name)->equals('Test');
    }

    public function testSetProviders()
    {
        $user = new User();
        $user->email = 'test@test.ru';
        $user->setProviders(['type' => UserProvider::TYPE_TWITTER]);

        expect_that($user->save());
        expect($user->providers[0]->type)->equals(UserProvider::TYPE_TWITTER);
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

        $user = User::find()->email($user->email)->one();
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
        expect_that($user = User::find()->passwordResetToken($user->password_reset_token)->one());
        expect($user->username)->equals('superuser');

        expect_not(User::find()->passwordResetToken(999)->one());
    }

    public function testFindByEmailConfirmToken()
    {
        $user = $this->tester->grabFixture('user', 'user-1');
        expect_that($user = User::find()->emailConfirmToken($user->email_confirm_token)->one());
        expect($user->username)->equals('superuser');

        expect_not(User::find()->emailConfirmToken(999)->one());
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
        expect_that($user = User::find()->email('superuser@example.com')->one());
        expect($user->username)->equals('superuser');

        expect_not(User::find()->email('test@test.com')->one());
    }

    public function testFindByUsername()
    {
        expect_that($user = User::find()->username('superuser')->one());
        expect($user->username)->equals('superuser');

        expect_not(User::find()->username('test_user')->one());
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

        $tokenizer = new Tokenizer();
        $user->setEmailConfirmToken($tokenizer->generate());
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

        $user = User::find()->email($user->email)->one();

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

        $user = User::find()->email('user-2@example.com')->one();
        expect_that($user->validatePassword('123123'));
        expect_not($user->validatePassword('test_password'));
    }
}
