<?php
if(!isset($_COOKIE['authCode'])){
    User::redirect("/");
}
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if(DB::RowCount("SELECT * FROM emailChanger WHERE emailChangerTo = :to AND emailChangerGetID = :getID", [":to" => $user->userID, ':getID' => $getID]) == 0){
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
<body id="application" style="height:100vh;" class="SystemRegister">
    <div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>
    <?php
    if(!empty($user->colorPallete)){
        echo "<style>.profileInformationCover{background:".$user->colorPallete.";}a,a:hover,a:active,a:visited{color:".$user->colorPallete.";}.button, .ui__gv0c67__S1C3Hd, .ui__JinNxQ__J3pBJx, #repostAvatarIcon{background-color:".$user->colorPallete.";}#tabs #tab.tab_active{box-shadow: 0 2px 0 0 ".$user->colorPallete.";}#sidebar #sidebarLogotype::before, #sidebar:hover #sidebarLogotype::before{background:".$user->colorPallete.";background-size: 100% 100%;-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}</style>";
    }
    ?>
    <nav id="navbar">
        <div class="navbarContainer">
            <a href="/" id="NVLOg">
                жабблер
            </a>
            <div>
                <input type="text" placeholder="Поиск в Жабблере" class="inputSrchBar">
            </div>
            <div class="navbarContentMain">
                <a onclick="Logout();">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm5.03 4.72a.75.75 0 010 1.06l-1.72 1.72h10.94a.75.75 0 010 1.5H10.81l1.72 1.72a.75.75 0 11-1.06 1.06l-3-3a.75.75 0 010-1.06l3-3a.75.75 0 011.06 0z" clip-rule="evenodd" />
                </svg>

                </a>
            </div>
        </div>
    </nav>
    <form class="SystemRegisterForm" action="/API/letters/email" method="post">
        <?php Alert::CheckForAlerts(); ?>
        <div>
            <h1 onclick="location.href = '/'">
                жабблер
            </h1>
        </div>
        <div>
            <span class="LandingStr">
                Введите свой новый электронный адрес
            </span>
        </div>
        <br>
        <div>
            <input type="hidden" name="getID" hidden value="<?=$getID?>">
            <div class="form_content">
                <label for="popupEmail" class="formLabel">email</label>
                <input type="email" name="email" id="popupEmail" autocomplete="off" class="input">
            </div>
            <div class="flex ai-c jsc-c">
                <button class="button">
                    Готово!
                </button>
            </div>
        </div>
    </form>
    <script src="/assets/js/preloader.js"></script>
    

</body>
</html>
<?php Alert::ClearAlerts(); ?>