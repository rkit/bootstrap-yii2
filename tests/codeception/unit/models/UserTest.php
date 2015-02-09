<?php

namespace tests\codeception\unit\admin\models;

use Yii;
use yii\codeception\DbTestCase;
use app\models\User;
use app\models\UserProfile;
use app\models\AuthItem;
use tests\codeception\fixtures\UserFixture;
use Codeception\Specify;

class UserTest extends DbTestCase
{
    use Specify;
    
    protected function tearDown()
    {
        Yii::$app->user->logout();
        parent::tearDown();
    }
    
    public function testUserNotCorrect()
    {
        $user = new User();
        $user->username = 'one';
        $user->email = 'two';

        expect('user should be false', $user->save())->false();
    }
    
    public function testUserEmpty()
    {
        $user = new User();
        $user->scenario = 'admin-edit';

        expect('user should be false', $user->save())->false();
    }
    
    public function testUserUnconfirmed()
    {
        $user = new User();
        $user->username = 'demo2';
        $user->email = 'demo2@example.com';
        $user->setPassword('fghfgh');
        $user->save();

        $user = User::findByEmail($user->email);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        expect('user should not be confirmed', !$user->isConfirmed())->true();
        
        $user->delete();
    }
    
    public function testUserNewPassword()
    {
        $user = User::findByEmail($this->user[0]['email']);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        
        $user->passwordNew = 'password-new';
        $user->save();
        
        expect('password should be correct', $user->validatePassword('password-new'))->true();
    }

    public function testUserSaving()
    {
        $user = new User();
        $user->username = 'demo3';
        $user->email = 'demo3@example.com';
        $user->setPassword('fghfgh');
        $user->setConfirmed();
        $user->save();

        $user = User::findByEmail($user->email);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        expect('user should be active', $user->isActive())->true();
        expect('user should be confirmed', $user->isConfirmed())->true();
        expect('email should be correct', $user->email)->equals($user->email);
        expect('password should be correct', $user->validatePassword('fghfgh'))->true();
        
        $user->delete();
    }
    
    public function testUserProfile()
    {
        $user = new User();
        $user->username = 'demo4';
        $user->email = 'demo4@example.com';
        $user->setPassword('fghfgh');
        
        $profile = new UserProfile();
        $profile->full_name = 'Nomad';
        $profile->birth_day = '2001-01-02';
        
        $user->populateRelation('profile', $profile);
        $user->save();

        $user = User::findByEmail($user->email);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        expect('full name should be correct', $user->profile->full_name)->equals('Nomad');
        expect('birth_day should be correct', $user->profile->birth_day)->equals('2001-01-02');
        
        $user->delete();
    }
    
    public function testUserRole()
    {
        $role = new AuthItem();
        $role->type = \yii\rbac\Item::TYPE_ROLE;
        $role->name = 'testRole';
        $role->description = 'test role';
        $role->save();
    
        $user = new User();
        $user->username = 'demo5';
        $user->email = 'demo5@example.com';
        $user->setPassword('fghfgh');
        $user->role = $role->name;
        $user->save();

        $user = User::findByEmail($user->email);
        
        $auth = Yii::$app->authManager;
        $auth->assign($auth->getRole($role->name), $user->id);
        
        $this->assertInstanceOf('app\models\User', $user, 'user should be exist');
        expect('role should be correct', $user->role)->equals($role->name);
        expect('access should be corrent', key($auth->getRolesByUser($user->id)))->equals($role->name);
        
        $role->delete();
        $user->delete();
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
