<?php


namespace app\models\backend\core;


use yii\helpers\Html;

abstract class PlayableCharacter
{
    protected string $name;
    public int $health;
    public int $defense;
    public int $strength;
    public int $maxHealth;
    public bool $specialReady = false;
    public bool $passiveReady = false;
    protected int $nb_attack = 0;
    abstract function attack():array;
    abstract function specialPower():array|bool;
    abstract function defense() : int;
    abstract function addHealth($health);
    abstract function takeDamage($damage) : int;
    public function getImage():string
    {
        return Html::img("@web/images/characters/$this->name.png", ['class'=>'character-img']);
    }
}