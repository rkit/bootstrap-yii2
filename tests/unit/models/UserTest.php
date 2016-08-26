<?php

namespace app\tests\unit\models;

use Yii;
use app\tests\fixtures\User as UserFixture;
use app\models\User;
use app\models\AuthItem;

class UserTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);

    }

    protected function createUser()
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

    protected function createRole()
    {
        $role = new AuthItem([
            'type' => \yii\rbac\Item::TYPE_ROLE,
            'name' => 'test',
            'description' => 'test_description'
        ]);

        expect_that($role->save());

        return $role;
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

    public function testFindByUsername()
    {
        expect_that($user = User::findByUsername('superuser'));
        expect($user->username)->equals('superuser');

        expect_not(User::findByUsername('test_user'));
    }

    public function testValidateAuthKey()
    {
        $user = User::findByUsername('superuser');
        expect_that($user->validateAuthKey('dKz8PzyduJUDyrrhAC05-Mn53IvaXvoA'));
        expect_not($user->validateAuthKey('test_password'));
    }

    public function testCreate()
    {
        $this->createUser();
    }

    public function testChangePassword()
    {
        $user = $this->createUser();
        $user->passwordNew = 'test_new_password';

        expect_that($user->save());
        expect_that($user->validatePassword('test_new_password'));
    }

    public function testFillProfile()
    {
        $user = $this->createUser();
        $user->profile->full_name = 'Test';
        $user->profile->birth_day = '2001-01-02';
        expect_that($user->save());

        $user = User::findByEmail($user->email);
        expect($user)->isInstanceOf('app\models\User');
        expect($user->profile->full_name)->equals('Test');
        expect($user->profile->birth_day)->equals('2001-01-02');
    }

    public function testAssignRole()
    {
        $user = $this->createUser();
        $role = $this->createRole();

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

    public function testStatusUnconfirmed()
    {
        $user = $this->createUser();

        expect_not($user->isConfirmed());
        expect($user->email_confirm_token)->notEmpty();
    }

    public function testStatusConfirmed()
    {
        $user = $this->createUser();
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
