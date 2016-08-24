<?php

namespace app\tests\functional\admin;

use Codeception\Util\Locator;
use yii\helpers\Url;
use app\tests\fixtures\Tag as TagFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class TagsCest
{
    protected $pageTitle = 'Tags';
    protected $formName = 'Tag';
    protected $formId = '#tags-form';
    protected $url = '/admin/tags';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'tag' => [
                 'class' => TagFixture::className(),
                 'dataFile' => codecept_data_dir() . 'tag.php',
             ],
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
        $I->amLoggedInAs(User::findByUsername('superuser'));
        $I->amOnRoute($this->url);
    }

    private function create($I, $title, $isAjax = false)
    {
        $I->amOnRoute($this->url . '/edit');

        $data = [
            $this->formName . '[title]' => $title,
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
        $I->see('Tag-1', '//table/tbody/tr[1]');
        $I->see('Tag-2', '//table/tbody/tr[2]');
        $I->see('Tag-3', '//table/tbody/tr[3]');
    }

    public function testIndexSortByTitle($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Tag-3', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Tag-1', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByCount($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Tag-1', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Tag-3', '//table/tbody/tr[1]/td');
    }

    public function testIndexFilterByTitle($I)
    {
        $I->amOnRoute($this->url . '/index', ['TagSearch[title]' => 'Tag-1']);
        $I->seeResponseCodeIs(200);
        $I->see('Tag-1');
        $I->dontSee('Tag-2');
        $I->dontSee('Tag-3');
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
        $I->see('Title cannot be blank', '.help-block');
    }

    public function testCreateWithEmptyFieldsViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute($this->url . '/edit'), [$this->formName . '[title]' => '']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Title cannot be blank');
    }

    public function testCreate($I)
    {
        $this->create($I, 'Tag-4');

        $I->amOnRoute($this->url);
        $I->see('Tag-4');
    }

    public function testCreateViaAjax($I)
    {
        $this->create($I, 'Tag-5', true);

        $I->amOnRoute($this->url);
        $I->see('Tag-5');
    }

    public function testUpdate($I)
    {
        $I->click('Tag-1');
        $I->submitForm($this->formId, [
            $this->formName . '[title]' =>  'Tag-1_UPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->see('Tag-1_UPD');
    }

    public function testDelete($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('Tag-1');
    }

    public function testDeleteAndReload($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url . '&reload=1');
        $I->seeResponseCodeIs(302);
        $I->amOnPage($I->grabHttpHeader('X-Redirect'));
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('Tag-1');
    }

    public function testDeleteAll($I)
    {
        $I->checkOption('//table/tbody/tr[1]/td/input');
        $I->checkOption('//table/tbody/tr[2]/td/input');
        $I->checkOption('//table/tbody/tr[3]/td/input');
        $I->click('delete');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 1);
        $I->see('No results found');
    }

    public function testAutocomplete($I)
    {
        $I->sendPOST(Url::toRoute('/admin/tags/autocomplete'), ['term' => 'test']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[]');

        $I->sendPOST(Url::toRoute('/admin/tags/autocomplete'), ['term' => 'Tag-1']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Tag-1');
        $I->dontSeeResponseContains('Tag-2');

        $I->sendPOST(Url::toRoute('/admin/tags/autocomplete'), ['term' => 'Tag']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Tag-1');
        $I->seeResponseContains('Tag-2');
        $I->seeResponseContains('Tag-3');
    }
}
