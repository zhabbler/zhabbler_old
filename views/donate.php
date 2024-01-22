
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
<body id="application" class="SystemWelcome">
    <div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>
    <?php
    if(!empty($user->colorPallete)){
        echo "<style>.profileInformationCover{background:".$user->colorPallete.";}a,a:hover,a:active,a:visited{color:".$user->colorPallete.";}.button, .ui__gv0c67__S1C3Hd, .ui__JinNxQ__J3pBJx, #repostAvatarIcon{background-color:".$user->colorPallete.";}#tabs #tab.tab_active{box-shadow: 0 2px 0 0 ".$user->colorPallete.";}#sidebar #sidebarLogotype::before, #sidebar:hover #sidebarLogotype::before{background:".$user->colorPallete.";background-size: 100% 100%;-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}</style>";
    }
    ?>
    <nav id="navbar" class="navbarSticky">
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
            </div>
        </div>
    </nav>
    <div id="container">
        <main id="mainContent">
<div id="SysContentPage">
            <?php Alert::CheckForAlerts(); ?>
            <div class="ui__93cZB8__XEL2S4">
                <div class="ui__Bb0NGu__1cbtAi">
                    <h1>
                        Поддержать проект
                    </h1>
                    <h3>
                        если есть ненужные деньги, то ты можешь отправить их сюда
                    </h3>
                </div>
            </div>
            <div style="padding:20px;">
                <h1>
                    Зачем?
                </h1>
                <div>
                    <span style="word-break: break-word;">
                        Поддерживая меня деньгами, я начинаю больше развиваться и работать над проектом.<br>
                        Также, я собираю деньги на новый сервер, потому что тот который стоит на данный момент очень ужасен.
                    </span>
                </div>
            </div>
            <div style="padding:20px;">
                <h1>
                    И как я могу поддержать?
                </h1>
                <div>
                    <span style="word-break: break-word;">
                        QIWI - по никнейму FLYDELICK<br>
                        Спасибо!
                    </span>
                </div>
            </div>
            </div>
        </main>
        <div id="options">
            <div>
                <h3>
                    В тренде
                </h3>
                <div>
                    <?php Follow::whoToFollow(0); ?>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/preloader.js"></script>
    

</body>
</html>
<?php Alert::ClearAlerts(); ?>