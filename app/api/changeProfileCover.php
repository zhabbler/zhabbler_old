<?php
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
if($user->activated != 1){
    Alert::PushMessage("Вы не можете выполнить данное действие из-за ограничений.");
    User::redirect("/");
}
if(!empty($user->reason)){
    Alert::PushMessage("Вы не можете выполнить данное действие из-за ограничений.");
    die("error");
}
if(!empty($_FILES['image_cover']['name'][0])){
    if($_FILES['image_cover']['size'] > 5242880){
        Alert::PushMessage('Выберите фотографию меньшего размера.');
        echo "error";
    }else if(!exif_imagetype($_FILES['image_cover']['tmp_name'])){
        Alert::PushMessage('Выбранный файл не является фотографией.');
        echo "error";
    }else{
        $image_extension = image_type_to_extension(exif_imagetype($_FILES['image_cover']['tmp_name']), true);
        $image_name = bin2hex(random_bytes(72)).$image_extension;
        move_uploaded_file($_FILES['image_cover']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/uploads/".$image_name);
        $imageTitleForJPG = md5($image_name).'__converted.jpeg';
        Image::convertImage($_SERVER['DOCUMENT_ROOT'].'/uploads/'.$image_name, $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$imageTitleForJPG, 100);
        DB::Query("UPDATE users SET profileCover = :pfc WHERE token = :token", false, false, [
            ":pfc" => '/uploads/'.$imageTitleForJPG,
            ":token" => $user->token
        ]);
        echo '/uploads/'.$imageTitleForJPG;
    }
}