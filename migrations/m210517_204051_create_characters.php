<?php

use yii\db\Migration;

/**
 * Class m210517_204051_create_characters
 */
class m210517_204051_create_characters extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $cardBerserker = new \app\models\CharacterCard();
        $cardBerserker->resume = "Redoutable guérrier, le Berserker est doté d'une grande puissance pouvant écraser ses enemies.";
        $cardBerserker->power_active_desc = "Capable d'affliger une attaque surpuissante à l'enemies sans qu'elle puisse être bloquée. (Se recharge après 4 tours)";
        $cardBerserker->power_passive_desc = "Augmentation des dégats lorsque la vie est faible";
        $cardBerserker->save();

        $cardShaman = new \app\models\CharacterCard();
        $cardShaman->resume = "Maîtrisant l'art de la magie, le Shaman fait usage de ses dons pour vaincre ses enemies.";
        $cardShaman->power_active_desc = "Peut lancer un sort qui étourdit l'ennemi l'empêchant de jouer pendant un tour. (Se recharge après 4 tours)";
        $cardShaman->power_passive_desc = "Sa vie se regénère de façon aléatoire à chaque 2 tours";
        $cardShaman->save();

        $cardViking = new \app\models\CharacterCard();
        $cardViking->resume = "Courageux et agile combatant, le viking est prêt à tout pour acquérir des richesses.";
        $cardViking->power_active_desc = "Peut attaquer 2 fois pendant le même tour. (Se recharge après 4 tours) ";
        $cardViking->power_passive_desc = "Plus de chance de bloquer une attaque à chaque 2 tours.";
        $cardViking->save();

        $cardSimon = new \app\models\CharacterCard();
        $cardSimon->resume = "Ne sous-estimez pas Simon, bien qu'il n'a pas de bras, ses cornes sont d'excelentes armes et son masque le protège des attaques les plus redoutables.";
        $cardSimon->power_active_desc = "Peut affaiblir la défense de l'ennemi pour le prochain tour. (Se recharge après 4 tours)";
        $cardSimon->power_passive_desc = "La défense immunitaire de Simon augmente de 2 point chaque tours.";
        $cardSimon->save();

        $berserker = new \app\models\Character();
        $berserker->name = "Berserker";
        $berserker->strength = 15;
        $berserker->health = 500;
        $berserker->defense = 10;
        $berserker->card_id = $cardBerserker->id;
        $berserker->save();

        $shaman = new \app\models\Character();
        $shaman->name = "Shaman";
        $shaman->strength = 5;
        $shaman->health = 300;
        $shaman->defense = 10;
        $shaman->card_id = $cardShaman->id;
        $shaman->save();

        $viking = new \app\models\Character();
        $viking->name = "Viking";
        $viking->strength = 10;
        $viking->health = 350;
        $viking->defense = 10;
        $viking->card_id = $cardViking->id;
        $viking->save();

        $simon = new \app\models\Character();
        $simon->name = "Simon";
        $simon->strength = 20;
        $simon->health = 400;
        $simon->defense = 30;
        $simon->card_id = $cardSimon->id;
        $simon->save();
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210517_204051_create_characters cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210517_204051_create_characters cannot be reverted.\n";

        return false;
    }
    */
}
