<?php


namespace app\controllers;


use app\models\Enemy;
use app\models\MonsterForm;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\UploadedFile;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete-monster' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['panel','create-monster','modify-monster','delete-monster'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['panel','create-monster','modify-monster','delete-monster'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function actionPanel(){
        $monsters = Enemy::find()->all();
        return $this->render("panel", ['monsters'=>$monsters]);
    }
    public function actionModifyMonster(){
        $monster = Enemy::findById(yii::$app->request->get("monster_id"));
        $model = new MonsterForm();
        $model->action = "update";
        if ($model->load(Yii::$app->request->post())) {
            $enemy = Enemy::findById($model->monster_id);
            $enemy->name = $model->name;
            $enemy->health = $model->health;
            $enemy->defense = $model->defense;
            $enemy->strength = $model->strength;
            $enemy->save();
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image <> ""){
                $model->image_name = $enemy->id;
                $model->upload();
            }
            return $this->render("modify", ['monster'=>$monster, "model"=>$model, 'message'=>"Le monstre a été mis à jours"]);
        }
        $model->name = $monster->name;
        $model->health = $monster->health;
        $model->defense = $monster->defense;
        $model->strength = $monster->strength;
        $model->image = "";
        return $this->render("modify", ['monster'=>$monster, "model"=>$model, 'message'=>null]);
    }
    public function actionDeleteMonster(){
        $id = Yii::$app->request->post("monster_id");
        Enemy::findById($id)->delete();
        return $this->redirect("/admin/panel");
    }
    public function actionCreateMonster(){
        $model = new MonsterForm();
        $model->action = "create";
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            $enemy = new Enemy();
            $enemy->name = $model->name;
            $enemy->health = $model->health;
            $enemy->defense = $model->defense;
            $enemy->strength = $model->strength;
            $enemy->save();
            $model->image_name = $enemy->id;
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->render("create",['model'=>$model, "message"=>"Le monstre a été ajouté."]);
            }
            else{
                $enemy->delete();
            }
        }
        return $this->render("create",['model'=>$model, "message"=>null]);
    }
}