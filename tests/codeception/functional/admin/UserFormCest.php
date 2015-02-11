<?php

namespace tests\codeception\functional;

use tests\codeception\_pages\admin\LoginPage;
use app\models\User;

class UserFormCest
{    
    public $I;
    
    public function _before($I) 
    {
        $login = LoginPage::openBy($I);
        $login->login('admin', 'fghfgh');
        
        $this->I = $I;
    }

    public function testAdd($I, $scenario)
    {
        $I->wantTo('admin/users/edit, check add');
        
        $this->I->amOnPage('/admin/users/edit'); 
        $this->I->selectOption('User[role]', User::ROLE_SUPERUSER);
        $this->I->fillField('input[name="User[username]"]', 'demo2');
        $this->I->fillField('input[name="User[email]"]', 'demo2@example.com');
        $this->I->fillField('input[name="User[passwordNew]"]', 'fghfgh');
        $this->I->click('Сохранить');
        
        $this->I->amOnPage('/admin/users');
        $this->I->see('demo2@example.com');
    }
    
    public function testEdit($I, $scenario)
    {
        $I->wantTo('admin/users/edit, check edit');
        
        $this->I->amOnPage('/admin/users');
        $this->I->click('demo2@example.com');
        $this->I->see(User::ROLE_SUPERUSER);
        $this->I->see('demo2');
        $this->I->see('demo2@example.com');
        $this->I->fillField('input[name="User[email]"]', 'demo3@example.com');
        $this->I->click('Сохранить');
        
        $this->I->amOnPage('/admin/users');
        $this->I->see('demo3@example.com');
    }
    
    public function testRole($I, $scenario)
    {
        $I->wantTo('admin/users/edit, check role');
        
        $this->I->amOnPage('/admin/users');
        $this->I->click('demo3@example.com');
        $this->I->see(User::ROLE_SUPERUSER);
        $this->I->selectOption('User[role]', null);
        $this->I->click('Сохранить');
        
        $this->I->amOnPage('/admin/users');
        $this->I->click('demo3@example.com');
        $this->I->see('Нет роли');
    }
    
    public function testProfile($I, $scenario)
    {
        $I->wantTo('admin/users/edit, check profile'); 
        
        $this->I->amOnPage('/admin/users');
        $this->I->click('demo3@example.com');
        $this->I->click('Профиль');
        $this->I->fillField('input[name="UserProfile[full_name]"]', 'Jeremy');
        $this->I->fillField('input[name="UserProfile[birth_day]"]', '1990-10-10');
        $this->I->click('Сохранить');
        
        $this->I->amOnPage('/admin/users');
        $this->I->click('demo3@example.com');
        $this->I->click('Профиль');
        $this->I->see('Jeremy');
        $this->I->see('1990-10-10');
    }
}
