<?php
class Follow extends DB{
    public static function followButton($from, $to, $isLink){
        if(!$isLink){
            if($from != $to){
                if(self::CheckFollow($from, $to)){
                    echo '<button class="button flex ai-c" onclick="Follow(`'.$to.'`, `y`)" id="FollowTo'.$to.'">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M14 11h8v2h-8zM4.5 8.552c0 1.995 1.505 3.5 3.5 3.5s3.5-1.505 3.5-3.5-1.505-3.5-3.5-3.5-3.5 1.505-3.5 3.5zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2z"></path></svg>
                Отписаться                                         
            </button>';
                }else{
                    echo '<button class="button flex ai-c" onclick="Follow(`'.$to.'`, `y`)" id="FollowTo'.$to.'">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4.5 8.552c0 1.995 1.505 3.5 3.5 3.5s3.5-1.505 3.5-3.5-1.505-3.5-3.5-3.5-3.5 1.505-3.5 3.5zM19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2z"></path></svg>
                Подписаться                       
            </button>';
                }
            }else{
                echo '<button class="button flex ai-c" style="margin-top:15px;" onclick="location.href = `/settings`;">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="m2.344 15.271 2 3.46a1 1 0 0 0 1.366.365l1.396-.806c.58.457 1.221.832 1.895 1.112V21a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-1.598a8.094 8.094 0 0 0 1.895-1.112l1.396.806c.477.275 1.091.11 1.366-.365l2-3.46a1.004 1.004 0 0 0-.365-1.366l-1.372-.793a7.683 7.683 0 0 0-.002-2.224l1.372-.793c.476-.275.641-.89.365-1.366l-2-3.46a1 1 0 0 0-1.366-.365l-1.396.806A8.034 8.034 0 0 0 15 4.598V3a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1.598A8.094 8.094 0 0 0 7.105 5.71L5.71 4.904a.999.999 0 0 0-1.366.365l-2 3.46a1.004 1.004 0 0 0 .365 1.366l1.372.793a7.683 7.683 0 0 0 0 2.224l-1.372.793c-.476.275-.641.89-.365 1.366zM12 8c2.206 0 4 1.794 4 4s-1.794 4-4 4-4-1.794-4-4 1.794-4 4-4z"></path></svg>
               Настройки                   
            </button>';
            }
        }else{
            if($from != $to){
                if(!self::CheckFollow($from, $to)){
                    return '<a href="#" onclick="Follow(`'.$to.'`, `n`)" id="ButtonFollowTo'.$to.'" style="margin-right:10px;">
                    Подписаться
                </a>';
                }
            }
        }
    }

    public static function followButtonInHTML($from, $to){
        if($from != $to){
            if(self::CheckFollow($from, $to)){
                echo '<button style="background-color: #13b552;color: #fff;border: 0;cursor: pointer;padding: 10px;font-size: 16px;font-weight: 600;border-radius: 3px;" onclick="$.post(`/API/account/follow`, {to:'.$to.'}, function(data){
                    if(data == `nothing`){
                        location.reload();
                    }else{
                    if(data == `yes`){
                            $(`#FollowTo'.$to.'`).html(`Отписаться`);
                        }else{
                            $(`#FollowTo'.$to.'`).html(`Подписаться`);
                        }
                }
                })" id="FollowTo'.$to.'">
            Отписаться                                       
        </button>';
            }else{
                echo '<button style="background-color: #13b552;color: #fff;border: 0;cursor: pointer;padding: 10px;font-size: 16px;font-weight: 600;border-radius: 3px;" onclick="$.post(`/API/account/follow`, {to:'.$to.'}, function(data){
                    if(data == `nothing`){
                        location.reload();
                    }else{
                    if(data == `yes`){
                            $(`#FollowTo'.$to.'`).html(`Отписаться`);
                        }else{
                            $(`#FollowTo'.$to.'`).html(`Подписаться`);
                        }
                }
                })" id="FollowTo'.$to.'">
            Подписаться                       
        </button>';
            }
        }
    }

    public static function whoToFollow($userID){
        if(DB::RowCount("SELECT * FROM users WHERE userID != :userID ORDER BY RAND() LIMIT 5", [":userID" => $userID]) == 0){
            echo '<center>Пока что не на кого подписаться )=</center>';
        }
        $query = DB::Query("SELECT * FROM users WHERE userID != :userID AND entered_all = 1 ORDER BY RAND() LIMIT 5", true, true, [":userID" => $userID]);
        foreach($query as $profile){
            if(!self::checkFollow($userID, $profile->userID) && empty($profile->reason)){
                echo '<a href="/profile/'.$profile->nickname.'" class="profile-mini-who-subscribe">
                <div>
                    <img src="'.$profile->profileImage.'" alt="Фотография профиля">
                </div>
                <div>
                    <div>
                        <span>
                            '.$profile->nickname.'
                        </span>
                    </div>
                    <div>
                        <button class="button button_follow">
                            Подписаться
                        </button>
                    </div>
                </div>
            </a>';
            }
        }
        echo '<div class="ui__EX3GK5__aUxogf"><span>Жабблер © '.date("Y").'</span> <a href="/donate">Поддержать проект</a> <span>/</span> <a href="/messages/zhabbler">Поддержка</a> <span>/</span> <a href="/help/tos">Условия использования</a></div>';
    }

    public static function FollowToUser($from, $to, $infoAboutChngs){
        if($from != $to){
            if(self::checkFollow($from, $to)){
                self::Query("DELETE FROM follows WHERE followBy = :from AND followTo = :to", false, false, [":from" => $from, ":to" => $to]);
                if($infoAboutChngs){
                    return "no";
                }
            }else{
                self::Query("INSERT INTO follows(followBy, followTo) VALUES(:from, :to)", false, false, [":from" => $from, ":to" => $to]);
                if($infoAboutChngs){
                    return "yes";
                }
            }
        }else{
            return "nothing";
        }
    }

    public static function CheckFollow($from, $to){
        if(self::RowCount("SELECT * FROM follows WHERE followBy = :from AND followTo = :to", [":from" => $from, ":to" => $to]) > 0){
            return true;
        }else{
            return false;
        }
    }
}