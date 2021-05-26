<?php


namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $password;
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    public function register(){
        $user = new User();
        $user->setPassword($this->password);
        $user->username = $this->username;
        $user->save();
        return $this->validate();
    }
    public function validatePassword(){
        return true;
    }
}