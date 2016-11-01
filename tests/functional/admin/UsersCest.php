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

class UsersCest
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
                 'dataFile' => codecept_data_dir() . 'auth_item.php',
             ],
             'authAssignment' => [
                 'class' => AuthAssignmentFixture::className(),
                 'dataFile' => codecept_data_dir() . 'auth_assignment.php',
             ],
             'authItemChild' => [
                 'class' => AuthItemChildFixture::className(),
                 'dataFile' => codecept_data_dir() . 'auth_item_child.php',
             ],
             'profile' => [
                 'class' => UserProfileFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user_profile.php',
             ],
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
        $I->amLoggedInAs(User::findByUsername('superuser'));
        $I->amOnRoute($this->url);
    }

    private function create($I, $username, $isAjax = false)
    {
        $I->amOnRoute($this->url . '/edit');

        $data = [
            $this->formName . '[username]' => $username,
            $this->formName . '[passwordNew]' => 'test_password',
        ];

        if ($isAjax) {
            $I->sendAjaxPostRequest(Url::toRoute($this->url . '/edit'), $data);
            $I->seeResponseCodeIs(200);
            $I->seeResponseContains('redirect');
        } else {
            $I->submitForm($this->formId, $data);
            $I->seeResponseCodeIs(200);
            $I->expectTo('see success');
            $I->see('Saved successfully');
        }
    }

    public function testOpenIndexPage($I)
    {
        $I->see($this->pageTitle);
        $I->see('superuser', '//table/tbody/tr[6]');
        $I->see('user-2', '//table/tbody/tr[5]');
        $I->see('user-3', '//table/tbody/tr[4]');
        $I->see('user-4', '//table/tbody/tr[3]');
        $I->see('user-5', '//table/tbody/tr[2]');
        $I->see('user-6', '//table/tbody/tr[1]');
    }

    public function testIndexSortByTitle($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('superuser', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('user-6', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByEmail($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('superuser', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('user-6', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByDateCreate($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[4]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('superuser', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[4]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('user-6', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByIp($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[5]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('superuser', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[5]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('user-6', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByRole($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[6]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('user-2', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[6]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('superuser', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByStatus($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[7]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('user-4', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[7]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('user-3', '//table/tbody/tr[1]/td');
    }

    public function testIndexFilterByTitle($I)
    {
        $I->amOnRoute($this->url . '/index', ['UserSearch[username]' => 'user-1']);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('user-1');
    }

    public function testIndexFilterByEmail($I)
    {
        $I->amOnRoute($this->url . '/index', ['UserSearch[email]' => 'user-2@example.com']);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('user-2@example.com');
    }

    public function testIndexFilterByDateCreate($I)
    {
        $I->amOnRoute($this->url . '/index', ['UserSearch[date_create]' => '2015-01-02']);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('user-2');
    }

    public function testIndexFilterByIp($I)
    {
        $I->amOnRoute($this->url . '/index', ['UserSearch[ip]' => '127.0.0.0']);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('user-2');
    }

    public function testIndexFilterByRole($I)
    {
        $I->amOnRoute($this->url . '/index', ['UserSearch[role]' => User::ROLE_SUPERUSER]);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('superuser');
    }

    public function testIndexFilterByStatus($I)
    {
        $I->amOnRoute($this->url . '/index', ['UserSearch[status]' => User::STATUS_BLOCKED]);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('user-3');
    }

    public function testOpenCreatePage($I)
    {
        $I->amOnRoute($this->url . '/edit');
        $I->see($this->pageTitle . ' / Create');
    }

    public function testCreateWithEmptyFields($I)
    {
        $I->amOnRoute($this->url . '/edit');
        $I->submitForm($this->formId, []);
        $I->expectTo('see validations errors');
        $I->see('You must fill in username or email', '.help-block');
    }

    public function testCreateWithEmptyFieldsViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute($this->url . '/edit'), [$this->formName . '[username]' => '']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('You must fill in username or email');
    }

    public function testCreate($I)
    {
        $this->create($I, 'user-10');

        $I->amOnRoute($this->url);
        $I->see('user-10');
    }

    public function testCreateViaAjax($I)
    {
        $this->create($I, 'user-11', true);

        $I->amOnRoute($this->url);
        $I->see('user-11');
    }

    public function testUpdate($I)
    {
        $I->click('user-2');
        $I->submitForm($this->formId, [
            $this->formName . '[username]' =>  'user-2_UPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->see('user-2_UPD');
    }

    public function testUpdateProfileWithWrongBirthDay($I)
    {
        $I->click('user-2');
        $I->click('Profile');
        $I->submitForm('#profile-form', [
            'UserProfile[birth_day]' => 'test',
        ]);
        $I->expectTo('see validations errors');
        $I->see('The format of Birth Day is invalid', '.help-block');
    }

    public function testUpdateProfile($I)
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

    public function testAddPhotoToProfile($I)
    {
        $model = new UserProfile();
        $file = $model->createFile('photo', Yii::getAlias('@tests/_tmp/files/300x300.png'));

        $I->click('user-2');
        $I->click('Profile');
        $I->submitForm('#profile-form', [
            'UserProfile[photo]' => $file->id,
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->seeInField('UserProfile[photo]', $model->fileUrl('photo', $file));
    }

    public function testUpdateProfileViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute(['/admin/users/profile', 'id' => 2]), [
            'UserProfile[full_name]' =>  'Profile-2_UPD',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('redirect');
    }

    public function testUpdateProfileWithWrongBirthDayViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute(['/admin/users/profile', 'id' => 2]), [
            'UserProfile[birth_day]' => 'test',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('The format of Birth Day is invalid');
    }

    public function testAssignRoleAndLogin($I)
    {
        $I->click('user-2');
        $I->submitForm($this->formId, [
            $this->formName . '[role]' => 'EditorNews',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amLoggedInAs(User::findByUsername('user-2'));
        $I->amOnRoute('/admin/news');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute('/admin/users');
        $I->seeResponseCodeIs(403);

        $I->amOnRoute('/admin');
        $I->see('News', '#menu');
        $I->seeNumberOfElements('#menu li', 1);
    }

    public function testEnable($I)
    {
        $element = '//table/tbody/tr[4]/td';
        $I->see('Locked', $element);

        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[4]/td/a', -2), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->see('Active', $element);
    }

    public function testDisable($I)
    {
        $element = '//table/tbody/tr[5]/td';
        $I->see('Active', $element);
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[5]/td/a', -2), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->see('Locked', $element);
    }

    public function testEnableAll($I)
    {
        $I->checkOption('//table/tbody/tr[1]/td/input');
        $I->checkOption('//table/tbody/tr[2]/td/input');
        $I->checkOption('//table/tbody/tr[3]/td/input');
        $I->checkOption('//table/tbody/tr[4]/td/input');
        $I->checkOption('//table/tbody/tr[5]/td/input');
        $I->checkOption('//table/tbody/tr[6]/td/input');
        $I->click('active');

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->dontSee('Locked', 'tbody');
    }

    public function testDisableAll($I)
    {
        $I->checkOption('//table/tbody/tr[1]/td/input');
        $I->checkOption('//table/tbody/tr[2]/td/input');
        $I->checkOption('//table/tbody/tr[3]/td/input');
        $I->checkOption('//table/tbody/tr[4]/td/input');
        $I->checkOption('//table/tbody/tr[5]/td/input');
        $I->click('block');

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 6);
        $I->see('Locked', '//table/tbody/tr[1]/td');
        $I->see('Locked', '//table/tbody/tr[2]/td');
        $I->see('Locked', '//table/tbody/tr[3]/td');
        $I->see('Locked', '//table/tbody/tr[4]/td');
        $I->see('Locked', '//table/tbody/tr[5]/td');
    }

    public function testDelete($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[2]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 5);
        $I->dontSee('user-5');
    }

    public function testDeleteAndReload($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[2]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url . '&reload=1');
        $I->seeResponseCodeIs(302);
        $I->amOnPage($I->grabHttpHeader('X-Redirect'));
        $I->seeNumberOfElements('//table/tbody/tr', 5);
        $I->dontSee('user-5');
    }

    public function testDeleteAll($I)
    {
        $I->checkOption('//table/tbody/tr[1]/td/input');
        $I->checkOption('//table/tbody/tr[2]/td/input');
        $I->checkOption('//table/tbody/tr[3]/td/input');
        $I->checkOption('//table/tbody/tr[4]/td/input');
        $I->checkOption('//table/tbody/tr[5]/td/input');
        $I->click('delete');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('superuser', 'tbody');
    }

    public function testAutocomplete($I)
    {
        $I->sendPOST(Url::toRoute('/admin/users/autocomplete'), ['term' => 'test']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[]');

        $I->sendPOST(Url::toRoute('/admin/users/autocomplete'), ['term' => 'user-2']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('user-2');
        $I->dontSeeResponseContains('user-3');

        $I->sendPOST(Url::toRoute('/admin/users/autocomplete'), ['term' => 'user']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('user-2');
        $I->seeResponseContains('user-3');
        $I->seeResponseContains('user-4');
    }
}
