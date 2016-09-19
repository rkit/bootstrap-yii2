<?php

namespace app\tests\unit\helpers;

use app\helpers\Model;
use app\models\News;

class ModelTest extends \Codeception\Test\Unit
{
    public function testCollectModelErrors()
    {
        $model = new News();
        $model->save();

        $errors = Model::collectErrors($model);

        expect_that(count($errors) === 4);
        expect($errors['news-title'])->notEmpty();
        expect($errors['news-type_id'])->notEmpty();
        expect($errors['news-text'])->notEmpty();
        expect($errors['news-date_pub'])->notEmpty();
    }
}
