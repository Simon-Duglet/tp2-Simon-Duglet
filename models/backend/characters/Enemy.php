<?php


namespace app\models\backend\characters;


use yii\db\ActiveRecord;
use yii\helpers\Html;
/**
 * Class Enemy
 * @property $id
 * @property $name
 * @property $strength
 * @property $health
 * @property $defense
 * @property $boss
 */
class Enemy extends ActiveRecord
{
    public bool $is_stunned = false;
    public static function tableName()
    {
        return 'enemies';
    }
    public static function findById($id)
    {
        return self::findOne($id);
    }
    public static function getBosses(): array
    {
        return self::findAll(["boss"=>true]);
    }
    public function stun(){
        $this->is_stunned = true;
    }
    public static function getRandomEnemy() : Enemy
    {
        $enemies = self::findAll(["boss"=>false]);
        return $enemies[array_rand($enemies)];
    }

    public static function getRandomBoss(): Enemy
    {
        $enemies = self::findAll(["boss"=>true]);
        return $enemies[array_rand($enemies)];
    }

    public function getImage(): string
    {
        return Html::img("@web/images/enemies/$this->id.png", ['class'=>'character-img']);
    }
    public function attack():array
    {
        return [
            "damage"=>$this->strength + rand(0,9)
        ];
    }
    public function takeDamage($damage): int{
        $damage_taken = $damage - rand(0, $this->defense);
        $damage_taken = ($damage_taken < 0 ? 0 : $damage_taken);
        $this->health -= $damage_taken;
        if ($this->health < 0 ){
            $this->health = 0;
        }
        if ($this->is_stunned)
            $this->is_stunned = false;
        return $damage_taken;
    }
    public function defense(): int
    {
        return rand(0,$this->defense);
    }
}