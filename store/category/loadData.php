<?php
    include "../../php/mysql.db.php";
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $id = $_POST["id"];
    
    $sql = "SELECT * FROM kategori WHERE id='$id'";
    $db->query($sql, $rec_count, $data);
    $db->close();
    
    echo(json_encode(array("total" => $rec_count, "results" => $data[0])));