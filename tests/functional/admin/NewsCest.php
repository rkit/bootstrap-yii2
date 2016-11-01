<?php

namespace app\tests\functional\admin;

use Yii;
use Codeception\Util\Locator;
use yii\helpers\Url;
use app\tests\fixtures\NewsType as NewsTypeFixture;
use app\tests\fixtures\News as NewsFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;
use app\models\News;

class NewsCest
{
    protected $pageTitle = 'News';
    protected $formName = 'News';
    protected $formId = '#news-form';
    protected $url = '/admin/news';

    // @codingStandardsIgnoreFile
    public function _before($I)
    {
        $I->haveFixtures([
             'news' => [
                'class' => NewsFixture::className(),
                'dataFile' => codecept_data_dir() . 'news.php',
             ],
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

    private function create($I, $title, $isAjax = false)
    {
        $I->amOnRoute($this->url . '/edit');

        $data = [
            $this->formName . '[title]' => $title,
            $this->formName . '[type_id]' => 1,
            $this->formName . '[text]' => 'Test',
            $this->formName . '[date_pub]' => '2016-08-18 10:10:21',
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
        $I->see('News-1', '//table/tbody/tr[3]');
        $I->see('News-2', '//table/tbody/tr[2]');
        $I->see('News-3', '//table/tbody/tr[1]');
    }

    public function testIndexSortByTitle($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-1', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[2]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-3', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByTypeId($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-1', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[3]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-3', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByDatePub($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[4]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-1', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[4]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-3', '//table/tbody/tr[1]/td');
    }

    public function testIndexSortByStatus($I)
    {
        $url = $I->grabAttributeFrom('//table/thead/tr/th[5]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-1', '//table/tbody/tr[1]/td');

        // change mode
        $url = $I->grabAttributeFrom('//table/thead/tr/th[5]/a', 'href');
        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('News-2', '//table/tbody/tr[1]/td');
    }

    public function testIndexFilterByTitle($I)
    {
        $I->amOnRoute($this->url . '/index', ['NewsSearch[title]' => 'News-1']);
        $I->seeResponseCodeIs(200);
        $I->see('News-1');
        $I->dontSee('News-2');
        $I->dontSee('News-3');
    }

    public function testIndexFilterByTypeId($I)
    {
        $I->amOnRoute($this->url . '/index', ['NewsSearch[type_id]' => 1]);
        $I->seeResponseCodeIs(200);
        $I->see('News-1');
        $I->see('News-2');
        $I->dontSee('News-3');

        $I->amOnRoute($this->url . '/index', ['NewsSearch[type_id]' => 2]);
        $I->seeResponseCodeIs(200);
        $I->dontSee('News-1');
        $I->see('News-3');
        $I->dontSee('News-2');
    }

    public function testIndexFilterByDatePub($I)
    {
        $I->amOnRoute($this->url . '/index', ['NewsSearch[date_pub]' => '2016-01-01']);
        $I->seeResponseCodeIs(200);
        $I->see('News-1');
        $I->dontSee('News-2');
        $I->dontSee('News-3');

        $I->amOnRoute($this->url . '/index', ['NewsSearch[date_pub]' => '2016-01-02']);
        $I->seeResponseCodeIs(200);
        $I->dontSee('News-1');
        $I->see('News-2');
        $I->dontSee('News-3');
    }

    public function testIndexFilterByStatus($I)
    {
        $I->amOnRoute($this->url . '/index', ['NewsSearch[status]' => 0]);
        $I->seeResponseCodeIs(200);
        $I->see('News-1');
        $I->dontSee('News-2');
        $I->dontSee('News-3');

        $I->amOnRoute($this->url . '/index', ['NewsSearch[status]' => 1]);
        $I->seeResponseCodeIs(200);
        $I->dontSee('News-1');
        $I->see('News-2');
        $I->see('News-3');
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
        $this->create($I, 'News-4');

        $I->amOnRoute($this->url);
        $I->see('News-4');
    }

    public function testCreateViaAjax($I)
    {
        $this->create($I, 'News-5', true);

        $I->amOnRoute($this->url);
        $I->see('News-5');
    }

    public function testUpdate($I)
    {
        $I->click('News-1');
        $I->submitForm($this->formId, [
            $this->formName . '[title]' =>  'News-1_UPD',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->see('News-1_UPD');
    }

    public function testAddPreview($I)
    {
        $model = new News();
        $file = $model->createFile('preview', Yii::getAlias('@tests/_tmp/files/300x300.png'));

        $I->click('News-1');
        $I->submitForm($this->formId, [
            $this->formName . '[preview]' =>  $file->id,
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->click('News-1');

        $I->seeInField('News[preview]', $model->fileUrl('preview', $file));
    }

    public function testAddGallery($I)
    {
        $model = new News();
        $file = $model->createFile('gallery', Yii::getAlias('@tests/_tmp/files/300x300.png'));

        $I->click('News-1');
        $I->submitForm($this->formId, [
            $this->formName . '[gallery]' => $file->id,
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute($this->url);
        $I->click('News-1');

        $I->seeInField('News[gallery][]', $file->id);
    }

    public function testPublish($I)
    {
        $element = '//table/tbody/tr[3]/td';
        $I->see('Not published', $element);

        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[3]/td/a', -2), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->dontSee('Not published', $element);
        $I->see('Published', $element);
    }

    public function testUnpublish($I)
    {
        $element = '//table/tbody/tr[1]/td';
        $I->dontSee('Not published', $element);
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -2), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->see('Not published', $element);
    }

    public function testPublishAll($I)
    {
        $I->checkOption('//table/tbody/tr[1]/td/input');
        $I->checkOption('//table/tbody/tr[2]/td/input');
        $I->checkOption('//table/tbody/tr[3]/td/input');
        $I->click('publish');

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->dontSee('Not published', 'tbody');
    }

    public function testUnpublishAll($I)
    {
        $I->checkOption('//table/tbody/tr[1]/td/input');
        $I->checkOption('//table/tbody/tr[2]/td/input');
        $I->checkOption('//table/tbody/tr[3]/td/input');
        $I->click('unpublish');

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 3);
        $I->see('Not published', 'tbody');
    }

    public function testDelete($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url);
        $I->seeResponseCodeIs(200);

        $I->amOnRoute($this->url);
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('News-3');
    }

    public function testDeleteAndReload($I)
    {
        $url = $I->grabAttributeFrom(Locator::elementAt('//table/tbody/tr[1]/td/a', -1), 'href');
        $I->sendAjaxPostRequest($url . '&reload=1');
        $I->seeResponseCodeIs(302);
        $I->amOnPage($I->grabHttpHeader('X-Redirect'));
        $I->seeNumberOfElements('//table/tbody/tr', 2);
        $I->dontSee('News-3');
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
