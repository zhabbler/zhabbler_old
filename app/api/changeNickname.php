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
if(isset($_POST['nickname'])){
    $nickname = Text::Prepare($_POST['nickname']);
    if($nickname == $user->nickname){
        User::redirect("/profile/".$user->nickname);
    }
    if(!Text::Null($nickname)){
        if(strlen($nickname) < 4 || strlen($nickname) > 20){
            Alert::PushMessage("Ваш никнейм короткий или слишком длинный!");
        }else if(preg_match("/[^a-zA-Z0-9\!]/", $nickname)){
            Alert::PushMessage("В никнейме допустимо только латинские символы и числа!");
        }else if(User::CheckNickname($nickname) === true){
            Alert::PushMessage("Такой никнейм уже используется другим пользователем!");
        }else{
            DB::Query("UPDATE users SET nickname = :nickname WHERE userID = :id", false, false, [":nickname" => $nickname, ":id" => $user->userID]);
            User::redirect("/profile/".$nickname);
            die();
        }
    }else{
        Alert::PushMessage("Никнейм не должен быть пустым!");
    }
}
User::redirect("/profile/".$user->nickname);