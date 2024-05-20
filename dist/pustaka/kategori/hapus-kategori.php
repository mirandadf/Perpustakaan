<?php
    include '../../../config/database.php';

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id_kategori_pustaka=input($_GET["id_kategori_pustaka"]);
 

    $hapus_kategori_pustaka=mysqli_query($kon,"delete from kategori_pustaka where id_kategori_pustaka=$id_kategori_pustaka");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_kategori_pustaka) {
        header("Location:../../../dist/index.php?page=kategori&hapus=berhasil");
    }
    else {
        header("Location:../../../dist/index.php?page=kategori&hapus=gagal");
    }
    
?>
