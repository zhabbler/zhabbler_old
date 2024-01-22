<?php
if(isset($_COOKIE['authCode'])){
    User::redirect("/");
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
<body id="application" class="systemUnlogedAccount">
    <?Alert::CheckForAlerts()?>
    <div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>
    <?php
    if(!empty($user->colorPallete)){
        echo "<style>.profileInformationCover{background:".$user->colorPallete.";}a,a:hover,a:active,a:visited{color:".$user->colorPallete.";}.button, .ui__gv0c67__S1C3Hd, .ui__JinNxQ__J3pBJx, #repostAvatarIcon{background-color:".$user->colorPallete.";}#tabs #tab.tab_active{box-shadow: 0 2px 0 0 ".$user->colorPallete.";}#sidebar #sidebarLogotype::before, #sidebar:hover #sidebarLogotype::before{background:".$user->colorPallete.";background-size: 100% 100%;-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}</style>";
    }
    ?>
    <div class="systemFooter">
        <div>
            <div>
                <a href="/help/tos">Условия использования</a>&nbsp;&nbsp;&nbsp;<a href="/donate">Поддержать проект</a>
            </div>
        </div>
    </div>
    <nav id="navbar" class="navbarTransparent">
        <div class="navbarContainer">
            <a href="/" id="NVLOg">
                жабблер
            </a>
            <div>
                <input type="text" placeholder="Поиск в Жабблере" class="inputSrchBar">
            </div>
            <div class="navbarContentMain">
                <a href="/">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="m21.743 12.331-9-10c-.379-.422-1.107-.422-1.486 0l-9 10a.998.998 0 0 0-.17 1.076c.16.361.518.593.913.593h2v7a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-4h4v4a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-7h2a.998.998 0 0 0 .743-1.669z"></path></svg>
                </a>
                <a href="#" onclick="loginPopup();">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2 7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z"></path></svg>
                </a>
            </div>
        </div>
    </nav>
    <div class="systemUnlogedAccountForm">
        <div>
            <div>
                <h1>
                    жабблер
                </h1>
            </div>
            <div>
                <p>Добро пожаловать в логово жаб.<br>Квакайте о чём угодно.</p>
            </div>
            <div id="SR2Bc">
                <form method="POST" id="JS__Login" action="/API/account/login">
                    <div id="JS__Step1" class="systemSteps">
                        <div>
                            <input type="email" class="systemInput" placeholder="Электронный адрес" name="email">
                        </div>
                        <div>
                            <input type="password" class="systemInput systemInputBorders" placeholder="Пароль" name="password">
                        </div>
                        <div>
                            <button class="button" type="submit">Войти</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/assets/js/preloader.js"></script>
    
    <script src="/assets/js/register.js"></script>

</body>
</html>
<?php Alert::ClearAlerts(); ?>