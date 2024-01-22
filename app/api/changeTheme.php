<?php
if(isset($_COOKIE['theme'])){
    setcookie("theme", "", time()-7000000, "/");
    die("Off");
}else{
    setcookie("theme", "On", time()+7000000, "/");
    die("On");
}