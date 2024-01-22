<?php
if(isset($_COOKIE['authCode'])){
    $user = User::GetUserInfoByAuthCode();
    if($user->entered_all != 1){
        include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
        die();
    }
}
if(DB::RowCount("SELECT * FROM zhabs WHERE zhabURLID = :id", [":id" => $id]) == 0){
    Alert::PushMessage("Упс! Похоже что жаб удален или не найден.");
    User::redirect("/");die();
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
        </nav>
        <main id="mainContent">
<div id="SysContentPage">
            <?php Alert::CheckForAlerts(); ?>
            </div>
        </main>
        <div id="options">
            <div>
                <input type="text" placeholder="Поиск в Жабблере" class="inputSrchBar">
            </div>
        </div>
    </div>
    <script src="/assets/js/preloader.js"></script>
    
    <script>
        $(document).ready(function(){
            $(document).on("click", ".id000alsA", function(){
                location.href = '/';
            })
            $(document).on("click", "#AlertWarningAboutTextEditor .button_gray", function(){
                location.reload();
                return false;
            })
            $(document).on("click", "#AlertWarningAboutRemoving .button_gray", function(){
                location.reload();
                return false;
            })
            $("body").prepend(`<div class='popup id000alsA' style="z-index:2047!important"><div class="ContentGoesRightThere"><span class="loading"><span id="ldF"></span><span id="ldS"></span><span id="ldT"></span></span></div></div>`)
            $.post("/API/zhabs/getZhabItself", {id:'<?=$id?>'}, function(data){
                $(".popup .ContentGoesRightThere").html(data);
                <?php
                if(isset($_GET['open_video']) && (int)$_GET['open_video'] == 1){
                ?>
                $(".popup .ContentGoesRightThere .zhabVideo:first").click();
                <?php
                }
                ?>
            })
        })
    </script>

</body>
</html>
<?php Alert::ClearAlerts(); ?>