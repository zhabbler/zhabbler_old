<?php
if(!isset($_COOKIE['authCode'])){
    die("USER__LOGIN");
}
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if($user->activated != 1){
    die("<div style='margin:10px 0;color:#f00;'>Вы не можете выполнить данное действие из-за ограничений.</div>");
}
if(!empty($user->reason)){
    die("<div style='margin:10px 0;color:#f00;'>Вы не можете выполнить данное действие из-за ограничений.</div>");
}
if(isset($_POST['id']) && isset($_POST['content'])){
    $content = nl2br(Text::Prepare($_POST['content']));
    $zhabInfo = Zhabs::getZhabInformation($_POST['id']);
    if($zhabInfo->zhabWhoCanComment == 0){
        if(DB::RowCount("SELECT * FROM zhabs WHERE zhabURLID = :id", [":id" => $_POST['id']]) > 0 && !Text::Null($content)){
            DB::Query("INSERT INTO comments(commentBy, commentTo, commentContent) VALUES(:by, :to, :content)", false, false, [":by" => $user->userID, ":to" => $_POST['id'], ":content" => $content]);
            if($user->userID != $zhabInfo->zhabBy){
                DB::Query("INSERT INTO notifications(notificationBy, notificationLinkedWith, notificationTo, notificationActed) VALUES(:by, :linked, :to, :acted)", false, false, [":by" => $user->userID, ":linked" => $zhabInfo->zhabURLID, ":to" => $zhabInfo->zhabBy, ":acted" => 2]);
            }
            die('<div id="postReply">
            <div>
                <a href="/profile/'.$user->nickname.'"><img src="'.$user->profileImage.'" alt="Фотография профиля"></a>
            </div>
            <div id="postReplyContainer">
                <b><a href="/profile/'.$user->nickname.'">'.$user->nickname.'</a></b>
                '.$content.'
            </div>
        </div>');
        }
    }
}