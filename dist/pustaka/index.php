<script>
   $(document).ready(function () {
         $(".select2").select2({
         });
   });
</script>

<main>
    <div class="container-fluid">
        <h2 class="mt-4">Data Pustaka</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Data Pustaka</li>
        </ol>

        <?php
            //Validasi untuk menampilkan pesan pemberitahuan saat user menambah pustaka
            if (isset($_GET['add'])) {
                //Mengecek nilai variabel add yang telah di enskripsi dengan method md5()
                if ($_GET['add']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Pustaka telah ditambah!</div>";
                }else if ($_GET['add']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Pustaka gagal ditambahkan!</div>";
                }else if  ($_GET['add']=='format_gambar_tidak_sesuai'){
                    echo"<div class='alert alert-warning'><strong>Gagal!</strong> Format gambar tidak sesuai!</div>";
                }   
            }

            if (isset($_GET['edit'])) {
            //Mengecek nilai variabel edit yang telah di enskripsi dengan method md5()
            if ($_GET['edit']=='berhasil'){
                echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Pustaka telah diupdate!</div>";
            }else if ($_GET['edit']=='gagal'){
                echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Pustaka gagal diupdate!</div>";
            }    
            }
            if (isset($_GET['hapus'])) {
            //Mengecek notifikasi hapus
            if ($_GET['hapus']=='berhasil'){
                echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Pustaka telah dihapus!</div>";
            }else if ($_GET['hapus']=='gagal'){
                echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Pustaka gagal dihapus!</div>";
            }    
            }
        ?>
        <div class="card mb-4">
            <div class="card-body">
                <form id="form_pencarian_pustaka">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="sel1">Kategori:</label>
                                <select class="form-control select2" multiple="multiple" name="kategori_pustaka[]">
                                    <?php
                                    include '../config/database.php';
                                    //Perintah sql untuk menampilkan semua data pada tabel penulis
                                    $sql="select * from kategori_pustaka";

                                    $hasil=mysqli_query($kon,$sql);
                                    $no=0;
                                    while ($data = mysqli_fetch_array($hasil)):
                                    $no++;
                                    ?>
                                    <option  value="<?php echo $data['id_kategori_pustaka'];?>"><?php echo $data['nama_kategori_pustaka'];?></option>
                                    <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="sel1">Penulis:</label>
                                <select class="form-control select2" multiple="multiple" name="penulis[]">
                                    <?php
                                    include '../config/database.php';
                                    //Perintah sql untuk menampilkan semua data pada tabel penulis
                                    $sql="select * from penulis";

                                    $hasil=mysqli_query($kon,$sql);
                                    $no=0;
                                    while ($data = mysqli_fetch_array($hasil)):
                                    $no++;
                                    ?>
                                    <option  value="<?php echo $data['id_penulis'];?>"><?php echo $data['nama_penulis'];?></option>
                                    <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="sel1">Penerbit:</label>
                                <select class="form-control select2" multiple="multiple" name="penerbit[]">
                                    <?php
                                    include '../config/database.php';
                                    //Perintah sql untuk menampilkan semua data pada tabel penulis
                                    $sql="select * from penerbit";

                                    $hasil=mysqli_query($kon,$sql);
                                    $no=0;
                                    while ($data = mysqli_fetch_array($hasil)):
                                    $no++;
                                    ?>
                                    <option  value="<?php echo $data['id_penerbit'];?>"><?php echo $data['nama_penerbit'];?></option>
                                    <?php
                                    endwhile;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row float-right">
                        <div class="col-sm-4">
                        <button  type="button" id="btn-cari"  class="btn btn-dark"><span class="text"><i class="fas fa-search"></i></span></button>
                    </div>
                </div>
              </form>
            </div>
        </div>
        <div id="tampil_pustaka">
            <!-- Daftar pustaka akan ditampilkan disini -->
        </div>
        <div id='ajax-wait'>
            <img alt='loading...' src='../src/img/Rolling-1s-84px.png' />
        </div>
        <style>
            #ajax-wait {
                display: none;
                position: fixed;
                z-index: 1999
            }
        </style>
    </div>
</main>

<script>

    //Menampilkan form penyewaan
    $(document).ready(function(){
        
        $.ajax({
            type	: 'POST',
            url: 'pustaka/tampil-pustaka.php',
            data	: '',
            cache	: false,
            success	: function(data){
                $("#tampil_pustaka").html(data);
            }
        });
    });

    $('#btn-cari').on('click',function(){
        $( document ).ajaxStart(function() {
        $( "#ajax-wait" ).css({
            left: ( $( window ).width() - 32 ) / 2 + "px", // 32 = lebar gambar
            top: ( $( window ).height() - 32 ) / 2 + "px", // 32 = tinggi gambar
            display: "block"
        })
        })
        .ajaxComplete( function() {
            $( "#ajax-wait" ).fadeOut();
        });

        var data = $('#form_pencarian_pustaka').serialize();
        $.ajax({
            type	: 'POST',
            url: 'pustaka/tampil-pustaka.php',
            data: data,
            cache	: false,
            success	: function(data){
                $("#tampil_pustaka").html(data);

            }
        });
    });

</script>