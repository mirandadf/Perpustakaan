<?php
session_start();
    //Koneksi database
    include '../../config/database.php';
    //Memulai transaksi
    mysqli_query($kon,"START TRANSACTION");

    $id_anggota=$_GET['id_anggota'];
    $kode_anggota=$_GET['kode_anggota'];

    //Menghapus data dalam tabel anggota
    $hapus_anggota=mysqli_query($kon,"delete from anggota where id_anggota='$id_anggota'");

    //Menghapus data dalam tabel pengguna
    $hapus_pengguna=mysqli_query($kon,"delete from pengguna where kode_pengguna='$kode_anggota'");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_anggota and $hapus_pengguna) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../dist/index.php?page=anggota&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../dist/index.php?page=anggota&hapus=gagal");

    }

?>