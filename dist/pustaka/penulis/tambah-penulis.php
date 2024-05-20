<?php
session_start();
    if (isset($_POST['tambah_penulis'])) {
        
        //Include file koneksi, untuk koneksikan ke database
        include '../../../config/database.php';
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //Cek apakah ada kiriman form dari method post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Memulai transaksi
            mysqli_query($kon,"START TRANSACTION");

            $kode_penulis=input($_POST["kode_penulis"]);
            $nama_penulis=input($_POST["nama_penulis"]);

            $sql="insert into penulis (kode_penulis,nama_penulis) values
                ('$kode_penulis','$nama_penulis')";

            //Mengeksekusi/menjalankan query 
            $simpan_penulis=mysqli_query($kon,$sql);

            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_penulis) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../../dist/index.php?page=penulis&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../../dist/index.php?page=penulis&add=gagal");
            }

        }
       
    }
?>


<?php
    // mengambil data barang dengan kode paling besar
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_penulis) as kodeTerbesar FROM penulis");
    $data = mysqli_fetch_array($query);
    $id_penulis = $data['kodeTerbesar'];
    $id_penulis++;
    $huruf = "P";
    $kodepenulis = $huruf . sprintf("%03s", $id_penulis);
?>
<form action="pustaka/penulis/tambah-penulis.php" method="post">
    <div class="form-group">
        <label>Kode penulis:</label>
        <h3><?php echo $kodepenulis; ?></h3>
        <input name="kode_penulis" value="<?php echo $kodepenulis; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama penulis:</label>
        <input name="nama_penulis" type="text" class="form-control" placeholder="Masukan nama penulis" required>
    </div>

    <button type="submit" name="tambah_penulis" id="btn-penulis" class="btn btn-dark">Tambah</button>
</form>

