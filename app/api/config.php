<?php
$user = User::GetUserInfoByAuthCode();
if(!empty($user->reason)){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/banned.php');
    die();
}
if($user->entered_all == 1){
    User::redirect("/");
}
if(isset($_POST['gender']) && isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])){
    $_POST['gender'] = intval($_POST['gender']);
    $_POST['day'] = intval($_POST['day']);
    $_POST['month'] = intval($_POST['month']);
    $_POST['year'] = intval($_POST['year']);
    $age = (date("md", date("U", mktime(0, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']))) > date("md")
    ? ((date("Y") - $_POST['year']) - 1)
    : (date("Y") - $_POST['year']));
    if(isset($_POST['ShowBirthDate'])){
        $showBirth = 1;
    }else{
        $showBirth = 0;
    }
    if($_POST['gender'] == 0){
        Alert::PushMessage("Не удалось создать аккаунт.");
    }else{
        if(checkdate($_POST['month'], $_POST['day'], $_POST['year'])){
            if($age < 13){
                Alert::PushMessage("Вам должно быть больше 13 лет чтобы пользоваться Жабблером.");
            }else{
                if($_POST['gender'] == 0 || $_POST['gender'] < 0 || $_POST['gender'] > 3){
                    Alert::PushMessage("Не удалось обработать пол.");
                }else{
                    DB::Query("UPDATE users SET gender = :gender, birth = :birth, joined = :joined, showBirth = :showBirth, entered_all = 1 WHERE token = :token", false, false, [":gender" => $_POST['gender'], ":birth" => $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'], ':joined' => date("Y-m-d"), ":showBirth" => $showBirth, ":token" => $user->token]);
                }
            }
        }else{
            Alert::PushMessage("Неверная дата рождения");
        }
    }
}else{
    Alert::PushMessage("Не удалось создать аккаунт.");
}
User::redirect("/?safed_token=".md5($user->token));