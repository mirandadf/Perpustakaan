<?php
session_start();
$kategori="";
$penulis="";
$penerbit="";

if (isset($_POST['kategori_pustaka'])) {
	foreach ($_POST['kategori_pustaka'] as $value)
	{
		$kategori .= "'$value'". ",";
	}
	$kategori = substr($kategori,0,-1);
}else {
    $kategori = "0"; 
}

if (isset($_POST['penulis'])) {
	foreach ($_POST['penulis'] as $value)
	{
		$penulis .= "'$value'". ",";
	}
	$penulis = substr($penulis,0,-1);
}

if (isset($_POST['penerbit'])) {
	foreach ($_POST['penerbit'] as $value)
	{
		$penerbit .= "'$value'". ",";
	}
	$penerbit = substr($penerbit,0,-1);

}
?>

<div class="row">
    <div class="col-sm-2">
        <div class="form-group">
        <?php 
            if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):
        ?>
            <button type="button" id="btn-tambah-pustaka" class="btn btn-warning"><span class="text"><i class="fas fa-book fa-sm"></i> Tambah Pustaka</span></button>
        <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">

<?php         
    // include database
    include '../../config/database.php';



    if (isset($_POST['kategori_pustaka']) and !isset($_POST['penulis']) and !isset($_POST['penerbit'])){
        $sql="select * from pustaka where kategori_pustaka in($kategori)";
    }else if (isset($_POST['kategori_pustaka']) and isset($_POST['penulis']) and !isset($_POST['penerbit'])){
        $sql="select * from pustaka where kategori_pustaka in($kategori) and penulis in($penulis)";
    }else if (isset($_POST['kategori_pustaka']) and !isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from pustaka where kategori_pustaka in($kategori) and penerbit in($penerbit)";
    }else if (isset($_POST['kategori_pustaka']) and isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from pustaka where kategori_pustaka in($kategori) and penulis in($penulis) and penerbit in($penerbit)";
    }else if (!isset($_POST['kategori_pustaka']) and isset($_POST['penulis']) and !isset($_POST['penerbit'])){
        $sql="select * from pustaka where penulis in($penulis)";
    }else if (!isset($_POST['kategori_pustaka']) and isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from pustaka where penulis in($penulis) and penerbit in($penerbit)";
    }else if (!isset($_POST['kategori_pustaka']) and !isset($_POST['penulis']) and isset($_POST['penerbit'])){
        $sql="select * from pustaka where penerbit in($penerbit)";
    }else{
        $sql="select * from pustaka";
    }

    $hasil=mysqli_query($kon,$sql);
    $cek=mysqli_num_rows($hasil);

    if ($cek<=0){
        echo"<div class='col-sm-12'><div class='alert alert-warning'>Data tidak ditemukan!</div></div>";
        exit;
    }
    $no=0;
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):
    $no++;
?>
<div class="col-sm-2">
    <div class="card">

        <div class="card bg-basic">
            <img class="card-img-top" src="../dist/pustaka/gambar/<?php echo $data['gambar_pustaka'];?>"  alt="Card image cap">
            <div class="card-body text-center">
            <?php 
                if ($_SESSION['level']=='Karyawan' or $_SESSION['level']=='karyawan'):
            ?>
                <button  type="button" class="btn-detail-pustaka btn btn-light" id_pustaka="<?php echo $data['id_pustaka'];?>"  kode_pustaka="<?php echo $data['kode_pustaka'];?>" ><span class="text"><i class="fas fa-mouse-pointer"></i></span></button>
				<button  type="button" class="btn-edit-pustaka btn btn-light" id_pustaka="<?php echo $data['id_pustaka'];?>" kode_pustaka="<?php echo $data['kode_pustaka'];?>" ><span class="text"><i class="fas fa-edit"></i></span></button>
				<a href="pustaka/hapus.php?id_pustaka=<?php echo $data['id_pustaka']; ?>&gambar_pustaka=<?php echo $data['gambar_pustaka']; ?>" class="btn-hapus btn btn-light" ><i class="fa fa-trash"></i></a>
            <?php endif; ?>
            <?php 
                if ($_SESSION['level']=='Anggota' or $_SESSION['level']=='anggota'):
            ?>
             <button  type="button" class="btn-detail-pustaka btn btn-warning btn-block" id_pustaka="<?php echo $data['id_pustaka'];?>"  kode_pustaka="<?php echo $data['kode_pustaka'];?>" ><span class="text">Lihat</span></button>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>
</div>


<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <!-- Bagian header -->
        <div class="modal-header">
            <h4 class="modal-title" id="judul"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Bagian body -->
        <div class="modal-body">
            <div id="tampil_data">

            </div>  
        </div>
        <!-- Bagian footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>


<script>
    // Tambah pustaka
    $('#btn-tambah-pustaka').on('click',function(){
        $.ajax({
            url: 'pustaka/tambah.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Tambah Pustaka Baru';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });

    // Melihat detail pustaka
    $('.btn-detail-pustaka').on('click',function(){
		var id_pustaka = $(this).attr("id_pustaka");
        var kode_pustaka = $(this).attr("kode_pustaka");
        $.ajax({
            url: 'pustaka/detail.php',
            method: 'post',
			data: {id_pustaka:id_pustaka},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Detail Pustaka #'+kode_pustaka;
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });

    // Edit pustaka
    $('.btn-edit-pustaka').on('click',function(){
		var id_pustaka = $(this).attr("id_pustaka");
		var kode_pustaka = $(this).attr("kode_pustaka");
        $.ajax({
            url: 'pustaka/edit.php',
            method: 'post',
			data: {id_pustaka:id_pustaka},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Edit Pustaka #'+kode_pustaka;
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });


       // fungsi hapus karyawan
    $('.btn-hapus').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus pustaka ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>
