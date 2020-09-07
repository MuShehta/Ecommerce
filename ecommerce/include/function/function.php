<?php

function gettitle() {
    global $pagetitle;
    $title = "None";
    if (isset($pagetitle)) {
        $title = $pagetitle;
    }
    echo $title;
}

function checkitem($select , $from , $value) {

    global $con;
    $stmt1 = $con->prepare("select $select from $from Where $select = ?");
    $stmt1 -> execute(array($value));
    $count = $stmt1 -> rowCount();
    return $count;
}


// count number of item
function countitem($item , $from) {
    global $con;
    $stmt = $con->prepare("select count($item) from $from");
    $stmt -> execute();
    return $stmt -> fetchColumn();
}

function checkuserstatue($user) {
    global $con;
    $stmt = $con->prepare("select user_name from users where user_id = $user and group_id = 1");
    $stmt -> execute();
    $count = $stmt->rowCount();
    return $count;
}

function make($select , $from ,$where ,$value) {
    global $con;
    $stmt1 = $con->prepare("select $select from $from Where $where = ?");
    $stmt1 -> execute(array($value));
    $count = $stmt1 -> fetchAll();
    return $count;
}
?>