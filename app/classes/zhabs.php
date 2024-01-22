<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/date.php';
class Zhabs extends DB{
    public static function checkZhab($zhabURLID){
        if(DB::RowCount("SELECT * FROM zhabs WHERE zhabURLID = :URLID", [":URLID" => $zhabURLID]) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function getZhabInformation($zhabURLID){
        if(self::RowCount("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :URLID AND reason = ''", [":URLID" => $zhabURLID]) > 0){
            return self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :URLID AND reason = ''", true, false, [":URLID" => $zhabURLID]);
        }else{
            die("<div class='popup_container'>Ошибка! Перезагрузите страницу.</div>");
        }
    }

    public static function checkLike($zhabURLID){
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
            if(DB::RowCount("SELECT * FROM likes WHERE likeBy = :userID AND likeTo = :URLID", [
                ":userID" => $user->userID,
                ":URLID" => $zhabURLID
            ]) > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function showPhotos($zhabURLID){
        if(DB::RowCount("SELECT * FROM photos WHERE photoTo = :to", [":to" => $zhabURLID]) > 0){
            $numItems = DB::RowCount("SELECT * FROM photos WHERE photoTo = :to", [":to" => $zhabURLID]);
            $i = 0;
            $photos = '<div class="zhabsPhotos" id="zhabp__'.$zhabURLID.'">';
            foreach(DB::Query("SELECT * FROM photos WHERE photoTo = :to", true, true, [":to" => $zhabURLID]) as $photo){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$photo->photoURL)){
                    if(++$i === 1 && $numItems % 2){
                        $photos .= '<img src="'.$photo->photoURL.'" data-photoid="'.$photo->photoID.'" style="width:100%!important;" class="zhabsPhotoItself">';
                    }else{
                        $photos .= '<img src="'.$photo->photoURL.'" data-photoid="'.$photo->photoID.'" class="zhabsPhotoItself">';
                    }
                }else{
                    DB::Query("DELETE FROM photos WHERE photoID = :id", false, false, [":id" => $photo->photoID]);
                    $photos .= '<style>#zhabp__'.$zhabURLID.'{display:none!important;}</style>';
                }
            }
            $photos .= '</div>';
        }else{
            $photos = '';
        }
        if(DB::RowCount("SELECT * FROM audios WHERE audioTo = :to", [":to" => $zhabURLID]) > 0){
            $numItems = DB::RowCount("SELECT * FROM audios WHERE audioTo = :to", [":to" => $zhabURLID]);
            $i = 0;
            $audios = '<div class="zhabsAudios" id="zhaba__'.$zhabURLID.'">';
            foreach(DB::Query("SELECT * FROM audios WHERE audioTo = :to", true, true, [":to" => $zhabURLID]) as $audio){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$audio->audioURL)){
                    $audios .= '<audio src="'.$audio->audioURL.'" data-audioid="'.$audio->audioID.'" controls></audio>';
                }else{
                    DB::Query("DELETE FROM audios WHERE audioID = :id", false, false, [":id" => $audio->audioID]);
                    $audios .= '<style>#zhaba__'.$zhabURLID.'{display:none!important;}</style>';
                }
            }
            $audios .= '</div>';
        }else{
            $audios = '';
        }
        if(DB::RowCount("SELECT * FROM videos WHERE videoTo = :to", [":to" => $zhabURLID]) > 0){
            $numItems = DB::RowCount("SELECT * FROM videos WHERE videoTo = :to", [":to" => $zhabURLID]);
            $i = 0;
            $videos = '<div class="zhabsVideos" id="zhabv__'.$zhabURLID.'">';
            foreach(DB::Query("SELECT * FROM videos WHERE videoTo = :to", true, true, [":to" => $zhabURLID]) as $video){
                if(file_exists($_SERVER['DOCUMENT_ROOT'].$video->videoURL)){
                    $videos .= '<div data-videoid="'.$video->videoID.'" class="zhabVideo" style="background-image:url('.$video->videoThumbnail.');">'.(Video::updateDuration($video->videoDuration) == "0:00" ? "" : '<span id="duration">'.Video::updateDuration($video->videoDuration).'</span>').'</div>';
                }else{
                    DB::Query("DELETE FROM videos WHERE videoID = :id", false, false, [":id" => $video->videoID]);
                    $videos .= '<style>#zhabv__'.$zhabURLID.'{display:none!important;}</style>';
                }
            }
            $videos .= '</div>';
        }else{
            $videos = '';
        }
        $result = self::checkIsReplied($zhabURLID).$photos.$videos.$audios;
        return $result;
    }

    public static function getNotifiesCount($userID){
        $count = DB::RowCount("SELECT * FROM notifications WHERE notificationTo = :to AND notificationReaded = 0", [":to" => $userID]);
        if($count > 0 && $count < 100){
            return "(".$count.")";
        }else if ($count >= 100){
            return "(99+)";
        }
    }

    public static function getNotifies($userID){
        DB::Query("UPDATE notifications SET notificationReaded = 1 WHERE notificationTo = :id", false, false, [":id" => $userID]);
        if(DB::RowCount("SELECT * FROM notifications LEFT JOIN users ON userID = notificationBy LEFT JOIN zhabs ON zhabURLID = notificationLinkedWith WHERE notificationTo = :to ORDER BY notificationID DESC", [":to" => $userID]) == 0){
            echo '<div id="nOnTFS"><div>
          <div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
          <path fill-rule="evenodd" d="M6.912 3a3 3 0 00-2.868 2.118l-2.411 7.838a3 3 0 00-.133.882V18a3 3 0 003 3h15a3 3 0 003-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0017.088 3H6.912zm13.823 9.75l-2.213-7.191A1.5 1.5 0 0017.088 4.5H6.912a1.5 1.5 0 00-1.434 1.059L3.265 12.75H6.11a3 3 0 012.684 1.658l.256.513a1.5 1.5 0 001.342.829h3.218a1.5 1.5 0 001.342-.83l.256-.512a3 3 0 012.684-1.658h2.844z" clip-rule="evenodd" />
        </svg><div>
          <div>Тут ничего нет...<div>
            </div>
          </div>';
        }
        foreach(DB::Query("SELECT * FROM notifications LEFT JOIN users ON userID = notificationBy LEFT JOIN zhabs ON zhabURLID = notificationLinkedWith WHERE notificationTo = :to ORDER BY notificationID DESC", true, true, [":to" => $userID]) as $notify){
            if($notify->notificationActed == 1){
                echo '<div class="notification" id="ntA1">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                        </svg>
                    </div>
                    <div>
                        <a href="/profile/'.$notify->nickname.'">'.$notify->nickname.'</a> понравился ваш <a onclick="getZhabInfo(`'.$notify->zhabURLID.'`)">пост</a>
                    </div>
                </div>';
            }else if($notify->notificationActed == 2){
                echo '<div class="notification" id="ntA2">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
  <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97zM6.75 8.25a.75.75 0 01.75-.75h9a.75.75 0 010 1.5h-9a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H7.5z" clip-rule="evenodd" />
</svg>

                    </div>
                    <div>
                        <a href="/profile/'.$notify->nickname.'">'.$notify->nickname.'</a> добавил комментарий к вашему <a onclick="getZhabInfo(`'.$notify->zhabURLID.'`)">посту</a>
                    </div>
                </div>';
            }
        }
    } 

    public static function likeZhab($zhabURLID){
        if(self::checkZhab($zhabURLID)){
            $zhab = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :URLID AND reason = ''", true, false, [":URLID" => $zhabURLID]);
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
            if(DB::RowCount("SELECT * FROM likes WHERE likeBy = :userID AND likeTo = :URLID", [
                ":userID" => $user->userID,
                ":URLID" => $zhabURLID
            ]) > 0){
                DB::Query("DELETE FROM likes WHERE likeBy = :userID AND likeTo = :URLID", false, false, [
                    ":userID" => $user->userID,
                    ":URLID" => $zhabURLID
                ]);
                DB::Query("UPDATE zhabs SET zhabLikes = zhabLikes - 1 WHERE zhabURLID = :URLID", false, false, [
                    ":URLID" => $zhabURLID
                ]);
            }else{
                if(DB::RowCount("SELECT * FROM notifications WHERE notificationBy = :by AND notificationLinkedWith = :linkedwith AND notificationActed = 1", [":by" => $user->userID, ":linkedwith" => $zhabURLID]) == 0 && $zhab->userID != $user->userID){
                    DB::Query("INSERT INTO notifications(notificationBy, notificationTo, notificationLinkedWith, notificationActed) VALUES(:by, :to, :linkedwith, 1)", false, false, [":by" => $user->userID, ":to" => $zhab->userID, ":linkedwith" => $zhabURLID]);
                }
                DB::Query("UPDATE zhabs SET zhabLikes = zhabLikes + 1 WHERE zhabURLID = :URLID", false, false, [
                    ":URLID" => $zhabURLID
                ]);
                DB::Query("INSERT INTO likes(likeBy, likeTo) VALUES(:userID, :URLID)", false, false, [
                    ":userID" => $user->userID,
                    ":URLID" => $zhabURLID
                ]);
            }
        }
    }

    public static function checkIsReplied($zhabURLID){
        $zhab = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :URLID AND reason = ''", true, false, [":URLID" => $zhabURLID]);
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        if(!empty($zhab->zhabRepliedTo)){
            if(self::RowCount("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :URLID", [":URLID" => $zhab->zhabRepliedTo]) > 0){
                $zhabReplied = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :URLID", true, false, [":URLID" => $zhab->zhabRepliedTo]);
                return '<div id="repost" class="rezhabby__'.$zhab->zhabURLID.' rezhab__'.$zhabReplied->zhabURLID.' RepliedTrue">
                <div style="border-radius: 3px;color: #000;width: -webkit-fill-available!important;width: -moz-available!important;width: fill-available!important;">
                    '.($zhab->zhabBy != $zhabReplied->zhabBy ? '<div id="postAuthor">
                        <a href="https://zhabbler.ru/profile/'.$zhabReplied->nickname.'" id="repostAvatar">
                            <img src="'.$zhabReplied->profileImage.'" alt="Фотография профиля">
                        </a>
                        <a href="/profile/'.$zhabReplied->nickname.'" id="postAuthorItself">
                    '.$zhabReplied->nickname.($zhabReplied->verifed == 1 ? "<span id='verifed'></span>" : "").'
                </a>
                        '.(isset($user) ? Follow::followButton($user->userID, $zhabReplied->userID, true) : '').'
                        <span id="SYSt200a">
                            '.date_parse($zhabReplied->zhabUploaded)['day']." ".Date::month(date_parse($zhabReplied->zhabUploaded)['month'])." ".date_parse($zhabReplied->zhabUploaded)['year'].'
                        </span>
                    </div>' : '').'
                    <div id="postContentItself" onclick="getZhabInfo(`'.$zhabReplied->zhabURLID.'`)">
                        '.self::showPhotos($zhabReplied->zhabURLID).'
                        <h1>
                        '.$zhabReplied->zhabTitle.'
                        </h1>
                        <div id="ff0000FFF">
                        '.$zhabReplied->zhabContent.'
                        </div>
                    </div>
                    <div id="postAuthor">
                        <a href="https://zhabbler.ru/profile/'.$zhab->nickname.'" id="repostAvatar">
                            <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
                            <div id="repostAvatarIcon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 7a1 1 0 0 0-1-1h-8v2h7v5h-3l3.969 5L22 13h-3V7zM5 17a1 1 0 0 0 1 1h8v-2H7v-5h3L6 6l-4 5h3v6z"></path></svg></div>
                        </a>
                        <a href="https://zhabbler.ru/profile/'.$zhab->nickname.'" id="postAuthorItself">
                            '.$zhab->nickname.'
                        </a>
                        '.(isset($user) ? Follow::followButton($user->userID, $zhab->userID, true) : '').'
                        <span id="SYSt200a">
                            '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                        </span>
                    </div>
                </div>
            </div>';
            }
        }
    }

    public static function GetZhabItself($id){
        if(DB::RowCount("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :id AND reason = ''", [":id" => $id]) == 0){
            die("Ошибка! Не удалось получить пост.");
        }
        $zhab = DB::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabURLID = :id AND reason = ''", true, false, [":id" => $id]);
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        echo '<div id="post" class="zhab__'.$zhab->zhabURLID.'">
        <a href="/profile/'.$zhab->nickname.'" id="postAvatar" style="top:10px!important;">
            <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
        </a>
        <div id="postContent">
            <div id="postAuthor">
                <a href="/profile/'.$zhab->nickname.'" id="postAuthorItself">
                    '.$zhab->nickname.($zhab->verifed == 1 ? "<span id='verifed'></span>" : "").'
                </a>
                '.(isset($user) ? Follow::followButton($user->userID, $zhab->userID, true) : '').'
                <span id="SYSt200a">
                    '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                </span>
            </div>
            <div id="postContentItself">'.self::showPhotos($zhab->zhabURLID).'
                <h1>
                '.$zhab->zhabTitle.'
                </h1>
                <div id="ff0000FFF">
                '.$zhab->zhabContent.'
                </div>
            </div>
            <div id="postInfo">
                <div id="postInfoCounter">
                    <span id="postInfoCounterItself">'.DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [
                        ":URLID" => $zhab->zhabURLID
                    ]).'</span> лайк'.(DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 0 ? 'ов' : (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) < 5 ? (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 1 ? '' : 'а') : 'ов')).'
                </div>
                <div id="postActionsItself">
                    <div id="postAction">
                        <svg onclick="openDropdown(`#GlobalIdentificator__'.$zhab->zhabURLID.'`)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="SysDropdown" style="display:none;" id="GlobalIdentificator__'.$zhab->zhabURLID.'">
                            <div class="SysDropdownElement" onclick="shareLink(`'.$zhab->zhabURLID.'`)">
                                Поделиться ссылкой
                            </div>
                            '.(isset($_COOKIE['authCode']) ? ($user->nickname != $zhab->nickname ? ($user->admin == 1 ? '<div class="SysDropdownElement" onclick="removeZhab(`'.$zhab->zhabURLID.'`, false)" style="color:#f00;">
                        Удалить
                    </div>' : '') :
                        '<div class="SysDropdownElement" onclick="removeZhab(`'.$zhab->zhabURLID.'`, false)" style="color:#f00;">
                        Удалить
                    </div>') : '').'
                        </div>                                                                              
                    </div>
                    <div id="postAction" data-arialabel="Репост" class="SystemReply" data-replyto="'.$zhab->zhabURLID.'">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                        </svg>                                                                            
                    </div>
                    <div id="postAction" data-arialabel="Комментарии" onclick="repliesZhab(`'.$zhab->zhabURLID.'`)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                        </svg>                                                    
                    </div>
                    <div id="postAction" data-arialabel="Нравится" class="SystemLike '.(self::checkLike($zhab->zhabURLID) ? 'SystemLikeTrue' : '').'" onclick="likeZhab(`'.$zhab->zhabURLID.'`)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>                              
                    </div>
                </div>
            </div>
        </div>
    </div>';
    }

    public static function getAllZhabs($lastID){
        if($lastID != 0){
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabID < :id AND reason = '' ORDER BY zhabID DESC LIMIT 16", true, true, [":id" => $lastID]);
        }else{
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE reason = '' ORDER BY zhabID DESC LIMIT 16", true, true, []);
        }
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        $counter = 1;
        foreach($zhabs as $zhab){
            if ($counter%4 == 1){  
                 echo "<div>";
            }
            echo '<div id="post" class="zhab__'.$zhab->zhabURLID.' RealZhab" realid="'.$zhab->zhabID.'">
            <a href="/profile/'.$zhab->nickname.'" id="postAvatar">
                <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
            </a>
            <div id="postContent">
                <div id="postAuthor">
                    <a href="/profile/'.$zhab->nickname.'" id="postAuthorItself">
                    '.$zhab->nickname.($zhab->verifed == 1 ? "<span id='verifed'></span>" : "").'
                </a>
                    '.(isset($user) ? Follow::followButton($user->userID, $zhab->userID, true) : '').'
                    <span id="SYSt200a">
                        '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                    </span>
                </div>
                <div id="postContentItself">
                '.self::showPhotos($zhab->zhabURLID).'
                    <h1>
                    '.$zhab->zhabTitle.'
                    </h1>
                    <div id="ff0000FFF">
                    '.$zhab->zhabContent.'
                    </div>
                </div>
                <div id="postInfo">
                    <div id="postInfoCounter">
                        <span id="postInfoCounterItself">'.DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [
                            ":URLID" => $zhab->zhabURLID
                        ]).'</span> лайк'.(DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 0 ? 'ов' : (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) < 5 ? (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 1 ? '' : 'а') : 'ов')).'
                    </div>
                    <div id="postActionsItself">
                        <div id="postAction">
                            <svg onclick="openDropdown(`#GlobalIdentificator__'.$zhab->zhabURLID.'`)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="SysDropdown" style="display:none;" id="GlobalIdentificator__'.$zhab->zhabURLID.'">
                                <div class="SysDropdownElement" onclick="shareLink(`'.$zhab->zhabURLID.'`)">
                                    Поделиться ссылкой
                                </div>
                                '.(isset($_COOKIE['authCode']) ? ($user->nickname != $zhab->nickname ? '
                            ' :
                            '<div class="SysDropdownElement" onclick="removeZhab(`'.$zhab->zhabURLID.'`, false)" style="color:#f00;">
                            Удалить
                        </div>') : '').'
                            </div>                                                                              
                        </div>
                        <div id="postAction" data-arialabel="Репост" class="SystemReply" data-replyto="'.$zhab->zhabURLID.'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>                                                                            
                        </div>
                        <div id="postAction" data-arialabel="Комментарии" onclick="repliesZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                            </svg>                                                    
                        </div>
                        <div id="postAction" data-arialabel="Нравится" class="SystemLike '.(self::checkLike($zhab->zhabURLID) ? 'SystemLikeTrue' : '').'" onclick="likeZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>';
            if ($counter%4 == 0){
                echo "</div>";
            }
            $counter++;
        }
    }

    public static function getAllZhabsPopular($lastID){
        if($lastID != 0){
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabID < :id AND reason = '' ORDER BY zhabLikes DESC LIMIT 7", true, true, [":id" => $lastID]);
        }else{
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE reason = '' ORDER BY zhabLikes DESC LIMIT 7", true, true, []);
        }
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        foreach($zhabs as $zhab){
            
            echo '<div id="post" class="zhab__'.$zhab->zhabURLID.' RealZhab" realid="'.$zhab->zhabID.'">
            <a href="/profile/'.$zhab->nickname.'" id="postAvatar">
                <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
            </a>
            <div id="postContent">
                <div id="postAuthor">
                    <a href="/profile/'.$zhab->nickname.'" id="postAuthorItself">
                    '.$zhab->nickname.($zhab->verifed == 1 ? "<span id='verifed'></span>" : "").'
                </a>
                    '.(isset($user) ? Follow::followButton($user->userID, $zhab->userID, true) : '').'
                    <span id="SYSt200a">
                        '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                    </span>
                </div>
                <div id="postContentItself">
                '.self::showPhotos($zhab->zhabURLID).'
                    <h1>
                    '.$zhab->zhabTitle.'
                    </h1>
                    <div id="ff0000FFF">
                    '.$zhab->zhabContent.'
                    </div>
                </div>
                <div id="postInfo">
                    <div id="postInfoCounter">
                        <span id="postInfoCounterItself">'.DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [
                            ":URLID" => $zhab->zhabURLID
                        ]).'</span> лайк'.(DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 0 ? 'ов' : (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) < 5 ? (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 1 ? '' : 'а') : 'ов')).'
                    </div>
                    <div id="postActionsItself">
                        <div id="postAction">
                            <svg onclick="openDropdown(`#GlobalIdentificator__'.$zhab->zhabURLID.'`)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="SysDropdown" style="display:none;" id="GlobalIdentificator__'.$zhab->zhabURLID.'">
                                <div class="SysDropdownElement" onclick="shareLink(`'.$zhab->zhabURLID.'`)">
                                    Поделиться ссылкой
                                </div>
                                '.(isset($_COOKIE['authCode']) ? ($user->nickname != $zhab->nickname ? '
                            ' :
                            '<div class="SysDropdownElement" onclick="removeZhab(`'.$zhab->zhabURLID.'`, false)" style="color:#f00;">
                            Удалить
                        </div>') : '').'
                            </div>                                                                              
                        </div>
                        <div id="postAction" data-arialabel="Репост" class="SystemReply" data-replyto="'.$zhab->zhabURLID.'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>                                                                            
                        </div>
                        <div id="postAction" data-arialabel="Комментарии" onclick="repliesZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                            </svg>                                                    
                        </div>
                        <div id="postAction" data-arialabel="Нравится" class="SystemLike '.(self::checkLike($zhab->zhabURLID) ? 'SystemLikeTrue' : '').'" onclick="likeZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }
    }

    public static function getAllZhabsLiked($by){
        $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy LEFT JOIN likes ON likeTo = zhabURLID WHERE likeBy = :by AND reason = '' ORDER BY zhabID DESC", true, true, [":by" => $by]);
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        if(DB::RowCount("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy LEFT JOIN likes ON likeTo = zhabURLID WHERE likeBy = :by AND reason = '' ORDER BY zhabID DESC", [":by" => $by]) == 0){
            echo '<div class="flex ai-c jsc-c">
                Тут пусто...
            </div>';
        }
        foreach($zhabs as $zhab){
            echo '<div id="post" class="zhab__'.$zhab->zhabURLID.' RealZhab" realid="'.$zhab->zhabID.'">
            <a href="/profile/'.$zhab->nickname.'" id="postAvatar">
                <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
            </a>
            <div id="postContent">
                <div id="postAuthor">
                    <a href="/profile/'.$zhab->nickname.'" id="postAuthorItself">
                    '.$zhab->nickname.($zhab->verifed == 1 ? "<span id='verifed'></span>" : "").'
                </a>
                    '.(isset($user) ? Follow::followButton($user->userID, $zhab->userID, true) : '').'
                    <span id="SYSt200a">
                        '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                    </span>
                </div>
                <div id="postContentItself">
                '.self::showPhotos($zhab->zhabURLID).'
                    <h1>
                    '.$zhab->zhabTitle.'
                    </h1>
                    <div id="ff0000FFF">
                    '.$zhab->zhabContent.'
                    </div>
                </div>
                <div id="postInfo">
                    <div id="postInfoCounter">
                        <span id="postInfoCounterItself">'.DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [
                            ":URLID" => $zhab->zhabURLID
                        ]).'</span> лайк'.(DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 0 ? 'ов' : (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) < 5 ? (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 1 ? '' : 'а') : 'ов')).'
                    </div>
                    <div id="postActionsItself">
                        <div id="postAction">
                            <svg onclick="openDropdown(`#GlobalIdentificator__'.$zhab->zhabURLID.'`)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="SysDropdown" style="display:none;" id="GlobalIdentificator__'.$zhab->zhabURLID.'">
                                <div class="SysDropdownElement" onclick="shareLink(`'.$zhab->zhabURLID.'`)">
                                    Поделиться ссылкой
                                </div>
                                '.(isset($_COOKIE['authCode']) ? ($user->nickname != $zhab->nickname ? '
                            ' :
                            '<div class="SysDropdownElement" onclick="removeZhab(`'.$zhab->zhabURLID.'`, false)" style="color:#f00;">
                            Удалить
                        </div>') : '').'
                            </div>                                                                              
                        </div>
                        <div id="postAction" data-arialabel="Репост" class="SystemReply" data-replyto="'.$zhab->zhabURLID.'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>                                                                            
                        </div>
                        <div id="postAction" data-arialabel="Комментарии" onclick="repliesZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                            </svg>                                                    
                        </div>
                        <div id="postAction" data-arialabel="Нравится" class="SystemLike '.(self::checkLike($zhab->zhabURLID) ? 'SystemLikeTrue' : '').'" onclick="likeZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }
    }

    public static function getAllZhabsFollowed($by, $lastID){
        global $locale;
        if($lastID != 0){
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy LEFT JOIN follows ON followTo = zhabBy WHERE zhabID < :id AND followBy = :by AND reason = '' ORDER BY zhabID DESC LIMIT 7", true, true, [":id" => $lastID, ":by" => $by]);
        }else{
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy LEFT JOIN follows ON followTo = zhabBy WHERE followBy = :by AND reason = '' ORDER BY zhabID DESC LIMIT 7", true, true, [":by" => $by]);
        }
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        if(DB::RowCount("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy LEFT JOIN follows ON followTo = zhabBy WHERE followBy = :by AND reason = ''", [":by" => $by]) == 0){
            echo '<div class="ui__M570Qm__IMa6SK">
                <div class="ui__DsX6sw__OKcL66">
                    <div class="ui__2At4Y8__Ydd86u">
                        <div class="ui__HOF4jG__XUQpHc">
                            <div class="ui__XnBqh0__MKx1ak">
                                <span class="ui__LB22qZ__jXVScv"></span>
                                <div class="ui__L3cy7J__dBIxKH">
                                    <span class="ui__7wR1Vh__rL6nhO"></span>
                                    <span class="ui__gv0c67__S1C3Hd"></span>
                                </div>
                            </div>
                        </div>
                        <div class="ui__HOF4jG__XUQpHc">
                            <div class="ui__XnBqh0__MKx1ak">
                                <span class="ui__tkLKli__ru06iM"></span>
                                <div class="ui__L3cy7J__dBIxKH">
                                    <span class="ui__qw455f__re0BWL"></span>
                                    <span class="ui__JinNxQ__J3pBJx">
                                        <svg version="1.1" class="ui__wVIcW2__Lr2GKR" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve"><polygon fill="#FFFFFF" points="8.2,20.9 8.2,4.9 19.8,16.5 13,16.5 12.6,16.6 "/><polygon fill="#FFFFFF" points="17.3,21.6 13.7,23.1 9,12 12.7,10.5 "/><rect x="12.5" y="13.6" transform="matrix(0.9221 -0.3871 0.3871 0.9221 -5.7605 6.5909)" width="2" height="8"/><polygon points="9.2,7.3 9.2,18.5 12.2,15.6 12.6,15.5 17.4,15.5 "/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="ui__HOF4jG__XUQpHc">
                            <div class="ui__XnBqh0__MKx1ak">
                                <span class="ui__LB22qZ__jXVScv"></span>
                                <div class="ui__L3cy7J__dBIxKH">
                                    <span class="ui__7wR1Vh__rL6nhO"></span>
                                    <span class="ui__gv0c67__S1C3Hd"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui__uBxRB1__iNP91c">
                        <h1 class="ui__7uDtoV__cLcmFu">
                        Тут как-то пусто... Но это можно исправить!
                        </h1>
                        <h4 class="ui__Y7Dznb__6GRJQ3">
                            Зайди на <a href="/explore">страницу обзора</a> (или в блоке "На кого подписаться?") и подпишись на тех людей которые тебе понравились.<br><br>
                        </h4>
                    </div>
                </div>
            </div>';
        }
        foreach($zhabs as $zhab){
            echo '<div id="post" class="zhab__'.$zhab->zhabURLID.' RealZhab" realid="'.$zhab->zhabID.'">
            <a href="/profile/'.$zhab->nickname.'" id="postAvatar">
                <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
            </a>
            <div id="postContent">
                <div id="postAuthor">
                    <a href="/profile/'.$zhab->nickname.'" id="postAuthorItself">
                    '.$zhab->nickname.($zhab->verifed == 1 ? "<span id='verifed'></span>" : "").'
                </a>
                    '.(isset($user) ? Follow::followButton($user->userID, $zhab->userID, true) : '').'
                    <span id="SYSt200a">
                        '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                    </span>
                </div>
                <div id="postContentItself">
                '.self::showPhotos($zhab->zhabURLID).'
                    <h1>
                    '.$zhab->zhabTitle.'
                    </h1>
                    <div id="ff0000FFF">
                    '.$zhab->zhabContent.'
                    </div>
                </div>
                <div id="postInfo">
                    <div id="postInfoCounter">
                        <span id="postInfoCounterItself">'.DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [
                            ":URLID" => $zhab->zhabURLID
                        ]).'</span> лайк'.(DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 0 ? 'ов' : (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) < 5 ? (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 1 ? '' : 'а') : 'ов')).'
                    </div>
                    <div id="postActionsItself">
                        <div id="postAction">
                            <svg onclick="openDropdown(`#GlobalIdentificator__'.$zhab->zhabURLID.'`)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="SysDropdown" style="display:none;" id="GlobalIdentificator__'.$zhab->zhabURLID.'">
                                <div class="SysDropdownElement" onclick="shareLink(`'.$zhab->zhabURLID.'`)">
                                    Поделиться ссылкой
                                </div>
                                '.(isset($_COOKIE['authCode']) ? ($user->nickname != $zhab->nickname ? '
                            ' :
                            '<div class="SysDropdownElement" onclick="removeZhab(`'.$zhab->zhabURLID.'`, false)" style="color:#f00;">
                            Удалить
                        </div>') : '').'
                            </div>                                                                              
                        </div>
                        <div id="postAction" data-arialabel="Репост" class="SystemReply" data-replyto="'.$zhab->zhabURLID.'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>                                                                            
                        </div>
                        <div id="postAction" data-arialabel="Комментарии" onclick="repliesZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                            </svg>                                                    
                        </div>
                        <div id="postAction" data-arialabel="Нравится" class="SystemLike '.(self::checkLike($zhab->zhabURLID) ? 'SystemLikeTrue' : '').'" onclick="likeZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }
    }
    public static function getAllZhabsByUIDHTML($userID){
        $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabBy = :uid AND reason = '' ORDER BY zhabID DESC", true, true, [":uid" => $userID]);
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        $zhabsEcho = '';
        foreach($zhabs as $zhab){
            $zhabsEcho .= '<style>#repost #postAuthor a[href="#"]{display:none!important}</style><div id="post" class="zhab__'.$zhab->zhabURLID.' RealZhab" realid="'.$zhab->zhabID.'">
            <a href="https://zhabbler.ru/profile/'.$zhab->nickname.'" id="postAvatar">
                <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
            </a>
            <div id="postContent">
                <div id="postAuthor">
                    <a href="https://zhabbler.ru/profile/'.$zhab->nickname.'" id="postAuthorItself">
                        '.$zhab->nickname.'
                    </a>
                    <span id="SYSt200a">
                        '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                    </span>
                </div>
                <div id="postContentItself">
                '.self::showPhotos($zhab->zhabURLID).'
                    <h1>
                    '.$zhab->zhabTitle.'
                    </h1>
                    <div id="ff0000FFF">
                    '.$zhab->zhabContent.'
                    </div>
                </div>
                <div id="postInfo">
                    <div id="postInfoCounter">
                        <span id="postInfoCounterItself">'.DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [
                            ":URLID" => $zhab->zhabURLID
                        ]).'</span> лайк'.(DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 0 ? 'ов' : (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) < 5 ? (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 1 ? '' : 'а') : 'ов')).'
                    </div>
                    <div id="postActionsItself">
                        <div id="postAction" onclick="window.open(`https://zhabbler.ru/zhab/'.$zhab->zhabURLID.'`, `__blank`)">
                            <svg onclick="openDropdown(`#GlobalIdentificator__'.$zhab->zhabURLID.'`)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>                                                                         
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }
        return $zhabsEcho;
    }
    public static function getAllZhabsByUID($userID, $lastID){
        if($lastID != 0){
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabID < :id AND zhabBy = :uid ORDER BY zhabID DESC LIMIT 7", true, true, [":id" => $lastID, ":uid" => $userID]);
        }else{
            $zhabs = self::Query("SELECT * FROM zhabs LEFT JOIN users ON userID = zhabBy WHERE zhabBy = :uid ORDER BY zhabID DESC LIMIT 7", true, true, [":uid" => $userID]);
        }
        if(isset($_COOKIE['authCode'])){
            $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
            $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        }
        foreach($zhabs as $zhab){
            echo '<div id="post" class="zhab__'.$zhab->zhabURLID.' RealZhab" realid="'.$zhab->zhabID.'">
            <a href="/profile/'.$zhab->nickname.'" id="postAvatar">
                <img src="'.$zhab->profileImage.'" alt="Фотография профиля">
            </a>
            <div id="postContent">
                <div id="postAuthor">
                    <a href="/profile/'.$zhab->nickname.'" id="postAuthorItself">
                    '.$zhab->nickname.($zhab->verifed == 1 ? "<span id='verifed'></span>" : "").'
                </a>
                    '.(isset($user) ? Follow::followButton($user->userID, $zhab->userID, true) : '').'
                    <span id="SYSt200a">
                        '.date_parse($zhab->zhabUploaded)['day']." ".Date::month(date_parse($zhab->zhabUploaded)['month'])." ".date_parse($zhab->zhabUploaded)['year'].'
                    </span>
                </div>
                <div id="postContentItself">
                '.self::showPhotos($zhab->zhabURLID).'
                    <h1>
                    '.$zhab->zhabTitle.'
                    </h1>
                    <div id="ff0000FFF">
                    '.$zhab->zhabContent.'
                    </div>
                </div>
                <div id="postInfo">
                    <div id="postInfoCounter">
                        <span id="postInfoCounterItself">'.DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [
                            ":URLID" => $zhab->zhabURLID
                        ]).'</span> лайк'.(DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 0 ? 'ов' : (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) < 5 ? (DB::RowCount("SELECT * FROM likes WHERE likeTo = :URLID", [":URLID" => $zhab->zhabURLID]) == 1 ? '' : 'а') : 'ов')).'
                    </div>
                    <div id="postActionsItself">
                        <div id="postAction">
                            <svg onclick="openDropdown(`#GlobalIdentificator__'.$zhab->zhabURLID.'`)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="SysDropdown" style="display:none;" id="GlobalIdentificator__'.$zhab->zhabURLID.'">
                                <div class="SysDropdownElement" onclick="shareLink(`'.$zhab->zhabURLID.'`)">
                                    Поделиться ссылкой
                                </div>
                                '.(isset($_COOKIE['authCode']) ? ($user->nickname != $zhab->nickname ? '
                            ' :
                            '<div class="SysDropdownElement" onclick="removeZhab(`'.$zhab->zhabURLID.'`, false)" style="color:#f00;">
                            Удалить
                        </div>') : '').'
                            </div>                                                                              
                        </div>
                        <div id="postAction" data-arialabel="Репост" class="SystemReply" data-replyto="'.$zhab->zhabURLID.'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
                            </svg>                                                                            
                        </div>
                        <div id="postAction" data-arialabel="Комментарии" onclick="repliesZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                            </svg>                                                    
                        </div>
                        <div id="postAction" data-arialabel="Нравится" class="SystemLike '.(self::checkLike($zhab->zhabURLID) ? 'SystemLikeTrue' : '').'" onclick="likeZhab(`'.$zhab->zhabURLID.'`)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>                              
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }
    }
}