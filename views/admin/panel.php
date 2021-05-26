<?php
/**
 * @var $monsters Enemy[]
 */

use app\models\Enemy;
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>
<style>
    .action{
        text-decoration: none;
    }
    .action-container{
        display: flex;
        flex-direction: column;
    }
</style>
<div class="action-container">
    <a class="action" href="create-monster">Créer un monstre</a>
    <p>Liste des monstres : </p>
    <ul>
        <?php
        foreach ($monsters as $monster){
            echo ("
            <li class='monster'>
                <span>$monster->name</span>
                <button onclick='deleteMonster($monster->id)'>Supprimer</button>
                <button onclick='modifyMonster($monster->id)'>Modifier</button>
            </li>
        ");
        }
        ?>
    </ul>
    <form action="/admin/modify-monster" method="get">
        <input type="hidden" name="monster_id" id="modify-monster-id" value="">
        <input type="submit" hidden id="modify-submit">
    </form>
    <form action="/admin/delete-monster" method="post">
        <input type="hidden" name="monster_id" id="delete-monster-id" value="">
        <input type="hidden" name='<?=$csrfParam?>' value="<?=$csrfToken?>">
        <input type="submit" hidden id="delete-submit">
    </form>
</div>
<script>
 function deleteMonster(id){
    document.getElementById("delete-monster-id").value = id;
    document.getElementById("delete-submit").click()
 }
 function modifyMonster(id){
     document.getElementById("modify-monster-id").value = id;
     document.getElementById("modify-submit").click()
 }
</script>