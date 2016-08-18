<?php

namespace app\tests\unit\helpers;

use Yii;
use app\helpers\Util;
use app\models\News;

class UtilTest extends \Codeception\Test\Unit
{
    public function testCollectModelErrors()
    {
        $model = new News();
        $model->save();

        $errors = Util::collectModelErrors($model);

        expect_that(count($errors) === 4);
        expect($errors['news-title'])->notEmpty();
        expect($errors['news-type_id'])->notEmpty();
        expect($errors['news-text'])->notEmpty();
        expect($errors['news-date_pub'])->notEmpty();
    }
}
