<?php
/**
 * @var $model \app\models\MonsterForm
 * @var $message string|null
 */

use yii\widgets\ActiveForm;
use app\models\Enemy;

?>

<?php
if (!is_null($message)){
    echo "<p style='color: green'>$message</p>";
}
$form = ActiveForm::begin()
?>

<?= $form->field($model, 'image')->fileInput(['multiple' => false,'accept' => 'image/png, image/jpeg']) ?>
<?= $form->field($model, 'name')->textInput()->label("Nom du monstre : ")?>
<?= $form->field($model, 'health')->textInput(['type'=>'number','step'=>'1','min'=>"1"])->label("Vie maximale : ")?>
<?= $form->field($model, 'defense')->textInput(['type'=>'number','step'=>'1','min'=>"1"])->label("Defense : ")?>
<?= $form->field($model, 'strength')->textInput(['type'=>'number','step'=>'1','min'=>"1"])->label("Force : ")?>

<button>Submit</button>

<?php ActiveForm::end() ?>
