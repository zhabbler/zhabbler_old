<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if(DB::RowCount("SELECT * FROM messages WHERE messageReaded = 0 AND messageTo = :to", [":to" => $user->userID]) > 0){
	echo '('.DB::RowCount("SELECT * FROM messages WHERE messageReaded = 0 AND messageTo = :to", [":to" => $user->userID]).')';
}