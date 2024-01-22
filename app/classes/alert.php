<?php
class Alert{
    public static function PushMessage($Text){
        $_SESSION['Error'] = '<script>document.querySelector("body").insertAdjacentHTML("beforeBegin", `<div class="error">'.$Text.'</div>`);setTimeout(() => {document.querySelector(".error").remove();}, 5000);</script>';
        
    }
    public static function PushMessageSuccess($Text){
        $_SESSION['Error'] = '<script>document.querySelector("body").insertAdjacentHTML("beforeBegin", `<div class="success">'.$Text.'</div>`);setTimeout(() => {document.querySelector(".success").remove();}, 5000)</script>';
        
    }
    public static function CheckForAlerts(){
        if(!empty($_SESSION['Error']) && !ctype_space($_SESSION['Error'])){
            echo $_SESSION['Error'];
        }
    }

    public static function ClearAlerts(){
        if(!empty($_SESSION['Error']) && !ctype_space($_SESSION['Error'])){
            $_SESSION['Error'] = '';
        }
    }
}