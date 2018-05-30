<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "db_arsip";
    $config = mysqli_connect($host, $username, $password, $database);

    if(!$config){
        die("Koneksi database gagal: " . mysqli_connect_error());
    }
    echo "";

    $searchTerm = $_GET['term'];
    $query = mysqli_query($config, "SELECT kode_bagian, nama_bagian FROM tbl_bag_pegawai WHERE nama_bagian LIKE '%".$searchTerm."%' ORDER BY kode_bagian ASC");
    while(list($kode_bagian, $nama_bagian) = mysqli_fetch_array($query)){
        $data[] = $kode_bagian." ".$nama_bagian;
    }

    echo json_encode($data);
?>
