<script>
    $('title').text('Data karyawan');
</script>

<?php
    $id_pengguna= $_SESSION["id_pengguna"];

    if ($_SESSION["level"]!='Karyawan' and $_SESSION["level"]!='karyawan'):
        echo"<div class='alert alert-danger'>Anda tidak punya hak akses</div>";
        exit;
    endif;
?>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Data karyawan</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Data karyawan</li>
        </ol>

        <?php
            //Validasi untuk menampilkan pesan pemberitahuan saat user menambah karyawan
            if (isset($_GET['add'])) {
                if ($_GET['add']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> data karyawan telah ditambah!</div>";
                }else if ($_GET['add']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data karyawan gagal ditambahkan!</div>";
                }    
            }

            if (isset($_GET['edit'])) {
                if ($_GET['edit']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data karyawan telah diupdate!</div>";
                }else if ($_GET['edit']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data karyawan gagal diupdate!</div>";
                }    
            }
            if (isset($_GET['hapus'])) {
                if ($_GET['hapus']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data karyawan telah dihapus!</div>";
                }else if ($_GET['hapus']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data karyawan gagal dihapus!</div>";
                }    
            }

            if (isset($_GET['setting-akun'])) {
                if ($_GET['setting-akun']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Akun pengguna telah disetting!</div>";
                }else if ($_GET['setting-akun']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Akun pengguna gagal disetting!</div>";
                }    
            }
        ?>

        <div class="card mb-4">
          <div class="card-header py-3">
            <!-- Tombol tambah karyawan -->
            <button  class="btn-tambah btn btn-dark btn-icon-split"><span class="text">Tambah</span></button>
          </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabel_karyawan" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Email</th>
                                <th>No Telp</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // include database
                            include '../config/database.php';
                        
                            $sql="select * from karyawan";
                            $hasil=mysqli_query($kon,$sql);
                            $no=0;
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                            $no++;
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['nip']; ?></td>
                            <td><?php echo $data['nama_karyawan']; ?></td>
                            <td><?php echo $data['jk'] == 1 ? 'Laki-laki' : 'Perempuan';?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo $data['no_telp']; ?></td>
                            <td>
                                <button class="setting-akun btn btn-primary btn-circle" kode_karyawan="<?php echo $data['kode_karyawan']; ?>" ><i class="fas fa-user"></i></button>
                                <button class="btn-edit btn btn-warning btn-circle" id_karyawan="<?php echo $data['id_karyawan']; ?>" kode_karyawan="<?php echo $data['kode_karyawan']; ?>" ><i class="fas fa-edit"></i></button>
                                <a href="karyawan/hapus.php?id_karyawan=<?php echo $data['id_karyawan']; ?>&kode_karyawan=<?php echo $data['kode_karyawan']; ?>" class="btn-hapus btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <!-- bagian akhir (penutup) while -->
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title" id="judul"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div id="tampil_data">
                 <!-- Data akan di load menggunakan AJAX -->                   
            </div>  
        </div>
  
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#tabel_karyawan').DataTable();
    });
</script>

<script>

    // Tambah karyawan
    $('.btn-tambah').on('click',function(){
        var level = $(this).attr("level");
        $.ajax({
            url: 'karyawan/tambah.php',
            method: 'post',
            data: {level:level},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah karyawan';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });


    // fungsi edit karyawan
    $('.btn-edit').on('click',function(){

        var id_karyawan = $(this).attr("id_karyawan");
        var kode_karyawan = $(this).attr("kode_karyawan");
        $.ajax({
            url: 'karyawan/edit.php',
            method: 'post',
            data: {id_karyawan:id_karyawan},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit karyawan #'+kode_karyawan;
            }
        });
            // Membuka modal
        $('#modal').modal('show');
    });

    // fungsi setting akun karyawan
    $('.setting-akun').on('click',function(){

        var kode_karyawan = $(this).attr("kode_karyawan");
        $.ajax({
            url: 'karyawan/setting-akun.php',
            method: 'post',
            data: {kode_karyawan:kode_karyawan},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Setting Akun';
            }
        });
            // Membuka modal
        $('#modal').modal('show');
    });


   // fungsi hapus karyawan
   $('.btn-hapus').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus karyawan ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
