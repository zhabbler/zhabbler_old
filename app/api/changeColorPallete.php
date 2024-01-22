<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if($user->activated != 1){
    Alert::PushMessage("Вы не можете выполнить данное действие из-за ограничений.");
    User::redirect("/");
}
if(isset($_POST['color'])){
    $colors = array("#13b552", "#1362b5", "#940fd7", "#f200ff", "#d5d218", "#ef6900", "#ff1515", "#909090");
    if(in_array($_POST['color'], $colors)){
        DB::Query("UPDATE users SET colorPallete = :pallete WHERE token = :token", false, false, [":pallete" => $_POST['color'], ":token" => $user->token]);
        echo $_POST['color'];
    }else{
        echo "error";
    }
}