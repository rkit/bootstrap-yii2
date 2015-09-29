<?php

namespace tests\codeception\unit\admin\models;

use Yii;
use yii\codeception\DbTestCase;
use app\models\User;
use app\models\UserProfile;
use app\models\AuthItem;
use tests\codeception\fixtures\UserFixture;
use tests\codeception\fixtures\AuthItemFixture;
use Codeception\Specify;

class UserTest extends DbTestCase
{
    use Specify;

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

    public function testUserSaving()
    {
        $user = $this->addUser();
        $user->setConfirmed();
        $this->assertTrue($user->save());

        $user = User::findByEmail($user->email);
        $this->assertTrue($user->isActive());
        $this->assertTrue($user->isConfirmed());
        $this->assertTrue($user->validatePassword('fghfgh'));
    }

    public function testUserUnconfirmed()
    {
        $user = $this->addUser();

        $this->assertFalse($user->isConfirmed());
        $this->assertNotEmpty($user->email_confirm_token);
    }

    public function testUserConfirmed()
    {
        $user = $this->addUser();
        $user->setConfirmed();

        $this->assertTrue($user->save());

        $user = User::findByEmail($user->email);
        $this->assertTrue($user->isConfirmed());
        $this->assertEmpty($user->email_confirm_token);
    }

    public function testUserNewPassword()
    {
        $user = User::findByEmail($this->user['2-active']['email']);

        $user->passwordNew = 'password-new';
        $this->assertTrue($user->save());
        $this->assertTrue($user->validatePassword('password-new'));
    }

    public function testUserProfile()
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

    public function testUserAuthKey()
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

    public function testFindIdentity()
    {
        $user = new User();
        $this->assertInstanceOf('app\models\User', $user->findIdentity($this->user['2-active']['id']));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage findIdentityByAccessToken is not implemented.
     */
    public function testFindIdentityByAccessToken()
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
