<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/chatHandler.php';
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if(isset($_POST['to']) && !Text::Null($_POST['to'])){
	DB::Query("UPDATE messages SET messageReaded = 1 WHERE messageBy = :by AND messageTo = :to", false, false, [":by" => $_POST['to'], ":to" => $user->userID]);
	echo ChatHandler::showMessages($_POST['to'], $user);
}else{
	echo json_encode(["error_ident" => "NO_GET_METHOD_WITH_TO_INT_FOUNDED_OR_IT_IS_EMPTY"], JSON_UNESCAPED_UNICODE);
}