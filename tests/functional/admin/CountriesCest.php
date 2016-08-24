<?php

namespace app\tests\functional\admin;

use Codeception\Util\Locator;
use yii\helpers\Url;
use app\tests\fixtures\Country as CountryFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class CountriesCest
{
    protected $pageTitle = 'Countries';
    protected $formName = 'Country';
    protected $formId = '#countries-form';
    protected $url = '/admin/countries';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'country' => [
                 'class' => CountryFixture::className(),
                 'dataFile' => codecept_data_dir() . 'country.php',
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
        $I->see('Country-1', '//table/tbody/tr[1]');
        $I->see('Country-2', '//table/tbody/tr[2]');
        $I->see('Country-3', '//table/tbody/tr[3]');
    }

    public function testIndexSortByTitle($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Country-3', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Country-1', '//table/tbody/tr[1]/td');
    }

    public function testIndexFilterByTitle($I)
    {
        $I->amOnRoute($this->url . '/index', ['CountrySearch[title]' => 'Country-1']);
        $I->seeResponseCodeIs(200);
        $I->see('Country-1');
        $I->dontSee('Country-2');
        $I->dontSee('Country-3');
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
        $this->create($I, 'Country-4');

        $I->amOnRoute($this->url);
        $I->see('Country-4');
    }

    public function testCreateViaAjax($I)
    {
        $this->create($I, 'Country-5', true);

        $I->amOnRoute($this->url);
        $I->see('Country-5');
    }

    public function testUpdate($I)
    {
        $I->click('Country-1');
        $I->submitForm($this->formId, [
            $this->formName . '[title]' =>  'Country-1_UPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->see('Country-1_UPD');
    }

    public function testDelete($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('Country-1');
    }

    public function testDeleteAndReload($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url . '&reload=1');
        $I->seeResponseCodeIs(302);
        $I->amOnPage($I->grabHttpHeader('X-Redirect'));
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('Country-1');
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
        $I->sendPOST(Url::toRoute('/admin/countries/autocomplete'), ['term' => 'test']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('[]');

        $I->sendPOST(Url::toRoute('/admin/countries/autocomplete'), ['term' => 'Country-1']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Country-1');
        $I->dontSeeResponseContains('Country-2');

        $I->sendPOST(Url::toRoute('/admin/countries/autocomplete'), ['term' => 'Country']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Country-1');
        $I->seeResponseContains('Country-2');
        $I->seeResponseContains('Country-3');
    }
}
