<?php


namespace app\models\backend\characters;

use app\models\backend\core\PlayableCharacter;

class Berserker extends PlayableCharacter
{
    public function __construct(int $strength, int $defense, int $health, int $maxHealth)
    {
        $this->name = "Berserker";
        $this->strength = $strength;
        $this->defense = $defense;
        $this->health = $health;
        $this->maxHealth = $maxHealth;
        if (!$this->passiveReady && $this->health <= $this->maxHealth / 3)
            $this->passiveReady = true;
    }

    public function attack(): array
    {
        $this->nb_attack++;
        $bonus = 0;
        if ($this->nb_attack == 4){
            $this->nb_attack = 0;
            $this->specialReady = true;
        }
        if ($this->passiveReady){
            $bonus = rand(10,100);
        }
        return [
            "damage"=>$this->strength + rand(0,10) + $bonus
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
        if (!$this->passiveReady && $this->health <= $this->maxHealth / 3)
            $this->passiveReady = true;
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
            "damage"=>$this->strength + rand($this->strength, $this->strength*2) + 1000,
        ];
    }

    function addHealth($health)
    {
        if ($this->health < $this->maxHealth){
            $this->health += $health;
        }
        $this->health = ($this->health > $this->maxHealth ? $this->maxHealth : $this->health);
        if ($this->health > $this->maxHealth /3)
            $this->passiveReady = false;
    }
}