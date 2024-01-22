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
if(isset($_POST['bio'])){
    $bio = Text::Prepare($_POST['bio']);
    if(ctype_space($bio)){
        Alert::PushMessage("Пустое значение биографии!");
    }else{
        DB::Query("UPDATE users SET biography = :bio WHERE userID = :id", false, false, [":bio" => $bio, ":id" => $user->userID]);
    }
}
User::redirect("/profile/".$user->nickname);
