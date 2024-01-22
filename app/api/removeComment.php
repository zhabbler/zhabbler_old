<?php
$user = User::GetUserInfoByAuthCode();
if(isset($_POST['id'])){
    $comment = DB::Query("SELECT * FROM comments WHERE commentID = :id", true, false, [":id" => $_POST['id']]);
    if($comment->commentBy == $user->userID or $user->admin == 1){
        DB::Query("DELETE FROM comments WHERE commentID = :id", false, false, [":id" => $_POST['id']]);
    }else{
        die("error");
    }
}