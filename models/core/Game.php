<?php


namespace app\models\core;

use yii\db\ActiveRecord;
/**
 * Class Game
 * @property $id
 * @property $character_id
 * @property $user_id
 * @property $strength_upgrade
 * @property $health_upgrade
 * @property $defense_upgrade
 * @property $current_level
 * @property $level_since_boss
 * @property $current_health
 * @property $gold
 */
class Game extends ActiveRecord
{
    private PlayableCharacter $player;
    public int $maxHealth;
    public function __construct($config = [])
    {
        parent::__construct($config);

    }
    private function setMaxHealth(){
        $character = Character::findById($this->character_id);
        $this->maxHealth = $character->health + $this->health_upgrade * Item::findByType("health")->bonus;
    }

    public static function findById($id) : Game | null{
        $game = self::findOne($id);
        if (is_null($game))
            return null;
        $game->setMaxHealth();
        return $game;
    }

    public static function tableName()
    {
        return 'games';
    }
    public function getCharacter() : PlayableCharacter | null{
        $character = Character::findById($this->character_id);
        switch ($character->name){
            case "Berserker":
                return new Berserker($character->strength + $this->strength_upgrade * Item::findByType("strength")->bonus, $character->defense + $this->defense_upgrade * 5, $this->current_health, $this->maxHealth);
            case "Shaman":
                return new Shaman($character->strength + $this->strength_upgrade * Item::findByType("strength")->bonus, $character->defense + $this->defense_upgrade * 5, $this->current_health, $this->maxHealth);
            case "Simon":
                return new Simon($character->strength + $this->strength_upgrade * Item::findByType("strength")->bonus, $character->defense + $this->defense_upgrade * 5, $this->current_health, $this->maxHealth);
            case "Viking":
                return new Viking($character->strength + $this->strength_upgrade * Item::findByType("strength")->bonus, $character->defense + $this->defense_upgrade * 5, $this->current_health, $this->maxHealth);
        }
        return null;
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return Game[]
     */
    public static function findByUserId($user_id): array
    {
        return self::findAll(['user_id'=>$user_id]);
    }


    public function generateEnemy() : Enemy
    {
        $enemy = Enemy::getRandomEnemy();
        $enemy->health += $this->current_level * 5;
        $enemy->strength += $this->current_level * 2;
        $enemy->defense += $this->current_level * 2;
        return $enemy;
    }

    public function generateBoss(): Enemy
    {
        $enemy = Enemy::getRandomBoss();
        $enemy->health += $this->current_level * 5;
        $enemy->strength += $this->current_level * 2;
        $enemy->defense += $this->current_level * 2;
        return $enemy;
    }

    public function died()
    {
        $character = Character::findById($this->character_id);
        $this->current_health = $this->maxHealth;
        $this->save();
    }
}