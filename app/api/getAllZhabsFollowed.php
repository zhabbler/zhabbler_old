<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
Zhabs::getAllZhabsFollowed($user->userID, $_POST['lastid']);
?>