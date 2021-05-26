<?php


namespace app\tests\fixtures;


use yii\test\ActiveFixture;
use app\models\User;
class UserFixture extends ActiveFixture
{
    public $modelClass = User::class;
}