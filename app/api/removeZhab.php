<?php
$user = User::GetUserInfoByAuthCode();
if(isset($_POST['id'])){
    if(DB::RowCount("SELECT * FROM zhabs WHERE zhabURLID = :id", [":id" => $_POST['id']]) > 0){
        $zhabInfo = DB::Query("SELECT * FROM zhabs WHERE zhabURLID = :id", true, false, [":id" => $_POST['id']]);
        if($zhabInfo->zhabBy == $user->userID or $user->admin == 1){
            DB::Query("DELETE FROM zhabs WHERE zhabURLID = :id", false, false, [":id" => $_POST['id']]);
            foreach(DB::Query("SELECT * FROM photos WHERE photoTo = :to", true, true, [':to' => $_POST['id']]) as $photo){
                unlink($_SERVER['DOCUMENT_ROOT'].$photo->photoURL);
            }
            DB::Query("DELETE FROM photos WHERE photoTo = :id", false, false, [":id" => $_POST['id']]);
            foreach(DB::Query("SELECT * FROM videos WHERE videoTo = :to", true, true, [':to' => $_POST['id']]) as $video){
                unlink($_SERVER['DOCUMENT_ROOT'].$video->videoURL);
            }
            DB::Query("DELETE FROM videos WHERE videoTo = :id", false, false, [":id" => $_POST['id']]);
            foreach(DB::Query("SELECT * FROM audios WHERE audioTo = :to", true, true, [':to' => $_POST['id']]) as $audio){
                unlink($_SERVER['DOCUMENT_ROOT'].$audio->audioURL);
            }
            DB::Query("DELETE FROM audios WHERE audioTo = :id", false, false, [":id" => $_POST['id']]);
            DB::Query("DELETE FROM notifications WHERE notificationLinkedWith = :id", false, false, [":id" => $_POST['id']]);
            DB::Query("DELETE FROM likes WHERE likeTo = :id", false, false, [":id" => $_POST['id']]);
        }
    }
}