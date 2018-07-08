<?php
    include "../../php/mysql.db.php";
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $data = array(
        "nama" => $_POST["nama"],
        "keterangan" => $_POST["keterangan"]
    );
    
    $sql = $db->SQLupdateData("kategori", $data, array("id" => $_POST["id"]));
    $db->execute($conn, $sql);
    $db->close();
    
    echo(json_encode(array("success" => true, "message" => "Simpan data kategori berhasil.")));