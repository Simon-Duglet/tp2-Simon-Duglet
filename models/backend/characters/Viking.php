<?php


namespace app\models\backend\characters;


use app\models\backend\core\PlayableCharacter;

class Viking extends PlayableCharacter
{
    private int $turn_passive = 0;
    public function __construct(int $strength, int $defense, int $health, int $maxHealth)
    {
        $this->name = "Viking";
        $this->strength = $strength;
        $this->defense = $defense;
        $this->health = $health;
        $this->maxHealth = $maxHealth;
    }

    public function attack(): array
    {
        $this->nb_attack++;
        $bonus = 0;
        if ($this->nb_attack == 4){
            $this->nb_attack = 0;
            $this->specialReady = true;
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
        $this->turn_passive++;
        if ($this->turn_passive == 2){
            $this->passiveReady = true;
            $this->turn_passive = 0;
        }
        else
            $this->passiveReady = false;
        if($this->passiveReady){
            $result = rand(0,1);
            if ($result === 1){
                $damage_taken = 0;
            }
            else
                $damage_taken = $damage - rand(0, $this->defense);
        }
        else
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
            "damage"=>($this->strength + rand(0,10)) + ($this->strength + rand(0,10)),
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