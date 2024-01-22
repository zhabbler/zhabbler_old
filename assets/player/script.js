$(document).on('click', '.zhabblerPlayerControlsSound', function(){
    if(document.querySelector($(this).data("for")).volume > 0){
        document.querySelector($(this).data("for")).volume = 0;
        $(this).html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
<path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM17.78 9.22a.75.75 0 10-1.06 1.06L18.44 12l-1.72 1.72a.75.75 0 001.06 1.06l1.72-1.72 1.72 1.72a.75.75 0 101.06-1.06L20.56 12l1.72-1.72a.75.75 0 00-1.06-1.06l-1.72 1.72-1.72-1.72z" />
</svg>
`);
    }else{
        document.querySelector($(this).data("for")).volume = 1;
        $(this).html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM18.584 5.106a.75.75 0 011.06 0c3.808 3.807 3.808 9.98 0 13.788a.75.75 0 11-1.06-1.06 8.25 8.25 0 000-11.668.75.75 0 010-1.06z" />
            <path d="M15.932 7.757a.75.75 0 011.061 0 6 6 0 010 8.486.75.75 0 01-1.06-1.061 4.5 4.5 0 000-6.364.75.75 0 010-1.06z" />
          </svg> 
`);
    }
})
$(document).on('click', '.zhabblerPlayerControlsFullScreen', function(){
    if(document.querySelector($(this).data("for")).requestFullscreen){
    document.querySelector($(this).data("for")).requestFullscreen();
    }else if (document.querySelector($(this).data("for")).webkitRequestFullscreen){
        document.querySelector($(this).data("for")).webkitRequestFullscreen();
    }else if (document.querySelector($(this).data("for")).msRequestFullscreen){
        document.querySelector($(this).data("for")).msRequestFullscreen();
    }
    if(document.exitFullscreen){
        document.exitFullscreen();
    }else if (document.webkitExitFullscreen){
        document.webkitExitFullscreen();
    }else if (document.msExitFullscreen){
        document.msExitFullscreen();
    }
})
$(document).on('click', '.zhabblerPlayerControlsPlay', function(){
    $(this).removeClass("zhabblerPlayerControlsPlay");
    $(this).addClass("zhabblerPlayerControlsPause");
    $(this).html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
<path fill-rule="evenodd" d="M6.75 5.25a.75.75 0 01.75-.75H9a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75V5.25zm7.5 0A.75.75 0 0115 4.5h1.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75V5.25z" clip-rule="evenodd" />
</svg>
`);
    document.querySelector($(this).data('play')).play();
})
$(document).on('click', '.zhabblerPlayerControlsPause', function(){
    $(this).addClass("zhabblerPlayerControlsPlay");
    $(this).removeClass("zhabblerPlayerControlsPause");
    $(this).html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
        </svg>  `);
    document.querySelector($(this).data('play')).pause();
})
document.querySelector('video').addEventListener("ended", function(e){
    $('.zhabblerPlayerControlsPause[data-play="#' + this.id + '"]').addClass("zhabblerPlayerControlsPlay");
    $('.zhabblerPlayerControlsPlay[data-play="#' + this.id + '"]').removeClass("zhabblerPlayerControlsPause");
    $('.zhabblerPlayerControlsPlay[data-play="#' + this.id + '"]').html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
        </svg>  `);
})
document.querySelector('video').addEventListener("click", function(e){
    $('.zhabblerPlayerControlsPlayPause[data-play="#' + this.id + '"]').click();
})
document.querySelector('video').addEventListener("timeupdate", function(e){
    $('.zhabblerDurProgress[data-for="#' + this.id + '"] .zhabblerDurProgressActive').css("width", (this.currentTime / this.duration) * 100 + '%');
})
$(document).on("click", ".zhabblerDurProgress", function(e){
    document.querySelector($(this).data("for")).currentTime = (e.offsetX / document.querySelector('.zhabblerDurProgress[data-for="'+ $(this).data("for") +'"]').offsetWidth) * document.querySelector($(this).data("for")).duration;
})