<?php


namespace app\models\backend\core;


use yii\helpers\Html;

abstract class Door
{
    public string $image;
    public string $hint;
    public int $gold_reward;
    public int $health_reward;
    public function __construct()
    {
        $this->image = Html::img("@web/images/door.png", ['class'=>'door-img']);
    }
    public static function createDoor(string $type) : Door{
        switch ($type){
            case "combat":
                return new CombatDoor();
            case "boss":
                return new BossDoor();
            case "gold":
                return new GoldDoor();
            case "heal":
                return new HealingDoor();
            case "empty":
                return new EmptyDoor();
        }
    }
    abstract function openDoor();
    protected static function generateHintType() : ?string{
        $types = [
            "good"=> 70,
            "hidden"=> 15,
            "bad"=>15,
        ];
        $rand = mt_rand(1, (int) array_sum($types));
        foreach ($types as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
        return null;
    }
    abstract function generateHint();
    public static function generateDoorType(int $level_since_boss) {
        $door_types = [];
        if ($level_since_boss === 6){
            $door_types = [
                "boss"=>10,
                "combat"=>30,
                "gold"=>10,
                "heal"=>30,
                "empty"=>20
            ];
        }
        elseif ($level_since_boss === 7){
            $door_types = [
                "boss"=>20,
                "combat"=>28,
                "gold"=>26,
                "heal"=>26,
            ];
        }
        elseif ($level_since_boss === 8){
            $door_types = [
                "boss"=>30,
                "combat"=>24,
                "gold"=>23,
                "heal"=>23,
            ];
        }
        elseif ($level_since_boss === 9){
            $door_types = [
                "boss"=>50,
                "combat"=>18,
                "gold"=>16,
                "heal"=>16,
            ];
        }
        elseif ($level_since_boss === 10){
            $door_types = [
                "boss"=>100,
                "combat"=>0,
                "gold"=>0,
                "heal"=>0,
            ];
        }
        else
        {
            $door_types = [
                "boss"=>0,
                "combat"=>25,
                "gold"=>25,
                "heal"=>25,
                "empty"=>25,
            ];
        }
        $rand = mt_rand(1, (int) array_sum($door_types));
        foreach ($door_types as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }
}