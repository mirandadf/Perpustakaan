<?php
session_start();
    if (isset($_POST['tambah_penerbit'])) {
        
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

            $kode_penerbit=input($_POST["kode_penerbit"]);
            $nama_penerbit=input($_POST["nama_penerbit"]);

            $sql="insert into penerbit (kode_penerbit,nama_penerbit) values
            ('$kode_penerbit','$nama_penerbit')";

            //Mengeksekusi/menjalankan query 
            $simpan_penerbit=mysqli_query($kon,$sql);

            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_penerbit) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../../dist/index.php?page=penerbit&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../../dist/index.php?page=penerbit&add=gagal");
            }
        }
    }
?>


<?php
    // mengambil data barang dengan kode paling besar
    include '../../../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_penerbit) as kodeTerbesar FROM penerbit");
    $data = mysqli_fetch_array($query);
    $id_penerbit = $data['kodeTerbesar'];
    $id_penerbit++;
    $huruf = "U";
    $kodepenerbit = $huruf . sprintf("%03s", $id_penerbit);
?>
<form action="pustaka/penerbit/tambah-penerbit.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Kode penerbit:</label>
        <h3><?php echo $kodepenerbit; ?></h3>
        <input name="kode_penerbit" value="<?php echo $kodepenerbit; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama Penerbit:</label>
        <input name="nama_penerbit" type="text" class="form-control" placeholder="Masukan nama penerbit" required>
    </div>

    <button type="submit" name="tambah_penerbit" id="btn-penerbit" class="btn btn-dark">Tambah</button>
</form>

