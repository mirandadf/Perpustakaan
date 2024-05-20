<script>
    $('title').text('Pengaturan Aplikasi');
</script>
<?php
    if ($_SESSION["level"]!='Karyawan' and $_SESSION["level"]!='karyawan'):
        echo"<div class='alert alert-danger'>Anda tidak punya hak akses</div>";
        exit;
    endif;
?>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Pengaturan Aplikasi</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Pengaturan Aplikasi</li>
        </ol>
        <?php
            if (isset($_GET['edit-aplikasi'])) {
                if ($_GET['edit-aplikasi']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Profil Aplikasi telah diupdate</div>";
                }else if ($_GET['edit-aplikasi']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Profil Aplikasi gagal diupdate</div>";
                }    
            }
            if (isset($_GET['edit-aturan'])) {
                if ($_GET['edit-aturan']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Aturan Perpustakaan telah diupdate</div>";
                }else if ($_GET['edit-aturan']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Aturan Perpustakaan gagal diupdate</div>";
                }    
            }
        ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Profil Aplikasi</h6>
                            </div>
                            <?php 
                                include '../config/database.php';
                            
                                $hasil=mysqli_query($kon,"select * from profil_aplikasi order by nama_aplikasi desc limit 1");
                                $data = mysqli_fetch_array($hasil); 
                            ?>
                            <!-- Card Body -->
                            <div class="card-body">
                                <form action="aplikasi/edit.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="<?php if (isset( $data['id'])) echo $data['id'];?>" name="id">  
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Aplikasi:</label>
                                        <input type="text" class="form-control" value="<?php if (isset( $data['nama_aplikasi'])) echo $data['nama_aplikasi'];?>" name="nama_aplikasi" required>  
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Ketua (Pimpinan):</label>
                                        <input type="text" class="form-control" value="<?php if (isset( $data['nama_pimpinan'])) echo $data['nama_pimpinan'];?>" name="nama_pimpinan" placeholder="Masukan nama Ketua" required>  
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat:</label>
                                        <input type="text" class="form-control" value="<?php if (isset( $data['alamat'])) echo $data['alamat'];?>" name="alamat">  
                                    </div>
                                    <div class="form-group">
                                        <label>No Telp:</label>
                                        <input type="text" class="form-control" value="<?php if (isset( $data['no_telp'])) echo $data['no_telp'];?>" name="no_telp">  
                                    </div>
                                    <div class="form-group">
                                        <label>Website:</label>
                                        <input type="text" class="form-control" value="<?php if (isset( $data['website'])) echo $data['website'];?>" name="website">  
                                    </div>
                                    <div class="form-group">
                                        <div id="msg"></div>
                                        <label>Logo:</label>
                                        <input type="file" name="logo" class="file" >
                                            <div class="input-group my-3">
                                                <input type="text" class="form-control" disabled placeholder="Upload Gambar" id="file">
                                                <div class="input-group-append">
                                                        <button type="button" id="pilih_logo" class="browse btn btn-dark">Pilih Logo</button>
                                                </div>
                                            </div>
                                        <img src="aplikasi/logo/<?php echo $data['logo'];?>" id="preview" width="70%" class="img-thumbnail">
                                        <input type="hidden" name="logo_sebelumnya" value="<?php if (isset( $data['logo'])) echo $data['logo'];?>" />
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" name="ubah_profil_aplikasi" >Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Aturan Perpustakaan</h6>
                            </div>
                            <?php 
                                include '../config/database.php';
                            
                                $hasil=mysqli_query($kon,"select * from aturan_perpustakaan limit 1");
                                $data = mysqli_fetch_array($hasil); 
                            ?>
                            <!-- Card Body -->
                            <div class="card-body">
                                <form action="aplikasi/edit.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" value="<?php echo $data['id'];?>" name="id">  
                                    </div>
                                    <div class="form-group">
                                        <label>Estimasi waktu Peminjaman: <span id="info_waktu_pinjam"><?php if (isset( $data['waktu_peminjaman'])) echo $data['waktu_peminjaman'];?> hari</span> </label>
                                        <input type="text" class="form-control" id="waktu_peminjaman" value="<?php if (isset( $data['waktu_peminjaman'])) echo $data['waktu_peminjaman'];?>" name="waktu_peminjaman" required>  
                                    </div>
                                    <div class="form-group">
                                        <label>Maksimal Peminjaman: <span id="info_maksimal_pinjam"><?php if (isset( $data['maksimal_peminjaman'])) echo $data['maksimal_peminjaman'];?> Pustaka</span> </label>
                                        <input type="text" class="form-control" id="maksimal_pinjam" value="<?php if (isset( $data['maksimal_peminjaman'])) echo $data['maksimal_peminjaman'];?>" name="maksimal_peminjaman" required>  
                                    </div>
                                    <div class="form-group">
                                        <label>Besaran Denda Keterlambatan: <span id="info_denda_terlambat">Rp. <?php if (isset( $data['denda_keterlambatan'])) echo number_format($data['denda_keterlambatan'],0,',','.'); ?>/hari</span> </label>
                                        <input type="text" class="form-control" id="denda_terlambat" value="<?php if (isset( $data['denda_keterlambatan'])) echo $data['denda_keterlambatan'];?>" name="denda_keterlambatan" required>  
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary"name="ubah_aturan_perpustakaan" >Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<style>
    .file {
    visibility: hidden;
    position: absolute;
    }
</style>
<script>

    //Untuk updload file foto
    $(document).on("click", "#pilih_logo", function() {
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

    //Fungsi untuk membuat format rupiah
    function format_rupiah(nominal){
        var  reverse = nominal.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
         return ribuan	= ribuan.join('.').split('').reverse().join('');
    }

    $('#waktu_peminjaman').bind('keyup', function () {
        var waktu_peminjaman=$("#waktu_peminjaman").val();
        $("#info_waktu_pinjam").text(waktu_peminjaman+' hari');     
    }); 

    $('#maksimal_pinjam').bind('keyup', function () {
        var maksimal_pinjam=$("#maksimal_pinjam").val();
        $("#info_maksimal_pinjam").text(maksimal_pinjam+' Pustaka');     
    }); 

    $('#denda_terlambat').bind('keyup', function () {
        var denda_terlambat=$("#denda_terlambat").val();
        $("#info_denda_terlambat").text('Rp.'+format_rupiah(denda_terlambat)+'/hari');     
    }); 

    $('#denda_hilang').bind('keyup', function () {
        var denda_hilang=$("#denda_hilang").val();
        $("#info_denda_hilang").text('Rp.'+format_rupiah(denda_hilang));     
    }); 


</script>