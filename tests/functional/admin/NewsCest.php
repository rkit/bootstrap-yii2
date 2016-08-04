<?php

namespace app\tests\functional\admin;

use yii\helpers\Url as Url;
use app\tests\fixtures\NewsType as NewsTypeFixture;
use app\tests\fixtures\User as UserFixture;
use app\models\User;
use app\models\News;

class NewsCest
{
    protected $formId = '#news-form';
    protected $lastId;

    public function _before($I)
    {
        $I->amLoggedInAs(User::findByUsername('superuser'));
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
        $I->amOnRoute('/admin/news');
    }

    public function openListPage($I)
    {
        $I->see('News');
    }

    public function openCreatePage($I)
    {
        $I->amOnRoute('/admin/news/edit');
        $I->see('News / Create');
    }

    public function testCreateWithEmptyFields($I)
    {
        $I->amOnRoute('/admin/news/edit');
        $I->submitForm($this->formId, []);
        $I->expectTo('see validations errors');
        $I->see('Title cannot be blank', '.help-block');
        $I->see('Type cannot be blank', '.help-block');
        $I->see('Text cannot be blank', '.help-block');
    }

    public function testCreate($I)
    {
        $I->amOnRoute('/admin/news/edit');
        $I->submitForm($this->formId, [
            'News[title]' => 'Test' . time(),
            'News[type_id]' => 1,
            'News[text]' => 'Test',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');
        $I->seeResponseCodeIs(200);

        $this->lastId = $I->grabFromCurrentUrl('/id=(\d+)/');
        $news = News::findOne($this->lastId);

        $I->amOnRoute('/admin/news');
        $I->see($news->title);
    }

    public function testUpdate($I)
    {
        $news = News::findOne($this->lastId);

        $I->click($news->title);
        $I->submitForm($this->formId, [
            'News[title]' => $news->title . '_UPD',
            'News[type_id]' => 1,
            'News[text]' => 'Test',
        ]);
        $I->expectTo('see success');
        $I->see('Saved successfully');

        $I->amOnRoute('/admin/news');
        $I->see($news->title . '_UPD');
    }

    public function testDelete($I)
    {
        $news = News::findOne($this->lastId);

        $I->sendAjaxPostRequest(Url::toRoute(['/admin/news/delete', 'id' => $this->lastId]));
        $I->dontSee($news->title);
    }
}
