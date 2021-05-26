<?php


namespace app\models\backend\characters;


use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class Character
 * @property $id
 * @property $name
 * @property $strength
 * @property $health
 * @property $defense
 * @property $card_id
 */
class Character extends ActiveRecord
{

    public function getImage() : string{
        return Html::img("@web/images/characters/$this->name.png", ['class'=>'character-img']);
    }
    public static function tableName()
    {
        return 'characters';
    }
    public static function findById($id) : Character
    {
        return self::findOne($id);
    }
    public static function findByName($name){

    }

    public function getCard(){
        $card = CharacterCard::findIdentity($this->card_id);
    }
}