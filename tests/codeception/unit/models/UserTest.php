<?php

namespace tests\codeception\unit\admin\models;

use Yii;
use yii\codeception\DbTestCase;
use tests\codeception\fixtures\UserFixture;
use tests\codeception\fixtures\AuthItemFixture;
use app\models\User;
use app\models\UserProfile;
use app\models\AuthItem;

class UserTest extends DbTestCase
{
    protected function tearDown()
    {
        User::deleteAll();
        parent::tearDown();
    }

    protected function addUser()
    {
        $user = new User();
        $user->username = 'demo';
        $user->email = 'demo@example.com';
        $user->generateEmailConfirmToken();
        $user->setPassword('fghfgh');

        $this->assertTrue($user->save());
        $this->assertInstanceOf('app\models\User', $user);
        $this->assertTrue($user->isActive());
        $this->assertTrue($user->validatePassword('fghfgh'));

        return $user;
    }

    protected function addRole()
    {
        $role = new AuthItem([
            'type' => \yii\rbac\Item::TYPE_ROLE,
            'name' => 'test',
            'description' => 'test role'
        ]);

        $this->assertTrue($role->save());

        return $role;
    }

    public function testUserAdd()
    {
        $this->addUser();
    }

    public function testUserChangePassword()
    {
        $user = $this->addUser();
        $user->passwordNew = 'password-new';

        $this->assertTrue($user->save());
        $this->assertTrue($user->validatePassword('password-new'));
    }

    public function testUserFillProfile()
    {
        $user = $this->addUser();
        $user->profile->full_name = 'Nomad';
        $user->profile->birth_day = '2001-01-02';
        $this->assertTrue($user->save());

        $user = User::findByEmail($user->email);
        $this->assertInstanceOf('app\models\User', $user);
        $this->assertEquals('Nomad', $user->profile->full_name);
        $this->assertEquals('2001-01-02', $user->profile->birth_day);
    }

    public function testUserAssignRole()
    {
        $user = $this->addUser();
        $role = $this->addRole();

        $user->role = $role->name;
        $this->assertTrue($user->save());

        $user = User::findByEmail($user->email);

        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole($user->role), $user->id);

        $this->assertTrue($user->roles->name === $role->name);
        $this->assertEquals($role->name, $user->role);
        $this->assertEquals($role->name, key($auth->getRolesByUser($user->id)));

        $role->delete();
    }

    public function testUserStatusUnconfirmed()
    {
        $user = $this->addUser();

        $this->assertFalse($user->isConfirmed());
        $this->assertNotEmpty($user->email_confirm_token);
    }

    public function testUserStatusConfirmed()
    {
        $user = $this->addUser();
        $user->setConfirmed();

        $this->assertTrue($user->save());

        $user = User::findByEmail($user->email);

        $this->assertTrue($user->isConfirmed());
        $this->assertEmpty($user->email_confirm_token);
    }

    public function testUserStatusDeleted()
    {
        $user = new User();

        $user->status = User::STATUS_DELETED;
        $this->assertTrue($user->isDeleted());
        $this->assertEquals('Deleted', $user->getStatusName());
        $this->assertEquals('Your account has been deleted', $user->getStatusDescription());
    }

    public function testUserStatusActive()
    {
        $user = new User();

        $user->status = User::STATUS_ACTIVE;
        $this->assertTrue($user->isActive());
        $this->assertEquals('Active', $user->getStatusName());

        $this->assertEquals('Your account is activated', $user->getStatusDescription());
    }

    public function testUserStatusBlocked()
    {
        $user = new User();

        $user->status = User::STATUS_BLOCKED;
        $this->assertTrue($user->isBlocked());
        $this->assertEquals('Locked', $user->getStatusName());
        $this->assertEquals('Your account has been suspended', $user->getStatusDescription());
    }

    public function testUserIsSuperUser()
    {
        $user = new User();
        $user->role = User::ROLE_SUPERUSER;
        $this->assertTrue($user->isSuperUser());
    }

    public function testUserValidateAuthKey()
    {
        $user = new User();
        $user->auth_key = $user->generateAuthKey();
        $this->assertTrue($user->validateAuthKey($user->getAuthKey()));
    }

    public function testUserValidatePassword()
    {
        $user = new User();
        $this->assertFalse($user->validatePassword(''));

        $user = User::findByEmail($this->user['2-active']['email']);
        $this->assertTrue($user->validatePassword('123123'));
    }

    public function testUserFindIdentity()
    {
        $user = new User();
        $user = $user->findIdentity($this->user['2-active']['id']);
        $this->assertInstanceOf('app\models\User', $user);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage findIdentityByAccessToken is not implemented.
     */
    public function testUserFindIdentityByAccessToken()
    {
        $user = new User();
        $user->findIdentityByAccessToken('123');
    }

    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/fixtures/data/user.php',
            ],
        ];
    }
}
