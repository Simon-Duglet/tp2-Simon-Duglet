<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%character_cards}}`.
 */
class m210517_195702_create_character_cards_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%character_cards}}', [
            'id' => $this->primaryKey()->unsigned(),
            'resume'=>$this->string()->notNull(),
            'power_active_desc'=>$this->string()->notNull(),
            'power_passive_desc'=>$this->string()->notNull(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%character_cards}}');
    }
}
