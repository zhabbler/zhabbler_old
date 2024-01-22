<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
echo DB::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy LEFT JOIN follows ON followTo = zhabBy WHERE followBy = :userID", true, false, [":userID" => $user->userID])->zhabID;
?>