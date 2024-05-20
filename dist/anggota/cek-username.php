<?php
    include '../../config/database.php';

    $username=$_POST['username'];

    if (empty($username)){
        echo "<p class='text-warning'>Username tidak boleh kosong</p>";
        echo "<script>   document.getElementById('submit').disabled = true; </script>";
    } else {
        $query = mysqli_query($kon, "SELECT username FROM pengguna where username='$username'");
        $jumlah = mysqli_num_rows($query);

        if ($jumlah>0){
            echo "<p class='text-danger'>Username sudah digunakan</p>";
            echo "<script>   document.getElementById('submit').disabled = true; </script>";
            
        }else {
            echo "<p class='text-success'>Username tersedia</p>";
            echo "<script>   document.getElementById('submit').disabled = false; </script>";
            
        }
    }
    

?>
