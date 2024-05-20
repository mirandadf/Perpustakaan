<?php
    include '../../config/database.php';

    $username_baru=$_POST['username_baru'];

    if (empty($username_baru)){
        echo "<p class='text-warning'>Username tidak boleh kosong</p>";
        echo "<script>   document.getElementById('simpan_profil').disabled = true; </script>";
    } else {
        $query = mysqli_query($kon, "SELECT username FROM pengguna where username='$username_baru'");
        $jumlah = mysqli_num_rows($query);

        if ($jumlah>0){
            echo "<p class='text-danger'>Username sudah digunakan</p>";
            echo "<script>   document.getElementById('simpan_profil').disabled = true; </script>";
            
        }else {
            echo "<p class='text-success'>Username tersedia</p>";
            echo "<script>   document.getElementById('simpan_profil').disabled = false; </script>";
            
        }
    }
    

?>
