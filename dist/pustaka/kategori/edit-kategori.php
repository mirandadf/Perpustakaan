<?php
session_start();
    if (isset($_POST['edit_kategori_pustaka'])) {
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
        $id_kategori_pustaka=input($_POST["id_kategori_pustaka"]);
        $nama_kategori_pustaka=input($_POST["nama_kategori_pustaka"]);
        
        $sql="update kategori_pustaka set
        nama_kategori_pustaka='$nama_kategori_pustaka'
        where id_kategori_pustaka=$id_kategori_pustaka";

        //Mengeksekusi atau menjalankan query 
        $edit_kategori_pustaka=mysqli_query($kon,$sql);
        
        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($edit_kategori_pustaka) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../dist/index.php?page=kategori&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../dist/index.php?page=kategori&edit=gagal");
        }
        
    }

    //-------------------------------------------------------------------------------------------

    $id_kategori_pustaka=$_POST["id_kategori_pustaka"];
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM kategori_pustaka where id_kategori_pustaka=$id_kategori_pustaka");
    $data = mysqli_fetch_array($query); 

    $kode_kategori_pustaka=$data['kode_kategori_pustaka'];
    $nama_kategori_pustaka=$data['nama_kategori_pustaka'];
 
?>
<form action="pustaka/kategori/edit-kategori.php" method="post">
    <div class="form-group">
        <label>Kode kategori Pustaka:</label>
        <h3><?php echo $kode_kategori_pustaka; ?></h3>
        <input name="kode_kategori_pustaka" value="<?php echo $kode_kategori_pustaka; ?>" type="hidden" class="form-control">
        <input name="id_kategori_pustaka" value="<?php echo $id_kategori_pustaka; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama kategori Pustaka:</label>
        <input name="nama_kategori_pustaka" value="<?php echo $nama_kategori_pustaka; ?>" type="text" class="form-control" placeholder="Masukan nama kategori" required>
    </div>

    <button type="submit" name="edit_kategori_pustaka" id="btn-kategori_pustaka" class="btn btn-dark" >Update</button>
</form>