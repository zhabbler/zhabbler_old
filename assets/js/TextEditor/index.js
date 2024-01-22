$(document).ready(function(){
    $(document).on("click", ".postAuthorConfident svg", function(){
        $(".postAuthorDropdown").fadeToggle(200);
    })
    $(document).on('change', '#imageFilesChoosePOPUP', function(){
        var totalFiles = $(this).get(0).files.length;
        $('#previewFiles').html('');
        $('#previewFiles').css('display', 'block');
		if($(this)[0].files.length < 11){
			for(var i = 0; i < totalFiles; i++){
	          	$('#previewFiles').append("<img class='previewImgPostPost' src = '"+URL.createObjectURL(event.target.files[i])+"'>");
	        }
		}else{
			alert("Вы не можете прикрепить больше 10 фотографий");
		}
    })
    $(document).on('change', '#audioFilesPOPUP', function(){
        var totalFiles = $(this).get(0).files.length;
        $('#previewAudios').html('');
        $('#previewAudios').css('display', 'block');
        if($(this)[0].files.length < 11){
            for(var i = 0; i < totalFiles; i++){
                $('#previewAudios').append("<audio controls src = '"+URL.createObjectURL(event.target.files[i])+"'></audio>");
            }
        }else{
            alert("Вы не можете прикрепить больше 5 аудио");
        }
    })
    $(document).on('change', '#videoFilesPOPUP', function(){
        var totalFiles = $(this).get(0).files.length;
        $('#previewVideos').css('display', 'block');
        $('#previewVideos').html('');
        if($(this)[0].files.length < 11){
            for(var i = 0; i < totalFiles; i++){
                $('#previewVideos').append("<video controls src = '"+URL.createObjectURL(event.target.files[i])+"'></video>");
            }
        }else{
            alert("Вы не можете прикрепить больше 5 видео");
        }
    })
	$(document).on('focusin', '#postContentInput', function(){
        if($('#postContentInput').hasClass('PlaceholderTRUE')){
            $('#postContentInput').text('');
            $('#postContentInput').removeClass('PlaceholderTRUE');
        }
    })
    $(document).on('click', '#postContentInput', function(){
        $("#js__o6zMOW").css('display', 'none');
    })
    $(document).on('click', '#emoji', function(e){
        if($("#js__o6zMOW").css('display') == 'block'){
            $("#js__o6zMOW").css('display', 'none');
        }else{
            $("#js__o6zMOW").css('display', 'block');
        }
        e.stopPropagation();
    })
    $(document).on('click', '#js__o6zMOW td', function(e){
        if($('#postContentInput').hasClass('PlaceholderTRUE')){
            $('#postContentInput').text('');
            $('#contentTX').val('');
            $('#postContentInput').removeClass('PlaceholderTRUE');
        }
        $('#postContentInput').html($('#postContentInput').html() + $(this).find('span').text());
        $('#contentTX').val($('#contentTX').val() + $(this).find('span').text());
        $("#js__o6zMOW").css('display', 'none');
    })
    $(document).on('focusout', '#postContentInput', function(){
        if($('#postContentInput').text() == ''){
            $('#postContentInput').html('<i style="color:#747474;">О чем будем квакать сегодня?</i>');
            if(!$('#postContentInput').hasClass("PlaceholderTRUE")){
                $('#postContentInput').addClass('PlaceholderTRUE');
            }
        }
    })
    $(document).on('click', '#bold', function(){
        // if($(this).hasClass("PostContentToolClicked")){
        //     $(this).removeClass("PostContentToolClicked");
        // }else{
        //     $(this).addClass("PostContentToolClicked");
        // }
        document.execCommand("bold",false,null);
    })
    $(document).on('click', '#italic', function(){
        // if($(this).hasClass("PostContentToolClicked")){
        //     $(this).removeClass("PostContentToolClicked");
        // }else{
        //     $(this).addClass("PostContentToolClicked");
        // }
        document.execCommand("italic",false,null);
    })
    $(document).on('click', '#createLink', function(){
        let userLink = prompt("Введите URL");
        if(userLink !== '' && userLink){
            if(/http/i.test(userLink)){
                document.execCommand('createLink', false, userLink);
            }else{
                userLink = "http://" + userLink;
                document.execCommand('createLink', false, userLink);
            }
        }
    })
    document.getElementById("postContentInput").addEventListener('input', function(){
        document.getElementById('contentTX').value = document.getElementById("postContentInput").innerHTML;
    }, false)
    $(document).on('click', '#unlink', function(){
        document.execCommand('unlink', false, null);
    })
    $(document).on('click', '#underline', function(){
        // if($(this).hasClass("PostContentToolClicked")){
        //     $(this).removeClass("PostContentToolClicked");
        // }else{
        //     $(this).addClass("PostContentToolClicked");
        // }
        document.execCommand("underline",false,null);
    })
    document.querySelector('#JSLoader').remove();
})