<?php
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php')){
    die("Error: please install composer and/or install composer dependencies");
}
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/app/.env')){
    die("Error: you forgot to rename zhabbler-example.env file to .env");
}
if(!file_exists('path/to/directory')){
    mkdir('path/to/directory', 0777, true);
}
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
session_start();
Dotenv\Dotenv::createImmutable(__DIR__."/app")->load();
if($_ENV['DEBUG_MODE'] == 1){
    Tracy\Debugger::enable();
}
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/alert.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/user.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/image.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/video.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/date.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/follow.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/auth.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/zhabs.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/app/classes/text.php';
Auth::CheckAuthCode();
include_once $_SERVER['DOCUMENT_ROOT'].'/app/routes/routes.php';