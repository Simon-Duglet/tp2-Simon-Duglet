<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegisterForm */
$form = ActiveForm::begin([
    'id' => 'login-form',
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]);
$this->title = 'Créer un compte';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<p>Veuillez remplir tous les champs ci-dessous</p>
<?= $form->field($model, 'username')->textInput(['autofocus' => true])->label("Nom d'utilisateur") ?>
<?= $form->field($model, 'password')->passwordInput()->label("Mot de passe") ?>
<div class="col-lg-offset-1 col-lg-11">
    <?= Html::submitButton('Créer un compte', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
</div>
<?php ActiveForm::end(); ?>