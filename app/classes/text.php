<?php
class Text{
    public static function Null($String){
        if(!empty($String) && !ctype_space($String) && mb_strlen(trim($String)) > 0){
            return false;
        }else{
            return true;
        }
    }

    public static function RandomStr($len) {
        return substr(bin2hex(random_bytes($len)), 0, $len);
    }

    public static function Prepare($String){
        return htmlspecialchars(stripslashes(trim($String)));
    }

    public static function escapeJsEvent($value){
        return preg_replace('/(<.+?)(?<=\s)on[a-z]+\s*=\s*(?:([\'"])(?!\2).+?\2|(?:\S+?\(.*?\)(?=[\s>])))(.*?>)/i', "$1 $3", $value);        
    }     
    
    public static function removeStyleAttrs($value){
        return preg_replace('/(<[^>]+) style=".*?"/i', '$1', $value);
    }
}