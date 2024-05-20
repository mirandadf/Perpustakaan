<?php
session_start();
    if (isset($_POST['tambah_anggota'])) {
        //Include file koneksi, untuk koneksikan ke database
        include '../../config/database.php';
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        //Cek apakah ada kiriman form dari method post
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            mysqli_query($kon,"START TRANSACTION");

            $kode=input($_POST["kode"]);
            $judul_pustaka=$_POST["judul_pustaka"];
            $kategori_pustaka=input($_POST["kategori_pustaka"]);
            $penulis=input($_POST["penulis"]);
            $penerbit=input($_POST["penerbit"]);
            $tahun=input($_POST["tahun"]);
            $halaman=input($_POST["halaman"]);
            $dimensi=input($_POST["dimensi"]);
            $stok=input($_POST["stok"]);
            $rak=input($_POST["rak"]);

            $tanggal=date("Y-m-d");

            $ekstensi_diperbolehkan	= array('png','jpg');
            $gambar_pustaka = $_FILES['gambar_pustaka']['name'];
            $x = explode('.', $gambar_pustaka);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['gambar_pustaka']['size'];
            $file_tmp = $_FILES['gambar_pustaka']['tmp_name'];	

            if (!empty($gambar_pustaka)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    if($ukuran < 1044070){	
                        //Mengupload gambar
                        move_uploaded_file($file_tmp, 'gambar/'.$gambar_pustaka);
                        $sql="insert into pustaka (kode_pustaka,judul_pustaka,kategori_pustaka,penulis,penerbit,tahun,gambar_pustaka,halaman,dimensi,stok,rak) values
                        ('$kode','$judul_pustaka','$kategori_pustaka','$penulis','$penerbit','$tahun','$gambar_pustaka','$halaman','$dimensi','$stok','$rak')";
                    }
                }
            }else {
                $gambar_pustaka="gambar_default.png";
                $sql="insert into pustaka (kode_pustaka,judul_pustaka,kategori_pustaka,penulis,penerbit,tahun,gambar_pustaka,halaman,dimensi,stok,rak) values
                ('$kode','$judul_pustaka','$kategori_pustaka','$penulis','$penerbit','$tahun','$gambar_pustaka','$halaman','$dimensi','$stok','$rak')";
            }

            $simpan_pustaka=mysqli_query($kon,$sql);

            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_pustaka) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../dist/index.php?page=pustaka&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../dist/index.php?page=pustaka&add=gagal");
            }
        }
    }
      // mengambil data pustaka dengan kode paling besar
      include '../../config/database.php';
      $query = mysqli_query($kon, "SELECT max(id_pustaka) as kodeTerbesar FROM pustaka");
      $data = mysqli_fetch_array($query);
      $id_pustaka = $data['kodeTerbesar'];
      $id_pustaka++;
      $huruf = "P";
      $kodepustaka = $huruf . sprintf("%04s", $id_pustaka);

?>
<form action="pustaka/tambah.php" method="post" enctype="multipart/form-data">
    <!-- rows -->
    <div class="row">
        <div class="col-sm-10">
            <div class="form-group">
                <label>Judul Pustaka:</label>
                <input name="judul_pustaka" type="text" class="form-control" placeholder="Masukan judul pustaka" required>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Kode:</label>
                <h3><?php echo $kodepustaka; ?></h3>
                <input name="kode" value="<?php echo $kodepustaka; ?>" type="hidden" class="form-control">
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
                    $sql="select * from kategori_pustaka order by id_kategori_pustaka asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option value="<?php echo $data['id_kategori_pustaka']; ?>"><?php echo $data['nama_kategori_pustaka']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Penulis:</label>
                <select name="penulis" class="form-control">
                <?php
                    
                    $sql="select * from penulis order by id_penulis asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option value="<?php echo $data['id_penulis']; ?>"><?php echo $data['nama_penulis']; ?></option>
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
                    $sql="select * from penerbit order by id_penerbit asc";
                    $hasil=mysqli_query($kon,$sql);
                    while ($data = mysqli_fetch_array($hasil)):
                ?>
                    <option value="<?php echo $data['id_penerbit']; ?>"><?php echo $data['nama_penerbit']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Tahun Terbit:</label>
                <input name="tahun" type="number" class="form-control" placeholder="Masukan tahun" required>
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
                        <input name="halaman" type="number" class="form-control" placeholder="Masukan jumlah halaman" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Dimensi:</label>
                        <input name="dimensi" type="text" class="form-control" placeholder="Masukan dimensi" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Jumlah Stok:</label>
                        <input name="stok" type="number" class="form-control" placeholder="Masukan stok" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Posisi Rak:</label>
                        <input name="rak" type="text" class="form-control" placeholder="Masukan posisi rak" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- rows -->   
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div id="msg"></div>
                <label>Gambar Pustaka:</label>
                <input type="file" name="gambar_pustaka" class="file" >
                    <div class="input-group my-3">
                        <input type="text" class="form-control" disabled placeholder="Upload Gambar" id="file">
                        <div class="input-group-append">
                            <button type="button" id="pilih_gambar" class="browse btn btn-dark">Pilih Gambar</button>
                        </div>
                    </div>
                <img src="../src/img/img80.png" id="preview" class="img-thumbnail">
            </div>
        </div>
    </div>

    <!-- rows -->   
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
             <button type="submit" name="tambah_anggota" class="btn btn-success">Tambah</button>
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
