<?php
session_start();
    if (isset($_POST['edit_penerbit'])) {
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
        $id_penerbit=input($_POST["id_penerbit"]);
        $nama_penerbit=input($_POST["nama_penerbit"]);
        
        $sql="update penerbit set
        nama_penerbit='$nama_penerbit'
        where id_penerbit=$id_penerbit";

        //Mengeksekusi atau menjalankan query 
        $edit_penerbit=mysqli_query($kon,$sql);
        
        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($edit_penerbit) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../dist/index.php?page=penerbit&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../dist/index.php?page=penerbit&edit=gagal");
        }
        
    }

    //-------------------------------------------------------------------------------------------

    $id_penerbit=$_POST["id_penerbit"];
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM penerbit where id_penerbit=$id_penerbit");
    $data = mysqli_fetch_array($query); 

    $kode_penerbit=$data['kode_penerbit'];
    $nama_penerbit=$data['nama_penerbit'];
 
?>
<form action="pustaka/penerbit/edit-penerbit.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Kode penerbit:</label>
        <h3><?php echo $kode_penerbit; ?></h3>
        <input name="kode_penerbit" value="<?php echo $kode_penerbit; ?>" type="hidden" class="form-control">
        <input name="id_penerbit" value="<?php echo $id_penerbit; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama penerbit:</label>
        <input name="nama_penerbit" value="<?php echo $nama_penerbit; ?>" type="text" class="form-control" placeholder="Masukan nama" required>
    </div>

    <button type="submit" name="edit_penerbit" id="btn-penerbit" class="btn btn-dark" >Update penerbit</button>
</form>