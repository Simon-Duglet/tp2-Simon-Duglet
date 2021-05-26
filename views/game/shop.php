<?php
/* @var $this yii\web\View */
/* @var $items \app\models\Item[] */
/* @var $gold int */
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>
<style>

</style>
<div>
    <p>Vous avez : <span id="gold"><?=$gold?></span> or</p>
    <?php
        foreach ($items as $item){
            echo("
                <div class='items'>
                    <button onclick='buy($item->id)'>$item->name</button>
                    <p class='item-desc'>$item->desc</p>
                    <p class='item-price'>Coût : $item->price</p>
                </div>
            ");
        }
    ?>
    <form action="/game/reset-upgrade" method="post">
        <input type="submit" value="Remise à zéro">
        <input type="hidden" name="<?= $csrfParam ?>" value="<?= $csrfToken ?>">
    </form>
</div>
<script>
const csrfParam = "<?= $csrfParam ?>"
const csrfToken = "<?= $csrfToken ?>"
function buy(id){
    let myHeaders = new Headers();
    let fd = new FormData();
    fd.append(csrfParam, csrfToken)
    fd.append("item_id", id)
    const init = { method: 'post',
        headers: myHeaders,
        body: fd
    };
    fetch("http://localhost/api/buy", init).then((res)=>{
        return res.json();
    }).then((data)=>{
        if (data.success){
            document.getElementById("gold").innerText = data.gold
        }
        alert(data.message)
        console.log(data)
    });
}
</script>