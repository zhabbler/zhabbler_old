<?php
if(DB::RowCount("SELECT * FROM photos WHERE photoID = :id", [":id" => $_POST['id']]) == 0){
    Alert::PushMessage("Не удалось получить изображение. (возможно оно удалено)");
    die("error");
}
$photo = DB::Query("SELECT * FROM photos WHERE photoID = :id", true, false, [":id" => $_POST['id']]);
if(!file_exists($_SERVER['DOCUMENT_ROOT'].$photo->photoURL)){
    Alert::PushMessage("Не удалось получить изображение. (возможно оно удалено)");
    die("error");
}
echo $photo->photoURL;
