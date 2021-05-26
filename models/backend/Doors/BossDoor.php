<?php


namespace app\models\backend\doors;


use app\models\backend\core\Door;

class BossDoor extends Door
{

    public function __construct()
    {
        parent::__construct();
        $hint_type = parent::generateHintType();
        switch ($hint_type){
            case "bad":
                $result = rand(0,2);
                $this->hint = match ($result) {
                    0 => (new HealingDoor())->hint,
                    1 => (new CombatDoor())->hint,
                    2 => (new EmptyDoor())->hint,
                };
                break;
            case "hidden":
                $this->hint = "???";
                break;
            case "good":
                $this->generateHint();
                break;
        }
        $this->gold_reward = rand(500, 1500);
    }
    function generateHint()
    {
        $hints = [
            "Quelque chose de térrifant se trouve de l'autre côté."=>34,
            "Il y a une ennemi extrêmement dangereux dans cette pièce."=>33,
        ];
        $rand = mt_rand(1, (int) array_sum($hints));
        foreach ($hints as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                $this->hint = $key;
                break;
            }
        }
    }

    function openDoor()
    {
        return[
            "combat"=>true,
            "boss"=>true,
        ];
    }
}