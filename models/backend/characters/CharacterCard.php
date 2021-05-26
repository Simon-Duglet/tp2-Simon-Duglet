<?php


namespace app\models\backend\characters;


use yii\db\ActiveRecord;
/**
 * Class Character
 * @property $id
 * @property $resume
 * @property $power_active_desc
 * @property $power_passive_desc
 */
class CharacterCard extends ActiveRecord
{
    public static function tableName()
    {
        return 'character_cards';
    }
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }
}