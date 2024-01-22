<?php
$user = User::GetUserInfoByAuthCode();
echo $user->nickname;