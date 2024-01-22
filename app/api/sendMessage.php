<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/chatHandler.php';
$user = User::GetUserInfoByAuthCode();
header('Content-Type: application/json');
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if($user->activated != 1){
    die("Вы не можете отправить сообщение");
}
if(!empty($user->reason)){
    die("Вы не можете отправить сообщение");
}
if(isset($_POST['to']) && !Text::Null($_POST['to']) && isset($_POST['message']) && !Text::Null($_POST['message'])){
	ChatHandler::sendMessage($_POST["message"], $_POST['to'], $user);
}else{
	echo json_encode(["error_ident" => "NOT_ISSET_OR_EMPTY_GET_STRINGS"], JSON_UNESCAPED_UNICODE);
}