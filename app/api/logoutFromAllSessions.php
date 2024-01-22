<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
DB::Query("DELETE FROM auths WHERE authToken = :token", false, false, [":token" => $user->token]);