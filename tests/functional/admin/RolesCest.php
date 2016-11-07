<?php

namespace app\tests\functional\admin;

use Codeception\Util\Locator;
use yii\helpers\Url;
use app\tests\fixtures\AuthItem as AuthItemFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class RolesCest
{
    protected $pageTitle = 'Roles';
    protected $formName = 'AuthItem';
    protected $formId = '#roles-form';
    protected $url = '/admin/roles';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'authItem' => [
                 'class' => AuthItemFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/auth_item.php',
             ],
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'models/user.php',
             ],
        ]);
        $I->amLoggedInAs(User::findByUsername('superuser'));
        $I->amOnRoute($this->url);
    }

    private function create($I, $name, $permissions, $roles, $isAjax = false)
    {
        $I->amOnRoute($this->url . '/edit');

        $data = [
            $this->formName . '[name]' => $name,
            $this->formName . '[description]' => 'Test',
            $this->formName . '[permissions]' => $permissions,
            $this->formName . '[roles]' => $roles,
        ];

        if ($isAjax) {
            $I->sendAjaxPostRequest(Url::toRoute($this->url . '/edit'), $data);
            $I->seeResponseCodeIs(200);
            $I->seeResponseContains('redirect');
            $response = json_decode($I->grabResponse());
            $I->amOnRoute($response->redirect);
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
        $I->see(User::ROLE_SUPERUSER, '//table/tbody/tr[4]');
        $I->see('EditorTags', '//table/tbody/tr[3]');
        $I->see('EditorSettings', '//table/tbody/tr[2]');
        $I->see('EditorNews', '//table/tbody/tr[1]');
    }

    public function testIndexSortByTitle($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 4);
        $I->see(User::ROLE_SUPERUSER, '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 4);
        $I->see('EditorNews', '//table/tbody/tr[1]/td');
    }

    public function testIndexFilterByTitle($I)
    {
        $I->amOnRoute($this->url . '/index', ['AuthItemSearch[name]' => 'EditorNews']);
        $I->seeResponseCodeIs(200);
        $I->see('EditorNews');
        $I->dontSee(User::ROLE_SUPERUSER);
        $I->dontSee('EditorSettings');
        $I->dontSee('EditorTags');
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
        $I->see('Name cannot be blank', '.help-block');
        $I->see('Description cannot be blank', '.help-block');
    }

    public function testCreateWithEmptyFieldsViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute($this->url . '/edit'), [$this->formName . '[name]' => '']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Name cannot be blank');
        $I->seeResponseContains('Description cannot be blank');
    }

    public function testCreate($I)
    {
        $this->create($I, 'EditorUsers', ['ACTION_AdminUsers'], []);
        $I->seeInField('AuthItem[name]', 'EditorUsers');
        $I->seeInField('AuthItem[permissions][]', 'ACTION_AdminUsers');
        $I->seeInField('AuthItem[roles][]', '');

        $I->amOnRoute($this->url);
        $I->see('EditorUsers');
    }

    public function testCreateWithExtendRole($I)
    {
        $this->create($I, 'EditorUsersAndNews', ['ACTION_AdminUsers'], ['EditorNews']);
        $I->seeInField('AuthItem[name]', 'EditorUsersAndNews');
        $I->seeInField('AuthItem[permissions][]', 'ACTION_AdminUsers');
        $I->seeInField('AuthItem[roles][]', 'EditorNews');

        $I->amOnRoute($this->url);
        $I->see('EditorUsersAndNews');
    }

    public function testCreateViaAjax($I)
    {
        $this->create($I, 'EditorUsersAjax', ['ACTION_AdminUsers'], [], true);

        $I->amOnRoute($this->url);
        $I->see('EditorUsersAjax');
    }

    public function testUpdate($I)
    {
        $I->click('EditorNews');
        $I->submitForm($this->formId, [
            $this->formName . '[name]' =>  'EditorNewsUPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->see('EditorNewsUPD');
    }

    public function testDelete($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[3]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->dontSee('Role3');
    }

    public function testDeleteAndReload($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[3]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url . '&reload=1');
        $I->seeResponseCodeIs(302);
        $I->amOnPage($I->grabHttpHeader('X-Redirect'));
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->dontSee('Role3');
    }

    public function testDeleteAll($I)
    {
        $I->checkOption('//table/tbody/tr[1]/td/input');
        $I->checkOption('//table/tbody/tr[2]/td/input');
        $I->checkOption('//table/tbody/tr[3]/td/input');
        $I->checkOption('//table/tbody/tr[4]/td/input');
        $I->click('delete');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see(User::ROLE_SUPERUSER);
    }
}
