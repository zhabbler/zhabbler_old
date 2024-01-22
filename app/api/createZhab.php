<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if(!empty($user->reason)){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/banned.php');
    die();
}
if(isset($_POST['content'])){
    if(!isset($_POST['title'])){
        $_POST['title'] = "";
    }
    if($user->activated != 1){
        Alert::PushMessage("Вы не можете добавлять посты из-за ограничений.");
        User::redirect("/profile/".$user->nickname);
    }
    $urlID = Text::RandomStr(72);
    if(Text::Null($_POST['content'])){
        Alert::PushMessage("Пожалуйста, введите текст.");
    }else if(empty(trim(html_entity_decode($_POST['content']), " \t\n\r\0\x0B\xC2\xA0")) && strlen($_POST['content']) > 0 && strlen(trim($_POST['content'])) == 0){
        Alert::PushMessage("Пожалуйста, введите текст.");
    }else if(Text::Null(Text::Prepare(strip_tags(trim(html_entity_decode($_POST['content']), " \t\n\r\0\x0B\xC2\xA0")))) === true){
        Alert::PushMessage("Пожалуйста, введите текст.");
    }else{
        if(strlen($_POST['title']) > 0 && Text::Null(Text::Prepare($_POST['title'])) === true && strlen($_POST['title']) > 0 && strlen(trim($_POST['title'])) == 0){
            Alert::PushMessage("Пожалуйста, введите название.");
            User::redirect("/profile/".$user->nickname);
        }
        if(DB::RowCount("SELECT * FROM rate_limit WHERE rate_limitTo = :to", [":to" => $user->userID]) > 0){
            DB::Query("UPDATE rate_limit SET rate_limitCount = rate_limitCount + 1 WHERE rate_limitTo = :to", false, false, [":to" => $user->userID]);
        }else{
            DB::Query("INSERT INTO rate_limit(rate_limitCount, rate_limitTo) VALUES(1, :to)", false, false, [":to" => $user->userID]);
        }
        $rate_limit = DB::Query("SELECT * FROM rate_limit WHERE rate_limitTo = :to", true, false, [":to" => $user->userID]);
        if($rate_limit->rate_limitCount > 64){
            DB::Query("UPDATE users SET reason = :reason WHERE userID = :id", false, false, [":reason" => "Превышен лимит запросов (свыше 64) + нарушение условий использования", ":id" => $user->userID]);
        }
        $content = str_replace('javascript:', 'https://zhabbler.ru/?from=xss_', Text::escapeJsEvent(Text::removeStyleAttrs(strip_tags($_POST['content'], "<br><b><u><i><a>"))));
        $title = Text::Prepare($_POST['title']);
        DB::Query("INSERT INTO zhabs(zhabURLID, zhabBy, zhabTitle, zhabContent, zhabUploaded, zhabWhoCanComment, zhabWhoCanRepost) VALUES(:urlid, :by, :title, :content, :date, :wcc, :wcr)", false, false, [
            ":urlid" => $urlID,
            ":by" => $user->userID,
            ":title" => $title,
            ":content" => $content,
            ":date" => date("Y-m-d"),
            ":wcc" => ($_POST['cf_comment'] == 0 || $_POST['cf_comment'] == 1 ? $_POST['cf_comment'] : 0),
            ":wcr" => ($_POST['cf_repost'] == 0 || $_POST['cf_repost'] == 1 ? $_POST['cf_repost'] : 0)
        ]);
        if(!empty($_FILES['imageFiles']['name'][0])){
            for ($i = 0; $i < count($_FILES['imageFiles']['name']); $i++) { 
                $imageName = $_FILES['imageFiles']["name"][$i];
                $tmpName = $_FILES['imageFiles']["tmp_name"][$i];

                $imageExtension = explode('.', $imageName);

                $name = $imageExtension[0];
                $imageExtension = strtolower(end($imageExtension));

                $newImageName = md5($name)."__ident__".uniqid()."__original";
                $newImageName .= '.' . $imageExtension;

                move_uploaded_file($tmpName, $_SERVER['DOCUMENT_ROOT'].'/uploads/' . $newImageName);
                $imageTitleForJPG = md5($newImageName).'__converted.jpeg';
                Image::convertImage($_SERVER['DOCUMENT_ROOT'].'/uploads/'.$newImageName, $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$imageTitleForJPG, 100);

                DB::Query("INSERT INTO photos(photoTo, photoURL) VALUES(:to, :url)", false, false, [
                    ":to" => $urlID,
                    ":url" => '/uploads/'.$imageTitleForJPG
                ]);
            }
        }
        if(!empty($_FILES['audioFiles']['name'][0])){
            for ($i = 0; $i < count($_FILES['audioFiles']['name']); $i++) {
                if(strstr($_FILES['audioFiles']['mime_type'][$i], 'audio/')){
                    Alert::PushMessage("Прикреплённые аудио файлы не являются аудио вовсе.");
                    User::redirect("/profile/".$user->nickname);
                }
                $audioName = $_FILES['audioFiles']["name"][$i];
                $tmpName = $_FILES['audioFiles']["tmp_name"][$i];

                $audioExtension = explode('.', $audioName);

                $name = $audioExtension[0];
                $audioExtension = strtolower(end($audioExtension));

                $newAudioName = md5($name)."__ident__".uniqid()."__original";
                $newAudioName .= '.' . $audioExtension;

                move_uploaded_file($tmpName, $_SERVER['DOCUMENT_ROOT'].'/uploads/audios/' . $newAudioName);

                DB::Query("INSERT INTO audios(audioTo, audioURL) VALUES(:to, :url)", false, false, [
                    ":to" => $urlID,
                    ":url" => '/uploads/'.$newAudioName
                ]);
            }
        }
        if(!empty($_FILES['videoFiles']['name'][0])){
            for ($i = 0; $i < count($_FILES['videoFiles']['name']); $i++) { 
                if(strstr($_FILES['videoFiles']['mime_type'][$i], 'video/')){
                    Alert::PushMessage("Прикреплённые видео файлы не являются видео вовсе.");
                    User::redirect("/profile/".$user->nickname);
                }
                $videoName = $_FILES['videoFiles']["name"][$i];
                $tmpName = $_FILES['videoFiles']["tmp_name"][$i];

                $videoExtension = explode('.', $videoName);

                $name = $videoExtension[0];
                $videoExtension = strtolower(end($videoExtension));

                $newVideoName = md5($name)."__ident__".uniqid()."__original";
                $newVideoName .= '.' . $videoExtension;

                move_uploaded_file($tmpName, $_SERVER['DOCUMENT_ROOT'].'/uploads/videos/' . $newVideoName);

                $thumbnailName = md5($newVideoName)."__converted__thumbnail.jpeg";
                $newVideoConvName = md5($newVideoName)."__converted.mp4";

                $thumbnailConvertation = Video::generateThumbnails($_SERVER['DOCUMENT_ROOT'].'/uploads/videos/'.$newVideoName, 'uploads/videos/thumbnails/'.$thumbnailName);
                $videoConvertation = Video::convertVideoToFormat($_SERVER['DOCUMENT_ROOT'].'/uploads/videos/'.$newVideoName, $_SERVER['DOCUMENT_ROOT'].'/uploads/videos/converted/'.$newVideoConvName);
                if($videoConvertation != 'err' || $thumbnailConvertation != 'err'){
                    DB::Query("INSERT INTO videos(videoTo, videoURL, videoThumbnail, videoDuration) VALUES(:to, :url, :thumbnails, :duration)", false, false, [
                        ":to" => $urlID,
                        ":url" => '/uploads/videos/converted/'.$newVideoConvName,
                        ":thumbnails" => '/uploads/videos/thumbnails/'.$thumbnailName,
                        ":duration" => Video::getVideoDuration($_SERVER['DOCUMENT_ROOT'].'/uploads/videos/converted/'.$newVideoConvName)
                    ]);
                }
            }
        }
    }
}else{
    Alert::PushMessage("Что-то пошло не так.");
}
User::redirect("/profile/".$user->nickname);