<?php

use app\models\User;
use yii\db\Migration;


/**
 * Class m210429_195845_init_rbac
 */
class m210429_195845_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;
        $adminRole = $auth->createRole("admin");
        $auth->add($adminRole);

        $admin = new User();
        $admin->username = "admin";
        $admin->setPassword("admin");;
        $admin->save();
        $auth->assign($adminRole, $admin->id);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \Yii::$app->authManager->removeAll();
        $admin = User::findByUsername("admin");
        if ($admin !== false){
            $admin->delete();
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210429_195845_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
