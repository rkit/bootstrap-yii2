<?php

namespace app\tests\unit\models;

use Yii;
use yii\helpers\FileHelper;
use app\tests\fixtures\NewsType as NewsTypeFixture;
use app\tests\fixtures\News as NewsFixture;
use app\models\News;

class NewsTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'news' => [
                 'class' => NewsFixture::className(),
                 'dataFile' => codecept_data_dir() . 'news.php',
             ],
             'newsType' => [
                 'class' => NewsTypeFixture::className(),
                 'dataFile' => codecept_data_dir() . 'news_type.php',
             ],
        ]);

        FileHelper::removeDirectory(Yii::getAlias('@tests/_tmp/files'));
        FileHelper::copyDirectory(
            Yii::getAlias('@tests/_data/files'),
            Yii::getAlias('@tests/_tmp/files')
        );
    }

    public function testAddPreview()
    {
        $model = News::findOne(1);

        $file = $model->createFile('preview', Yii::getAlias('@tests/_tmp/files/300x300.png'));
        expect_that($file);

        $model->preview = $file->id;
        expect_that($model->save());

        $thumb = $model->thumbPath('preview', '200x200');
        expect($thumb)->contains('200x200');
        expect_that(file_exists($thumb));

        $thumb = $model->thumbPath('preview', '1000x1000');
        expect($thumb)->contains('1000x1000');
        // because original was replaced
        expect_not(file_exists($thumb));
    }

    public function testAddGallery()
    {
        $model = News::findOne(1);

        $file = $model->createFile('gallery', Yii::getAlias('@tests/_tmp/files/500x500.png'), '500x500', false);
        expect_that($file);

        $model->gallery = [$file->id => '500x500'];
        expect_that($model->save());

        $thumb = $model->thumbPath('gallery', '80x80', $file);
        expect($thumb)->contains('80x80');
        expect_that(file_exists($thumb));
    }
}
