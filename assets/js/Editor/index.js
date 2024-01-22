$(document).ready(function(){
    $(".loader").fadeOut(500);
    $(document).on("submit", "#profFormSettings", function(){
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(){
                window.location.reload();
            },
        });
        return false;
    })
})
function bodyhtml(){
    if($('.sidebar').css('width') == '600px'){
        $('.sidebar').css('width', '300px');
        $('#pgitself').css('margin-left', '300px');
        $(".sidebar #body").hide(0);
        $(".sidebar #home").fadeIn(500);
    }else{
        $('.sidebar').css('width', '600px');
        $('#pgitself').css('margin-left', '600px');
        $(".sidebar #body").fadeIn(500);
        $(".sidebar #home").hide(0);
    }
}
function profileSettings(){
    if($('.sidebar #profile').css('display') != 'none'){
        $(".sidebar #profile").hide(0);
        $(".sidebar #home").fadeIn(500);
    }else{
        $(".sidebar #profile").fadeIn(500);
        $(".sidebar #home").hide(0);
    }
}
function css(){
    if($('.sidebar').css('width') == '600px'){
        $('.sidebar').css('width', '300px');
        $('#pgitself').css('margin-left', '300px');
        $(".sidebar #css").hide(0);
        $(".sidebar #home").fadeIn(500);
    }else{
        $('.sidebar').css('width', '600px');
        $('#pgitself').css('margin-left', '600px');
        $(".sidebar #css").fadeIn(500);
        $(".sidebar #home").hide(0);
    }
}