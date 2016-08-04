<?php

namespace app\tests\functional\admin;

use Codeception\Util\Locator;
use yii\helpers\Url as Url;
use app\tests\fixtures\NewsType as NewsTypeFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;

class NewsTypeCest
{
    protected $pageTitle = 'Type of news';
    protected $modelClass = 'app\models\NewsType';
    protected $formName = 'NewsType';
    protected $formId = '#news-type-form';
    protected $url = '/admin/news-types';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'newsType' => [
                 'class' => NewsTypeFixture::className(),
                 'dataFile' => codecept_data_dir() . 'news_type.php',
             ],
             'user' => [
                 'class' => UserFixture::className(),
                 'dataFile' => codecept_data_dir() . 'user.php',
             ],
        ]);
        $I->amLoggedInAs(User::findByUsername('superuser'));
        $I->amOnRoute($this->url);
    }

    private function create($I, $title)
    {
        $I->amOnRoute($this->url . '/edit');
        $I->submitForm($this->formId, [
            $this->formName . '[title]' => $title,
        ]);

        $I->expectTo('see success');
        $I->see('Saved successfully');
        $I->seeResponseCodeIs(200);
    }

    public function testOpenIndexPage($I)
    {
        $I->see($this->pageTitle);
        $I->see('Type-1', '//table/tbody/tr[1]');
        $I->see('Type-2', '//table/tbody/tr[2]');
        $I->see('Type-3', '//table/tbody/tr[3]');
    }

    public function testIndexSortByTitle($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Type-3', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Type-1', '//table/tbody/tr[1]/td');
    }

    public function testIndexFilterByTitle($I)
    {
        $I->amOnRoute($this->url . '/index', ['NewsTypeSearch[title]' => 'Type-1']);
        $I->seeResponseCodeIs(200);
        $I->see('Type-1');
        $I->dontSee('Type-2');
        $I->dontSee('Type-3');
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

    public function testCreate($I)
    {
        $this->create($I, 'Type-4');

        $I->amOnRoute($this->url);
        $I->see('Type-4');
    }

    public function testUpdate($I)
    {
        $I->click('Type-1');
        $I->submitForm($this->formId, [
            $this->formName . '[title]' =>  'Type-1_UPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->see('Type-1_UPD');
    }

    public function testDelete($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('Type-1');
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
