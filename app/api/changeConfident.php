<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
	User::redirect("/");
}
if($user->activated != 1){
    Alert::PushMessage("Вы не можете выполнить данное действие из-за ограничений.");
    User::redirect("/");
}
if(!empty($user->reason)){
	include_once($_SERVER['DOCUMENT_ROOT'].'/views/banned.php');
	die();
}
if(isset($_POST['msgs'])){
	$msgs = (int)$_POST['msgs'];
	if($msgs > -1 && $msgs < 6){
		DB::Query("UPDATE users SET cf_messages = :msgs WHERE userID = :id", false, false, [":msgs" => $msgs, ':id' => $user->userID]);
	}
}
if(isset($_POST['birth'])){
	$birth = (int)$_POST['birth'];
	if($birth > -1 && $birth < 2){
		DB::Query("UPDATE users SET cf_birth = :birth WHERE userID = :id", false, false, [":birth" => $birth, ':id' => $user->userID]);
	}
}