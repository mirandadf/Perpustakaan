<?php

    include '../../../config/database.php';

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id_penerbit=input($_GET["id_penerbit"]);
  
    $hapus_penerbit=mysqli_query($kon,"delete from penerbit where id_penerbit=$id_penerbit");

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($hapus_penerbit) {
        header("Location:../../../dist/index.php?page=penerbit&hapus=berhasil");
    }
    else {
        header("Location:../../../dist/index.php?page=penerbit&hapus=gagal");
    }

?>
