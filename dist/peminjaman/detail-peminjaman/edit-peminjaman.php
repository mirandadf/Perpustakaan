<?php
session_start();
if (isset($_POST['edit_peminjaman_pustaka'])) {

    include '../../../config/database.php';

    mysqli_query($kon,"START TRANSACTION");

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id_detail_peminjaman=input($_POST["id_detail_peminjaman"]);
    $kode_peminjaman=input($_POST["kode_peminjaman"]);
    $kode_pustaka=input($_POST["kode_pustaka"]);

    


    $sql="update detail_peminjaman set
    kode_pustaka='$kode_pustaka'
    where id_detail_peminjaman=$id_detail_peminjaman";


    //Mengeksekusi atau menjalankan query diatas
    $edit_peminjaman_pustaka=mysqli_query($kon,$sql);

    $id_pengguna=$_SESSION["id_pengguna"];
    $waktu=date("Y-m-d h:i:s");
    $log_aktivitas="Edit Peminjaman Pustaka #$kode_pustaka ";
    $simpan_aktivitas=mysqli_query($kon,"insert into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$log_aktivitas',$id_pengguna)");


    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($edit_peminjaman_pustaka) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&edit-peminjaman=berhasil#bagian_detail_peminjaman");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&edit-peminjaman=gagal#bagian_detail_peminjaman");

    }

}
//----------------------------------------------------------------------------
?>



<?php
  $kode_pustaka=$_POST['kode_pustaka'];
?>
<form action="peminjaman/detail-peminjaman/edit-peminjaman.php" method="post">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <input type="hidden" class="form-control" name="id_detail_peminjaman" value="<?php echo $_POST['id_detail_peminjaman'];?>">   
                <input type="hidden" class="form-control" name="kode_peminjaman" value="<?php echo $_POST['kode_peminjaman'];?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Pustaka:</label>
                <select class="form-control" name="kode_pustaka">
                    <?php
                        include '../../../config/database.php';
                        if ($kode_pustaka=='') echo "<option value='0'>-</option>";
                        $hasil=mysqli_query($kon,"select * from pustaka order by id_pustaka asc");
                        while ($data = mysqli_fetch_array($hasil)):
                    ?>
                        <option <?php if ($kode_pustaka==$data['kode_pustaka']) echo "selected"; ?>  value="<?php echo $data['kode_pustaka']; ?>"><?php echo $data['judul_pustaka']; ?></option>
                        <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <button class="btn btn-warning btn-circle" name="edit_peminjaman_pustaka" ><i class="fas fa-cart-plus"></i> Update</button>
            </div>
        </div>
    </div>
</form>