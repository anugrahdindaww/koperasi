<?php 
$koneksi = mysqli_connect("localhost","root","","koperasi");

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>