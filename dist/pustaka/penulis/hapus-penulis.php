<?php

    include '../../../config/database.php';

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id_penulis=input($_GET["id_penulis"]);

    $hapus_penulis=mysqli_query($kon,"delete from penulis where id_penulis=$id_penulis");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_penulis) {
        header("Location:../../../dist/index.php?page=penulis&hapus=berhasil");
    }
    else {
        header("Location:../../../dist/index.php?page=penulis&hapus=gagal");
    }
    
?>
