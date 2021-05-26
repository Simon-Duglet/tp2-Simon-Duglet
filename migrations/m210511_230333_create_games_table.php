<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%games}}`.
 */
class m210511_230333_create_games_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%games}}', [
            'id' => $this->primaryKey()->unsigned(),
            'character_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'strength_upgrade' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'health_upgrade' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'defense_upgrade' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'current_level' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'level_since_boss' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'current_health' => $this->integer()->unsigned()->notNull(),
            'gold' => $this->integer()->unsigned()->notNull()->defaultValue(0),
        ]);
        $this->createIndex(
            'idx-user-game',
            'games',
            'user_id'
        );
        $this->addForeignKey(
            'fk-user-game',
            'games',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%games}}');
    }
}
