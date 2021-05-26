<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m210429_191700_User
 */
class m210429_191700_User extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users',[
            'id' => $this->primaryKey()->unsigned(),
            'username' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'authKey' => $this->string(),
            'accessToken' => $this->string(),
        ]);
        $normal_user = new app\models\User();
        $normal_user->username = "normal_user";
        $normal_user->setPassword("normal_password");
        $normal_user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("users");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210429_191700_User cannot be reverted.\n";

        return false;
    }
    */
}
