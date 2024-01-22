<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if(isset($_POST['name']) && isset($_POST['nickname']) && isset($_POST['email']) && isset($_POST['password'])){
    $name = Text::Prepare($_POST['name']);
    $nickname = Text::Prepare($_POST['nickname']);
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(!Text::Null($name) && !Text::Null($nickname) && !Text::Null($email) && !Text::Null($password)){
        if(strlen($name) > 48){
            Alert::PushMessage("Ваше имя слишком длинное!");
        }else if(strlen($nickname) < 4 || strlen($nickname) > 20){
            Alert::PushMessage("Ваш никнейм короткий или слишком длинный!");
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            Alert::PushMessage("Неверная форма эл. почты");
        }else if(strlen($password) < 6){
            Alert::PushMessage("Ваш пароль должен быть больше 6 символов!");
        }else{
            if(User::CheckEmail($email) === true){
                Alert::PushMessage("Почта уже используется другим пользователем!");
            }else if(User::CheckNickname($nickname) === true){
                Alert::PushMessage("Такой никнейм уже используется другим пользователем!");
            }else if(preg_match("/[^a-zA-Z0-9\!]/", $nickname)){
                Alert::PushMessage("В никнейме допустимо только латинские символы и числа!");
            }else{
                $password = password_hash($password, PASSWORD_DEFAULT);
                $URLCodenot_registered = Text::RandomStr(256);
                DB::Query("INSERT INTO not_registered(name, nickname, email, password, URLCode) VALUES(:name, :nickname, :email, :password, :URLCode)", false, false, array(":name" => $name, ":nickname" => $nickname, ":email" => $email, ":password" => $password, ":URLCode" => $URLCodenot_registered));
                $mail = new PHPMailer(true);
                $URLCode = Text::RandomStr(256);
                DB::Query("INSERT INTO emailVerify(emailVerifyFor, emailVerifyGetID) VALUES(:to, :getID)", false, false, [":to" => $nickname, ":getID" => $URLCode]);
                try {
                    $mail->isSMTP();
                    $mail->Host = $_ENV['SMTP_HOST'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $_ENV['SMTP_USER'];
                    $mail->Password = $_ENV['SMTP_PASSWORD'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = $_ENV['SMTP_PORT'];

                    $mail->setFrom($_ENV['SMTP_EMAIL'], 'Жабблер');
                    $mail->addAddress($email);
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
                                    <table role="presentation" style="width:600px;background-color:#13b552;padding:20px;">
                                        <tr>
                                            <td>
                                                <img src="https://zhabbler.ru/etc/logotype.png" width="200" alt="жабблер">
                                            </td>
                                        </tr>
                                    </table>
                                    <table role="presentation" style="width:600px;background-color:#003510;padding:0 20px;">
                                        <tr>
                                            <td>
                                                <h1>
                                                    Привет!
                                                </h1>
                                            </td>
                                        </tr>
                                    </table>
                                    <table role="presentation" style="width:600px;background-color:#003510;padding:0 20px;">
                                        <tr>
                                            <td>
                                                <span>
                                                    Осталось лишь немного и ты сможешь пользоваться Жабблером! Просто нажми на кнопку.
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                    <table role="presentation" style="width:600px;background-color:#003510;padding:0 20px;">
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
                    $mail->send();
                } catch (Exception $e) {
                    Alert::PushMessage("Не удалось отправить письмо");
                }
                User::redirect('/activate/'.$URLCodenot_registered);
            }
        }
    }else{
        Alert::PushMessage("Поля для ввода пустые");
    }
}else{
    Alert::PushMessage("Не удалось создать аккаунт");
}
User::redirect("/account/new");