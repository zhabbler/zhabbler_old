<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
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
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/banned.php');
    die();
}
$mail = new PHPMailer(true);
$URLCode = Text::RandomStr(32);
DB::Query("INSERT INTO passwordChanger(passwordChangerTo, passwordChangerGetID) VALUES(:to, :getID)", false, false, [":to" => $user->userID, ":getID" => $URLCode]);
try {
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV['SMTP_PORT'];

    $mail->setFrom($_ENV['SMTP_EMAIL'], 'Жабблер');
    $mail->addAddress($user->email);
    $mail->CharSet = "UTF-8";
    $mail->isHTML(true);
    $mail->Subject = 'Изменение пароля в Жабблере';
    $mail->Body    = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Zhabbler Email Notify</title>
        <style>
            table, td, div, h1, p {font-family:Arial, Helvetica, sans-serif;color:#fff;}
            a{color:#0099ff;text-decoration: underline;}
        </style>
    </head>
    <body style="margin:0;padding:0;color:#fff;">
        <table role="presentation" style="width:100%;padding-top:1em;">
            <tr>
                <td align="center" style="padding:0;">
                    <table style="width:600px;background-color:#003510;">
                        <tr>
                            <td>
                                <center>
                                    <img src="https://zhabbler.ru/etc/logotype.png" alt="жабблер">
                                </center>
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;background-color:#003510;">
                        <tr>
                            <td>
                                <center>
                                    <h1>
                                        Изменение пароля в Жабблере.
                                    </h1>
                                </center>
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;background-color:#003510;">
                        <tr>
                            <td>
                                <center>
                                    <span>
                                        Нажмите на кнопку ниже чтобы изменить ваш пароль.
                                    </span>
                                </center>
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;background-color:#003510;">
                        <tr>
                            <td>
                                <center>
                                    <br><br>
                                    <a href="https://zhabbler.ru/letters/password/'.$URLCode.'" style="text-decoration:none;background-color: #13b552;color: #fff;border: 0;cursor: pointer;padding: 10px;font-size: 16px;font-weight: 600;border-radius: 3px;">
                                        Изменить пароль
                                    </a>
                                    <br><br><br>
                                </center>
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;color:#666;background-color:#003510;">
                        <tr>
                            <td>
                                <center>
                                    <span>
                                        Если вы ничего не делали на сайте <a href="https://zhabbler.ru">zhabbler.ru</a> просто проигнорируйте или удалите данное письмо.
                                    </span>
                                </center>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';

    $mail->send();
 	echo 'success';
} catch (Exception $e) {
    echo 'error';
}