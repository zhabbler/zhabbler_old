<?php
class Routes{
    public static function POST($path, $url){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            self::url($path, $url);
        }
    }
    public static function GET($path, $url){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            self::url($path, $url);
        }
    }
    public static function PUT($path, $url){
        if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            self::url($path, $url);
        }
    }
    public static function DELETE($path, $url){
        if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            self::url($path, $url);
        }
    }
    public static function any($path, $url){
        self::url($path, $url);
    }
    public static function url($path, $url){
        $ROOT = $_SERVER['DOCUMENT_ROOT'];
        if($url == "/404"){
            include_once("$ROOT/$path");
            die();
        }  
        $request_url = strtok(rtrim(filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL), "/"), "?");
        $url_parts = explode('/', $url);
        $request_url_parts = explode('/', $request_url);
        array_shift($url_parts);
        array_shift($request_url_parts);
        if($url_parts[0] == '' && count($request_url_parts) == 0){
            include_once("$ROOT/$path");
            die();
        }
        if(count($url_parts) != count($request_url_parts)){
            return;
        }  
        $parameters = [];
        for($i=0;$i<count($url_parts);$i++){
            $url_part = $url_parts[$i];
            if(preg_match("/^[$]/", $url_part)){
                $url_part = ltrim($url_part, '$');
                array_push($parameters, $request_url_parts[$i]);
                $$url_part=$request_url_parts[$i];
            }else if($url_parts[$i] != $request_url_parts[$i]){
                return;
            } 
        }
        include_once("$ROOT/$path");
        die();
    }
}