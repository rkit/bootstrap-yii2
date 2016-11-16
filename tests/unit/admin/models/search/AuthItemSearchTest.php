<?php

namespace app\tests\unit\admin\models\search;

use app\tests\fixtures\AuthItem as AuthItemFixture;
use app\modules\admin\models\search\AuthItemSearch;

class AuthItemSearchTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'authItem' => AuthItemFixture::className(),
        ]);
    }

    public function testEmptyFields()
    {
        $model = new AuthItemSearch();
        $result = $model->search([]);
        $models = $result->getModels();

        expect(count($models))->equals(2);
    }

    public function testSearchByName()
    {
        $model = new AuthItemSearch();
        $result = $model->search([
            'AuthItemSearch' => [
                'name' => 'SuperUser'
            ]
        ]);

        $models = $result->getModels();

        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->name)->equals('SuperUser');
        }
    }
}
