<?php


namespace app\models\backend\characters;


use app\models\backend\core\PlayableCharacter;

class Simon extends PlayableCharacter
{

    public function __construct(int $strength, int $defense, int $health, int $maxHealth)
    {
        $this->name = "Simon";
        $this->strength = $strength;
        $this->defense = $defense;
        $this->health = $health;
        $this->maxHealth = $maxHealth;
        $this->passiveReady = true;
    }

    public function attack(): array
    {
        $this->nb_attack++;
        if ($this->nb_attack == 4){
            $this->specialReady = true;
        }
        if ($this->passiveReady){
            $this->defense += 2;
        }
        return [
            "damage"=>$this->strength + rand(0,10)
        ];
    }

    public function defense(): int
    {
        return rand(0, $this->defense);
    }

    public function takeDamage($damage) : int
    {
        $damage_taken = $damage - rand(0, $this->defense);
        $damage_taken = ($damage_taken < 0 ? 0 : $damage_taken);
        $this->health -= $damage_taken;
        if ($this->health < 0 ){
            $this->health = 0;
        }
        return $damage_taken;
    }

    function specialPower(): array|bool
    {
        if (!$this->specialReady){
            return false;
        }
        $this->nb_attack = 0;
        $this->specialReady = false;
        return [
            "defense"=>rand(2,5),
        ];
    }

    function addHealth($health)
    {
        if ($this->health < $this->maxHealth){
            $this->health += $health;
        }
        $this->health = ($this->health > $this->maxHealth ? $this->maxHealth : $this->health);
    }
}