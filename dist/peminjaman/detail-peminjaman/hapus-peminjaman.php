<?php
session_start();
     

    include '../../../config/database.php';
    //Memulai peminjaman
    mysqli_query($kon,"START TRANSACTION");

    $id_detail_peminjaman=$_GET["id_detail_peminjaman"];
    $kode_peminjaman=$_GET["kode_peminjaman"];

    //Mengeksekusi atau menjalankan query 
    $hapus_detail_peminjaman=mysqli_query($kon,"delete from detail_peminjaman where id_detail_peminjaman=$id_detail_peminjaman");

    $hasil=mysqli_query($kon,"select * from detail_peminjaman where kode_peminjaman='$kode_peminjaman'");

    $cek = mysqli_num_rows($hasil);

    if ($cek==0){
        $hapus_peminjaman=mysqli_query($kon,"delete from peminjaman where kode_peminjaman='$kode_peminjaman'");

        if ($hapus_detail_peminjaman and $hapus_peminjaman) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../dist/index.php?page=daftar-peminjaman&hapus-peminjaman=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../dist/index.php?page=daftar-peminjaman&hapus-peminjaman=gagal");
        }
    } else {

        if ($hapus_detail_peminjaman) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../../dist/index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&hapus-peminjaman=berhasil&#bagian_peminjaman");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../../dist/index.php?page=detail-peminjaman&id_peminjaman=$id_peminjaman&hapus-peminjaman=gagal&#bagian_peminjaman");
        }

    }

    
?>
<form action="peminjaman/detail-peminjaman/hapus-peminjaman.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                    <h5>Yakin ingin menghapus peminjaman ini?</h5>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_detail_peminjaman" value="<?php echo $_POST["id_detail_peminjaman"]; ?>" />
    <input type="hidden" name="kode_peminjaman" value="<?php echo $_POST["kode_peminjaman"]; ?>" />
    <button type="submit" name="hapus_peminjaman" class="btn btn-primary">Hapus</button>
</form>

