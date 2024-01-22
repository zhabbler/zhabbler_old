<?php
$profile = User::getProfileInfo($nickname);
if(isset($_COOKIE['authCode'])){
    $user = User::GetUserInfoByAuthCode();
    if($user->entered_all != 1){
        include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
        die();
    }
}
if($profile->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/404.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/main.css">
    <?=(isset($_COOKIE['theme']) ? "<style id='dark'>@import url('/assets/css/dark.css')</style>" : '')?>
    <link rel="icon" type="image/png" href="/assets/images/icon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <style>
    @media only screen and (max-width: 900px) {#postAvatar{top:110px;}}</style>
    <title>Жабблер</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body id="application">
    <div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>
    <?php
    if(!empty($profile->colorPallete)){
        echo "<style>.profileInformationCover{background:".$profile->colorPallete.";}a,a:hover,a:active,a:visited{color:".$profile->colorPallete.";}.button, .ui__gv0c67__S1C3Hd, .ui__JinNxQ__J3pBJx, #repostAvatarIcon{background-color:".$profile->colorPallete.";}#tabs #tab.tab_active{box-shadow: 0 2px 0 0 ".$profile->colorPallete.";}#sidebar #sidebarLogotype::before, #sidebar:hover #sidebarLogotype::before{background:".$profile->colorPallete.";background-size: 100% 100%;-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}</style>";
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
            <?php
            if(isset($_COOKIE['authCode'])){
            ?>
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
            <?php } ?>
        </nav>
        <main id="mainContent" class="WelcomePage"><div id="SysContentPage">

        <?php
                if(!empty($profile->reason)){
                ?>
            <div class="profileInformation">
                <div class="profileInformationCover"></div>
                <div class="profileInformationProfilePicture">
                    <img src="/assets/images/no_avatar_1900.png" alt="Фотография профиля">
                </div>
                <center class="alert__banned">
                    <h2>
                        Пользователь был заблокирован.
                    </h2>
                    <div>
                        <span>
                            Пользователь был заблокирован за нарушение правил сайта.<br>
                            Пользователь может быть заблокирован временно или вечно.
                        </span>
                    </div>
                    <?=($user->admin == 1 ? '<a href="/admin?block='.$profile->nickname.'">Разблокировать '.$profile->nickname.'</a>' : '')?>
                </center>
                </div>
            <?php }else{ ?>
            <div class="profileInformation">
                <div class="profileInformationCover" style="<?php if(!empty($profile->profileCover)){?> background: url('<?=$profile->profileCover?>') center/cover; <?php } ?>"></div>
                <div class="profileInformationProfilePicture">
                    <img src="<?=$profile->profileImage?>" alt="Фотография профиля">
                </div>
                <?php
                if(isset($_COOKIE['authCode'])){
                ?>
                <div class="profileInformationItself">
                    <div>
                        <h2 <?=($profile->nickname == $user->nickname ? 'class="ProfileToolsEdit"' : '')?>>
                            <div class="NeedsToHide flex ai-c jsc-c">
                            <?=$profile->name?>
                            <?=($profile->verifed == 1 ? "<span id='verifed'></span>" : "")?>
                            <?=($profile->nickname == $user->nickname ? '<svg id="MAl10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                            </svg>' : '')?>
                            </div>
                            <?=($profile->nickname == $user->nickname ? '<form id="WhatNeedsToChange" style="display:none;" action="/API/account/changeProfileName" method="post" class="flex ai-c"><input type="text" name="name" value="'.$user->name.'" maxlength="48" class="input"><button class="button" style="margin-left:10px;">Готово</button></form>' : '')?>
                        </h2>
                    </div>
                    <div>
                        <span class="cl6 <?=($profile->nickname == $user->nickname ? 'ProfileToolsEdit' : '')?>">
                            <div class="NeedsToHide flex ai-c jsc-c">    
                            @<?=$profile->nickname?>
                            <?=($profile->nickname == $user->nickname ? '<svg id="MAl10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                            </svg>' : '')?>
                            
                            </div>
                            <?=($profile->nickname == $user->nickname ? '<form id="WhatNeedsToChange" style="display:none;" action="/API/account/changeNickname" method="post" class="flex ai-c"><input type="text" name="nickname" value="'.$user->nickname.'" class="input"><button class="button" style="margin-left:10px;">Готово</button></form>' : '')?>
                        </span>
                    </div>
                    <div>
                        <?php
                        if($profile->showBirth == 1){
                        ?><br>
                        <span class="cl6 flex ai-c jsc-c">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: #666;margin-right:10px;"><path d="M16.997 15c-1.601 0-2.446-.676-3.125-1.219-.567-.453-.977-.781-1.878-.781-.898 0-1.287.311-1.874.78-.679.544-1.524 1.22-3.125 1.22s-2.444-.676-3.123-1.22C3.285 13.311 2.897 13 2 13v5c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2v-5c-.899 0-1.288.311-1.876.781-.68.543-1.525 1.219-3.127 1.219zM19 5h-6V2h-2v3H5C3.346 5 2 6.346 2 8v3c1.6 0 2.443.676 3.122 1.22.587.469.975.78 1.873.78.899 0 1.287-.311 1.875-.781.679-.543 1.524-1.219 3.124-1.219 1.602 0 2.447.676 3.127 1.219.588.47.977.781 1.876.781.9 0 1.311-.328 1.878-.781C19.554 11.676 20.399 11 22 11V8c0-1.654-1.346-3-3-3z"></path></svg>
                            <?=($profile->showBirth == 1 ? ($profile->cf_birth == 1 ? date_parse($profile->birth)['day']." ".Date::month(date_parse($profile->birth)['month']) : date_parse($profile->birth)['day']." ".Date::month(date_parse($profile->birth)['month'])." ".date_parse($profile->birth)['year']) : '')?>
                        </span>
                        <?php } ?>
                    </div><br>
                    <?php if(!empty($profile->biography) && $profile->nickname != $user->nickname){ ?>
                    <center>
                        <span>
                        <?=nl2br($profile->biography)?>
                        </span>
                    </center><br>
                    <?php }else if($profile->nickname == $user->nickname){ ?>
                    <div>
                        <span class="ProfileToolsEdit" style="width:100%;">
                            <div class="NeedsToHide flex ai-c jsc-c">    
                        <?=(!empty($profile->biography) ? nl2br($profile->biography) : '<i>Добавить биографию о себе</i>')?>
                        <?=($profile->nickname == $user->nickname ? '<svg id="MAl10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                            </svg>' : '') ?>
                            </div>
                            <form id="WhatNeedsToChange" class="ds-b prfl-bio" style="display:none;" action="/API/account/changeProfileBio" method="post" class="flex ai-c">
                                <textarea name="bio" maxlength="255" id="inpBio" placeholder="Добавить биографию о себе" class="input"><?=$user->biography?></textarea>
                                <button class="button">Готово</button>
                            </form>
                        </span>
                    </div>
                    <?php }?>
                    <div class="ml-a flex ai-c jsc-c">
                        <?php
                        if($user->admin == 1 && $user->userID != $profile->userID){
                        ?>
                        <a class="button" href="/admin?block=<?=$profile->nickname?>" id="buttonProfileSendMsg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zM4 12c0-1.846.634-3.542 1.688-4.897l11.209 11.209A7.946 7.946 0 0 1 12 20c-4.411 0-8-3.589-8-8zm14.312 4.897L7.103 5.688A7.948 7.948 0 0 1 12 4c4.411 0 8 3.589 8 8a7.954 7.954 0 0 1-1.688 4.897z"></path></svg>
                        </a>
                        <?php } ?>
                        <?php
                        if($user->userID != $profile->userID){
                            $show = false;
                            if($profile->cf_messages == 0){
                                $show = true;
                            }else if($profile->cf_messages == 1 && DB::RowCount("SELECT * FROM follows WHERE followBy = :by AND followTo = :to", [":by" => $profile->userID, ":to" => $user->userID]) == 1){
                                    $show = true;
                            }else if($profile->cf_messages == 2 && DB::RowCount("SELECT * FROM follows WHERE followTo = :by AND followBy = :to", [":by" => $profile->userID, ":to" => $user->userID]) == 1){
                                    $show = true;
                            }else if($profile->cf_messages == 3 && DB::RowCount("SELECT * FROM follows WHERE (followTo = :by AND followBy = :to) OR (followBy = :by AND followTo = :to)", [":by" => $profile->userID, ":to" => $user->userID]) == 2){
                                    $show = true;
                            }
                            if($show === true){
                        ?>
                        <a class="button" href="/messages/<?=$profile->nickname?>" id="buttonProfileSendMsg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm-5 10.5A1.5 1.5 0 0 1 9.5 14c-.086 0-.168-.011-.25-.025-.083.01-.164.025-.25.025a2 2 0 1 1 2-2c0 .085-.015.167-.025.25.013.082.025.164.025.25zm4 1.5c-.086 0-.167-.015-.25-.025a1.471 1.471 0 0 1-.25.025 1.5 1.5 0 0 1-1.5-1.5c0-.085.012-.168.025-.25-.01-.083-.025-.164-.025-.25a2 2 0 1 1 2 2z"></path></svg>
                        </a>
                        <?php
                            }
                        }
                        ?>
                        <?php
                        if(isset($_COOKIE['authCode'])){
                        ?>
                        <?php Follow::followButton($user->userID, $profile->userID, false) ?>
                        <?php } ?>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="profileInformationItself">
                    <div>
                        <h2>
                            <div class="NeedsToHide flex ai-c">
                            <?=$profile->name?>
                            </div>
                        </h2>
                    </div>
                    <div>
                        <span class="cl6">
                            <div class="NeedsToHide flex ai-c">    
                                @<?=$profile->nickname?>
                            </div>
                            
                        </span><br>
                        <span class="cl6">
                             *чтобы увидеть больше, <a onclick="loginPopup()">войдите в свою учётную запись</a>.
                        </span>
                    </div>
            <?php } ?>
            </div>
            <br>
            <div id="tabs">
                <a id="tab" onclick="void(0);">
                    Посты
                </a>
                <a id="tab" href="/profile/<?=$profile->nickname?>/followers">
                    Подписчики (<?=DB::RowCount("SELECT * FROM follows LEFT JOIN users ON userID = followBy WHERE followTo = :to AND reason = ''", [":to" => $profile->userID])?>)
                </a>
                <a id="tab" href="/profile/<?=$profile->nickname?>/following">
                    В подписанных (<?=DB::RowCount("SELECT * FROM follows LEFT JOIN users ON userID = followTo WHERE followBy = :to AND reason = ''", [":to" => $profile->userID])?>)
                </a>
            </div>
            <?php Alert::CheckForAlerts(); ?>
            <?php
            if(DB::RowCount("SELECT * FROM zhabs WHERE zhabBy = :by", [":by" => $profile->userID]) == 0){
                $textRandZhabs = [
                    1 => "Классный профиль. Но пустой.",
                    2 => "В этом профиле ничего нет.",
                    3 => "Помедитируйте немного над этим пустым профилем.",
                    4 => "В этом минималистичном профиле нет постов."
                ];
                echo '<center style="margin:3em 0;">'.$textRandZhabs[rand(1, 4)].'</center>';
            }else{
                Zhabs::getAllZhabsByUID($profile->userID, 0);
            }
        }
            ?>
            <script>
        $(document).ready(function(){
            infScroll();
        })
        function infScroll(){
            $(document).scroll(function(e){
                // console.log($(window).scrollTop());
                // console.log($(document).height() - $(window).height());
                if($(window).scrollTop() == $(document).height() - $(window).height()){
                    $.post("/API/zhabs/totalCountByUID", {userid:<?=$profile->userID?>}, function (data) {
                        if($("#post").length < Number(data)) {
                            $(window).off("scroll");
                            $.post("/API/zhabs/getAllZhabsByUID", {lastid:Number($("#mainContent .RealZhab").last().attr("realid")), userid:<?=$profile->userID?>}, function(data){
                                $("#mainContent").append(data);
                            })
                        }
                    })
                }
            });
        }
    </script>
            </div>
        </main>
        <div id="options">
            <div>
                <input type="text" placeholder="Поиск в Жабблере" class="inputSrchBar">
            </div>
            <?php
            if(isset($_COOKIE['authCode'])){
            ?>
            <div>
                <h3>
                    На кого подписаться?
                </h3>
                <div>
                    <?php Follow::whoToFollow($user->userID); ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <script src="/assets/js/preloader.js"></script>
</body>
</html>
<?php Alert::ClearAlerts(); ?>