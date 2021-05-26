<?php
/**
 * @var $player \app\models\PlayableCharacter
 * @var $message string
 * @var $enemy \app\models\Enemy
 */
use yii\helpers\Html;

$enemy_image = $enemy->getImage();
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

?>
<style>
    .combat-container{
        display: flex;
        justify-content: space-between;
    }
    .enemy-container, .player-container{
        float: right;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .player-container > img{
        width: 200px;
        height: 300px;
        object-fit: contain;
        transform: scaleX(-1);
    }
    .enemy-container > img{
        width: 200px;
        height: 300px;
        object-fit: contain;
    }
    .message-container{
        position: absolute;
        width: 100vw;
        height: 100vh;
        background-color: #0f0f0f;
        left: 0;
        top: 0;
        z-index: 1;
        opacity: 1;
        transition-duration: 3s;
        transition-property: opacity;

    }
    #message{
        color: white;
        font-size: 2rem;
        position: absolute;
        width: 100vw;
        height: 100vh;
        text-align: center;
        line-height: 100vh;
    }
    .player-stats, .enemy-stats{
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
        width: 300px;
    }
    button{
        cursor: pointer;
    }
    .combat-message-container{
        display: flex;
        align-items: center;
        font-size: 1.7rem;
        flex-direction: column;

    }
    #combat-message{
        background-color: #c4c4c4;
        color: black;
        border-radius: 4px;
        padding: 10px;
        opacity: 0;
        transition-duration: 1s;
    }
    #quit-btn{
        text-decoration: none;
        color: red;
        background-color: #333;
        padding: 10px 40px;
        border-radius: 1pc;
        display: none;
    }
</style>
<div class="message-container">
    <span id="message"><?= $message ?></span>
</div>
<div class="combat-container">
    <div class="player-container">
        <?= $player->getImage() ?>
        <div class="player-stats">
            <p>Vie : <span id="player-health"><?=$player->health?></span></p>
            <p>Défense : <span id="player-defense"><?=$player->defense?></span></p>
            <p>Force : <span id="player-strength"><?=$player->strength?></span></p>
        </div>
        <div class="player-btns">
            <button onclick="attack(this)">Attaquer</button>
            <button id="special-btn" onclick="specialAttack(this)">Attaque spécial</button>
        </div>
    </div>
    <div class="combat-message-container">
        <p id="combat-message"></p>
        <a id="quit-btn" href="/game/doors">Quitter</a>
    </div>
    <div class="enemy-container">
        <?= $enemy->name ?>
        <?= $enemy_image ?>
        <div class="enemy-stats">
            <p>Vie : <span id="enemy-health"><?=$enemy->health?></span></p>
            <p>Défense : <span id="enemy-defense"><?=$enemy->defense?></span></p>
            <p>Force : <span id="enemy-strength"><?=$enemy->strength?></span></p>
        </div>
    </div>
</div>
<script src="/js/combat.js"></script>
<script>
    initCsrf('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>')
    hideMessage();
</script>