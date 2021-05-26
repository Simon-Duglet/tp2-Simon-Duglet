<?php


namespace app\controllers;


use app\models\Berserker;
use app\models\Character;
use app\models\Door;
use app\models\Enemy;
use app\models\Game;
use app\models\GameSession;
use app\models\Item;
use app\models\JWT;
use app\models\Shaman;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;

class GameController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'reset-upgrade' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['start','doors','open','combat','shop', 'reset-upgrade'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['start', 'doors','open','combat','shop', 'reset-upgrade'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function checkSession(){
        $session = yii::$app->session;
        if (!isset($session["game_id"])){
            $this->redirect("/site/play");
        }
        else
        {
            $game = Game::findById($session["game_id"]);
            if($game->user_id <> yii::$app->user->id){
                $this->redirect("/site/load");
            }
        }

    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionShop(){
        $items = Item::find()->all();
        return $this->render("shop",["items"=>$items, "gold"=>$this->getGame()->gold]);
    }
    public function actionStart()
    {
        $this->checkSession();
        $this->redirect("/game/doors");
    }
    private function levelPassed(){
        $game = Game::findById(yii::$app->session->get("game_id"));
        if ($game->level_since_boss === 10)
            $game->level_since_boss = 0;
        else
            $game->level_since_boss++;
        $game->current_level++;
        $game->save();
        unset(yii::$app->session["doors"]);
    }
    private function makeDoors(): array
    {
        $game = Game::findById(yii::$app->session->get("game_id"));
        $door_type_1 = $door_type_2 = null;
        while ($door_type_1 === $door_type_2){
            $door_type_1 = Door::generateDoorType($game->level_since_boss);
            $door_type_2 = Door::generateDoorType($game->level_since_boss);
            if ($game->level_since_boss === 10){
                break;
            }
        }
        return[
            0=>Door::createDoor($door_type_1),
            1=>Door::createDoor($door_type_2),
        ];
    }
    public function actionOpen(){
        $door_clicked = null;

        if (is_null(yii::$app->session["current_door"])) {
            if (is_null(yii::$app->request->post("door_index")) || yii::$app->request->post("door_index") < 0 || yii::$app->request->post("door_index") > 1) {
                return $this->redirect("/game/doors");
            }
        }
        else{
            $door_clicked = yii::$app->session->get("current_door");
        }
        if (is_null($door_clicked)){
            $door_clicked = yii::$app->request->post("door_index");
        }
        $game = Game::findById(yii::$app->session->get("game_id"));

        $doors = yii::$app->session->get("doors");
        $door = $doors[$door_clicked];
        $result = $door->openDoor();
        yii::$app->session->set("door_clicked", $door_clicked);
        if (array_key_exists("empty",$result)){
            $this->levelPassed();
            yii::$app->session->set("door_message", "Il n'y a rien dans cette pièce..");
            $this->redirect("/game/doors");
        }
        elseif (array_key_exists("healing", $result)){
            $game->current_health += $door->health_reward;
            $character = Character::findById($game->character_id);
            if ($game->current_health > $game->maxHealth){
                $game->current_health = $game->maxHealth;
            }
            yii::$app->session->set("door_message", "Vous avez trouvé quelque chose pour vous soigner.\nVous récupérez " . $door->health_reward . " points de vie.");
            $game->save();
            $this->levelPassed();
            return $this->redirect("/game/doors");
        }
        elseif (array_key_exists("gold", $result)){
            $game->gold += $door->gold_reward;
            $game->save();
            yii::$app->session->set("door_message", "Vous avez trouvé des pièces d'or.\nVous récupérez " . $door->gold_reward . " or.");
            $this->levelPassed();
            return $this->redirect("/game/doors");
        }
        if (array_key_exists("combat", $result)){
            if (array_key_exists("boss", $result)){
                yii::$app->session->set("boss",true);
            }
            yii::$app->session->set("current_door",$door_clicked);
            return $this->redirect("/game/combat");
        }
        return null;
    }
    private function getGame(): Game | null
    {
        return Game::findById(yii::$app->session->get("game_id"));
    }
    public function actionCombat(){

        if (is_null(yii::$app->session["current_door"])){
            return $this->redirect("/game/doors");
        }

        $game = $this->getGame();
        $player = $game->getCharacter();
        if (is_null(yii::$app->session["player"]) || is_null(yii::$app->session["enemy"]) ){
            if (isset(yii::$app->session["boss"])){
                $enemy = $game->generateBoss();
            }
            else
                $enemy = $game->generateEnemy();
            yii::$app->session->set("player", $player);
            yii::$app->session->set("enemy", $enemy);
        }
        else{
            $enemy = yii::$app->session->get("enemy");
        }
        return $this->render("combat", ["player"=> $player, "message"=>"Vous entrez dans la pièce et appercevez un ennemi...", "enemy"=>$enemy]);
    }
    public function actionDoors()
    {
        //unset(yii::$app->session["doors"]);
        if (isset(yii::$app->session["current_door"])){
            $this->redirect("/game/open");
        }
        if (isset(yii::$app->session["doors"])){
            return $this->render('doors', ["doors"=>yii::$app->session->get("doors"), "message"=>null]);
        }
        $game = $this->getGame();
        if (is_null($game)){
            return $this->redirect("/site/play");
        }
        $doors = $this->makeDoors();
        yii::$app->session->set("doors", $doors);
        if (isset(yii::$app->session["door_message"])){
            $message = yii::$app->session["door_message"];
            unset(yii::$app->session["door_message"]);
            return $this->render('doors', ["doors"=>$doors, "message"=>$message]);
        }
        else
            return $this->render('doors', ["doors"=>$doors, "message"=>null]);
    }
    public function actionResetUpgrade(): \yii\web\Response
    {
        $game = $this->getGame();
        $item_health = Item::findByType("health");
        // TODO : $item_defense = Item::findByType("defense");
        $item_strength = Item::findByType("strength");
        $game->gold += $game->health_upgrade * $item_health->price;
        $game->gold += $game->strength_upgrade * $item_strength->price;
        // TODO : $game->gold += $game->defense_upgrade * $item_defense->price;
        $game->health_upgrade = 0;
        $game->strength_upgrade = 0;
        // TODO: $game->defense_upgrade = 0;
        $game->save();
        return $this->redirect('/game/shop');
    }
}