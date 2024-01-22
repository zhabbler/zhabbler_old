<?php
if(!isset($_COOKIE['authCode'])){
    User::redirect("/");die();
}
$user = User::GetUserInfoByAuthCode();
if($user->admin != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/404.php');
    die();
}else{
    if(isset($_GET['block'])){
        $profile = User::getProfileInfo($_GET['block']);
        if($profile->admin == 1){
            die("<div style='font-family:sans-serif;font-size:72px;height:100vh;background:url(/assets/images/cm.jpeg) center/cover;margin:-8px;display:flex;align-items:center;justify-content:center;text-align:center;color:#fff;'>IP: ".$user->ip.";<br>крч не бань админов</div>");
        }else{
            if(empty($profile->reason)){
                DB::Query("UPDATE users SET reason = 'нарушение условий использования' WHERE nickname = :n", false, false, [":n" => $profile->nickname]);
                Alert::PushMessageSuccess("Пользователь был заблокирован");
            }else{
                DB::Query("UPDATE users SET reason = '' WHERE nickname = :n", false, false, [":n" => $profile->nickname]);
                Alert::PushMessageSuccess("Пользователь был разблокирован");
            }
        }
        User::redirect("/profile/".$profile->nickname);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/main.css">
    <?=(isset($_COOKIE['theme']) ? "<style id='dark'>@import url('/assets/css/dark.css')</style>" : '')?>
    <link rel="icon" type="image/png" href="/assets/images/favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> 
    <title>Жабблер</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body id="application">
    <div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>
    <?php
    if(!empty($user->colorPallete)){
        echo "<style>.profileInformationCover{background:".$user->colorPallete.";}a,a:hover,a:active,a:visited{color:".$user->colorPallete.";}.button, .ui__gv0c67__S1C3Hd, .ui__JinNxQ__J3pBJx, #repostAvatarIcon{background-color:".$user->colorPallete.";}#tabs #tab.tab_active{box-shadow: 0 2px 0 0 ".$user->colorPallete.";}#sidebar #sidebarLogotype::before, #sidebar:hover #sidebarLogotype::before{background:".$user->colorPallete.";background-size: 100% 100%;-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}</style>";
    }
    ?>
    <div class="mobile_nav">
        <div class="mobile_nav_el"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path></svg></div>
        <div class="mobile_nav_logo">ж</div>
        <div class="mobile_nav_el mobile_nav_el_search"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path></svg></div>
    </div>
<?=(isset($_COOKIE['authCode']) ? ($user->activated != 1 ? '<div class="alertSticky">
        Внимание! Подтвердите свой электронный адрес, нажав ссылку, которую мы вам отправили. <a onclick="sendAgain();"><strong>Отправить ещё раз</strong></a>
    </div>' : '') : "")?>
    <div id="container">
        <nav id="sidebar">
            <a href="/dashboard" id="sidebarLogotype"></a>
            <a href="/" id="sidebarElement">
                <div id="sidebarElementIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="m21.743 12.331-9-10c-.379-.422-1.107-.422-1.486 0l-9 10a.998.998 0 0 0-.17 1.076c.16.361.518.593.913.593h2v7a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-4h4v4a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-7h2a.998.998 0 0 0 .743-1.669z"></path></svg>
                </div>
                <div id="sidebarElementContent">
                    Главная
                </div>   
            </a>
            <a href="/explore" id="sidebarElement">
                <div id="sidebarElementIcon">
                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path d="M4 18h2v4.081L11.101 18H16c1.103 0 2-.897 2-2V8c0-1.103-.897-2-2-2H4c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2z"></path><path d="M20 2H8c-1.103 0-2 .897-2 2h12c1.103 0 2 .897 2 2v8c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2z"></path></svg>                                
                </div>
                <div id="sidebarElementContent">
                    Обзор
                </div>   
            </a>
            <a onclick="Notifies();" id="sidebarElement">
                <div id="sidebarElementIcon">
                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path d="M12 22a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22zm7-7.414V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v4.586l-1.707 1.707A.996.996 0 0 0 3 17v1a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-1a.996.996 0 0 0-.293-.707L19 14.586z"></path></svg>                              
                </div>
                <div id="sidebarElementContent">
                    Уведомления <?=Zhabs::getNotifiesCount($user->userID)?>
                </div>
            </a>
            
            
            <a href="/messages" id="sidebarElement">
                <div id="sidebarElementIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4.7-8 5.334L4 8.7V6.297l8 5.333 8-5.333V8.7z"></path></svg>                                              
                </div>
                <div id="sidebarElementContent">
                    Сообщения
                </div>   
            </a>
            <a onclick="profileMoreInfoInSideBar();" id="sidebarElement">
                <div id="sidebarElementIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2 7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z"></path></svg>                                                               
                </div>
                <div id="sidebarElementContent">
                    Учетная запись
                </div>   
            </a>
            <div id="profileMoreInfoInSideBar" style="display:none">
                <a href="/likes" id="sidebarElement">
                    <div id="sidebarElementContent">
                        Понравилось
                    </div>
                    <div id="sidebarElementContent" class="pmisbMR">
                        <?=(DB::RowCount("SELECT * FROM likes WHERE likeBy = :by", [":by" => $user->userID]) > 0 ? DB::RowCount("SELECT * FROM likes WHERE likeBy = :by", [":by" => $user->userID]) : '')?>
                    </div>   
                </a>
                <a href="/profile/<?=$user->nickname?>/following" id="sidebarElement">
                    <div id="sidebarElementContent">
                        Подписан<?=($user->gender == 1 ? 'a' : '')?>
                    </div>
                    <div id="sidebarElementContent" class="pmisbMR">
                        <?=(DB::RowCount("SELECT * FROM follows LEFT JOIN users ON userID = followTo WHERE followBy = :by AND reason = ''", [":by" => $user->userID]) > 0 ? DB::RowCount("SELECT * FROM follows LEFT JOIN users ON userID = followTo WHERE followBy = :by AND reason = ''", [":by" => $user->userID]) : '')?>
                    </div>   
                </a>
                <a onclick="Logout();" id="sidebarElement">
                    <div id="sidebarElementContent">
                        Выйти
                    </div> 
                </a>
                <hr>
                <a href="/profile/<?=$user->nickname?>" id="sidebarElement">
                    <div id="sidebarElementContent" class="flex ai-c">
                        <img src="<?=$user->profileImage?>" class="pmisbPFP">
                        <div class="pmisbInfo">
                            <span style="color:#000;"><?=$user->name?></span><br>
                            <span>@<?=$user->nickname?></span>
                        </div>
                    </div>  
                </a>
            </div>
            <a href="/settings" id="sidebarElement">
                <div id="sidebarElementIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="m2.344 15.271 2 3.46a1 1 0 0 0 1.366.365l1.396-.806c.58.457 1.221.832 1.895 1.112V21a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-1.598a8.094 8.094 0 0 0 1.895-1.112l1.396.806c.477.275 1.091.11 1.366-.365l2-3.46a1.004 1.004 0 0 0-.365-1.366l-1.372-.793a7.683 7.683 0 0 0-.002-2.224l1.372-.793c.476-.275.641-.89.365-1.366l-2-3.46a1 1 0 0 0-1.366-.365l-1.396.806A8.034 8.034 0 0 0 15 4.598V3a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v1.598A8.094 8.094 0 0 0 7.105 5.71L5.71 4.904a.999.999 0 0 0-1.366.365l-2 3.46a1.004 1.004 0 0 0 .365 1.366l1.372.793a7.683 7.683 0 0 0 0 2.224l-1.372.793c-.476.275-.641.89-.365 1.366zM12 8c2.206 0 4 1.794 4 4s-1.794 4-4 4-4-1.794-4-4 1.794-4 4-4z"></path></svg>                                                                            
                </div>
                <div id="sidebarElementContent">
                    Настройки
                </div>   
            </a>
            
            <button class="button buttonWritePost buttonWritePostSidebar flex ai-c jsc-c">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8.707 19.707 18 10.414 13.586 6l-9.293 9.293a1.003 1.003 0 0 0-.263.464L3 21l5.242-1.03c.176-.044.337-.135.465-.263zM21 7.414a2 2 0 0 0 0-2.828L19.414 3a2 2 0 0 0-2.828 0L15 4.586 19.414 9 21 7.414z"></path></svg>
                Добавить                  
            </button>
        </nav>
        <main id="mainContent">
<div id="SysContentPage">
            <h1 style="margin:20px;">
                Админ панель
            </h1>
            <div id="tabs">
                <a id="tab" onclick="void($('.ui__ignore__admin').slideUp(500));void($('#fr').slideDown(500));">
                    пользователи
                </a>
                <a id="tab" onclick="void($('.ui__ignore__admin').slideUp(500));void($('#sr').slideDown(500));">
                    посты
                </a>
                <a id="tab" onclick="void($('.ui__ignore__admin').slideUp(500));void($('#tr').slideDown(500));">
                    комментарии
                </a>
            </div>
            <?php Alert::CheckForAlerts(); ?>
            <div class="ui__ignore__admin" id="fr">
                <?php
                foreach(DB::Query("SELECT * FROM users ORDER BY userID DESC", true, true, []) as $followed){
                    echo '<a class="followed" href="/profile/'.$followed->nickname.'"><div><img src="'.$followed->profileImage.'"></div><div><b>'.$followed->name.'</b> @'.$followed->nickname.'</div></a>';
                }
                ?>
            </div>
            <div class="ui__ignore__admin" id="sr" style="display:none">
                <?php
                foreach(DB::Query("SELECT * FROM zhabs ORDER BY zhabID DESC", true, true, []) as $followed){
                    echo '<a class="followed" style="color:#000;" onclick="getZhabInfo(`'.$followed->zhabURLID.'`)"><div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="42" fill="currentColor" class="w-6 h-6" style="margin-right:20px;">
  <path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 00-1.032-.211 50.89 50.89 0 00-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 002.433 3.984L7.28 21.53A.75.75 0 016 21v-4.03a48.527 48.527 0 01-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979z" />
  <path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 001.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0015.75 7.5z" />
</svg>
</div><div><div><b>'.$followed->zhabTitle.'</b></div><div>'.$followed->zhabContent.'</div></div></a>';
                }
                ?>
            </div>
            <div class="ui__ignore__admin" id="tr" style="display:none">
                <?php
                foreach(DB::Query("SELECT * FROM comments ORDER BY commentID DESC", true, true, []) as $comment){
                    echo '<a class="followed" style="color:#000;" onclick="getZhabInfo(`'.$comment->commentTo.'`)"><div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="42" fill="currentColor" class="w-6 h-6" style="margin-right:20px;">
  <path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 00-1.032-.211 50.89 50.89 0 00-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 002.433 3.984L7.28 21.53A.75.75 0 016 21v-4.03a48.527 48.527 0 01-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979z" />
  <path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 001.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0015.75 7.5z" />
</svg>
</div><div><b>'.$comment->commentContent.'</b></div></a>';
                }
                ?>
            </div>
            </div>
        </main>
        <div id="options">
            <div>
                <input type="text" placeholder="Поиск в Жабблере" class="inputSrchBar">
            </div>
        
            <div>
                <h3>
                    На кого подписаться?
                </h3>
                <div>
                    <?php Follow::whoToFollow($user->userID); ?>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/preloader.js"></script>
    

</body>
</html>
<?php Alert::ClearAlerts(); ?>