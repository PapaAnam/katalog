<?php
    include "../../php/mysql.db.php";
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    if(strlen($_POST["kode"])==0) {
        $db->close($conn);
        die(json_encode(array("success" => false, "message" => "Kode produk harus diisi.")));
    }
  
    if(strlen($_POST["nama"])==0) {
        $db->close($conn);
        die(json_encode(array("success" => false, "message" => "Nama produk harus diisi.")));
    }
    
    $sql = "SELECT COUNT(*) AS TOTAL FROM produk WHERE kode='".$_POST["kode"]."'";
    $db->query($sql, $nbrows, $rs);
    if($rs[0]["TOTAL"]>0) {
        $db->close($conn);
        die(json_encode(array("success" => false, "message" => "Kode produk sudah ada.")));
    }
    
    $id = $db->getLastNumber("produk", "id");
    $data = array(
        "id" => $id,
        "kode" => $_POST["kode"],
        "nama" => $_POST["nama"],
        "keterangan" => $_POST["keterangan"],
        "qty" => $_POST["qty"],
        "kategori_id" => $_POST["kategori"],
        "gambar_1" => $_POST["gambar_1"],
        "gambar_2" => $_POST["gambar_2"],
        "gambar_3" => $_POST["gambar_3"],
        "gambar_4" => $_POST["gambar_4"]
    );
    
    $sql = $db->SQLinsertData("produk", $data);
    $db->execute($conn, $sql);
    $db->close();
    
    //upload gambar
    if(!empty($_FILES["gambar"])) {
        $file = $_FILES["gambar"];
        $upload_directory = realpath("../../") . "/images/".$id;
        if (is_dir($upload_directory)) rmdir($upload_directory);
                
        mkdir($upload_directory);
        move_uploaded_file($file['tmp_name'], $upload_directory."/".$file['name']);
            
        //EXTRACT FILE ZIP:
        $zip = new ZipArchive;
        $zip->open($upload_directory."/".$file['name']);
        $zip->extractTo($upload_directory."/");
        $zip->close();
                
        //HAPUS FILE ZIP:
        unlink($upload_directory."/".$file['name']);
        //die(json_encode(array("success" => false, "message" => $upload_directory."/".$file['name'])));
    }
    
    echo(json_encode(array("success" => true, "message" => "Tambah data produk berhasil.")));