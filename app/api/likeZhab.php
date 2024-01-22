<?php
if(!isset($_COOKIE['authCode'])){die("USER__LOGIN");}
$user = User::GetUserInfoByAuthCode();
if(!empty($user->reason)){
    die("banned");
}
Zhabs::likeZhab($_POST['urlID']);