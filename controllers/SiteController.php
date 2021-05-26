<?php

namespace app\controllers;

use app\models\Character;
use app\models\Game;
use app\models\NewGameForm;
use app\models\RegisterForm;
use app\models\User;
use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\frontend\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = true;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        if (!$session->isActive){
            $session->open();
        }
        return $this->render('index');
    }
    public function actionMigrateUp()
    {
        // https://github.com/yiisoft/yii2/issues/1764#issuecomment-42436905
        $oldApp = \Yii::$app;
        new \yii\console\Application([
            'id'            => 'Command runner',
            'basePath'      => '@app',
            'components'    => [
                'db' => $oldApp->db,
                'authManager' => [
                    'class' => 'yii\rbac\DbManager',
                ],
            ],
        ]);
        \Yii::$app->runAction('migrate/up', ['migrationPath' => '@yii/rbac/migrations', 'interactive' => false]);
        \Yii::$app->runAction('migrate/up', ['interactive' => false]);
        \Yii::$app = $oldApp;
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    /**
     * Display characters page
     *
     *
     */
    public function actionPlay(){
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        else{
            $user = Yii::$app->user;
            $saves = User::findIdentity($user->id)->savedGames;
            if (count($saves) === 0){
                return $this->redirect("/site/game");
            }
            else{
                unset(yii::$app->session["doors"]);
                unset(yii::$app->session["current_door"]);
                unset(yii::$app->session["player"]);
                unset(yii::$app->session["enemy"]);
                return $this->redirect("/site/load");
            }
        }
    }
    public function actionAdmin(){
        return $this->render('phpMyAdmin/index');
    }
    public function actionNewgame(){
        if (Yii::$app->user->isGuest) {
            return $this->redirect("/site/login");
        }
        $model = new NewGameForm();
        $model->load(Yii::$app->request->post());
        $user = Yii::$app->user;
        $game = Game::createNewGame($user->id, $model->character_id);
        $game->current_health = Character::findById($game->character_id)->health;
        $game->save();
        Yii::$app->session->set("game_id", $game->id);
        return $this->redirect("/game/start");
    }
    public function actionLoad(){
        if (Yii::$app->request->post("game_id"))
        {
            $game_id = Yii::$app->request->post("game_id");
            $game = Game::findById($game_id);
            $user_id = Yii::$app->user->id;
            if ($user_id === $game->user_id)
            {
                Yii::$app->session->set("game_id", $game_id);
                $this->redirect("/game/start");
            }
        }
        $user = Yii::$app->user;
        $saves = User::findIdentity($user->id)->savedGames;
        return $this->render('load', ['saves'=>$saves]);
    }
    public function actionGame(){
        $characters = Character::find()->all();
        $model = new NewGameForm();

        return $this->render("charaters", ["characters"=>$characters, "model"=>$model]);
    }
    public function actionRegister(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            return $this->goBack();
        }
        return $this->render('register', ['model'=>$model]);
    }
}
