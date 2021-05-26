<?php


namespace app\controllers;


use app\models\Character;
use app\models\CharacterCard;
use app\models\Game;
use app\models\Item;
use app\models\JWT;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ApiController extends Controller
{
    public $userClass = 'app\models\User';
    public $characterClass = 'app\models\Character';
    public $characterCardClass = 'app\models\CharacterCard';
    public $enableCsrfValidation = true;
    public function init(){
        parent::init();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
    public function behaviors()
    {
        return['verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'characterinfo' => ['GET'],
                'buy'=>['POST'],
            ],
        ]
        ];
    }
    private function getGame(): Game{
        return Game::findById(yii::$app->session->get("game_id"));
    }
    public function actionCharacterinfo()
    {
        $id = Yii::$app->request->get("id");
        $character = Character::findById($id);
        $card = CharacterCard::findIdentity($character->card_id);
        return [
            "character"=>$character,
            "card"=>$card,
        ];
    }
    public function actionBuy(){
        $item = Item::findById(yii::$app->request->post("item_id"));
        $game = $this->getGame();
        $response = [
            "success" =>false,
            "message" => "Vous n'avez pas assez d'or pour acheter : " . $item->name
        ];
        if ($item->price < $game->gold){
            $game->gold -= $item->price;
            $game[$item->type . "_upgrade"] += 1;
            $game->save();
            $response["success"] = true;
            $response["message"] = "Vous avez acheter : " . $item->name;
            $response["gold"] = $game->gold;
            $response[$item->type] = $game[$item->type . "_upgrade"];
        }
        return $response;
    }
}