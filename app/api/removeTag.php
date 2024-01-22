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
DB::Query("DELETE FROM interested WHERE interestedFor = :for AND interestedIn = :in", false, false, [":for" => $user->userID, ":in" => $_POST['tag']]);
User::redirect("/settings");