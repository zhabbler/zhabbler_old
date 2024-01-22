<?php
if(isset($_COOKIE['authCode'])){
    $user = User::GetUserInfoByAuthCode();
    echo $user->profileImage;
}else{
    echo '/assets/images/no_avatar_1900.png';
}