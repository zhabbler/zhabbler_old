console.log("Done with main.js");
console.log("Done with loading!");
var page_id = 0;
function likeZhab(id){
    $.post("/API/zhabs/like", {urlID:id}, function(data){
        if(data == 'USER__LOGIN'){
            loginPopup();
        }else if(data == 'banned'){
            $('body').prepend(`<div class="popup">
    <div class="popup_container" id="">
        <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
        <div><h3 style="margin:5px 0;">Уведомление</h3></div>
        <div style="text-align:center:font-weight:normal!important;margin:20px 0;">
            Мы ограничили вам доступ к Жабблеру.<br>
            За нарушение условий использования Жабблера, ваш профиль был переведён в режим "только чтения".<br>
            Вы можете только смотреть посты. Ваши посты были отправлены в архив.<br>
            Ограничение может быть временным или вечным.<br>
            За большей информацией, <a href="/messages/zhabbler">зайдите в поддержку</a>.
        </div>
    </div></div>`);
        }else{
            if($(`.zhab__${id} .SystemLike`).hasClass("SystemLikeTrue")){
                $(`.zhab__${id} .SystemLike`).removeClass("SystemLikeTrue");
            }else{
                $(`.zhab__${id} .SystemLike`).addClass("SystemLikeTrue");
            }
        }
    })
}
function sendAgain(){
    $(".popup").remove();
    $('body').prepend(`<div id="JSLoader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
}
function profileMoreInfoInSideBar() {
    $("#profileMoreInfoInSideBar").show(0);
    if($("#profileMoreInfoInSideBar").css("height") != "174px"){
        $("#profileMoreInfoInSideBar").css("height", "174px");
    }else{
        $("#profileMoreInfoInSideBar").css("height", "0px");
    }
}
function confidentSettings(type){
    if(type == 'msgs'){
        $.post("/API/account/changeConfident", {msgs:$("#cfmsgs").val()});
    }
    if(type == 'birth'){
        $.post("/API/account/changeConfident", {birth:$("#cfbirth").val()});
    }
}
function displayGrid(){
    $("main").prepend("<style>main{min-width:1280px!important;}.PostsGrid{display: grid!important;grid-template-columns: auto auto auto auto;}#postAvatar{display:none!important}#postContent{width:300px!important;}#post{padding:20px 10px!important;}</style>");
}
function sendReply(id){
    $(`.zhab__${id} #postWriteReply button`).prop("disabled", true);
    $(`.zhab__${id} #postWriteReply button`).html("Отправка...");
    $.post("/API/zhabs/sendReply", {id:id, content:$(`.zhab__${id} #replyContentTX`).val()}, function(data){
        if(data == 'USER__LOGIN'){
            loginPopup();
        }else if(data != 'error'){
            $.post("/API/zhabs/getComments", {id:id}, function(data){
                $(`.zhab__${id} #repliesITitSELF`).html(data);
            })
        }
        $(`.zhab__${id} #replyContentTX`).val("");
        $(`.zhab__${id} #postWriteReply button`).html("Отправить");
    })
}
function shareLink(id){
    $("body").prepend(`<div class="popup">
    <div class="popup_container" id="popupMini">
        <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
        <center><br><h3><b>Поделитесь данной ссылкой в соц.сетях:</b></h3></center>
        <input type="text" id="IdentifyOO22ShareFunc" value="https://zhabbler.ru/zhab/${id}?source=share" onclick="document.querySelector('#IdentifyOO22ShareFunc').select();navigator.clipboard.writeText(document.querySelector('#IdentifyOO22ShareFunc').value);$('#infoIG').show();" readonly class="input">
        <b id="infoIG" style="display:none;">Скопировано в буфер обмена.</b>
    </div>
</div>`);
}
function removeZhab(id, bool){
    if(bool == true){
        $("body").prepend("<div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>");
        $('#AlertWarningAboutRemoving').remove();
        $.post("/API/zhabs/removeZhab", {id:id}, function(){
            $(`.zhab__${id}`).remove();
            if($(`.rezhabby__${id}`).length > 0){
                $(`.rezhabby__${id}`).remove();
            }
            $("#JSLoader").remove();
        })
    }else if(bool == false){
        $('body').prepend('<div class="popup" id="AlertWarningAboutRemoving"><div class="popup_container" id="popupMini"><center><h2>Стоп! Вы уверены что хотите удалить данный пост?</h2><br></center><div class="flex ai-c jsc-c"><button class="button" style="margin-right:10px;" onclick="$(`.popup`).remove();">Нет!</button><button class="button button_gray" onclick="removeZhab(`'+id+'`, true)">Да, я уверен</button></div></div></div>');
    }
}
function repostsZhab(id){
    $(`.zhab__${id} .postRLPelement_active`).removeClass("postRLPelement_active");
    $(`.zhab__${id} #repostsZhab`).addClass("postRLPelement_active");
    $(`.zhab__${id} #postWriteReply`).hide(0);
    $(`.zhab__${id} #repliesITitSELF`).hide(0);
    $(`.zhab__${id} #commentsITitSELF`).show(0);
    $(`.zhab__${id} #likesITitSELF`).hide(0);
    $.post("/API/zhabs/getReposts", {id:id}, function(data){
        if(data.trim() == ''){
            $(`.zhab__${id} #commentsITitSELF`).html(`<center style="margin:1em 0;">Тут нет репостов! Сделайте первый репост под этим постом!</center>`);
        }else{
            $(`.zhab__${id} #commentsITitSELF`).html(data);
        }
    })
}
function likesZhab(id){
    $(`.zhab__${id} .postRLPelement_active`).removeClass("postRLPelement_active");
    $(`.zhab__${id} #likesZhab`).addClass("postRLPelement_active");
    $(`.zhab__${id} #postWriteReply`).hide(0);
    $(`.zhab__${id} #repliesITitSELF`).hide(0);
    $(`.zhab__${id} #commentsITitSELF`).hide(0);
    $(`.zhab__${id} #likesITitSELF`).show(0);
    $.post("/API/zhabs/getLikes", {id:id}, function(data){
        if(data.trim() == ''){
            $(`.zhab__${id} #likesITitSELF`).html(`<center style="margin:1em 0;">Тут вообще нет лайков! Нажмите на кнопку лайка, а автору будет приятно!</center>`);
        }else{
            $(`.zhab__${id} #likesITitSELF`).html(data);
        }
    })
}
function showReplies(id){
    $(`.zhab__${id} .postRLPelement_active`).removeClass("postRLPelement_active");
    $(`.zhab__${id} #commentsZhab`).addClass("postRLPelement_active");
    $(`.zhab__${id} #postWriteReply`).show(0);
    $(`.zhab__${id} #repliesITitSELF`).show(0);
    $(`.zhab__${id} #commentsITitSELF`).hide(0);
    $(`.zhab__${id} #likesITitSELF`).hide(0);
}
function repliesZhab(id){
    if($(`.zhab__${id} #postReplies`).length == 0){
        $(`.zhab__${id} #postContent`).append(`<div id="postReplies">
        <div id="postRepliesLikesReposts">
        <div class="postRLPelement postRLPelement_active" id="commentsZhab" onclick="showReplies('${id}')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
            </svg>
            <span id="commentsCount">...</span>
        </div>
        <div class="postRLPelement" id="repostsZhab" onclick="repostsZhab('${id}')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" />
            </svg>
            <span id="repostsCount">...</span>
        </div>
        <div class="postRLPelement" id="likesZhab" onclick="likesZhab('${id}')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
            <span id="likesCount">...</span>
        </div>
    </div>
        <div id="postWriteReply">
            <div>
                <img src="/assets/images/no_avatar_1900.png" id="SYSaVA" alt="Фотография профиля">
            </div>
            <div id="postWriteReplyIDENT02">
                <textarea maxlength="128" id="replyContentTX" data-for="${id}" name="replyContent" placeholder="напишите свой комментарий"></textarea>
                <button onclick="sendReply('${id}')" disabled>Отправить</button>
            </div>
        </div>
        <center id="ccLLoader">
            <span class="loading"><span id="ldF"></span><span id="ldS"></span><span id="ldT"></span></span>
        </center>
        <div id="repliesITitSELF"></div>
        <div id="commentsITitSELF" style="display:none"><span class="loading"><span id="ldF"></span><span id="ldS"></span><span id="ldT"></span></span></div>
        <div id="likesITitSELF" style="display:none"><span class="loading"><span id="ldF"></span><span id="ldS"></span><span id="ldT"></span></span></div>
    </div>`);
        $.post("/API/account/getProfileImage", function(data){
            $(`.zhab__${id} #SYSaVA`).attr('src', data);
        })
        $.post("/API/zhabs/totalCountOfPostsIntegers", {postID:id, which:1}, function(data){
            $(`.zhab__${id} #commentsCount`).html(data);
        })
        $.post("/API/zhabs/totalCountOfPostsIntegers", {postID:id, which:2}, function(data){
            $(`.zhab__${id} #repostsCount`).html(data);
        })
        $.post("/API/zhabs/totalCountOfPostsIntegers", {postID:id, which:3}, function(data){
            $(`.zhab__${id} #likesCount`).html(data);
        })
        $.post("/API/zhabs/getComments", {id:id}, function(data){
            if(data == '<div class="notify_comn"><div><div><svg xmlns="http://www.w3.org/2000/svg"viewBox="0 0 24 24"><path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zm6 10 .002 8H6v-8h12zm-9-2V7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9z"></path></svg><div></div><div><span>Возможность комментирования ограничена</span></div></div></div></div>'){
                $("#postWriteReply").remove();
            }
            $(`.zhab__${id} #repliesITitSELF`).html(data);
            $(`.zhab__${id} #ccLLoader`).remove();
        })
    }else{
        $(`.zhab__${id} #postReplies`).remove();
    }
}
$(document).ready(function(){
    setInterval(() => {
        $("#JS__Script").each(function(){
            $(this).replaceWith($('<script>' + $(this).text() + '</script>'));
        })
    }, 500);
    $(document).on("click", ".zhabsPhotoItself", function(){
        $('body').prepend(`<div class='popup photoViewer'><div class='popup_container'><div class="popup_close" style="display:none;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div><span class="loading"><span id="ldF"></span><span id="ldS"></span><span id="ldT"></span></span></div></div>`);
        $.post("/API/photos/getPhotoSrcByID", {id:$(this).data("photoid")}, function(data){
            if(data == 'error'){
                location.reload();
            }else{
                $(".photoViewer .popup_container").append("<img src='"+data+"'>");
            }
            $('.popup_container .loading').remove();
            $('.popup_container .popup_close').show();
        })
    })
    $(document).on("click", ".settingsChngPrms", function(){
        if($(".settingsButtonIcon").hasClass("settingsButtonIconBeingShowd")){
            $(".settingsButtonIcon").removeClass("settingsButtonIconBeingShowd");
            $(this).removeClass("button_transparent");
            $(this).addClass("button_white");
            $(this).text("Изменить");
        }else{
            $(".settingsButtonIcon").addClass("settingsButtonIconBeingShowd");
            $(this).addClass("button_transparent");
            $(this).removeClass("button_white");
            $(this).text("Отменить");
        }
    })
    $(document).on("click", ".SystemReply", function(){
        $('body').prepend("<div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>");
        $.post("/API/zhabs/replyToZhab", {id:$(this).data("replyto")}, function(data){
            if(data == 'USER__LOGIN'){
                loginPopup();
            }else{
                $('body').prepend(`<div class='popup sysEditor'>${data}</div>`);
            }
            $('#JSLoader').remove();
        })
    })
    $(document).on("click", ".zhabTag", function(){
        $.post("/API/tags/removeTag", {tag:$(this).html()}, function(){
            location.reload();
        })
    })
    $(document).on("click", "#NVLOg", function(){
        window.location.href = '/';
        return false;
    });
    $(document).on("click", ".navbarContentMain a", function(){
        window.location.href = $(this).attr("href");
        return false;
    });
    $(document).on("click", ".zhabVideo", function(){
        $('body').prepend(`<div id="JSLoader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
        $.post('/API/zhabs/getVideo', {id:$(this).data("videoid")}, function(data){
            $("body").prepend(data);
            $("#JSLoader").remove();
        })
    })
    if($('#SysMessagesCounter').length == 0){
        $("#sidebarElement[href='/messages']").append('&nbsp;<span id="SysMessagesCounter">...</span>')
    }
    if($("#sidebarElement[href='/messages']").length > 0){
        $.get("/API/messages/messagesCounter", function(data){
            if(data.length < 13){
                $("#SysMessagesCounter").html(data);
            }
        })
    }
    if($("#sidebarElement[href='/messages']").length > 0){
        $.get("/API/messages/messagesCounter", function(data){
            if(data != '' && data.length < 11){
                if(document.title == 'Жабблер - квакайте о чем угодно!'){
                    if(data == '(1)'){
                        document.title = '1 новое сообщение!';
                    }else{
                        document.title = data.substring(1, data.length - 1) + ' новых сообщений!';
                    }
                }else{
                    document.title = 'Жабблер - квакайте о чем угодно!';
                }
            }
        })
    }
    $(document).on('click', '#ntfies', function(event){
        return false;
    })
    $(document).on('input', '.inputSrchBar', function(){
        $(".ui__3EvavR__gLKNOP").remove();
        if($(this).val() != ''){
            $(this).after(`<div class="ui__3EvavR__gLKNOP" style="display:none;"><span class='loading' style="filter:invert(100%);padding:20px 0;"><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
            $(".ui__3EvavR__gLKNOP").fadeIn(200);
            $.post("/API/account/searchUsers", {value:$(this).val()}, function(data){
                $(".ui__3EvavR__gLKNOP").html(data);
            })
        }
    })
    $(document).on('click', '#postReplyRemove', function(){
        $(this).html('<span class="loader2"></span>');
        $.post("/API/zhabs/removeComment", {id:$(this).data('remove')})
        $(`.reply__${$(this).data('remove')}`).remove();
    })
    $(document).on('click', '.ProfileToolsEdit', function(){
        $(this).find('.NeedsToHide').hide(0);
        $(this).find('#WhatNeedsToChange').show(0);
    })
    $(document).on("click", "#profileMoreInfoInSideBar #sidebarElement", function(){
        profileMoreInfoInSideBar();
    })
    $(document).on("click", ".input__color", function(){
        if(!$(this).hasClass("input__color__val")){
            $(".input__colors").css("animation", "");
            if(!$(".input__colors").hasClass("input__colors__showing")){
                $(".input__colors").css("display", "");
                $(".input__colors").css("animation", "input__colors .2s");
                $(".input__colors").addClass("input__colors__showing");
            }else{
                $(".input__colors").css("animation", "input__colors__rev .2s");
                setTimeout(() => {
                    $(".input__colors").css("display", "none");
                }, 190);
                $(".input__colors").removeClass("input__colors__showing");
            }
        }else{
            const rgba2hex = (rgba) => `#${rgba.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+\.{0,1}\d*))?\)$/).slice(1).map((n, i) => (i === 3 ? Math.round(parseFloat(n) * 255) : parseFloat(n)).toString(16).padStart(2, '0').replace('NaN', '')).join('')}`
            $.post("/API/account/changeColorPallete", {color:rgba2hex($(this).find(".input__color__itself").css("backgroundColor"))}, function(data){
                if(data != "error"){
                    $("style").remove();
                    $("body").prepend(`<style>@import url("https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&display=swap");.profileInformationCover{background:${data};}a,a:hover,a:active,a:visited{color:${data};}.button, .ui__gv0c67__S1C3Hd, .ui__JinNxQ__J3pBJx, #repostAvatarIcon{background-color:${data};}#tabs #tab.tab_active{box-shadow: 0 2px 0 0 ${data};}#sidebar #sidebarLogotype::before, #sidebar:hover #sidebarLogotype::before{background:${data};background-size: 100% 100%;-webkit-background-clip: text;-moz-background-clip: text;background-clip: text;}</style>`);
                    document.querySelector("body").insertAdjacentHTML("beforeBegin", `<div class="success">Цветовая палитра успешно изменена.</div>`);setTimeout(() => {document.querySelector(".success").remove();}, 5000)
                }
            })
        }
    })
    // $(document).on("click", '#sidebarElement', function(){
    //     if(typeof $(this).attr('onclick') === 'undefined' && $(this).attr('onclick') !== true){
    //         if($(window).width() < 900){
    //             $("#sidebar").removeClass("sidebarShowingTrue");
    //             $("body").css("overflow", "");
    //             $("main").fadeIn(500);
    //         }
    //         return false;
    //     }
    // });
    $(document).on("click", 'a', function(){
        if($(this).attr("href") == '#'){
            return false;
        }
    });
    $(document).on("click", "#settings__changeTheme", function(){
        var elem = $(this);
        $.post("/API/themes/changeTheme", function(data){
            if(data == 'On'){
                elem.html("Выключить тёмную тему");
                $("head").prepend('<style id="dark">@import url("/assets/css/dark.css");</style>');
            }else{
                elem.html("Включить тёмную тему");
                $("#dark").remove();
            }
        });
        return false;
    })
    $('#JSLoader').remove();
})
// window.addEventListener('popstate', function (event) {
//     console.log(`location: ${document.location}, state: ${JSON.stringify(history.state)}`,);
//     togo = `${document.location} #SysContentPage`;
//     if($("#mainContent").length == 0){
//         location.href = document.location;
//         return false;
//     }
//     $('html,body').scrollTop(0);
//     $("#id000alsA").remove();
//     $("#mainContent").load(togo, function(){
//         window.history.pushState({page:page_id++}, 'Жабблер', href);
//     })
// }, false);
// function goToPage(href){
//     console.log(`location: ${document.location}, state: ${JSON.stringify(history.state)}`);
//     $("#application").prepend(`<div class="loader_bar" style="display:none;"></div>`);
//     setTimeout(() => {
//         $(".loader_bar").fadeIn(500);
//     }, 500);
//     togo = `${href} #SysContentPage`;
//     $('html,body').scrollTop(0);
//     $("#id000alsA").remove();
//     $(`#sidebarElement[href='${href}']`).addClass("sidebarElementClicked");
//     if($("#mainContent").length == 0){
//         location.href = href;
//         return false;
//     }
//     $("#mainContent").load(togo, function(){
//         window.history.pushState({page:page_id++}, 'Жабблер', href);
//         $(".loader_bar").fadeOut(500);
//         setTimeout(() => {
//             $(".loader_bar").remove();
//         }, 500);
//     })
// }
function sendAgain(){
    location.href = "/API/account/sendEmailVerification";
}
function openDropdown(id){
    $(id).slideToggle(0);
}
function Follow(nickname, button){
    if(button == "y"){
        $(`#FollowTo${nickname}`).html("<span class='loading loadingCC'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span>");
    }else{
        $(`#ButtonFollowTo${nickname}`).html("<span class='loading loadingMini'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span>");
    }
    $.post("/API/account/follow", {to:nickname}, function(data){
        if(data == "nothing"){
            location.reload();
        }else if(button == "y"){
            if(data == "yes"){
                $(`#FollowTo${nickname}`).html(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M14 11h8v2h-8zM4.5 8.552c0 1.995 1.505 3.5 3.5 3.5s3.5-1.505 3.5-3.5-1.505-3.5-3.5-3.5-3.5 1.505-3.5 3.5zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2z"></path></svg>
                Отписаться                                         `);
            }else{
                $(`#FollowTo${nickname}`).html(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M4.5 8.552c0 1.995 1.505 3.5 3.5 3.5s3.5-1.505 3.5-3.5-1.505-3.5-3.5-3.5-3.5 1.505-3.5 3.5zM19 8h-2v3h-3v2h3v3h2v-3h3v-2h-3zM4 19h10v-1c0-2.757-2.243-5-5-5H7c-2.757 0-5 2.243-5 5v1h2z"></path></svg>
                Подписаться                           `);
            }
        }else if(button == "n"){
            $(`#ButtonFollowTo${nickname}`).remove();
        }
    })
}
function getZhabInfo(id){
    $("body").prepend(`<div class='popup' style="z-index:2047!important"><div class="ContentGoesRightThere"><span class="loading"><span id="ldF"></span><span id="ldS"></span><span id="ldT"></span></span></div></div>`)
    $.post("/API/zhabs/getZhabItself", {id:id}, function(data){
        $(".popup .ContentGoesRightThere").html(data);
    })
}
function Notifies(){
    if($('#ntfies').length > 0){
        $('#ntfies').remove();
    }else{
        $('#sidebarElement[onclick="Notifies();"]').prepend(`<div class="sysDrop" id="ntfies">
    <div class="sysDropHeader">
        <div>Уведомления</div>
    </div>
    <div class="sysDropMainContent">
    <span class="loading"><span id="ldF" style="background: rgba(0, 0, 0, 1)!important;"></span><span id="ldS" style="background: rgba(0, 0, 0, 1)!important;"></span><span id="ldT" style="background: rgba(0, 0, 0, 1)!important;"></span></span>
    </div>
</div>`)
        $.post("/API/zhabs/getNotifies", function(data){
            $('#ntfies .sysDropMainContent').html(data);
        })
    }
}
function sendChangingEmail(){
    $(".popup").remove();
    $('body').prepend(`<div id="JSLoader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
    $.post("/API/account/changeEmailLetter", function(data){
        if(data == 'success'){
            $("#JSLoader").remove();
            $('body').prepend(`<div class="popup">
            <div class="popup_container" id="popupMini">
                <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div><h3 style="margin:5px 0;">Уведомление</h3></div>
                <center style="text-align:center:font-weight:normal!important;margin:20px 0;">
                    Письмо с ссылкой на изменение почты успешно доставлено.
                </center>
                <div style="text-align:center;">
                    <button class="button" onclick="$('.popup').remove()">Хорошо</button>
                </div>
            </div></div>`);
        }
    })
}
function InformationAboutChangingEmail(){
    $('body').prepend(`<div class="popup">
    <div class="popup_container" id="popupMini">
        <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
        <div><h3 style="margin:5px 0;">Уведомление</h3></div>
        <center style="text-align:center:font-weight:normal!important;margin:20px 0;">
            Имейте ввиду, что ссылка для изменения почты придёт на вашу старую почту.<br>
            Если возникли затруднения или не можете получить доступ к старой почте, <a href="/messages/zhabbler">напишите нам.</a>
        </center>
        <div style="text-align:center;">
            <button class="button button_gray" onclick="$('.popup').remove()">Отменить</button>
            <button class="button" onclick="sendChangingEmail()">Продолжить</button>
        </div>
    </div></div>`);
}
function ChangePassword(){
    $(".popup").remove();
    $('body').prepend(`<div id="JSLoader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
    $.post("/API/account/changePasswordLetter", function(data){
        if(data == 'success'){
            $("#JSLoader").remove();
            $('body').prepend(`<div class="popup">
            <div class="popup_container" id="popupMini">
                <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                <div><h3 style="margin:5px 0;">Уведомление</h3></div>
                <center style="text-align:center:font-weight:normal!important;margin:20px 0;">
                    Письмо с ссылкой на изменение пароля успешно доставлено.
                </center>
                <div style="text-align:center;">
                    <button class="button" onclick="$('.popup').remove()">Хорошо</button>
                </div>
            </div></div>`);
        }
    })
}
function changeGender(){
    // $('body').prepend(`<div id="JSLoader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
    $('.ui__0SWCHY__k98vZ1').show(0);
    $('#GenderSettings').hide(0);
    $.post("/API/account/changeGender", {gender:$("#gender").val()}, function(data){
        if(data == 'error'){
            location.reload();
        }else if(data == 'success'){
            $('.ui__0SWCHY__k98vZ1').hide(0);
            $('#GenderSettings').show(0);
        }
    })
}
function logoutFromAllSessions(){
    $('body').prepend(`<div id="JSLoader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
    $.post("/API/account/logoutFromAllSessions", function(){
        location.reload();
    })
}
function submitChngPfp(e){
    if(document.getElementById('imageACChid').files.length > 0){
        $(".settingsButtonIcon").hide(0);
        $("#system__settings__pfp").prepend(`<div class="system__settings__pfp__loader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
        var formData = new FormData();
        formData.append("image_avatar", document.getElementById('imageACChid').files[0]);
        $.ajax({
            type: "POST",
            url: "/API/account/changeProfilePicture/",
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData: false,
            error: function(){
                alert("Не удалось добавить вашу фотографию.");
                location.reload();
            }
        }).done(function(data){
            if(data != 'error'){
                $("#system__settings__pfp img").attr("src", data);
                $(".system__settings__pfp__loader").remove();
                $(".settingsChngPrms").click();
                document.querySelector("body").insertAdjacentHTML("beforeBegin", `<div class="success">Фотография профиля успешно изменена.</div>`);setTimeout(() => {document.querySelector(".success").remove();}, 5000)
            }else{
                location.reload();
            }
            $(".settingsButtonIcon").show(0);
        })
    }
    return false;
}
function submitChngCover(e){
    if(document.getElementById('imageACCovhid').files.length > 0){
        $(".settingsButtonIcon").hide(0);
        $("#profileInformationCover").prepend(`<div class="system__settings__cover__loader"><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>`);
        var formData = new FormData();
        formData.append("image_cover", document.getElementById('imageACCovhid').files[0]);
        $.ajax({
            type: "POST",
            url: "/API/account/changeProfileCover/",
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData: false,
            error: function(){
                alert("Не удалось добавить вашу фотографию.");
                location.reload();
            }
        }).done(function(data){
            if(data != 'error'){
                $(".profileInformationCover").css("background", "");
                $(".profileInformationCover").attr("style", `background: url(${data}) center/cover!important;`);
                $(".system__settings__cover__loader").remove();
                $(".settingsChngPrms").click();
                document.querySelector("body").insertAdjacentHTML("beforeBegin", `<div class="success">Обложка профиля успешно изменена.</div>`);setTimeout(() => {document.querySelector(".success").remove();}, 5000)
            }else{
                location.reload();
            }
            $(".settingsButtonIcon").show(0);
        })
    }
    return false;
}