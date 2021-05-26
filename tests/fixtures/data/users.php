<?php
return [
    [
        "username"=>"normal_user",
        "password"=>Yii::$app->security->generatePasswordHash("normal_password"),
    ],
    [
        "username"=>"admin",
        "password"=>Yii::$app->security->generatePasswordHash("admin")
    ]

];