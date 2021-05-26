<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ennemi}}`.
 */
class m210520_225825_create_enemies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%enemies}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->unique(),
            'defense'=>$this->integer()->unsigned()->notNull(),
            'health'=>$this->integer()->unsigned()->notNull(),
            'strength'=>$this->integer()->unsigned()->notNull(),
            'boss'=>$this->boolean()
        ]);
        $noob = new \app\models\Enemy();
        $noob->name = "noob";
        $noob->boss = false;
        $noob->health = 100;
        $noob->strength = 5;
        $noob->defense = 5;
        $noob->save();

        $slime = new \app\models\Enemy();
        $slime->name = "slime";
        $slime->boss = false;
        $slime->health = 150;
        $slime->strength = 10;
        $slime->defense = 10;
        $slime->save();

        $roche = new \app\models\Enemy();
        $roche->name = "lanceu de roche";
        $roche->boss = false;
        $roche->health = 150;
        $roche->strength = 20;
        $roche->defense = 5;
        $roche->save();

        $trump = new \app\models\Enemy();
        $trump->name = "trump";
        $trump->boss = true;
        $trump->health = 300;
        $trump->strength = 5;
        $trump->defense = 20;
        $trump->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%enemies}}');
    }
}
