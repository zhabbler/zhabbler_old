console.log("Done with ui.js");
$(document).ready(function(){
    $(document).on('focusout', '.input', function(){
        if($(this).val() == ''){
            $('.formLabel[for=' + $(this).attr('id') + ']').removeClass('formLabelFocused');
        }
    })
    $(document).on("click", ".mobile_nav_el", function(){
        if(!$(this).hasClass("mobile_nav_el_search")){
            if($("#options").hasClass("optionsShowingTrue")){
                $("#options").removeClass("optionsShowingTrue");
            }
            if(!$("#sidebar").hasClass("sidebarShowingTrue")){
                $("#sidebar").addClass("sidebarShowingTrue");
                $("body").css("overflow", "hidden");
                $("main").fadeOut(500);
            }else{
                $("#sidebar").removeClass("sidebarShowingTrue");
                $("body").css("overflow", "");
                $("main").fadeIn(500);
            }
        }else{
            if(!$("#options").hasClass("optionsShowingTrue")){
                $("#options").addClass("optionsShowingTrue");
                $("body").css("overflow", "hidden");
                $("main").fadeOut(500);
            }else{
                $("#options").removeClass("optionsShowingTrue");
                $("body").css("overflow", "");
                $("main").fadeIn(500);
            }
        }
    })
    $(document).on("click", ".mobile_nav_logo", function(){
        location.href = '/dashboard';
        return false;
    })
    $(document).on('focusin', '.input', function(){
        if(!$('.formLabel[for=' + $(this).attr('id') + ']').hasClass('formLabelFocused')){
            $('.formLabel[for=' + $(this).attr('id') + ']').addClass('formLabelFocused');
        }
    })
    $(document).on('click', '.popup_close', function(){
        $('.popup:first').animate({
            opacity: 0,
            transform: "scale(.9) translate(-55%, -55%)"
        }, 200, function(){
            $('.popup:first').remove();
        });
    })
    $(document).on('click', '.popup_container', function(e){
        e.stopPropagation();
    })
    $(document).on('click', '.popup #post', function(e){
        e.stopPropagation();
    })
    $(document).on('submit', 'form', function(){
        $('body').prepend("<div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>");
    })
    $(document).on("input", "#postWriteReplyIDENT02 textarea", function () {
        this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
        if($(this).val().trim().length < 1 || $(this).val().trim().length > 128){
            $(`.zhab__${$(this).data("for")} #postWriteReply button`).prop("disabled", true);
        }else{
            $(`.zhab__${$(this).data("for")} #postWriteReply button`).prop("disabled", false);
        }
        this.style.height = 0;
        this.style.height = (this.scrollHeight) + "px";
    });
    $(document).on("mouseover", "#postAction", function(){
        if($(this).find(".ariaLabelVisible").length == 0 && typeof $(this).data("arialabel") !== 'undefined'){
            $(this).prepend(`<div class="ariaLabelVisible">${$(this).data("arialabel")}</div>`);
        }
    })
    $(document).on("mouseout", "#postAction", function(){
        if($(this).find(".ariaLabelVisible").length > 0){
            $(this).find(".ariaLabelVisible").remove();
        }
    })
    $(document).on("input", "#messageContent", function () {
        this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
        this.style.height = 0;
        this.style.height = (this.scrollHeight) + "px";
    });
    $(document).on('click', '.buttonWritePost', function(){
        $('body').prepend("<div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>");
        $('body').prepend("<div class='popup sysEditor'></div>");
        $('.popup').load('/assets/popups/create_post.html');
    })
    $(document).on('click', '#AlertWarningAboutTextEditor', function(e){
        e.stopPropagation();
    })
    $(document).on('click', '.popup', function(e){
        if(!$(this).hasClass("sysEditor")){
            $('.popup:first').fadeOut(200);
            setTimeout(() => {
                $('.popup:first').remove();
            }, 200);
        }
    })
    $(document).on('click', '.questions__ui__W34zDQ__UhYcYy', function(e){
        e.stopPropagation();
    })
    $(document).on('click', '.questions__ui__WEzu16__9DJENA', function(e){
        $('.questions__ui__WEzu16__9DJENA').remove();
    })
    $(document).on('click', '#CancelTextEditor', function(){
        $('.sysEditor').hide();
        $('body').prepend('<div class="popup sysEditor" id="AlertWarningAboutTextEditor"><div class="popup_container" id="popupMini"><center><h2>Стоп! Вы уверены что хотите выйти из редактора?</h2><br></center><div class="flex ai-c jsc-c"><button class="button" style="margin-right:10px;" onclick="$(`#AlertWarningAboutTextEditor`).remove();$(`.sysEditor`).show();">Нет!</button><button class="button button_gray" onclick="$(`.popup`).remove()">Да, я уверен</button></div></div></div>');
    })
    if($('#navbar').length > 0){
        prevScrollpos = window.pageYOffset;
        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;
            if (prevScrollpos > window.pageYOffset) {
              document.getElementById("navbar").style.top = "0";
              document.getElementById("options").style.top = '70px';
            } else {
              document.getElementById("navbar").style.top = "-100px";
              document.getElementById("options").style.top = '0';
            }
            prevScrollpos = currentScrollPos;
        }
    }
})
function loginPopup(){
    $('body').prepend("<div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>");
    $('body').prepend("<div class='popup'></div>");
    $('.popup').load('/assets/popups/login.html');
}
function Logout(){
    $('body').prepend("<div id='JSLoader'><span class='loading'><span id='ldF'></span><span id='ldS'></span><span id='ldT'></span></span></div>");
    $.post("/API/account/logout", function(){
        location.href = '/';
    })
    return false;
}