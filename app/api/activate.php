<?php
if(isset($_COOKIE['authCode'])){
    User::redirect("/");
}
if(DB::RowCount("SELECT * FROM not_registered WHERE URLCode = :code", [":code" => $URLCode]) > 0){
    $NTSession = DB::Query("SELECT * FROM not_registered WHERE URLCode = :code", true, false, [":code" => $URLCode]);
    $Token = Text::RandomStr(255);
    DB::Query("INSERT INTO users(nickname, name, email, password, profileImage, token) VALUES(:nickname, :name, :email, :password, '/assets/images/no_avatar_1900.png', :token)", false, false, [
        ":nickname" => $NTSession->nickname,
        ":name" => $NTSession->name,
        ":email" => $NTSession->email,
        ":password" => $NTSession->password,
        ":token" => $Token
    ]);
    DB::Query("DELETE FROM not_registered WHERE URLCode = :code", true, false, [":code" => $URLCode]);
    Auth::CreateAuthCode($Token);
}
User::redirect("/");