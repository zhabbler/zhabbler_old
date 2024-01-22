<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$user = User::GetUserInfoByAuthCode();
if($user->entered_all != 1){
    include_once($_SERVER['DOCUMENT_ROOT'].'/views/config.php');
    die();
}
DB::Query("DELETE FROM emailVerify WHERE emailVerifyFor = :for", false, false, [":for" => $user->nickname]);
$mail = new PHPMailer(true);
$URLCode = Text::RandomStr(256);
DB::Query("INSERT INTO emailVerify(emailVerifyFor, emailVerifyGetID) VALUES(:to, :getID)", false, false, [":to" => $user->nickname, ":getID" => $URLCode]);
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
    $mail->Subject = 'Подтверждение почты в Жабблере';
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
                    <table style="width:600px;background-color:#13b552;padding:20px;">
                        <tr>
                            <td>
                                <img src="https://zhabbler.ru/etc/logotype.png" width="200" alt="жабблер">
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;background-color:#003510;padding:0 20px;">
                        <tr>
                            <td>
                                <h1>
                                    Привет!
                                </h1>
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;background-color:#003510;padding:0 20px;">
                        <tr>
                            <td>
                                <span>
                                    Осталось лишь немного и ты сможешь пользоваться Жабблером! Просто нажми на кнопку.
                                </span>
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;background-color:#003510;padding:0 20px;">
                        <tr>
                            <td>
                                <br><br>
                                <a href="https://zhabbler.ru/verification/'.$URLCode.'" style="text-decoration:none;background-color: #13b552;color: #fff;border: 0;cursor: pointer;padding: 10px;font-size: 16px;font-weight: 600;border-radius: 3px;">
                                    Подтвердить почту
                                </a>
                                <br><br><br>
                            </td>
                        </tr>
                    </table>
                    <table style="width:600px;color:#666;background-color:#003510;padding:0 20px;">
                        <tr>
                            <td>
                                <span>
                                        Если вы ничего не делали на сайте <a href="https://zhabbler.ru">zhabbler.ru</a> просто проигнорируйте или удалите данное письмо.
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    Alert::PushMessageSuccess("Письмо успешно отправлено!");
    $mail->send();
} catch (Exception $e) {
    Alert::PushMessage("Не удалось отправить письмо");
}
User::redirect("/");