<?php
class Auth extends DB{
    public static function CreateAuthCode($Token){
        $code = substr(bin2hex(random_bytes(255)), 0, 255);
        self::Query("INSERT INTO auths(authCode, authToken) VALUES(:code, :token)", false, false, [
            ":code" => $code,
            ":token" => $Token
        ]);
        setcookie("authCode", $code, time()+7000000, '/');
    }

    public static function CheckAuthCode(){
        if(isset($_COOKIE['authCode'])){
            if(self::RowCount("SELECT * FROM auths WHERE authCode = :authCode", [":authCode" => $_COOKIE['authCode']]) == 0){
                setcookie("authCode", '', time()-7000000, '/');
                User::redirect("/");
            }
        }
    }
}