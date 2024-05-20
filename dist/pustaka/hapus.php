<?php
    session_start();
    include '../../config/database.php';

    mysqli_query($kon,"START TRANSACTION");

    $id_pustaka=$_GET["id_pustaka"];
    $gambar_pustaka=$_GET["gambar_pustaka"];

    $sql="delete from pustaka where id_pustaka='$id_pustaka'";
    $hapus_pustaka=mysqli_query($kon,$sql);

    //Menghapus file foto jika foto selain gambar default
    if ($gambar_pustaka!='gambar_default.png'){
        unlink("gambar/".$gambar_pustaka);
    }

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query-query diatas
    if ($hapus_pustaka) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../dist/index.php?page=pustaka&hapus=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../dist/index.php?page=pustaka&hapus=gagal");

    }

?>

