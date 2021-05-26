<?php

namespace app\models\core;

use yii\db\ActiveRecord;
use app\models\Character;
/**
 * Class User
 * @property $id
 * @property $username
 * @property $password
 * @property $authKey
 * @property $accessToken
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public $savedGames = null;
    public static function tableName()
    {
        return 'users';
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = self::findOne($id);
        if ($user){
            $user->savedGames = Game::findByUserId($id);
        }
        return $user;
    }
    public static function getPlayedGames($id){
        return [

        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['accessToken'=>$token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username'=>$username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        \Yii::$app->db->createCommand("set sql_mode = ''")->query();
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
    public function setPassword($password){
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }
}
