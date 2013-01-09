<?php
function addslashes_mssql($str){
    if (is_array($str)) {
        foreach($str AS $id => $value) {
            $str[$id] = addslashes_mssql($value);
        }
    } else {
        $str = str_replace("'", "''", $str);
    }

    return $str;
}

function stripslashes_mssql($str){
    if (is_array($str)) {
        foreach($str AS $id => $value) {
            $str[$id] = stripslashes_mssql($value);
        }
    } else {
        $str = str_replace("''", "'", $str);
        $str = str_replace("\'", "'", $str);
    }

    return $str;
}
?>