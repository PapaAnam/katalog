<?php
    include "../../php/mysql.db.php";
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $sql = "DELETE FROM kategori WHERE id IN ('".  str_replace(",", "','", $_POST["selected"])."')";
    $db->execute($conn, $sql);
    $db->close();
        
    echo(json_encode(array("success" => true, "message" => "Hapus data kategori berhasil.")));