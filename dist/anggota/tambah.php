<?php
session_start();
    if (isset($_POST['simpan_tambah'])) {
        
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
            //Memulai transaksi
            mysqli_query($kon,"START TRANSACTION");

            $kode_anggota=input($_POST["kode_anggota"]);
            $nama_anggota=input($_POST["nama_anggota"]);
            $email=input($_POST["email"]);
            $no_telp=input($_POST["no_telp"]);
            $alamat=input($_POST["alamat"]);
            $jenis_kelamin=input($_POST["jenis_kelamin"]);
            $tempat_lahir=input($_POST["tempat_lahir"]);
            $tanggal_lahir=input($_POST["tanggal_lahir"]);
            $status=input($_POST["status"]);

            $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
            $foto = $_FILES['foto']['name'];
            $x = explode('.', $foto);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['foto']['size'];
            $file_tmp = $_FILES['foto']['tmp_name'];	

            if (!empty($foto)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    //Mengupload gambar
                    move_uploaded_file($file_tmp, 'foto/'.$foto);
                    //Sql jika menggunakan foto
                    $sql="insert into anggota (kode_anggota,nama_anggota,email,no_telp,alamat,jenis_kelamin,tempat_lahir,tanggal_lahir,foto) values
                        ('$kode_anggota','$nama_anggota','$email','$no_telp','$alamat','$jenis_kelamin','$tempat_lahir','$tanggal_lahir','$foto')";
                }
            }else {
                //Sql jika tidak menggunakan foto, maka akan memakai gambar foto_default.png
                $sql="insert into anggota (kode_anggota,nama_anggota,email,no_telp,alamat,jenis_kelamin,tempat_lahir,tanggal_lahir,foto) values
                ('$kode_anggota','$nama_anggota','$email','$no_telp','$alamat','$jenis_kelamin','$tempat_lahir','$tanggal_lahir','foto_default.png')";
            }

            //Mengeksekusi query 
            $simpan_anggota=mysqli_query($kon,$sql);
            $level="Anggota";
            $sql1="insert into pengguna (kode_pengguna,status,level) values
            ('$kode_anggota','$status','$level')";

            //Menyimpan ke tabel pengguna
            $simpan_pengguna=mysqli_query($kon,$sql1);


            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($simpan_anggota and $simpan_pengguna) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../dist/index.php?page=anggota&add=berhasil");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../dist/index.php?page=anggota&add=gagal");
            }
        }
    }
?>


<?php
    // mengambil data barang dengan kode paling besar
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT max(id_anggota) as kodeTerbesar FROM anggota");
    $data = mysqli_fetch_array($query);
    $id_anggota = $data['kodeTerbesar'];
    $id_anggota++;
    $huruf = "A";
    $kodeanggota = $huruf . sprintf("%03s", $id_anggota);
?>
<form action="anggota/tambah.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Kode anggota:</label>
        <h3><?php echo $kodeanggota; ?></h3>
        <input name="kode_anggota" value="<?php echo $kodeanggota; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama anggota:</label>
        <input name="nama_anggota" type="text" class="form-control" placeholder="Masukan nama" required>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" class="form-control" placeholder="Masukan Tempat Lahir" required>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" class="form-control" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select class="form-control" name="jenis_kelamin" required>
                    <option>Pilih</option>
                    <option value="1">Laki-laki</option>
                    <option value="2">Perempuan</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
        <div class="form-group">
                <label>Email:</label>
                <input name="email" type="email" class="form-control" placeholder="Masukan email" required>
        </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>No Telp:</label>
                <input name="no_telp" type="text" class="form-control" placeholder="Masukan no telp" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Alamat:</label>
                <textarea class="form-control" name="alamat" rows="2" id="alamat"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div id="msg"></div>
                <label>Foto:</label>
                <input type="file" name="foto" class="file" >
                    <div class="input-group my-3">
                        <input type="text" class="form-control" disabled placeholder="Upload Foto" id="file">
                        <div class="input-group-append">
                            <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih Foto</button>
                        </div>
                    </div>
                <img src="../src/img/img80.png" id="preview" class="img-thumbnail">
            </div>
        </div>
    </div>

    <button type="submit" name="simpan_tambah" id="btn-anggota" class="btn btn-dark">Tambah</button>
</form>

<style>
    .file {
    visibility: hidden;
    position: absolute;
    }
</style>

<script>

    $(document).on("click", "#pilih_foto", function() {
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

