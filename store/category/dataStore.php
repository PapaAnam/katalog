<?php
    include "../../php/mysql.db.php";
   
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $start = $_POST["start"];
    $limit = $_POST["limit"];
    
    $sql = "SELECT COUNT(*) AS TOTAL FROM kategori";
    $db->query($sql, $rec_count, $rs);
    $nbrow = $rs[0]["TOTAL"];
    
    $data = array();    
    if($nbrow>0) {
        $sql = "SELECT * FROM kategori"; //LIMIT $start, $limit";
        $db->query($sql, $rec_count, $data);
    }    
    $db->close();
    
    echo(json_encode(array("totalCount" => $nbrow, "topics" => $data)));