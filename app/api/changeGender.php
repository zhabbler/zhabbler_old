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
if(isset($_POST['gender'])){
	$gender = intval($_POST['gender']);
	if($_POST['gender'] == 0){
        Alert::PushMessage("Не удалось создать аккаунт.");
        echo 'error';
    }else if($_POST['gender'] == 0 || $_POST['gender'] < 0 || $_POST['gender'] > 3){
        Alert::PushMessage("Не удалось обработать пол.");
        echo 'error';
    }else{
        DB::Query("UPDATE users SET gender = :gender WHERE token = :token", false, false, [":gender" => $gender, ":token" => $user->token]);
        echo 'success';
    }
}