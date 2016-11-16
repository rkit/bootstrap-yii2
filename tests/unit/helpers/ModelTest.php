<?php

namespace app\tests\unit\helpers;

use app\helpers\Model;
use app\models\forms\SignupForm;

class ModelTest extends \Codeception\Test\Unit
{
    public function testCollectModelErrors()
    {
        $model = new SignupForm();
        $model->validate();

        $errors = Model::collectErrors($model);

        expect_that(count($errors) === 3);
        expect($errors['signupform-fullname'])->notEmpty();
        expect($errors['signupform-email'])->notEmpty();
        expect($errors['signupform-password'])->notEmpty();
    }
}
