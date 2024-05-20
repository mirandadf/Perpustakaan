<?php
session_start();
if (isset($_POST['edit_pustaka'])) {

    include '../../config/database.php';

    mysqli_query($kon,"START TRANSACTION");

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $id_pustaka=input($_POST["id_pustaka"]);
    $kode=input($_POST["kode"]);
    $judul_pustaka=input($_POST["judul_pustaka"]);
    $kategori_pustaka=input($_POST["kategori_pustaka"]);
    $penulis=input($_POST["penulis"]);
    $penerbit=input($_POST["penerbit"]);
    $tahun=input($_POST["tahun"]);
    $halaman=input($_POST["halaman"]);
    $dimensi=input($_POST["dimensi"]);
    $stok=input($_POST["stok"]);
    $rak=input($_POST["rak"]);

    $gambar_saat_ini=$_POST['gambar_saat_ini'];
 
    $gambar_baru = $_FILES['gambar_baru']['name'];
    $ekstensi_diperbolehkan	= array('png','jpg');
    $x = explode('.', $gambar_baru);
    $ekstensi = strtolower(end($x));
    $ukuran	= $_FILES['gambar_baru']['size'];
    $file_tmp = $_FILES['gambar_baru']['tmp_name'];

    
    if (!empty($gambar_baru)){
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true){
            if ($ukuran < 2044070){

                //Mengupload logo baru
                move_uploaded_file($file_tmp, 'gambar/'.$gambar_baru);
                //menghapus logo lama
                unlink("gambar/".$gambar_saat_ini);

                $sql="update pustaka set
                judul_pustaka='$judul_pustaka',
                kategori_pustaka='$kategori_pustaka',
                penulis='$penulis',
                penerbit='$penerbit',
                tahun='$tahun',
                halaman='$halaman',
                dimensi='$dimensi',
                stok='$stok',
                rak='$rak',
                gambar_pustaka='$gambar_baru'
                where id_pustaka=$id_pustaka";
            }
        }
    }else {

        $sql="update pustaka set
        judul_pustaka='$judul_pustaka',
        kategori_pustaka='$kategori_pustaka',
        penulis='$penulis',
        penerbit='$penerbit',
        tahun='$tahun',
        halaman='$halaman',
        dimensi='$dimensi',
        stok='$stok',
        rak='$rak'
        where id_pustaka=$id_pustaka";
    }

    //Mengeksekusi atau menjalankan query diatas
    $edit_pustaka=mysqli_query($kon,$sql);

    //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
    if ($edit_pustaka) {
        mysqli_query($kon,"COMMIT");
        header("Location:../../dist/index.php?page=pustaka&edit=berhasil");
    }
    else {
        mysqli_query($kon,"ROLLBACK");
        header("Location:../../dist/index.php?page=pustaka&edit=gagal");

    }

}

?>
  <!-- ------------------------------------------------------------------------------------ -->
<?php

    $id_pustaka=$_POST["id_pustaka"];
    // mengambil data pustaka dengan kode paling besar
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM pustaka where id_pustaka=$id_pustaka");
    $data = mysqli_fetch_array($query); 

    $kode_pustaka=$data['kode_pustaka'];
    $judul_pustaka=$data['judul_pustaka'];
    $kategori_pustaka=$data['kategori_pustaka'];
    $penulis=$data['penulis'];
    $penerbit=$data['penerbit'];
    $tahun=$data['tahun'];
    $halaman=$data['halaman'];
    $dimensi=$data['dimensi'];
    $stok=$data['stok'];
    $rak=$data['rak'];
    $gambar_pustaka=$data['gambar_pustaka'];
   

?>
<form action="pustaka/edit.php" method="post" enctype="multipart/form-data">
    <!-- rows -->
    <div class="row">
        <div class="col-sm-10">
            <div class="form-group">
                <label>Judul Pustaka:</label>
                <input name="judul_pustaka" type="text" value="<?php echo $judul_pustaka; ?>" class="form-control" placeholder="Masukan judul pustaka" required>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Kode:</label>
                <h3><?php echo $kode_pustaka; ?></h3>
                <input name="kode" value="<?php echo $kode_pustaka; ?>" type="hidden" class="form-control">
                <input name="id_pustaka" value="<?php echo $id_pustaka; ?>" type="hidden" class="form-control">
            </div>
        </div>
    </div>
    <!-- rows -->                 
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Kategori:</label>
                <select name="kategori_pustaka" class="form-control">
                <?php
                  if ($kategori_pustaka==0) echo "<option value='0'>-</option>";
                    $sql="select * from kategori_pustaka order by id_kategori_pustaka asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option <?php if ($kategori_pustaka==$data['id_kategori_pustaka']) echo "selected"; ?> value="<?php echo $data['id_kategori_pustaka']; ?>"><?php echo $data['nama_kategori_pustaka']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Penulis:</label>
                <select name="penulis" class="form-control">
                <?php
                    if ($penulis==0) echo "<option value='0'>-</option>";
                    $sql="select * from penulis order by id_penulis asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option <?php if ($penulis==$data['id_penulis']) echo "selected"; ?> value="<?php echo $data['id_penulis']; ?>"><?php echo $data['nama_penulis']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </div>
    <!-- rows -->
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Penerbit:</label>
                <select name="penerbit" class="form-control">
                <?php
                    if ($penerbit==0) echo "<option value='0'>-</option>";
                    $sql="select * from penerbit order by id_penerbit asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option <?php if ($penerbit==$data['id_penerbit']) echo "selected"; ?>  value="<?php echo $data['id_penerbit']; ?>"><?php echo $data['nama_penerbit']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Tahun Terbit:</label>
                <input name="tahun" type="number" value="<?php echo $tahun; ?>" class="form-control" placeholder="Masukan tahun" required>
            </div>
        </div>
    </div>
    <!-- rows -->                 
    <div class="row">
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Halaman:</label>
                        <input name="halaman" type="number" value="<?php echo $halaman; ?>" class="form-control" placeholder="Masukan jumlah halaman" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Dimensi:</label>
                        <input name="dimensi" type="text" value="<?php echo $dimensi; ?>" class="form-control" placeholder="Masukan dimensi" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Jumlah Stok:</label>
                        <input name="stok" type="number" value="<?php echo $stok; ?>" class="form-control" placeholder="Masukan stok" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Posisi Rak:</label>
                        <input name="rak" type="text" value="<?php echo $rak; ?>" class="form-control" placeholder="Masukan posisi rak" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- rows -->                 
    <div class="row">
        <div class="col-sm-6">
        <label>Gambar saat ini:</label>
            <img src="../dist/pustaka/gambar/<?php echo $gambar_pustaka;?>" class="rounded" width="70%" alt="Cinque Terre">
            <input type="hidden" name="gambar_saat_ini" value="<?php echo $gambar_pustaka;?>" class="form-control" />
        </div>
        <div class="col-sm-6">
            <div id="msg"></div>
            <label>Gambar Baru:</label>
            <input type="file" name="gambar_baru" class="file" >
                <div class="input-group my-3">
                    <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                    <div class="input-group-append">
                            <button type="button" id="pilih_gambar" class="browse btn btn-dark">Pilih Gambar</button>
                    </div>
                </div>
            <img src="../src/img/img80.png" id="preview" class="img-thumbnail">
        </div>
    </div>
    <br>
    <!-- rows -->   
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
             <button type="submit" name="edit_pustaka" class="btn btn-success">Update</button>
            </div>
        </div>
    </div>

       
</form>
<style>
    .file {
    visibility: hidden;
    position: absolute;
    }
</style>
<script>
    $(document).on("click", "#pilih_gambar", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
    });
    $('input[type="file"]').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    reader.onload = function(e) {
        // get loaded data and render thumbnail.
        document.getElementById("preview").src = e.target.result;
    };
    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
    });
</script>
