<?php


namespace app\models;


use yii\base\Model;

class NewGameForm extends Model
{
    public $character_id;
    public function rules()
    {
        return [
            [['character_id'], 'required'],
        ];
    }
    public function newGame(){
        return $this->validate();
    }
}