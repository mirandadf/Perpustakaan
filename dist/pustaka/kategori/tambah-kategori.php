<?php
session_start();
    if (isset($_POST['tambah_kategori_pustaka'])) {
        
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

            $kode_kategori_pustaka=input($_POST["kode_kategori_pustaka"]);
            $nama_kategori_pustaka=input($_POST["nama_kategori_pustaka"]);

            $sql="insert into kategori_pustaka (kode_kategori_pustaka,nama_kategori_pustaka) values
                ('$kode_kategori_pustaka','$nama_kategori_pustaka')";


            //Mengeksekusi/menjalankan query 
            $simpan_kategori_pustaka=mysqli_query($kon,$sql);

            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_kategori_pustaka) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../../dist/index.php?page=kategori&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../../dist/index.php?page=kategori&add=gagal");
            }

        }
       
    }
?>


<?php
    // mengambil data barang dengan kode paling besar
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_kategori_pustaka) as kodeTerbesar FROM kategori_pustaka");
    $data = mysqli_fetch_array($query);
    $id_kategori_pustaka = $data['kodeTerbesar'];
    $id_kategori_pustaka++;
    $huruf = "K";
    $kodekategori_pustaka = $huruf . sprintf("%03s", $id_kategori_pustaka);
?>
<form action="pustaka/kategori/tambah-kategori.php" method="post">
    <div class="form-group">
        <label>Kode kategori Pustaka:</label>
        <h3><?php echo $kodekategori_pustaka; ?></h3>
        <input name="kode_kategori_pustaka" value="<?php echo $kodekategori_pustaka; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama kategori Pustaka:</label>
        <input name="nama_kategori_pustaka" type="text" class="form-control" placeholder="Masukan nama kategori pustaka" required>
    </div>

    <button type="submit" name="tambah_kategori_pustaka" id="btn-kategori_pustaka" class="btn btn-dark">Tambah</button>
</form>

