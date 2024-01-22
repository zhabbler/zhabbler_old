<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/chatHandler.php';
$user = User::GetUserInfoByAuthCode();
ChatHandler::showConversations($user);