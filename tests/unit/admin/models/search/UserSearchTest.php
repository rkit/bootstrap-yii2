<?php

namespace app\tests\unit\admin\models\search;

use app\tests\fixtures\User as UserFixture;
use app\modules\admin\models\search\UserSearch;
use app\models\User;

class UserSearchTest extends \Codeception\Test\Unit
{
    // @codingStandardsIgnoreFile
    protected function _before()
    {
        $this->tester->haveFixtures([
             'user' => UserFixture::class,
        ]);
    }

    public function testEmptyFields()
    {
        $model = new UserSearch();
        $result = $model->search([]);
        $models = $result->getModels();

        expect(count($models))->equals(6);
    }

    public function testSearchByUsername()
    {
        $model = new UserSearch();
        $result = $model->search([
            'UserSearch' => [
                'username' => 'user-2'
            ]
        ]);

        $models = $result->getModels();

        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->username)->equals('user-2');
        }
    }

    public function testSearchByEmail()
    {
        $model = new UserSearch();
        $result = $model->search([
            'UserSearch' => [
                'email' => 'user-2@example.com'
            ]
        ]);

        $models = $result->getModels();

        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->email)->equals('user-2@example.com');
        }
    }

    public function testSearchByIp()
    {
        $model = new UserSearch();
        $result = $model->search([
            'UserSearch' => [
                'ip' => '127.0.0.1'
            ]
        ]);

        $models = $result->getModels();

        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->username)->equals('user-3');
        }
    }

    public function testSearchByDateCreate()
    {
        $model = new UserSearch();
        $result = $model->search([
            'UserSearch' => [
                'date_create' => '2015-01-02'
            ]
        ]);

        $models = $result->getModels();

        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->username)->equals('user-2');
        }
    }

    public function testSearchByRole()
    {
        $model = new UserSearch();
        $result = $model->search([
            'UserSearch' => [
                'role' => 'Editor'
            ]
        ]);

        $models = $result->getModels();

        expect(count($models))->equals(1);
        foreach ($models as $model) {
            expect($model->role)->equals('Editor');
        }
    }

    public function testSearchByStatus()
    {
        $model = new UserSearch();
        $result = $model->search([
            'UserSearch' => [
                'status' => User::STATUS_ACTIVE
            ]
        ]);

        $models = $result->getModels();

        expect(count($models))->equals(4);
        foreach ($models as $model) {
            expect($model->status)->equals(User::STATUS_ACTIVE);
        }
    }
}
