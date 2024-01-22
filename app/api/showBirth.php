<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if(isset($_POST['ShowBirthDate'])){
	$ShowBirthDate = 1;
}else{
	$ShowBirthDate = 0;
}
if(!empty($user->reason)){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/banned.php');
    die();
}
DB::Query("UPDATE users SET showBirth = :showBirth WHERE token = :token", false, false, [":showBirth" => $ShowBirthDate, ":token" => $user->token]);
User::redirect("/settings");