<?php

namespace tests\codeception\functional;

use tests\codeception\_pages\admin\LoginPage;
use app\models\User;

class UserListCest
{
    public $I;

    public function _before($I)
    {
        $login = LoginPage::openBy($I);
        $login->login('admin', 'fghfgh');

        $this->I = $I;
    }

    public function testSort($I, $scenario)
    {
        $I->wantTo('admin/users, check sorting');

        $this->I->amOnPage('/admin/users');

        $this->I->click('Username');
        $this->I->seeCurrentUrlMatches('/sort=username/');
        $this->I->see('admin@example.com');

        $this->I->click('Email');
        $this->I->seeCurrentUrlMatches('/sort=email/');
        $this->I->see('admin@example.com');

        $this->I->click('Date create');
        $this->I->seeCurrentUrlMatches('/sort=date_create/');
        $this->I->see('admin@example.com');

        $this->I->click('IP');
        $this->I->seeCurrentUrlMatches('/sort=ip/');
        $this->I->see('admin@example.com');

        $this->I->click('Role');
        $this->I->seeCurrentUrlMatches('/sort=role/');
        $this->I->see('admin@example.com');

        $this->I->click('Status');
        $this->I->seeCurrentUrlMatches('/sort=status/');
        $this->I->see('admin@example.com');
    }

    public function testFilter($I, $scenario)
    {
        $I->wantTo('admin/users, check filter');

        $this->I->amOnPage('/admin/users/index?UserSearch[username]=admin');
        $this->I->see('admin@example.com');

        $this->I->amOnPage('/admin/users/index?UserSearch[email]=admin');
        $this->I->see('admin@example.com');

        $this->I->amOnPage('/admin/users/index?UserSearch[date_create]=2015-01-01');
        $this->I->see('admin@example.com');

        $this->I->amOnPage('/admin/users/index?UserSearch[ip]=127.0.0.1');
        $this->I->see('example@example.com');

        $this->I->amOnPage('/admin/users/index?UserSearch[role]=' . User::ROLE_SUPERUSER);
        $this->I->see('admin@example.com');

        $this->I->amOnPage('/admin/users/index?UserSearch[status]=' . User::STATUS_ACTIVE);
        $this->I->see('admin@example.com');

        $this->I->amOnPage('/admin/users/index?UserSearch[status]=' . User::STATUS_BLOCKED);
        $this->I->see('example-blocked@example.com');

        $this->I->amOnPage('/admin/users/index?UserSearch[status]=' . User::STATUS_DELETED);
        $this->I->see('example-deleted@example.com');
    }

    public function testFilterIncorrent($I, $scenario)
    {
        $I->wantTo('admin/users, check filter with incorrect data');

        $this->I->amOnPage('/admin/users/index?UserSearch[username]=jeremy');
        $this->I->see('No results found');

        $this->I->amOnPage('/admin/users/index?UserSearch[email]=jeremy');
        $this->I->see('No results found');

        $this->I->amOnPage('/admin/users/index?UserSearch[date_create]=2015-01-02');
        $this->I->see('No results found');

        $this->I->amOnPage('/admin/users/index?UserSearch[ip]=127.0.0.2');
        $this->I->see('No results found');
    }

    public function testBlock($I, $scenario)
    {
        $I->wantTo('admin/users, check block');
        $this->I->sendAjaxPostRequest('/admin/users/block?id=2');

        $this->I->amOnPage('/admin/users/index?UserSearch[status]=' . User::STATUS_BLOCKED);
        $this->I->see('example@example.com');
    }

    public function testActivate($I, $scenario)
    {
        $I->wantTo('admin/users, check activate');
        $this->I->sendAjaxPostRequest('/admin/users/activate?id=2');

        $this->I->amOnPage('/admin/users/index?UserSearch[status]=' . User::STATUS_ACTIVE);
        $this->I->see('example@example.com');
    }

    public function testDelete($I, $scenario)
    {
        $I->wantTo('admin/users, check delete');
        $this->I->sendAjaxPostRequest('/admin/users/delete?id=5');

        $this->I->amOnPage('/admin/users');
        $this->I->dontSee('example-5@example.com');
    }

    public function testOperations($I, $scenario)
    {
        $I->wantTo('admin/users, check operations');

        $this->I->sendAjaxPostRequest('/admin/users/operations', [
            'operation' => 'block',
            'selection' => [6]
        ]);

        $this->I->amOnPage('/admin/users/index?UserSearch[status]=' . User::STATUS_BLOCKED);
        $this->I->see('example-6@example.com');

        $this->I->sendAjaxPostRequest('/admin/users/operations', [
            'operation' => 'activate',
            'selection' => [6]
        ]);

        $this->I->amOnPage('/admin/users/index?UserSearch[status]=' . User::STATUS_ACTIVE);
        $this->I->see('example-6@example.com');

        $this->I->sendAjaxPostRequest('/admin/users/operations', [
            'operation' => 'delete',
            'selection' => [6]
        ]);

        $this->I->amOnPage('/admin/users');
        $this->I->dontSee('example-6@example.com');
    }
}
