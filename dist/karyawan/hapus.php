<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai karyawan
    mysqli_query($kon,"START TRANSACTION");

    $id_karyawan=$_GET['id_karyawan'];
    $kode_karyawan=$_GET['kode_karyawan'];

    //Menghapus data dalam tabel karyawan
    $hapus_karyawan=mysqli_query($kon,"delete from karyawan where id_karyawan='$id_karyawan'");

    //Menghapus data dalam tabel pengguna
    $hapus_pengguna=mysqli_query($kon,"delete from pengguna where kode_pengguna='$kode_karyawan'");


    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_karyawan and $hapus_pengguna) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../dist/index.php?page=karyawan&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../dist/index.php?page=karyawan&hapus=gagal");

    }

?>