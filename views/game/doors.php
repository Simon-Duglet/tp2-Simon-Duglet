<?php
/* @var $this yii\web\View */
/* @var $doors Door[] */
/* @var $message string|null */

use app\models\Door;
$showMessage = !is_null($message);
$lvl = \app\models\Game::findById(yii::$app->session->get("game_id"))->current_level
?>
<style>
    .door-img{
        height: 300px;
        width: 200px;
    }
    .doors-container {
        margin-right: auto;
        margin-left: auto;
        display: flex;
        justify-content: space-between;
        width: 50%;
    }
    .door{
        display: flex;
        flex-direction: column;
        cursor: pointer;
        align-items: center;
    }
    .door>span{
        text-align: center;
        width: 200px;
        color: white;
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
    #shop-btn{
        text-decoration: none;
        color: white;
        padding: 10px 40px;
        background-color: #16cc16;
        font-size: 1.8rem;
        border-radius: 1pc;
    }
    .wrap{
        background-color: #223559;
    }
    #lvl{
        color: white;
    }
</style>
<div>
    <?php
        if ($showMessage){
            echo ("
                <div class='message-container'>
                    <span id='message'>$message</span>
                </div>
            ");
        }
    ?>
    <h1 id="lvl">Niveau : <?= $lvl ?></h1>
    <div class="doors-container">
    <?php
    $i = 0;
    foreach ($doors as $door){
        $image = $door->image;
        echo ("
        <div class='door' onclick='doorClicked($i)'>
                $image
                <span>$door->hint</span>
        </div>
    ");
        $i++;
    }
    ?>
    </div>
    <a id="shop-btn" href="/game/shop">Boutique</a>
    <form method="post" action="/game/open">
        <input type="hidden" name="door_index" id="door_index" value="">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" id="csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input type="submit" hidden id="submit-btn">
    </form>
</div>
<script>
    function doorClicked(index){
        document.getElementById('door_index').value = index
        document.getElementById("submit-btn").click()
    }
    <?php
        if ($showMessage){
            echo ("
                setTimeout(()=>{
                    document.getElementsByClassName('message-container')[0].style.opacity = 0;
                    setTimeout(()=>{
                        document.getElementsByClassName('message-container')[0].style.display = 'none';
            
                    }, 3000);
                }, 3000);          
            ");
        }
    ?>
</script>
