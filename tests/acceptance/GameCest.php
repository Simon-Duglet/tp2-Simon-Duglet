<?php

use app\tests\fixtures\UserFixture;
use yii\helpers\Url;


class GameCest
{
    public function _before(AcceptanceTester $I)
    {
    }
    public function _fixtures(){
        return[
            'users'=>[
                'class'=>UserFixture::class,
                'dataFile'=> 'tests/fixtures/data/users.php'
            ]
        ];
    }
    private function normalLogin($I){

    }

    public function ensureThatUserCantAccessCombatWhenNotReady(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->fillField('input[name="LoginForm[username]"]', 'normal_user');
        $I->fillField('input[name="LoginForm[password]"]', 'normal_password');
        $I->click('login-button');
        $I->see("Déconnecter (normal_user)");

        $I->amOnPage('/game/combat');
        $I->see("Veuillez choisir un personnage parmit ceux proposés.");
    }
    public function showMessageWhenLoosing(AcceptanceTester $I)
    {

        $I->amOnPage(Url::toRoute('/site/login'));
        $I->fillField('input[name="LoginForm[username]"]', 'normal_user');
        $I->fillField('input[name="LoginForm[password]"]', 'normal_password');
        $I->click('login-button');
        $I->see("Déconnecter (normal_user)");

        $I->amOnPage(Url::toRoute('/site/play'));

       // $I->see("Veuillez choisir un personnage parmit ceux proposés.");
        //$I->fillField('input[name="NewGameForm[character_id]"]', 1);
       // $I->click('start-btn');
    }
}
