<?php

namespace app\tests\functional\admin;

use Codeception\Util\Locator;
use yii\helpers\Url;
use app\tests\fixtures\Country as CountryFixture;
use app\tests\fixtures\Region as RegionFixture;
use app\tests\fixtures\City as CityFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class CitiesCest
{
    protected $pageTitle = 'Cities';
    protected $modelClass = 'app\models\City';
    protected $formName = 'City';
    protected $formId = '#cities-form';
    protected $url = '/admin/cities';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'country' => [
                 'class' => CountryFixture::className(),
                 'dataFile' => codecept_data_dir() . 'country.php',
             ],
             'region' => [
                 'class' => RegionFixture::className(),
                 'dataFile' => codecept_data_dir() . 'region.php',
             ],
             'city' => [
                 'class' => CityFixture::className(),
                 'dataFile' => codecept_data_dir() . 'city.php',
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
            $this->formName . '[region_id]' => 1,
            $this->formName . '[country_id]' => 1,
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
        $I->see('City-1', '//table/tbody/tr[1]');
        $I->see('City-2', '//table/tbody/tr[2]');
        $I->see('City-3', '//table/tbody/tr[3]');
    }

    public function testIndexSortByTitle($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-3', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-1', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByCountryId($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-1', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-3', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByRegionId($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[4]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-1', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[4]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-3', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByImportant($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[5]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-2', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[5]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('City-1', '//table/tbody/tr[1]/td');
    }

    public function testIndexFilterByTitle($I)
    {
        $I->amOnRoute($this->url . '/index', ['CitySearch[title]' => 'City-1']);
        $I->seeResponseCodeIs(200);
        $I->see('City-1');
        $I->dontSee('City-2');
        $I->dontSee('City-3');
    }

    public function testIndexFilterByCountryId($I)
    {
        $I->amOnRoute($this->url . '/index', ['CitySearch[country_id]' => 1]);
        $I->seeResponseCodeIs(200);
        $I->see('City-1');
        $I->dontSee('City-2');
        $I->dontSee('City-3');
    }

    public function testIndexFilterByRegionId($I)
    {
        $I->amOnRoute($this->url . '/index', ['CitySearch[region_id]' => 1]);
        $I->seeResponseCodeIs(200);
        $I->see('City-1');
        $I->dontSee('City-2');
        $I->dontSee('City-3');
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
        $I->see('Country cannot be blank', '.help-block');
    }

    public function testCreateWithEmptyFieldsViaAjax($I)
    {
        $I->sendAjaxPostRequest(Url::toRoute($this->url . '/edit'), [$this->formName . '[title]' => '']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Title cannot be blank');
        $I->seeResponseContains('Country cannot be blank');
    }

    public function testCreate($I)
    {
        $this->create($I, 'City-4');

        $I->amOnRoute($this->url);
        $I->see('City-4');
    }

    public function testCreateViaAjax($I)
    {
        $this->create($I, 'City-5', true);

        $I->amOnRoute($this->url);
        $I->see('City-5');
    }

    public function testUpdate($I)
    {
        $I->click('City-1');
        $I->submitForm($this->formId, [
            $this->formName . '[title]' =>  'City-1_UPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->see('City-1_UPD');
    }

    public function testDelete($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('City-1');
    }

    public function testDeleteAndReload($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url . '&reload=1');
        $I->seeResponseCodeIs(302);
        $I->amOnPage($I->grabHttpHeader('X-Redirect'));
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('City-1');
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
}
