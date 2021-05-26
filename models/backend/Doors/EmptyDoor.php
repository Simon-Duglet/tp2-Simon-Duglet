<?php


namespace app\models\backend\doors;


use app\models\backend\core\Door;

class EmptyDoor extends Door
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
                    2 => (new GoldDoor())->hint,
                };
                break;
            case "hidden":
                $this->hint = "???";
                break;
            case "good":
                $this->generateHint();
        }
    }
    public function openDoor()
    {
        return[
            "message"=>"Il n'y a rien dans cette pièce...",
            "empty"=>true,
        ];
    }

    public function generateHint()
    {
        $hints = [
            "Aucun bruit ne vien de cette porte."=>34,
            "Rien ne semble être de l'autre côté."=>33,
            "Cette pièce est vide."=>33
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