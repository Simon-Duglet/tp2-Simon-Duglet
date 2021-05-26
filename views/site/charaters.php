<?php

/* @var $this yii\web\View */
/* @var $characters \app\models\Character[] */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\NewGameForm */
use yii\helpers\Html;
use yii\bootstrap;
use yii\widgets\ActiveForm;
$this->title = 'My Yii Application';
$this->registerCssFile("@web/css/characters-page.css");
?>
<style>

</style>
<div>
    <h1>Nouvelle partie</h1>
    <p>Veuillez choisir un personnage parmit ceux proposés.</p>
</div>
<div class="characters-container">
    <?php
        $i = 0;
         foreach ($characters as $character ){
            $image = Html::img("@web/images/characters/$character->name.png", ['class'=>'character-img']);
            if ($i === 0){
                echo ("
                    <div class=\"character-thumbnail selected-character\" character-id=\"$character->id\" onclick=\"characterSelected(this)\">
                        $image
                        <span>$character->name</span>
                    </div>
                ");
            }
            else{
                echo ("
                    <div class=\"character-thumbnail\" character-id=\"$character->id\" onclick=\"characterSelected(this)\">
                        $image
                        <span>$character->name</span>
                    </div>
                ");
            }
            $i++;
         }
    ?>

</div>
<div>
    <h3>Description du personnage</h3>
    <p class="character-resume">Redoutable guérr, le Berserker est doté d'une grande puissance pouvant écraser ses ennemis.</p>
    <span><b>Pouvoir actif :</b> <span class="character-special1">Capable d'affliger une attaque surpuissante à l'ennemis sans qu'elle puisse être bloquée </span></span>
    <br>
    <span><b>Pouvoir passif :</b> <span class="character-special2">Augmentation des dégats lorsque la vie est faible</span>
        <p>Statistiques : </p>
        <ul class="character-stats">
            <li>Force : <span class="stat-strength"></span></li>
            <li>Vie : <span class="stat-health"></span></li>
            <li>Défense : <span class="stat-defense"></span></li>
        </ul>
            <?php $form = ActiveForm::begin([
                'id' => 'new-game-form',
                'action'=>'/site/newgame'
            ]); ?>
            <?= $form->field($model, 'character_id')->hiddenInput()->label(false) ?>
            <input name="start-btn" type="submit" value="Commencer">
        <?php ActiveForm::end(); ?>

</div>
<script src="/js/characters-page.js"></script>
<script id="init">
    characterSelected(document.getElementsByClassName("character-thumbnail")[0]);
</script>