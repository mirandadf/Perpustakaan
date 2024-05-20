<?php
session_start();
    if (isset($_POST['edit_anggota'])) {
        //Include file koneksi, untuk koneksikan ke database
        include '../../config/database.php';

        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $id_anggota=input($_POST["id_anggota"]);
        $kode_anggota=input($_POST["kode_anggota"]);
        $nama_anggota=input($_POST["nama_anggota"]);
        $email=input($_POST["email"]);
        $no_telp=input($_POST["no_telp"]);
        $alamat=input($_POST["alamat"]);
        $jenis_kelamin=input($_POST["jenis_kelamin"]);
        $tempat_lahir=input($_POST["tempat_lahir"]);
        $tanggal_lahir=input($_POST["tanggal_lahir"]);
        $status=input($_POST["status"]);
        $foto_saat_ini=$_POST['foto_saat_ini'];
        $foto_baru = $_FILES['foto_baru']['name'];
        $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
        $x = explode('.', $foto_baru);
        $ekstensi = strtolower(end($x));
        $ukuran	= $_FILES['foto_baru']['size'];
        $file_tmp = $_FILES['foto_baru']['tmp_name'];


        if (!empty($foto_baru)){
            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                //Mengupload foto baru
                move_uploaded_file($file_tmp, 'foto/'.$foto_baru);

                //Menghapus foto lama, foto yang dihapus selain foto default
                if ($foto_saat_ini!='foto_default.png'){
                    unlink("foto/".$foto_saat_ini);
                }
                
                $sql="update anggota set
                nama_anggota='$nama_anggota',
                email='$email',
                no_telp='$no_telp',
                alamat='$alamat',
                jenis_kelamin='$jenis_kelamin',
                tempat_lahir='$tempat_lahir',
                tanggal_lahir='$tanggal_lahir',
                foto='$foto_baru'
                where id_anggota=$id_anggota";
            }
        }else {
            $sql="update anggota set
            nama_anggota='$nama_anggota',
            email='$email',
            no_telp='$no_telp',
            alamat='$alamat',
            jenis_kelamin='$jenis_kelamin',
            tempat_lahir='$tempat_lahir',
            tanggal_lahir='$tanggal_lahir'
            where id_anggota=$id_anggota";
        }

        //Mengeksekusi atau menjalankan query 
        $edit_anggota=mysqli_query($kon,$sql);

        $edit_status=mysqli_query($kon,"update pengguna set status='$status' where kode_pengguna='$kode_anggota'");


        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($edit_anggota and $edit_status) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../dist/index.php?page=anggota&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../dist/index.php?page=anggota&edit=gagal");
        }
    }

    //-------------------------------------------------------------------------------------------

    $id_anggota=$_POST["id_anggota"];
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM anggota inner join pengguna on anggota.kode_anggota=pengguna.kode_pengguna where id_anggota=$id_anggota");
    $data = mysqli_fetch_array($query); 

    $kode_anggota=$data['kode_anggota'];
    $nama_anggota=$data['nama_anggota'];
    $email=$data['email'];
    $no_telp=$data['no_telp'];
    $status=$data['status'];
    $jenis_kelamin=$data['jenis_kelamin'];
    $tempat_lahir=$data['tempat_lahir'];
    $tanggal_lahir=$data['tanggal_lahir'];
    $alamat=$data['alamat'];
?>
<form action="anggota/edit.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Kode anggota:</label>
        <h3><?php echo $kode_anggota; ?></h3>
        <input name="kode_anggota" value="<?php echo $kode_anggota; ?>" type="hidden" class="form-control">
        <input name="id_anggota" value="<?php echo $id_anggota; ?>" type="hidden" class="form-control">
    </div>
    <div class="form-group">
        <label>Nama anggota:</label>
        <input name="nama_anggota" value="<?php echo $nama_anggota; ?>" type="text" class="form-control" placeholder="Masukan nama" required>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Tempat Lahir:</label>
                <input type="text" name="tempat_lahir" value="<?php echo $tempat_lahir; ?>" class="form-control" placeholder="Masukan Tempat Lahir" required>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Tanggal Lahir:</label>
                <input type="date" name="tanggal_lahir" value="<?php echo $tanggal_lahir; ?>" class="form-control" required>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <select class="form-control" name="jenis_kelamin" required>
                    <option>Pilih</option>
                    <option  <?php if ($jenis_kelamin==1) echo "selected"; ?> value="1">Laki-laki</option>
                    <option  <?php if ($jenis_kelamin==2) echo "selected"; ?> value="2">Perempuan</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Email:</label>
                <input name="email" value="<?php echo $email; ?>" type="email" class="form-control" placeholder="Masukan email" required>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>No Telp:</label>
                <input name="no_telp" value="<?php echo $no_telp; ?>" type="text" class="form-control" placeholder="Masukan no telp" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Alamat:</label>
                <textarea class="form-control" name="alamat" rows="2" id="alamat"><?php echo $alamat; ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option <?php if ($status==1) echo "selected"; ?> value="1">Aktif</option>
                    <option <?php if ($status==0) echo "selected"; ?> value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>              
    <div class="row">
        <div class="col-sm-3">
        <label>Foto:</label><br>
            <img src="anggota/foto/<?php echo $data['foto'];?>" id="preview" width="90%" class="rounded" alt="Cinque Terre">
            <input type="hidden" name="foto_saat_ini" value="<?php echo $data['foto'];?>" class="form-control" />
        </div>
        <div class="col-sm-4">
            <div id="msg"></div>
            <label>Upload Foto Baru:</label>
            <input type="file" name="foto_baru" class="file" >
            <div class="input-group my-3">
                <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                <div class="input-group-append">
                        <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih Foto</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <button type="submit" name="edit_anggota" id="btn-anggota" class="btn btn-dark" >Update anggota</button>
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


    //Cek ketersediaan username
    $("#username").bind('keyup', function () {

        var username = $('#username').val();

        $.ajax({
            url: 'anggota/cek-username.php',
            method: 'POST',
            data:{username:username},
            success:function(data){
                $('#info_username').show();
                $('#info_username').html(data);
            }
        }); 

    });
</script>
