<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if($user->activated != 1){
    Alert::PushMessage("Вы не можете выполнить данное действие из-за ограничений.");
    die("nothing");
}
if(!empty($user->reason)){
    Alert::PushMessage("Вы не можете выполнить данное действие из-за ограничений.");
    die("nothing");
}
if(isset($_POST['to'])){
    $profile = DB::RowCount("SELECT * FROM users WHERE userID = :to", true, false, [":to" => $_POST['to']]);
    if(!empty($profile->reason)){
        Alert::PushMessage("Произошла ошибка. (error_id=USER_BANNED)");
        echo "nothing";
    }else{
        if(DB::RowCount("SELECT * FROM users WHERE userID = :to", [":to" => $_POST['to']]) > 0){
            echo Follow::FollowToUser($user->userID, $_POST['to'], true);
        }else{
            Alert::PushMessage("Произошла ошибка. (error_id=USER_DOES_NOT_EXIST)");
            echo "nothing";
        }
    }
}