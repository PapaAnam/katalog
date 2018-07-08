<?php
    include "../../php/mysql.db.php";
    $config =  require("../../php/initDB.php");
    $db = new koneksi($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    $conn = $db->connect(true);
    
    $sql = "DELETE FROM produk WHERE id IN ('".  str_replace(",", "','", $_POST["selected"])."')";
    $db->execute($conn, $sql);
    $db->close();
        
    $temp = explode(",", $_POST["selected"]);
    foreach($temp as $key => $value) {
        $dirname =  realpath("../../") . "/images/$value";
        if(is_dir($dirname)) deleteDirectory($dirname);
    }
    
    echo(json_encode(array("success" => true, "message" => "Hapus data produk berhasil.")));
    
    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }