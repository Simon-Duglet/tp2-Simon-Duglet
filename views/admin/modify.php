<?php
/**
 * @var $monster \app\models\Enemy
 * @var $model \app\models\MonsterForm
 * @var $message string|null
 */

use yii\widgets\ActiveForm;

?>
<style>
    #monster-img > img{
        width: 200px;
        height: 300px;
        object-fit: contain;
    }
    #back-btn{
        background-color: red;
        padding: 10px 20px;
        color: white;
        border-radius: 4px;
        margin-bottom: 20px;
        height: 100px;
        display: block;
        width: max-content;
        height: max-content;
    }
</style>
<a id="back-btn" href="/admin/panel">Retour</a>
<?php
if (!is_null($message)){
    echo "<p style='color: green'>$message</p>";
}
$form = ActiveForm::begin()
?>


<?= $form->field($model, 'image')->fileInput(['multiple' => false,'accept' => 'image/png, image/jpeg'])->label("Image du monstre") ?>
<div id="monster-img">
    <?=$monster->getImage()?>
</div>
<?= $form->field($model, 'name')->textInput()->label("Nom du monstre : ")?>
<?= $form->field($model, 'health')->textInput(['type'=>'number','step'=>'1','min'=>"1"])->label("Vie maximale : ")?>
<?= $form->field($model, 'defense')->textInput(['type'=>'number','step'=>'1','min'=>"1"])->label("Defense : ")?>
<?= $form->field($model, 'strength')->textInput(['type'=>'number','step'=>'1','min'=>"1"])->label("Force : ")?>
<?= $form->field($model, 'monster_id')->hiddenInput(["value"=>$monster->id])->label(false)?>

<button>Mettre à jour</button>

<?php ActiveForm::end() ?>
