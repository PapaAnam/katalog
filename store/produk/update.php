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
    
    $sql = "SELECT COUNT(*) AS TOTAL FROM produk WHERE kode='".$_POST["kode"]."' AND id<>'".$_POST["id"]."'";
    $db->query($sql, $nbrows, $rs);
    if($rs[0]["TOTAL"]>0) {
        $db->close($conn);
        die(json_encode(array("success" => false, "message" => "Kode produk sudah ada.")));
    }
    
    $data = array(
        "kode" => $_POST["kode"],
        "nama" => $_POST["nama"],
        "keterangan" => $_POST["keterangan"],
        "kategori_id" => $_POST["kategori"]
    );
    $upload_directory = realpath("../../") . "/images/".$_POST["id"];
    for($i=0; $i<4; $i++) {
        if(strlen($_POST["gambar_".($i+1)])>0) {
            $data["gambar_".($i+1)] = $_POST["gambar_".($i+1)];
            if(file_exists($upload_directory."/gambar_".($i+1)."jpg")) unlink($upload_directory."/gambar_".($i+1)."jpg");
        }
    }
    
    
    $sql = $db->SQLupdateData("produk", $data, array("id" => $_POST["id"]));
    $db->execute($conn, $sql);
    $db->close();
    
    
    //upload gambar
    if(!empty($_FILES["gambar"])) {
        $file = $_FILES["gambar"];
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
    
    echo(json_encode(array("success" => true, "message" => "Simpan data produk berhasil.")));