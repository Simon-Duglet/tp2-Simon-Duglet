<?php


namespace app\controllers;


use app\models\Character;
use app\models\Enemy;
use app\models\Game;
use app\models\PlayableCharacter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class CombatApiController extends Controller
{
    public $enableCsrfValidation = true;
    public function behaviors()
    {
        return['verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
            'attack' => ['POST'],
            'special-attack' => ['POST'],
        ],
        ]
    ];
    }

    public function init(){
        parent::init();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
    private function getPlayer() : PlayableCharacter{
        return yii::$app->session->get("player");
    }
    private function getEnemy() : Enemy{
        return yii::$app->session->get("enemy");
    }
    private function getGame(): Game{
        return Game::findById(yii::$app->session->get("game_id"));
    }
    public function actionSpecialAttack(){
        $player = $this->getPlayer();
        $enemy = $this->getEnemy();
        $damage_taken = 0;
        $damage_given = 0;
        if (is_null($player) || is_null($enemy)){
            return [
              "success" => false
            ];
        }
        $special_result = $player->specialPower();
        if (!$special_result){
            return [
                "success" => false
            ];
        }

        $response = [
            "success"=>true,
            "player_died"=>false,
            "enemy_died"=>false,
            "player"=>[
                "health" => null,
                "defense"=>null,
                "strength"=>null,
            ],
            "enemy"=>[
                "health" => null,
                "defense"=>null,
                "strength"=>null,
            ],
            "message_given"=>0,
            "message_taken"=>0,
        ];
        $game = $this->getGame();
        if (array_key_exists("damage", $special_result)){
            $damage_given = $enemy->takeDamage($special_result["damage"]);
            $enemy_attack_result = $enemy->attack();
            $damage_taken = $enemy_attack_result["damage"];
            $response["message_given"] = "Vous avez lancé votre attaque spéciale, l'ennemi à perdu " . $damage_given . " points de vie";
            $response["message_taken"] = "l'ennemi à lancé une attaque, vous avez perdu " . $damage_taken . " points de vie";
            $game->current_health = $player->health;
            $game->save();
        }
        elseif (array_key_exists("stun", $special_result)){
            $enemy->stun();
            $response["message_given"] = "Vous avez lancé votre attaque spéciale.";
            $response["message_taken"] = "L'ennemi ne peut pas jouer pendant 1 tour.";
        }
        elseif (array_key_exists("defense", $special_result)){
            $enemy->defense -= $special_result["defense"];
            $enemy->defense = ($enemy->defense < 0 ? 0 : $enemy->defense);
            $response["message_given"] = "Vous avez lancé votre attaque spéciale.";
            $response["message_taken"] = "La defense de l'ennemi à été affaiblie.";
        }
        $dead_character = $this->checkIfDied($game, $enemy);
        if ($dead_character == $enemy){
            $response["enemy_died"] = true;
            $door = yii::$app->session["doors"][yii::$app->session->get("current_door")];
            $response["gold_reward"] = $door->gold_reward;
            $game->current_health = $player->health += $damage_taken;
            $game->save();
            $this->unsetCombat();
        }
        elseif ($dead_character == $player){
            $response["player_died"] = true;
            $this->unsetCombat();
        }
        $game->save();
        $response["player"]["health"] = $player->health;
        $response["player"]["defense"] = $player->defense;
        $response["player"]["strength"] = $player->strength;
        $response["enemy"]["strength"] = $enemy->strength;
        $response["enemy"]["defense"] = $enemy->defense;
        $response["enemy"]["health"] = $enemy->health;

        return $response;
    }
    private function checkIfDied($game, $enemy) : PlayableCharacter | Enemy | bool{
        if ($game->current_health <= 0){
            $game->died();
            return $this->getPlayer();
        }
        elseif ($enemy->health <= 0){
            $door = yii::$app->session["doors"][yii::$app->session->get("current_door")];
            $game->gold += $door->gold_reward;
            $game->current_level++;
            if (isset(yii::$app->session["boss"])) {
                $game->level_since_boss = 0;
                unset(yii::$app->session["boss"]);
            }
            else
                $game->level_since_boss++;
            $game->save();
            return $this->getEnemy();
        }
        else
            return false;
    }
    public function actionAttack(): array
    {
        $player = $this->getPlayer();
        $enemy = $this->getEnemy();

        if(is_null($player) || is_null($enemy)){
            return [
              "success"=>false,
            ];
        }
        $timestamp = (new \DateTime())->getTimestamp();
        if (isset(yii::$app->session["last_hit"])){
            $last_timestamp = yii::$app->session["last_hit"];
            $diff = $timestamp - $last_timestamp;
            if ($diff < 2){
                return[
                    "success"=>false,
                ];
            }
        }
        $response = [
            "success"=>false,
            "player_died"=>false,
            "enemy_died"=>false,
            "player"=>[
                "health" => null,
                "defense"=>null,
                "strength"=>null,
            ],
            "enemy"=>[
                "health" => null,
                "defense"=>null,
                "strength"=>null,
            ],
            "message_given"=>null,
            "message_taken"=>null,
        ];

        $player_attack_result = $player->attack();
        if ($enemy->is_stunned)
        {
            $response["message_taken"] = "L'ennemi est étourdit, il ne peut pas attaquer";
        }
        else
        {
            $enemy_attack_result = $enemy->attack();
            $damage_taken = $player->takeDamage($enemy_attack_result["damage"]);
        }


        $damage_given = $enemy->takeDamage($player_attack_result["damage"]);

        $game = $this->getGame();
        $game->current_health = $player->health;
        $game->save();
        $dead_character = $this->checkIfDied($game, $enemy);
        if ($dead_character == $enemy){
            $response["enemy_died"] = true;
            $door = yii::$app->session["doors"][yii::$app->session->get("current_door")];
            $response["gold_reward"] = $door->gold_reward;
            $game->current_health = $player->health += $damage_taken;
            $game->save();
            $this->unsetCombat();
        }
        elseif ($dead_character == $player){
            $response["player_died"] = true;
            $this->unsetCombat();
        }
        $game->save();
        yii::$app->session->set("last_hit",$timestamp) ;
        $response["success"]= true;
        $response["player"]["health"] = $player->health;
        $response["player"]["defense"] = $player->defense;
        $response["player"]["strength"] = $player->strength;
        $response["enemy"]["strength"] = $enemy->strength;
        $response["enemy"]["defense"] = $enemy->defense;
        $response["enemy"]["health"] = $enemy->health;
        $response["message_given"] = "Vous avez lancé une attaque, l'ennemi a perdu " . $damage_given . " points de vie";
        if (is_null($response["message_taken"])){
            $response["message_taken"] = "L'ennemi a lancé une attaque, vous avez perdu " . $damage_taken . " points de vie";
        }
        $response["special_ready"] = $player->specialReady;
        return $response;
    }

    private function unsetCombat()
    {
        unset(yii::$app->session["doors"]);
        unset(yii::$app->session["current_door"]);
        unset(yii::$app->session["player"]);
        unset(yii::$app->session["enemy"]);
    }
}