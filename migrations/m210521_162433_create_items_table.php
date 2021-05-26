<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%items}}`.
 */
class m210521_162433_create_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull(),
            'price'=>$this->integer()->unsigned()->notNull(),
            'bonus'=>$this->integer()->unsigned()->notNull(),
            'type'=>$this->string()->notNull()->unique(),
            'desc'=>$this->string()->notNull(),
        ]);
        $strength = new \app\models\Item();
        $strength->name = "Shake de protéine";
        $strength->price = 1000;
        $strength->bonus = 5;
        $strength->type = "strength";
        $strength->desc = "Chaque shake de protéine achetés augmente votre force de 5 points.";

        $health = new \app\models\Item();
        $health->name = "Une pomme";
        $health->price = 2000;
        $health->bonus = 10;
        $health->type = "health";
        $health->desc = "<i>Une pomme par jour éloigne le médecin pour toujours.</i><br>Chaque pomme augmente votre vie maximale de 10 points.";

        $strength->save();
        $health->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%items}}');
    }
}
