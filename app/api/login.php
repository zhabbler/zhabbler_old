<?php
if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(!Text::Null($email) && !Text::Null($password)){
        if(DB::RowCount("SELECT * FROM users WHERE email = :email", [":email" => $email]) > 0){
            $user = DB::Query("SELECT * FROM users WHERE email = :email", true, false, [":email" => $email]);
            if(password_verify($password, $user->password)){
                Auth::CreateAuthCode($user->token);
            }else{
                Alert::PushMessage("Вы ввели неверный пароль!");
            }
        }else{
            Alert::PushMessage("Пользователя по такому email не существует!");
        }
    }else{
        Alert::PushMessage("Поля для ввода пустые");
    }
}else{
    Alert::PushMessage("Не удалось войти в аккаунт");
}
User::redirect("/account/login");