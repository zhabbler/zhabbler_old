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
if(isset($_POST['name'])){
    $name = Text::Prepare($_POST['name']);
    if(!Text::Null($name)){
        if(strlen($name) > 48){
            Alert::PushMessage("Ваше имя слишком длинное!");
        }else{
            DB::Query("UPDATE users SET name = :name WHERE userID = :id", false, false, [":name" => $name, ":id" => $user->userID]);
        }
    }else{
        Alert::PushMessage("Имя не должно быть пустым!");
    }
}
User::redirect("/profile/".$user->nickname);