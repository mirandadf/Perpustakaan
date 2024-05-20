<?php
session_start();
if (isset($_POST['edit_anggota'])) {

    include '../../../config/database.php';

    mysqli_query($kon,"START TRANSACTION");

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $kode_peminjaman=input($_POST["kode_peminjaman"]);
    echo $kode_anggota=input($_POST["kode_anggota"]);

    
    $sql="update peminjaman set
    kode_anggota='$kode_anggota'
    where kode_peminjaman='$kode_peminjaman'";


    //Mengeksekusi atau menjalankan query diatas
    $edit_peminjaman_anggota=mysqli_query($kon,$sql);

    $id_pengguna=$_SESSION["id_pengguna"];
    $waktu=date("Y-m-d h:i:s");
    $log_aktivitas="Edit Peminjaman anggota #$kode_anggota ";
    $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");


    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($edit_peminjaman_anggota) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&edit-anggota=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&edit-anggota=gagal");

    }

}
//----------------------------------------------------------------------------
?>



<?php
  $kode_anggota=$_POST['kode_anggota'];
?>
<form action="peminjaman/detail-peminjaman/edit-anggota.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <input type="hidden" class="form-control" name="kode_peminjaman" value="<?php echo $_POST['kode_peminjaman'];?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Anggota:</label>
                <select class="form-control" name="kode_anggota">
                    <?php
                        include '../../../config/database.php';
                        if ($kode_anggota=='') echo "<option value='0'>-</option>";
                        $hasil=mysqli_query($kon,"select * from anggota order by id_anggota asc");
                        while ($data = mysqli_fetch_array($hasil)):
                    ?>
                        <option <?php if ($kode_anggota==$data['kode_anggota']) echo "selected"; ?>  value="<?php echo $data['kode_anggota']; ?>"><?php echo $data['nama_anggota']; ?></option>
                        <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <button class="btn btn-warning btn-circle" name="edit_anggota" ><i class="fas fa-cart-plus"></i> Update</button>
            </div>
        </div>
    </div>
</form>