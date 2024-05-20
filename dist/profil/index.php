<script>
    $('title').text('Profil');
</script>
<main>
    <div class="container-fluid">
        <h1 class="mt-4">Profil</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Profil</li>
        </ol>
        <?php
            //Validasi untuk menampilkan pesan pemberitahuan saat user menambah pengguna
            if (isset($_GET['edit'])) {
                //Mengecek nilai variabel add yang telah di enskripsi dengan method md5()
                if ($_GET['edit']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Profil telah diupdate</div>";
                }else if ($_GET['edit']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Profil gagal diupdate</div>";
                }    
            }
        ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Pie Chart -->
                    <div class="col-xl-12 col-lg-5">
                        <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Profil Pengguna</h6>
                            </div>



                            <?php 
                                include '../config/database.php';
                                $id_pengguna=$_SESSION["id_pengguna"];
                                
                                if ($_SESSION["level"]=='Karyawan' or $_SESSION["level"]=='karyawan'){
                                    $sql="select * from pengguna p
                                    inner join karyawan k on k.kode_karyawan=p.kode_pengguna
                                    where p.id_pengguna=$id_pengguna limit 1";
                                }
                                
                                if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'){
                                    $sql="select * from pengguna p
                                    inner join anggota a on a.kode_anggota=p.kode_pengguna
                                    where p.id_pengguna=$id_pengguna limit 1";
                                }
                       
                       
                                $hasil=mysqli_query($kon,$sql);
                                $data = mysqli_fetch_array($hasil); 
                            ?>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                    <?php 
                                        if ($_SESSION["level"]=='Karyawan' or $_SESSION["level"]=='karyawan'):
                                    ?>
                                    <img class="card-img-top" src="karyawan/foto/<?php echo $data['foto'];?>" width="54px" alt="Card image">
                                    <?php endif; ?>

                                    <?php 
                                        if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'):
                                    ?>
                                    <img class="card-img-top" src="anggota/foto/<?php echo $data['foto'];?>" alt="Card image">
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <BR>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>Kode</td>
                                                    <td width="80%">: <?php echo $data['kode_pengguna'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Nama</td>
                                                    <?php if ($_SESSION["level"]=='Karyawan' or $_SESSION["level"]=='karyawan'):?>
                                                    <td width="80%">: <?php echo $data['nama_karyawan'];?></td>
                                                    <?php endif; ?>

                                                    <?php if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'):?>
                                                    <td width="80%">: <?php echo $data['nama_anggota'];?></td>
                                                    <?php endif; ?>
                                                    
                                                </tr>
                                                <tr>
                                                    <td>Username</td>
                                                    <td width="80%">: <?php echo $data['username'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>No Telp</td>
                                                    <td width="80%">: <?php echo $data['no_telp'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td width="80%">: <?php echo $data['email'];?></td>
                                                </tr>
                                                <?php if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'):?>
                                                <tr>
                                                    <td>Tempat Lahir</td>
                                                    <td width="80%">: <?php echo $data['tempat_lahir'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Lahir</td>
                                                    <td width="80%">: <?php echo $data['tanggal_lahir'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Jenis Kelamin</td>
                                                    <td width="80%">:  <?php echo $data['jenis_kelamin'] == 1 ? 'Laki-laki' : 'Perempuan';?></td>
                                                </tr>
                                                <tr>
                                                    <td>Alamat</td>
                                                    <td width="80%">: <?php echo $data['alamat'];?></td>
                                                </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <td>Sebagai</td>
                                                    <td width="80%">: <?php echo $data['level'];?></td>
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td width="80%">: <?php echo $data['status'] == 1 ? 'Aktif' : 'Tidak Aktif';?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#ubah_profil">Edit Profil</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- The Modal -->
<div class="modal fade" id="ubah_profil">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Ubah Profil</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                    <form action="profil/edit-profil.php" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Data Diri</h6>
                            </div>
                            <div class="card-body">

                            <!-- Form edit profil untuk karyawan -->  
                            <?php 
                                if ($_SESSION["level"]=='Karyawan' or $_SESSION["level"]=='karyawan'):
                            ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Kode:</label>
                                            <input name="kode" value="<?php echo $data['kode_pengguna']?>" type="text" class="form-control" placeholder="Masukan kode" disabled>
                                            <input name="id_pengguna" value="<?php echo $data['id_pengguna'];?>" type="hidden" class="form-control">
                                            <input name="id_karyawan" value="<?php echo $data['id_karyawan'];?>" type="hidden" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama:</label>
                                             <input name="nama" value="<?php echo $data['nama_karyawan']?>" type="text" class="form-control" placeholder="Masukan nama" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input name="email" id="email" value="<?php echo $data['email']?>" type="email" class="form-control" placeholder="Masukan email" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>No Telp:</label>
                                            <input name="no_telp" value="<?php echo $data['no_telp']?>" type="text" class="form-control" placeholder="Masukan no telp" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Jenis Kelamin:</label>
                                            <select class="form-control" name="jk" required>
                                                <option>Pilih</option>
                                                <option  <?php if ($data["jk"]==1) echo "selected"; ?> value="1">Laki-laki</option>
                                                <option  <?php if ($data["jk"]==2) echo "selected"; ?> value="2">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat:</label>
                                            <textarea class="form-control" name="alamat" rows="5" ><?php echo $data['alamat'];?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div id="msg"></div>
                                        <label>Foto:</label>
                                        <input type="hidden" name="foto_saat_ini" value="<?php echo $data['foto'];?>" class="form-control" />
                                        <input type="file" name="foto_baru" class="file" >
                                            <div class="input-group my-3">
                                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                                <div class="input-group-append">
                                                    <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih Foto</button>
                                                </div>
                                            </div>
                                        <img src="karyawan/foto/<?php echo $data['foto'];?>" width="50%" id="preview" class="img-thumbnail">
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Form edit profil untuk anggota -->  

                                <?php 
                                if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'):
                            ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Kode:</label>
                                            <input name="kode_pengguna" value="<?php echo $data['kode_pengguna'];?>" type="text" class="form-control" disabled>
                                            <input name="id_pengguna" value="<?php echo $data['id_pengguna'];?>" type="hidden" class="form-control">
                                            <input name="id_anggota" value="<?php echo $data['id_anggota'];?>" type="hidden" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nama:</label>
                                             <input name="nama" value="<?php echo $data['nama_anggota'];?>" type="text" class="form-control" placeholder="Masukan nama" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input name="email" id="email" value="<?php echo $data['email'];?>" type="email" class="form-control" placeholder="Masukan email" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>No Telp:</label>
                                            <input name="no_telp" value="<?php echo $data['no_telp'];?>" type="text" class="form-control" placeholder="Masukan no telp" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tempat Lahir:</label>
                                            <input name="tempat_lahir" id="tempat_lahir" value="<?php echo $data['tempat_lahir'];?>" type="text" class="form-control" placeholder="Masukan tempat lahir">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tanggal Lahir:</label>
                                            <input name="tanggal_lahir" value="<?php echo $data['tanggal_lahir'];?>" type="date" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Alamat:</label>
                                            <textarea class="form-control" name="alamat" rows="5" ><?php echo $data['alamat'];?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Jenis Kelamin:</label>
                                            <select class="form-control" name="jenis_kelamin" required>
                                                <option>Pilih</option>
                                                <option  <?php if ($data["jenis_kelamin"]==1) echo "selected"; ?> value="1">Laki-laki</option>
                                                <option  <?php if ($data["jenis_kelamin"]==2) echo "selected"; ?> value="2">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- rows -->                 
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div id="msg"></div>
                                        <label>Foto:</label>
                                        <input type="hidden" name="foto_saat_ini" value="<?php echo $data['foto'];?>" class="form-control" />
                                        <input type="file" name="foto_baru" class="file" >
                                            <div class="input-group my-3">
                                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                                <div class="input-group-append">
                                                        <button type="button" id="pilih_foto" class="browse btn btn-dark">Pilih Foto</button>
                                                </div>
                                            </div>
                                        <img src="anggota/foto/<?php echo $data['foto'];?>" width="50%" id="preview" class="img-thumbnail">
                                    </div>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Setting Akun</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Username:</label>
                                            <input name="username_baru" id="username_baru" value="<?php echo $data['username']?>" type="text" class="form-control" placeholder="Masukan username" required>
                                            <input name="username_lama" id="username_lama" value="<?php echo $data['username']?>" type="hidden" class="form-control">
                                            <!-- Informasi ketersediaan username akan ditampilkan disini -->
                                            <div id="info_username"> </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Password:</label>
                                            <input name="password" value="<?php echo $data['password']?>" type="password" class="form-control" placeholder="Masukan password" required>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id_pengguna" value="<?php echo $data['id_pengguna']?>"/>
                                <button type="submit" name="simpan_profil"  id="simpan_profil" class="btn btn-success" >Simpan</button>

                            </div>
                        </div>   
                    </form>
            <!-- akhir body -->
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    $("#username_baru").bind('keyup', function () {
        var username_baru = $('#username_baru').val();
        var username_lama = $('#username_lama').val();

        if (username_baru==username_lama){
            $.ajax({
                    url: 'profil/cek-username.php',
                    method: 'POST',
                    data:{username_baru:username_baru},
                    success:function(data){
                        $('#info_username').show();
                        $('#info_username').html(data);
                    }
                }); 
        } else {
            document.getElementById("username_baru").value=username_baru;
            $('#info_username').hide();
        }
                
    });
</script>

