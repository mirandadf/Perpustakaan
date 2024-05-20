<script>
    $('title').text('Data Penerbit');
</script>

<main>
    <div class="container-fluid">
        <h2 class="mt-4">Data Penerbit</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Data Penerbit</li>
        </ol>

        <?php
            //Validasi untuk menampilkan pesan pemberitahuan saat user menambah penerbit
            if (isset($_GET['add'])) {
                if ($_GET['add']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Penerbit telah ditambah!</div>";
                }else if ($_GET['add']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Penerbit gagal ditambahkan!</div>";
                }    
            }

            if (isset($_GET['edit'])) {
                if ($_GET['edit']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Penerbit telah diupdate!</div>";
                }else if ($_GET['edit']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Penerbit gagal diupdate!</div>";
                }    
            }
            if (isset($_GET['hapus'])) {
                if ($_GET['hapus']=='berhasil'){
                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Data Penerbit telah dihapus!</div>";
                }else if ($_GET['hapus']=='gagal'){
                    echo"<div class='alert alert-danger'><strong>Gagal!</strong> Data Penerbit gagal dihapus!</div>";
                }    
            }
        ?>

        <div class="card mb-4">
          <div class="card-header py-3">
            <!-- Tombol tambah penerbit -->
            <button  class="btn-tambah btn btn-dark btn-icon-split"><span class="text">Tambah</span></button>
          </div>
            <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="tabel_penerbit" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Kode</th>
                          <th>Nama Penerbit</th>
                          <th width="10%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                              // include database
                              include '../config/database.php';
                              // perintah sql untuk menampilkan daftar penerbit yang berelasi dengan tabel kategori penerbit
                              $sql="select * from penerbit order by id_penerbit desc";
                              $hasil=mysqli_query($kon,$sql);
                              $no=0;
                              //Menampilkan data dengan perulangan while
                              while ($data = mysqli_fetch_array($hasil)):
                              $no++;
                          ?>
                          <tr>
                              <td><?php echo $no; ?></td>
                              <td><?php echo $data['kode_penerbit']; ?></td>
                              <td><?php echo $data['nama_penerbit']; ?></td>
                              <td>
                                  <button class="btn-edit btn btn-warning btn-circle" id_penerbit="<?php echo $data['id_penerbit']; ?>" kode_penerbit="<?php echo $data['kode_penerbit']; ?>"><i class="fas fa-edit"></i></button>
                                  <a href="pustaka/penerbit/hapus-penerbit.php?id_penerbit=<?php echo $data['id_penerbit']; ?>" class="btn-hapus btn btn-danger btn-circle" ><i class="fa fa-trash"></i></a>
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

<script>
    $(document).ready(function(){
        $('#tabel_penerbit').DataTable();
    });
</script>

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

    // Tambah penerbit
    $('.btn-tambah').on('click',function(){
        var level = $(this).attr("level");
        $.ajax({
            url: 'pustaka/penerbit/tambah-penerbit.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Penerbit';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });


    // fungsi edit penerbit
    $('.btn-edit').on('click',function(){

        var id_penerbit = $(this).attr("id_penerbit");
        var kode_penerbit = $(this).attr("kode_penerbit");
        $.ajax({
            url: 'pustaka/penerbit/edit-penerbit.php',
            method: 'post',
            data: {id_penerbit:id_penerbit},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit Penerbit #'+kode_penerbit;
            }
        });
            // Membuka modal
        $('#modal').modal('show');
    });


    // fungsi hapus penerbit
    $('.btn-hapus').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus penerbit ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>

