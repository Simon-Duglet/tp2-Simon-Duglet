<?php


use yii\db\Migration;

/**
 * Handles the creation of table `{{%characters}}`.
 */
class m210517_200156_create_characters_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%characters}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'strength' => $this->integer()->notNull()->unsigned(),
            'health' => $this->integer()->notNull()->unsigned(),
            'defense' => $this->integer()->notNull()->unsigned(),
            'card_id' => $this->integer()->notNull()->unsigned(),
        ]);
        $this->createIndex(
            'idx-character-card',
            'characters',
            'card_id'
        );
        $this->addForeignKey(
            'fk-character-card',
            'characters',
            'card_id',
            'character_cards',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%characters}}');
    }
}
