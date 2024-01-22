
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
    <div id="container" style="width:654px;">
        <main id="mainContent">
<div id="SysContentPage">
            <div style="padding:20px;">
                <h1 style="font-size:48px">
                    Условия использования
                </h1>
                <div>
                    <p>
                        Используя сайт zhabbler.ru, вы соглашаесь соблюдать следующие условия ("Условия использования")
                    </p>
                    <h2>
                        Владение
                    </h2>
                    <p>
                        Веб-сайт zhabbler.ru ("Жабблер") на данный момент принадлежит и управляется flydelick works. Принимая настоящие Условия использования, вы подтверждаете, что flydelick works является единственным владельцем.
                    </p>
                    <h2>
                        Основные условия
                    </h2>
                    <ul>
                        <li>
                            Вам должно быть 14 лет или старше чтобы использовать данный сайт
                        </li>
                        <li>
                            Вы ответственны за любые поступки, изменения которые происходят под вашим псевдонимом/никнеймом.
                        </li>
                        <li>
                            Вы ответственны за безопастность своего аккаунта.
                        </li>
                        <li>
                            Вы не должны преследовать, оскорблять, угрожать, выдавать себя за другое лицо или запугивать пользователей. 
                        </li>
                        <li>
                            Вы не имеете права использовать сервис Жабблер в каких-либо незаконных или несанкционированных целях. Международные пользователи соглашаются соблюдать все местные законы, касающиеся поведения в Интернете и приемлемого контента.
                        </li>
                        <li>
                            Вы несете единоличную ответственность за свое поведение и любые данные, текст, информацию, псевдонимы, графику, фотографии, профили, аудио- и видеоклипы, ссылки («Контент»), которые вы отправляете, публикуете и отображаете в сервисе Жабблер.
                        </li>
                        <li>
                            Вы не имеете права изменять, адаптировать или взламывать Жабблер или изменять другой веб-сайт таким образом, чтобы создать ложное впечатление, что он связан с Жабблером.
                        </li>
                        <li>
                            Вы не имеете права взламывать, делать сайт не комфортным для использования
                        </li>
                        <li>
                            Вы не имеете права создавать или отправлять нежелательные электронные письма участникам Жабблера («Спам»).
                        </li>
                        <li>
                            При использовании Жабблера вы не должны нарушать законы вашей юрисдикции (включая, помимо прочего, законы об авторском праве).
                        </li>
                    </ul>
                    <p>
                        Нарушение любого из этих условий приведет к прекращению действия вашей учетной записи. Хотя Жабблер запрещает такое поведение и контент на своем сайте, вы понимаете и соглашаетесь с тем, что Жабблер не может нести ответственность за Контент, размещенный на веб-сайте, и, тем не менее, вы можете подвергнуться воздействию таких материалов и что вы используете сервис Жабблер по своему усмотрению.
                    </p>
                    <h2>Общие условия</h2>
                    <ul>
                        <li>
                            Мы оставляем за собой право изменить или прекратить работу Жабблера по любой причине и без предварительного уведомления в любое время.
                        </li>
                        <li>
                            Мы оставляем за собой право изменять настоящие Условия использования в любое время. Если изменения представляют собой существенное изменение Условий использования, мы уведомим вас в соответствии с предпочтениями, указанными в вашей учетной записи. Что представляет собой «существенное изменение», будет определяться по нашему собственному усмотрению, добросовестно, на основе здравого смысла и разумного суждения.
                        </li>
                        <li>
                            Мы оставляем за собой право отказать в обслуживании кому угодно по любой причине в любое время.
                        </li>
                        <li>
                            Мы можем, но не обязаны удалять Контент и учетные записи, содержащие Контент, который, по нашему собственному усмотрению, мы определяем как незаконный, оскорбительный, угрожающий, клеветнический, клеветнический, непристойный или иным образом нежелательный или нарушающий интеллектуальную собственность любой стороны или настоящие Условия использования.
                        </li>
                        <li>
                            Жабблер разрешает публиковать изображения и текст, размещенные на Жабблере, на внешних веб-сайтах. Такое использование допускается. Однако страницы других веб-сайтов, на которых отображаются данные, размещенные на Жабблер, должны содержать обратную ссылку на Жабблер.
                        </li>
                    </ul>
                    <h2>
                        Авторское право
                    </h2>
                    <p>
                        Если вы думаете, что контент на Жабблере нарушает авторские права, напишите нам на почту <a href="mailto:copyright@zhabbler.ru;">copyright@zhabbler.ru</a>
                    </p>
                </div>
            </div>
            </div>
        </main>
        <div id="options" style="display:none!important;"></div>
    </div>
    <script src="/assets/js/preloader.js"></script>
    

</body>
</html>
<?php Alert::ClearAlerts(); ?>