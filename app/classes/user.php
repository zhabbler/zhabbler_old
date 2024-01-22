<?php
class User extends DB{
    public static function CheckEmail($Email){
        if(self::RowCount("SELECT * FROM users WHERE email = :email", [":email" => $Email]) > 0){
            return true;
        }else{
            return false;
        }
    }

    public static function CheckNickname($Nickname){
        if(self::RowCount("SELECT * FROM users WHERE nickname = :nickname", [":nickname" => $Nickname]) > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function redirect($URL){
        header("Location: ".$URL);die();
    }

    public static function getProfileInfo($Nickname){
        if(self::RowCount("SELECT * FROM users WHERE nickname = :nickname", [":nickname" => $Nickname]) > 0){
            return self::Query("SELECT * FROM users WHERE nickname = :nickname", true, false, [":nickname" => $Nickname]);
        }else{
            include $_SERVER['DOCUMENT_ROOT'].'/views/404.php';
            die();
        }
    }

    public static function GetUserInfoByAuthCode(){
        $codeInfo = self::Query("SELECT * FROM auths WHERE authCode = :code", true, false, [":code" => $_COOKIE['authCode']]);
        $user = self::Query("SELECT * FROM users WHERE token = :token", true, false, [":token" => $codeInfo->authToken]);
        DB::Query("UPDATE users SET ip = :ip WHERE userID = :id", false, false, [":ip" => $_SERVER['REMOTE_ADDR'], ':id' => $user->userID]);
        return $user;
    }
}