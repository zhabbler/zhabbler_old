<?php
class DB{
    public static function Connect(){
        $pdo = new PDO('mysql:host=localhost;dbname=zhabbler_ru;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function RowCount($Query, $Parameters){
        $stmt = self::Connect()->prepare($Query);
        $stmt->execute($Parameters);
        return $stmt->rowCount();
    }

    public static function Query($Query, $Fetch, $FetchAll, $Parameters){
        $stmt = self::Connect()->prepare($Query);
        $stmt->execute($Parameters);

        if($Fetch){
            if($FetchAll){
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }else{
                return $stmt->fetch(PDO::FETCH_OBJ);
            }
        }
    }
}