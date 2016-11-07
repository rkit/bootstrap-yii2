<?php

namespace app\tests\functional\admin;

use Yii;
use Codeception\Util\Locator;
use yii\helpers\Url;
use app\tests\fixtures\AuthItem as AuthItemFixture;
use app\tests\fixtures\AuthItemChild as AuthItemChildFixture;
use app\tests\fixtures\AuthAssignment as  AuthAssignmentFixture;
use app\tests\fixtures\UserProfile as UserProfileFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;
use app\models\UserProfile;

class UsersProfileCest
{
    protected $pageTitle = 'Users';
    protected $formName = 'UserForm';
    protected $formId = '#users-form';
    protected $url = '/admin/users';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'authItem' => [
                 'class' => AuthItemFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/auth_item.php',
             ],
             'authAssignment' => [
                 'class' => AuthAssignmentFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/auth_assignment.php',
             ],
             'authItemChild' => [
                 'class' => AuthItemChildFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/auth_item_child.php',
             ],
             'profile' => [
                 'class' => UserProfileFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/user_profile.php',
             ],
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/user.php',
             ],
        ]);
        $I->amLoggedInAs(User::findByUsername('superuser'));
        $I->amOnRoute($this->url);
    }

    public function testUpdateWithWrongBirthDay($I)
    {
        $I->click('user-2');
        $I->click('Profile');
        $I->submitForm('#profile-form', [
            'UserProfile[birth_day]' => 'test',
        ]);
        $I->expectTo('see validations errors');
        $I->see('The format of Birth Day is invalid', '.help-block');
    }

    public function testUpdate($I)
    {
        $I->click('user-2');
        $I->click('Profile');
        $I->submitForm('#profile-form', [
            'UserProfile[full_name]' =>  'Profile-2_UPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->seeInField('UserProfile[full_name]', 'Profile-2_UPD');
    }

    public function testAddPhoto($I)
    {
        $model = new UserProfile();
        $file = $model->createFile('photo', Yii::getAlias('@tests/_data/files/300x300.png'), '300x300.png');

        $I->click('user-2');
        $I->click('Profile');
        $I->submitForm('#profile-form', [
            'UserProfile[photo]' => $file->id,
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->seeInField('UserProfile[photo]', $model->fileUrl('photo', $file));
    }

    public function testUpdateViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute(['/admin/users/profile', 'id' => 2]), [
            'UserProfile[full_name]' =>  'Profile-2_UPD',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('redirect');
    }

    public function testUpdateWithWrongBirthDayViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute(['/admin/users/profile', 'id' => 2]), [
            'UserProfile[birth_day]' => 'test',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('The format of Birth Day is invalid');
    }
}
