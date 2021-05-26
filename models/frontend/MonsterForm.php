<?php


namespace app\models;


use Faker\Provider\Image;
use yii\base\Model;

class MonsterForm extends Model
{
    public $name;
    public $health;
    public $defense;
    public $strength;
    public $image = null;
    public $monster_id;
    public string $image_name;
    public string $action;

    public function rules(): array
    {
        if ($this->action === "create"){
            return [
                [['name', 'health','defense','strength','image'], 'required'],
                [['image'],"image","skipOnEmpty"=>false,'extensions'=>'png, jpg', 'maxSize'=>2097152],
            ];
        }
        elseif ($this->action ==="update"){
            return [
                [['name', 'health','defense','strength', 'monster_id'], 'required'],
                [['image'],"image","skipOnEmpty"=>true,'extensions'=>'png, jpg', 'maxSize'=>2097152],
            ];
        }
        return [];
    }
    public function upload(): bool
    {
        if ($this->validate()) {
            $this->image->saveAs('./images/enemies/' . $this->image_name . '.' . $this->image->extension);
            return true;
        } else {
            return false;
        }
    }
}