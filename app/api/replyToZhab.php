<?php if(!isset($_COOKIE['authCode'])){die("USER__LOGIN");} ?>
<?php
$zhabInfo = Zhabs::getZhabInformation($_POST['id']);
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if(!empty($user->reason)){
    die('<div class="popup_container">
        <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
        <div><h3 style="margin:5px 0;">Уведомление</h3></div>
        <div style="text-align:center:font-weight:normal!important;margin:20px 0;">
            Мы ограничили вам доступ к Жабблеру.<br>
            За нарушение условий использования Жабблера, ваш профиль был переведён в режим "только чтения".<br>
            Вы можете только смотреть посты. Ваши посты были отправлены в архив.<br>
            Ограничение может быть временным или вечным.<br>
            За большей информацией, <a href="/messages/zhabbler">зайдите в поддержку</a>.
        </div>
    </div>');
}
if($zhabInfo->zhabWhoCanRepost == 1){
    die('<div class="popup_container" id="popupMini">
        <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
        <div class="notify_comn"><div><div><svg xmlns="http://www.w3.org/2000/svg"viewBox="0 0 24 24"><path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10 .002 8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z"></path></svg><div></div><div><span>Возможность делать репосты на этот пост ограничена</span></div></div></div></div>
    </div>');
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
<div id="PPReply">
    <div id="post" class="InReplyTo">
        <div>
            <a href="/profile/<?=$zhabInfo->nickname?>" id="postAvatar">
                <img src="<?=$zhabInfo->profileImage?>" alt="Фотография профиля">
            </a>
            <div id="ReP__-1">
                <span id="ReP__-2"></span>
            </div>
        </div>
        <form method="POST" enctype="multipart/form-data" action="/API/zhabs/create" id="postContent">
            <div id="postAuthor">
                <a href="/profile/<?=$zhabInfo->nickname?>" id="postAuthorItself">
                <?=$zhabInfo->nickname?>
                </a>
            </div>
            <div id="postContentItself">
                <h1>
                <?=$zhabInfo->zhabTitle?>
                </h1>
                <div>
                <?=$zhabInfo->zhabContent?>
                </div>
                
            </div>
        </form>
    </div>
    <div id="post">
        <a href="/profile/<?=$user->nickname?>" id="postAvatar">
            <img src="<?=$user->profileImage?>" alt="Фотография профиля">
        </a>
        <form method="POST" enctype="multipart/form-data" action="/API/zhabs/replyTo" id="postContent">
            <div id="postAuthor" style="overflow: unset!important;">
                <a href="/profile/<?=$user->nickname?>" id="postAuthorItself">
                <?=$user->nickname?>
                </a>
                <div class="postAuthorConfident">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div class="postAuthorDropdown" style="display: none;">
                        <div class="postAuthorDropdownElement">
                            <div class="postAuthorDropdownElementPart">
                                <b>
                                    Кто может комментировать данный пост?
                                </b>
                            </div>
                            <div class="postAuthorDropdownElementPart">
                                <select name="cf_comment" class="select">
                                    <option value="0" selected>Все</option>
                                    <option value="1">Никто</option>
                                </select>
                            </div>
                        </div>
                        <div class="postAuthorDropdownElement">
                            <div class="postAuthorDropdownElementPart">
                                <b>
                                    Кто может репостить данный пост?
                                </b>
                            </div>
                            <div class="postAuthorDropdownElementPart">
                                <select name="cf_repost" class="select">
                                    <option value="0" selected>Все</option>
                                    <option value="1">Никто</option>
                                </select>
                            </div>
                        </div>
                    </div>               
                </div>
            </div>
            <div id="postContentItself">
                <div>
                    <input type="hidden" hidden name="inReplyTo" value="<?=$zhabInfo->zhabURLID?>">
                    <input type="text" name="title" autocomplete="off" maxlength="28" placeholder="Заголовок (необязательно)" class="postH1Input">
                </div>
                <div>
                    <div id="PostContentTools">
                        <label class="PostContentTool" id="audio">
                            <input type="file" accept="audio/*" name="audioFiles[]" id="audioFilesPOPUP" multiple hidden>
                            <i class="fa-solid fa-music"></i>
                        </label>
                        <label class="PostContentTool" id="video">
                            <input type="file" accept="video/*" name="videoFiles[]" id="videoFilesPOPUP" multiple hidden>
                            <i class="fa-solid fa-video-camera"></i>
                        </label>
                        <label class="PostContentTool" id="image">
                            <input type="file" accept="image/*" name="imageFiles[]" id="imageFilesChoosePOPUP" multiple hidden>
                            <i class="fa-solid fa-image"></i>
                        </label>
                        <hr>
                        <div>
                            <button class="PostContentTool" type="button" id="emoji">
                                =)
                            </button>
                            <div class="ui__WMkUTA__oMhVhh" id="js__o6zMOW" style="display: none;">
                                <table id="kaomoji">
                                    <tbody><tr>
                                        <td><span>(* ^ ω ^)</span></td>
                                        <td><span>(´ ∀ ` *)</span></td>
                                        <td><span>٩(◕‿◕｡)۶</span></td>
                                        <td><span>☆*:.｡.o(≧▽≦)o.｡.:*☆</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(o^▽^o)</span></td>
                                        <td><span>(⌒▽⌒)☆</span></td>
                                        <td><span>。.:☆*:･'(*⌒―⌒*)))</span></td>
                                        <td><span>°˖✧◝(⁰▿⁰)◜✧˖°</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>ヽ(・∀・)ﾉ</span></td>
                                        <td><span>(´｡• ω •｡`)</span></td>
                                        <td><span>(￣ω￣)</span></td>
                                        <td><span>｀;:゛;｀;･(°ε° )</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(o･ω･o)</span></td>
                                        <td><span>(＠＾◡＾)</span></td>
                                        <td><span>ヽ(*・ω・)ﾉ</span></td>
                                        <td><span>(o_ _)ﾉ彡☆</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(ﾉ´ з `)ノ</span></td>
                                        <td><span>(♡μ_μ)</span></td>
                                        <td><span>(*^^*)♡</span></td>
                                        <td><span>☆⌒ヽ(*'､^*)chu</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(♡-_-♡)</span></td>
                                        <td><span>(￣ε￣＠)</span></td>
                                        <td><span>ヽ(♡‿♡)ノ</span></td>
                                        <td><span>( ´ ∀ `)ノ～ ♡</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(─‿‿─)♡</span></td>
                                        <td><span>(´｡• ᵕ •｡`) ♡</span></td>
                                        <td><span>(*♡∀♡)</span></td>
                                        <td><span>(｡・//ε//・｡)</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(´ ω `♡)</span></td>
                                        <td><span>♡( ◡‿◡ )</span></td>
                                        <td><span>(◕‿◕)♡</span></td>
                                        <td><span>(/▽＼*)｡o○♡</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(*/_＼)</span></td>
                                        <td><span>(*ﾉωﾉ)</span></td>
                                        <td><span>(o-_-o)</span></td>
                                        <td><span>(*μ_μ)</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>( ◡‿◡ *)</span></td>
                                        <td><span>(ᵔ.ᵔ)</span></td>
                                        <td><span>(*ﾉ∀`*)</span></td>
                                        <td><span>(//▽//)</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(￣▽￣*)ゞ</span></td>
                                        <td><span>(⁄ ⁄•⁄ω⁄•⁄ ⁄)</span></td>
                                        <td><span>(*/▽＼*)</span></td>
                                        <td><span>(⁄ ⁄•⁄ ▽ ⁄•⁄ ⁄)</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(￢_￢;)</span></td>
                                        <td><span>(＞ｍ＜)</span></td>
                                        <td><span>(」°ロ°)」</span></td>
                                        <td><span>(〃＞＿＜;〃)</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(＾＾＃)</span></td>
                                        <td><span>(︶︹︺)</span></td>
                                        <td><span>(￣ヘ￣)</span></td>
                                        <td><span>(￣ ﹌ ￣)</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>ヾ( ￣O￣)ツ</span></td>
                                        <td><span>(⇀‸↼‶)</span></td>
                                        <td><span>o(＞＜ )o</span></td>
                                        <td><span>(」＞＜)」</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>٩(╬ʘ益ʘ╬)۶</span></td>
                                        <td><span>(╬ Ò﹏Ó)</span></td>
                                        <td><span>＼＼٩(๑`^´๑)۶／／</span></td>
                                        <td><span>(凸ಠ益ಠ)凸</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(҂ `з´ )</span></td>
                                        <td><span>(‡▼益▼)</span></td>
                                        <td><span>(҂` ﾛ ´)凸</span></td>
                                        <td><span>((╬◣﹏◢))</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>(μ_μ)</span></td>
                                        <td><span>(ﾉД`)</span></td>
                                        <td><span>(-ω-、)</span></td>
                                        <td><span>。゜゜(´Ｏ`) ゜゜。</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>o(TヘTo)</span></td>
                                        <td><span>( ; ω ; )</span></td>
                                        <td><span>(｡╯︵╰｡)</span></td>
                                        <td><span>｡･ﾟﾟ*(＞д＜)*ﾟﾟ･｡</span></td>
                                    </tr>
                                </tbody></table>
                            </div>
                        </div>
                        <button class="PostContentTool" type="button" id="bold">
                            <i class="fa-solid fa-bold"></i>
                        </button>
                        <button class="PostContentTool" type="button" id="italic">
                            <i class="fa-solid fa-italic"></i>
                        </button>
                        <button class="PostContentTool" type="button" id="underline">
                            <i class="fa-solid fa-underline"></i>
                        </button>
                        <button class="PostContentTool" type="button" id="createLink">
                            <i class="fa fa-link"></i>
                        </button>
                        <button class="PostContentTool" type="button" id="unlink">
                            <i class="fa fa-unlink"></i>
                        </button>
                    </div>
                    <textarea name="content" id="contentTX" hidden></textarea>
                    <div id="postContentInput" class="PlaceholderTRUE" contenteditable="true">
                        <i style="color:#747474;">О чем будем квакать сегодня?</i>
                    </div>
                </div>
            </div>
            <div id="previewFiles" style="display: none;"></div>
            <div id="previewVideos" style="display: none;"></div>
            <div id="previewAudios" style="display: none;"></div>
            <div id="postInfo">
                <button class="button button_gray" type="button" id="CancelTextEditor">
                    Отмена
                </button>
                <button class="button ml-a" id="addPost">
                    Добавить
                </button>
            </div>
        </form>
    </div>
    <script src="/assets/js/TextEditor/index.js"></script>
</div>