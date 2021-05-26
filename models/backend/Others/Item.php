<?php


namespace app\models;


use yii\db\ActiveRecord;
/**
 * Class Item
 * @property $id
 * @property $name
 * @property $price
 * @property $bonus
 * @property $type
 * @property $desc
 */
class Item extends ActiveRecord
{
    public static function tableName()
    {
        return 'items';
    }

    public static function findById(int $id) : Item
    {
        return self::findOne($id);
    }

    public static function findByType(string $type) : Item
    {
        return self::findOne(["type"=>$type]);
    }
}