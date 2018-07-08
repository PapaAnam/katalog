<?php 
$host='localhost';
$user='root';
$pass='';
$db='katalog_produk';
$con = mysqli_connect($host, $user, $pass, $db) or die('gagal koneksi');
$res = mysqli_query($con, 'select * from kategori');
	var_dump($res);
while ($r = mysqli_fetch_array($res)) {
	die;
}