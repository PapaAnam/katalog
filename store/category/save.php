<?php
    include "../../php/mysql.db.php";
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $kode = $db->getLastNumber("kategori", "kode", 2);
    $data = array(
        "kode" => $kode,
        "nama" => $_POST["nama"],
        "keterangan" => $_POST["keterangan"]
    );
    
    $sql = $db->SQLinsertData("kategori", $data);
    $db->execute($conn, $sql);
    $db->close();
    
    echo(json_encode(array("success" => true, "message" => "Tambah data kategori berhasil.")));