<?php


namespace app\models\backend\doors;


use app\models\backend\core\Door;

class GoldDoor extends Door
{
    public function __construct()
    {
        parent::__construct();
        $hint_type = parent::generateHintType();
        switch ($hint_type){
            default:
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
        $this->gold_reward = rand(100, 1000);
    }

    function openDoor(): array
    {
        return[
            "gold"=>true
        ];
    }

    function generateHint()
    {
        $hints = [
            "Des richesses ce trouve  l'intérieur."=>34,
            "Il semble y avoir quelques pièces d'or par ici."=>33,
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
}