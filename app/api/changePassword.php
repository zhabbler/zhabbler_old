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
if(isset($_POST['password']) && isset($_POST['getID'])){
	$password = $_POST['password'];
	$getID = $_POST['getID'];
	if(DB::RowCount("SELECT * FROM passwordChanger WHERE passwordChangerTo = :to AND passwordChangerGetID = :getID", [":to" => $user->userID, ':getID' => $getID]) == 0){
	    Alert::PushMessage("Не удалось изменить пароль");
	}else{
		if(!Text::Null($password)){
			if(strlen($password) < 6){
            	Alert::PushMessage("Ваш пароль должен быть больше 6 символов!");
        	}else{
				DB::Query("DELETE FROM auths WHERE authToken = :token", false, false, [":token" => $user->token]);
				DB::Query("UPDATE users SET password = :password WHERE userID = :userID", false, false, [":password" => password_hash($password, PASSWORD_DEFAULT), ":userID" => $user->userID]);
				DB::Query("DELETE FROM passwordChanger WHERE passwordChangerTo = :to", false, false, [":to" => $user->userID]);
				Alert::PushMessageSuccess("Пароль успешно изменён. Войдите в свою учётную запись снова с новым паролем.");
			}
		}else{
			Alert::PushMessage("Введите пароль!");
		}
	}
	User::redirect("/letters/password/".$_POST['getID']);
}else{
	Alert::PushMessage("Не удалось изменить пароль");
}
User::redirect("/");