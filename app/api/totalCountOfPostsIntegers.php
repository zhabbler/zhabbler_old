<?php
if(isset($_POST['which']) && isset($_POST['postID'])){
    if(DB::RowCount("SELECT * FROM zhabs WHERE zhabURLID = :getID", [":getID" => $_POST['postID']]) == 0){
        echo(0);
    }else{
        if($_POST['which'] == 1){
            echo(DB::RowCount("SELECT * FROM comments WHERE commentTo = :getID", [":getID" => $_POST['postID']]));
        }else if($_POST['which'] == 2){
            echo(DB::RowCount("SELECT * FROM zhabs WHERE zhabRepliedTo = :getID", [":getID" => $_POST['postID']]));
        }else if($_POST['which'] == 3){
            echo(DB::RowCount("SELECT * FROM likes WHERE likeTo = :getID", [":getID" => $_POST['postID']]));
        }else{
            echo(0);
        }
    }
}else{
    echo(0);
}