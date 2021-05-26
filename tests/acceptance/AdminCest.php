<?php

use app\tests\fixtures\UserFixture;
use yii\helpers\Url;

class AdminCest
{
    public function _fixtures(){
        return[
            'users'=>[
                'class'=>UserFixture::class,
                'dataFile'=> 'tests/fixtures/data/users.php'
            ]
        ];
    }

    public function ensureThatNormalUserCantAccessAdminPanel(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->fillField('input[name="LoginForm[username]"]', 'normal_user');
        $I->fillField('input[name="LoginForm[password]"]', 'normal_password');
        $I->click('login-button');
        $I->see("Déconnecter");

        $I->am("logged in as a normal user");
        $I->expectTo("be blocked");

        $I->amOnPage(Url::toRoute('/admin/panel'));
        $I->see('You are not allowed to perform this action.');
    }

    public function ensureThatAdminUserCanAccessAdminPanel(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->fillField('input[name="LoginForm[username]"]', 'admin');
        $I->fillField('input[name="LoginForm[password]"]', 'admin');
        $I->click('login-button');
        $I->see("Déconnecter");

        $I->am("logged in as an admin");
        $I->expectTo("access the panel");

        $I->amOnPage(Url::toRoute('/admin/panel'));
        $I->see('Liste des monstres : ');
    }
}
