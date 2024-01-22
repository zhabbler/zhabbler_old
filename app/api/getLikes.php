<?php
if(isset($_POST['id'])){
    foreach(DB::Query("SELECT * FROM likes LEFT JOIN users ON userID = likeBy WHERE likeTo = :id ORDER BY likeID DESC", true, true, [":id" => $_POST['id']]) as $repost){
        if(empty($repost->reason)){
        echo '<a class="followed" href="/profile/'.$repost->nickname.'"><div><img src="'.$repost->profileImage.'"></div><div><b>'.$repost->name.'</b> @'.$repost->nickname.'</div></a>';
        }
    }
}