<?php
class Date{
    public static function month($num, $lang = 'ru'){
        $month = array(
            "ru" => array(
                "short" => array("янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек"),
                "full"  => array(
                    "января",
                    "февраля",
                    "марта",
                    "апреля",
                    "мая",
                    "июня",
                    "июля",
                    "августа",
                    "сентября",
                    "октября",
                    "ноября",
                    "декабря"
                )
            )
                );
        return $month["ru"]["full"][$num - 1];
    }
}