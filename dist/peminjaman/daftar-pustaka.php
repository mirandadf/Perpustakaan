<?php
session_start();
$pustaka= "";
if (isset($_POST['pustaka'])) {
	foreach ($_POST['pustaka'] as $value)
	{
		$pustaka .= "'$value'". ",";
	}
	$pustaka = substr($pustaka,0,-1);
}

?>


<?php
    $kode_pustaka="";
    if(!empty($_SESSION["cart_pustaka"])):
        foreach ($_SESSION["cart_pustaka"] as $item):
            $kode=$item["kode_pustaka"];
            $kode_pustaka .= "'$kode'". ",";
        endforeach;
    $kode_pustaka = substr($kode_pustaka,0,-1);
    endif;

?>

<div class="row">
<?php         
// include database
include '../../config/database.php';
// perintah sql untuk menampilkan daftar pengguna yang berelasi dengan tabel kategori pengguna
if(!empty($_SESSION["cart_pustaka"])) {
    $sql="select * from pustaka where kode_pustaka not in($kode_pustaka) and stok>=1";
}else {
    $sql="select * from pustaka where stok>=1";
}

$hasil=mysqli_query($kon,$sql);
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
                <button  type="button" data-dismiss="modal" class="btn-pilih-pustaka btn btn-dark btn-block" aksi="pilih_pustaka" id_pustaka="<?php echo $data['id_pustaka'];?>"  kode_pustaka="<?php echo $data['kode_pustaka'];?>" ><span class="text"><i class="fas fa-mouse-pointer"></i></span> Pilih </button>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>
</div>

<script>
$('.btn-pilih-pustaka').on('click',function(){
    var aksi = $(this).attr("aksi");
    var kode_pustaka= $(this).attr("kode_pustaka");

    $.ajax({
        url: 'peminjaman/cart.php',
        method: 'POST',
        data:{kode_pustaka:kode_pustaka,aksi:aksi},
        success:function(data){
            $('#tampil_cart').html(data);
        }
    }); 

});
</script>