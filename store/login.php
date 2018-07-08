<?php
    include "../php/mysql.db.php";
    $config =  require("../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $sql = "SELECT * FROM user WHERE userid='".$_POST["userid"]."' AND passwd=MD5('".$_POST["passwd"]."')";
    $db->query($sql, $rec_num, $rs);
    $db->close($conn);

    $result=array("success"=>($rec_num>0));
    die(json_encode($result));