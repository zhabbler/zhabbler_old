<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if($user->activated != 1){
    Alert::PushMessage("Вы не можете выполнить данное действие из-за ограничений.");
    User::redirect("/");
}
if(!empty($user->reason)){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/banned.php');
    die();
}
if(isset($_POST['biourl'])){
    $biourl = Text::Prepare($_POST['biourl']);
    if(!empty($biourl) && !filter_var($biourl, FILTER_VALIDATE_URL)){
        Alert::PushMessage("То что вы ввели, не является URL-ом вовсе. (URL должен начинаться с http:// или https://)");
    }else if(ctype_space($biourl)){
        Alert::PushMessage("почему ты это делаешь?! сам же знаешь, что я все проверяю хех...");
    }else{
        DB::Query("UPDATE users SET biourl = :url WHERE userID = :id", false, false, [":url" => $biourl, ":id" => $user->userID]);
    }
}
User::redirect("/profile/".$user->nickname);