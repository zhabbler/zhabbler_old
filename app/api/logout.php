<?php
if(isset($_COOKIE['authCode'])){
    DB::Query("DELETE FROM auths WHERE authCode = :code", false, false, [":code" => $_COOKIE['authCode']]);
    Alert::PushMessageSuccess("Вы успешно вышли из своего аккаунта.");
}