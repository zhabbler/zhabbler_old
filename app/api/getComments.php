<?php
if(isset($_COOKIE['authCode'])){
    $user = User::GetUserInfoByAuthCode();
}
if(isset($_POST['id'])){
    $zhabInfo = Zhabs::getZhabInformation($_POST['id']);
    if($zhabInfo->zhabWhoCanComment == 1){
        echo '<div class="notify_comn"><div><div><svg xmlns="http://www.w3.org/2000/svg"viewBox="0 0 24 24"><path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10 .002 8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z"></path></svg><div></div><div><span>Возможность комментирования ограничена</span></div></div></div></div>';
    }else{
        foreach(DB::Query("SELECT * FROM comments LEFT JOIN users ON userID = commentBy WHERE commentTo = :id ORDER BY commentID DESC", true, true, [":id" => $_POST['id']]) as $comment){
            if(empty($comment->reason)){
            echo '<div id="postReply" class="reply__'.$comment->commentID.'">
            <div>
                <a href="/profile/'.$comment->nickname.'"><img src="'.$comment->profileImage.'" alt="Фотография профиля"></a>
            </div>
            <div id="postReplyContainer">
                <b><a href="/profile/'.$comment->nickname.'">'.$comment->nickname.'</a></b>
                '.$comment->commentContent.'
            </div>
            '.(isset($_COOKIE['authCode']) && $comment->userID == $user->userID ? '<div id="postReplyRemove" data-remove="'.$comment->commentID.'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
          </svg>
          </div>' : '').'
            '.(isset($_COOKIE['authCode']) && $user->admin == 1 && $user->userID != $comment->userID ? '<div id="postReplyRemove" data-remove="'.$comment->commentID.'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
          </svg>
          </div>' : '').'
        </div>';
            }
        }
    }
}