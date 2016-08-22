<?php

namespace app\tests\unit\models\query;

use Yii;
use app\tests\fixtures\News as NewsFixture;
use app\models\News;

class NewsQueryTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'news' => [
                 'class' => NewsFixture::className(),
                 'dataFile' => codecept_data_dir() . 'news.php',
             ],
        ]);
    }

    public function testFindActive()
    {
        $models = News::find()->active()->all();
        expect(count($models))->equals(2);
        foreach ($models as $model) {
            expect($model->isActive())->true();
        }
    }

    public function testFindBlocked()
    {
        $models = News::find()->blocked()->all();
        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->isBlocked())->true();
        }
    }
}
