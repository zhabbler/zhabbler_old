<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if($user->activated != 1){
	if(DB::RowCount("SELECT * FROM emailVerify WHERE emailVerifyFor = :for AND emailVerifyGetID = :id", [":for" => $user->nickname, ":id" => $id]) > 0){
		DB::Query("DELETE FROM emailVerify WHERE emailVerifyFor = :for", false, false, [":for" => $user->nickname]);
		DB::Query("UPDATE users SET activated = 1 WHERE userID = :for", false, false, [":for" => $user->userID]);
		Alert::PushMessageSuccess("Ваша почта успешно подтверждена!");
	}else{
		Alert::PushMessage("Не удалось подтвердить вашу почту.");
	}
	User::redirect("/");
}else{
	echo "denied";
}