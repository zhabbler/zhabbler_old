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
if(isset($_POST['email']) && isset($_POST['getID'])){
	$email = $_POST['email'];
	$getID = $_POST['getID'];
	if(DB::RowCount("SELECT * FROM emailChanger WHERE emailChangerTo = :to AND emailChangerGetID = :getID", [":to" => $user->userID, ':getID' => $getID]) == 0){
	    Alert::PushMessage("Не удалось изменить почту");
	}else{
		if(!Text::Null($email)){
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				Alert::PushMessage("Неверная почта");
			}else if($email == $user->email){
				Alert::PushMessage("Почта соответсвует старой почте.");
			}else if(User::CheckEmail($email) === true){
                Alert::PushMessage("Почта уже используется другим пользователем!");
            }else{
				DB::Query("UPDATE users SET email = :email WHERE userID = :userID", false, false, [":email" => $email, ":userID" => $user->userID]);
				DB::Query("DELETE FROM emailChanger WHERE emailChangerTo = :to", false, false, [":to" => $user->userID]);
				Alert::PushMessageSuccess("Почта успешна изменена.");
			}
		}else{
			Alert::PushMessage("Введите почту!");
		}
	}
	User::redirect("/letters/email/".$_POST['getID']);
}else{
	Alert::PushMessage("Не удалось изменить почту");
}
User::redirect("/");