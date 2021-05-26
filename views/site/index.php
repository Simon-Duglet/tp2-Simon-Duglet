<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<style>
    .transition{
        opacity: 0;
        transition: opacity .5s;
    }
    .game-name{
        color: white !important;
    }
    .lead{
        color: #9bd7f5;
    }
    .play-btn{
        animation-name: play-btn-anim;
        animation-duration: 0.5s;
        animation-iteration-count: infinite;
        animation-direction: alternate;
        color: #d0d0d0;
        padding-right: 50px !important;
        padding-left: 50px !important;
    }
    .play-btn > .play-icon{
        box-sizing: border-box;
        border-style: solid;
        width: 40px;
        height: 40px;
        border-width: 25px 0 25px 45px;
        border-color: transparent transparent transparent white;
    }
    @keyframes play-btn-anim {
        0%
        {
            background-color: #3dc721;

        }
        100%
        {
            background-color: #4ed04e;
            transform: scale(1.02);
        }
    }
</style>
<div class="site-index">
    <div class="jumbotron" style="background-color: #1e3757">
        <h1 class="game-name">Vikings</h1>
        <p class="lead">Appuyer sur le bouton pour commencer</p>

        <a class="btn play-btn" href="/site/play"><div class="play-icon"></div></a>
    </div>

        </div>

    </div>
</div>
