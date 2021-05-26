<?php


namespace app\models\backend\doors;


use app\models\backend\core\Door;

class CombatDoor extends Door
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
                    1 => (new GoldDoor())->hint,
                    2 => (new EmptyDoor())->hint,
                };
                break;
            case "hidden":
                $this->hint = "???";
                break;
            case "good":
                $this->generateHint();
        }
        $this->gold_reward = rand(0, 1000);
    }
    function generateHint()
    {
        $hints = [
            "Il y a des bruits menaçant derrière cette porte"=>34,
            "Quelque chose d'hostile semble être de l'autre côté."=>33,
            "Un ennemi se trouve de l'autre côté"=>33
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
            "message"=>"Vous ouvrez la porte puis appercevez un enemies...",
            "combat"=>true,
        ];
    }
}