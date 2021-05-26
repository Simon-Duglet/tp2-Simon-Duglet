<?php

/* @var $this yii\web\View */
/* @var $saves Game[] */

use app\models\Game;

$this->title = 'My Yii Application';
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>
<?php echo "Parties sauvegardées : " . count($saves)?>
<div class="saves-container">
    <?php
        foreach ($saves as $save){
            $character_name = \app\models\Character::findById($save->character_id)->name;
            $lvl = $save->current_level;
            $gold = $save->gold;

            echo ("
                <form method='post' action='/site/load'>
                    <input type='hidden' id='csrf' name='$csrfParam' value='$csrfToken' required>
                    <input type='hidden' id='game_id' name='game_id' value='' required>
                    <input id='form-btn' type='submit' hidden>
                </form>
                <div class='saves' id='$save->id'>
                    <span>$character_name</span>
                    <span>niveau : $lvl</span>
                    <button onclick='loadGame(this.parentNode.getAttribute(\"id\"))'>Charger</button>
                </div>       
            ");
        }
    ?>
</div>
<a href="/site/game/">Nouvelle partie</a>
<script>
    function loadGame(id){
        document.getElementById("game_id").value = id;
        document.getElementById("form-btn").click();
    }
</script>