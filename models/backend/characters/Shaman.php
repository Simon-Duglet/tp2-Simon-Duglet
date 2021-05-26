<?php


namespace app\models\backend\characters;


use app\models\backend\core\PlayableCharacter;

class Shaman extends PlayableCharacter
{
    public function __construct(int $strength, int $defense, int $health, int $maxHealth)
    {
        $this->name = "Shaman";
        $this->strength = $strength;
        $this->defense = $defense;
        $this->health = $health;
        $this->maxHealth = $maxHealth;
    }

    function attack(): array
    {
        $this->nb_attack++;
        if ($this->nb_attack == 2){
            $this->nb_attack = 0;
            $this->addHealth(rand(10,100));
            $this->specialReady = true;
        }
        return [
            "damage"=>$this->strength + rand(0,10)
        ];
    }

    function specialPower(): array|bool
    {
        if (!$this->specialReady){
            return false;
        }
        $this->nb_attack = 0;
        $this->specialReady = false;
        return [
            "stun"=>true,
        ];
    }

    function defense(): int
    {
        return rand(0, $this->defense);
    }

    function addHealth($health)
    {
        if ($this->health < $this->maxHealth){
            $this->health += $health;
        }
        $this->health = ($this->health > $this->maxHealth ? $this->maxHealth : $this->health);
    }

    function takeDamage($damage): int
    {
        $damage_taken = $damage - rand(0, $this->defense);
        $damage_taken = ($damage_taken < 0 ? 0 : $damage_taken);
        $this->health -= $damage_taken;
        if ($this->health < 0 ){
            $this->health = 0;
        }
        return $damage_taken;
    }
}