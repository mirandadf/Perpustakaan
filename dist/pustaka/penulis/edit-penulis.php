<?php
session_start();
    if (isset($_POST['edit_penulis'])) {
        //Include file koneksi, untuk koneksikan ke database
        include '../../../config/database.php';

        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $id_penulis=input($_POST["id_penulis"]);
        $nama_penulis=input($_POST["nama_penulis"]);
        
        $sql="update penulis set
        nama_penulis='$nama_penulis'
        where id_penulis=$id_penulis";

        //Mengeksekusi atau menjalankan query 
        $edit_penulis=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($edit_penulis) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../dist/index.php?page=penulis&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../dist/index.php?page=penulis&edit=gagal");
        }
        
    }

    //-------------------------------------------------------------------------------------------

    $id_penulis=$_POST["id_penulis"];
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM penulis where id_penulis=$id_penulis");
    $data = mysqli_fetch_array($query); 

    $kode_penulis=$data['kode_penulis'];
    $nama_penulis=$data['nama_penulis'];
 
?>
<form action="pustaka/penulis/edit-penulis.php" method="post">
    <div class="form-group">
        <label>Kode penulis:</label>
        <h3><?php echo $kode_penulis; ?></h3>
        <input name="kode_penulis" value="<?php echo $kode_penulis; ?>" type="hidden" class="form-control">
        <input name="id_penulis" value="<?php echo $id_penulis; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama penulis:</label>
        <input name="nama_penulis" value="<?php echo $nama_penulis; ?>" type="text" class="form-control" placeholder="Masukan nama" required>
    </div>

    <button type="submit" name="edit_penulis" id="btn-penulis" class="btn btn-dark" >Update penulis</button>
</form>