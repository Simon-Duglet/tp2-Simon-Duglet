<?php


namespace app\models\backend\doors;


use app\models\backend\core\Door;

class HealingDoor extends Door
{
    public function __construct()
    {
        parent::__construct();
        $hint_type = parent::generateHintType();
        switch ($hint_type){
            case "bad":
                $result = rand(0,2);
                $this->hint = match ($result) {
                    0 => (new GoldDoor())->hint,
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
        $this->gold_reward = 0;
        $this->health_reward = rand(10, 100);
    }

    function openDoor(): array
    {
        return[
          "healing"=>true
        ];
    }

    function generateHint()
    {
        $hints = [
            "Un docteur semble être dans la pièce."=>34,
            "Il semble y avoir quelque chose pour se soigner."=>33,
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