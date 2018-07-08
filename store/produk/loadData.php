<?php
    include "../../php/mysql.db.php";
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $sql = "SELECT * FROM kategori";
    $db->query($sql, $rec_count, $data_kategori);
    
    $id = $_POST["id"];
    $sql = "SELECT id, kode, nama, keterangan, kategori_id AS kategori, qty, gambar_1, gambar_2, gambar_3, gambar_4 FROM produk
            WHERE id='$id'";
    $db->query($sql, $rec_count, $data);
    for($i=0; $i<$rec_count; $i++) {
        $sql = "SELECT * FROM kategori WHERE id='".$data[$i]["kategori"]."'";
        $db->query($sql, $rs_num, $rs);
        if ($rs_num == 0) {
            unset($data[$i]["kategori"]);
        } else {
            $data[$i]["kategori"] = $rs[0];
        }

        $data[$i]["gambar"] = array(
            array("nama" => $data[$i]["gambar_1"]),
            array("nama" => $data[$i]["gambar_2"]),
            array("nama" => $data[$i]["gambar_3"]),
            array("nama" => $data[$i]["gambar_4"])
        );

        unset($data[$i]["gambar_1"]);
        unset($data[$i]["gambar_2"]);
        unset($data[$i]["gambar_3"]);
        unset($data[$i]["gambar_4"]);
    }
    
    $db->close();
    echo(json_encode(array("total" => $rec_count, "data_kategori" => $data_kategori, "results" => $data[0])));