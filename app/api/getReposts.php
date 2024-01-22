<?php
if(isset($_POST['id'])){
    foreach(DB::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabRepliedTo = :id ORDER BY zhabID DESC", true, true, [":id" => $_POST['id']]) as $repost){
        if(empty($repost->reason)){
        echo '<div class="reposts_Posts_in" onclick="getZhabInfo(`'.$repost->zhabURLID.'`)">
        <div class="reposts_Posts_InfoAbAUser">
            <div class="reposts_Posts_A">
                <img src="'.$repost->profileImage.'">
                <div id="repostAvatarIcon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 7a1 1 0 0 0-1-1h-8v2h7v5h-3l3.969 5L22 13h-3V7zM5 17a1 1 0 0 0 1 1h8v-2H7v-5h3L6 6l-4 5h3v6z"></path></svg></div>
            </div>
            <div>
                <b>'.$repost->nickname.'</b>
            </div>
        </div>
        <div>
            <span>'.preg_replace("/<br\W*?\/?>/", " ", $repost->zhabContent).'</span>
        </div>
    </div>';
        }
    }
}