<?php
    include "../php/mysql.db.php";
    $config =  require("../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);

    $sql = "SELECT * FROM kategori";
    $db->query($sql, $rec_count, $data);
        
    $db->close();
    
    echo(json_encode(array("totalCount" => $rec_count, "data_kategori" => $data)));