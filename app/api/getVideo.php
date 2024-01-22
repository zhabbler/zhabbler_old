<?php
if(isset($_POST['id'])){
    if(DB::RowCount("SELECT * FROM videos WHERE videoID = :id", [":id" => $_POST['id']]) > 0){
        $video = DB::Query("SELECT * FROM videos WHERE videoID = :id", true, false, [":id" => $_POST['id']]);
        $zhab = Zhabs::getZhabInformation($video->videoTo);
    }else{
        die("Не удалось загрузить видео.");
    }
}else{
    die("Не удалось загрузить видео.");
}
?>
<div class="popup">
    <div class="popup_container popup_video">
        <div class="popup_close"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></div>
        <div class="zhabblerPlayer" id="zP__<?=md5($video->videoURL)?>">`
            <div class="zhabblerPlayerControls">
                <div class="zhabblerPlayerControl zhabblerPlayerControlsPlayPause zhabblerPlayerControlsPause" data-play="#video_<?=$video->videoID?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd" d="M6.75 5.25a.75.75 0 01.75-.75H9a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H7.5a.75.75 0 01-.75-.75V5.25zm7.5 0A.75.75 0 0115 4.5h1.5a.75.75 0 01.75.75v13.5a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75V5.25z" clip-rule="evenodd" />
                    </svg>             
                </div>
                <div class="zhabblerDurProgress" data-for="#video_<?=$video->videoID?>">
                    <div class="zhabblerDurProgressActive"></div>
                </div>
                <div class="zhabblerPlayerControl zhabblerPlayerControlsSound" data-for="#video_<?=$video->videoID?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.318.664-2.66 1.905A9.76 9.76 0 001.5 12c0 .898.121 1.768.35 2.595.341 1.24 1.518 1.905 2.659 1.905h1.93l4.5 4.5c.945.945 2.561.276 2.561-1.06V4.06zM18.584 5.106a.75.75 0 011.06 0c3.808 3.807 3.808 9.98 0 13.788a.75.75 0 11-1.06-1.06 8.25 8.25 0 000-11.668.75.75 0 010-1.06z" />
                        <path d="M15.932 7.757a.75.75 0 011.061 0 6 6 0 010 8.486.75.75 0 01-1.06-1.061 4.5 4.5 0 000-6.364.75.75 0 010-1.06z" />
                    </svg>                                                   
                </div>
                <div class="zhabblerPlayerControl zhabblerPlayerControlsFullScreen" data-for="#zP__<?=md5($video->videoURL)?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M15 3.75a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V5.56l-3.97 3.97a.75.75 0 11-1.06-1.06l3.97-3.97h-2.69a.75.75 0 01-.75-.75zm-12 0A.75.75 0 013.75 3h4.5a.75.75 0 010 1.5H5.56l3.97 3.97a.75.75 0 01-1.06 1.06L4.5 5.56v2.69a.75.75 0 01-1.5 0v-4.5zm11.47 11.78a.75.75 0 111.06-1.06l3.97 3.97v-2.69a.75.75 0 011.5 0v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 010-1.5h2.69l-3.97-3.97zm-4.94-1.06a.75.75 0 010 1.06L5.56 19.5h2.69a.75.75 0 010 1.5h-4.5a.75.75 0 01-.75-.75v-4.5a.75.75 0 011.5 0v2.69l3.97-3.97a.75.75 0 011.06 0z" clip-rule="evenodd" />
                    </svg>                                  
                </div>
            </div>
            <video src="<?=$video->videoURL?>" id="video_<?=$video->videoID?>" autoplay></video>
        </div>
        <div class="popup_video_information">
            <?=(!empty($zhab->zhabTitle) ? "<div><h1>".$zhab->zhabTitle."</h1></div>" : "")?>
            <div>
                <?=$zhab->zhabContent?>
            </div>
            <div class="popup_video_information_author">
                <div>
                    <a href="/profile/<?=$zhab->nickname?>">
                        <img src="<?=$zhab->profileImage?>">
                    </a>
                </div>
                <div>
                    <div>
                        <a href="/profile/<?=$zhab->nickname?>" id="zm">
                            <?=$zhab->nickname?>
                        </a>
                    </div>
                    <div>
                        <small style="color:#666;">
                            <?=DB::RowCount("SELECT * FROM follows LEFT JOIN users ON userID = followBy WHERE followTo = :to AND reason = ''", [":to" => $zhab->userID])?> подписчиков
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/player/script.js"></script>