<?php
session_start();
    if (isset($_POST['kode_pustaka'])) {
        $kode_pustaka=$_POST['kode_pustaka'];
           
        include '../../config/database.php';
        $sql= "SELECT * from pustaka p
        inner join penulis s on s.id_penulis=p.penulis
        inner join penerbit t on t.id_penerbit=p.penerbit
        where p.kode_pustaka='$kode_pustaka'";
        $query = mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($query);
        $judul_pustaka=$data['judul_pustaka'];
        $nama_penulis=$data['nama_penulis'];
        $nama_penerbit=$data['nama_penerbit'];
        $tahun=$data['tahun'];
    }else {
        $kode_pustaka="";
    }
    if (isset($_POST['aksi'])) {
        $aksi=$_POST['aksi'];
    }else {
        $aksi="";
    }


    //Memasukan data ke dalam array
    if (isset($_POST['aksi'])) {
    $itemArray = array($data['kode_pustaka']=>array('kode_pustaka'=>$kode_pustaka,'judul_pustaka'=>$judul_pustaka,'nama_penulis'=>$nama_penulis,'nama_penerbit'=>$nama_penerbit,'tahun'=>$tahun));
    }
    switch($aksi) {	
        //Fungsi untuk menambah penyewaan kedalam cart
        case "pilih_pustaka":
        if(!empty($_SESSION["cart_pustaka"])) {
            if(in_array($data['kode_pustaka'],array_keys($_SESSION["cart_pustaka"]))) {
                foreach($_SESSION["cart_pustaka"] as $k => $v) {
                        if($data['kode_pustaka'] == $k) {
                            $_SESSION["cart_pustaka"] = array_merge($_SESSION["cart_pustaka"],$itemArray);
                        }
                }
            } else {
                $_SESSION["cart_pustaka"] = array_merge($_SESSION["cart_pustaka"],$itemArray);
            }
        } else {
            $_SESSION["cart_pustaka"] = $itemArray;
        }
        break;
        //Fungsi untuk menghapus penyewaan dari cart
        case "hapus_pustaka":
    		if(!empty($_SESSION["cart_pustaka"])) {
                foreach($_SESSION["cart_pustaka"] as $k => $v) {
                        if($_POST["kode_pustaka"] == $k)
                            unset($_SESSION["cart_pustaka"][$k]);
                        if(empty($_SESSION["cart_pustaka"]))
                            unset($_SESSION["cart_pustaka"]);
                }
            }
        break;
    }
?>
 <div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <button type="button" name="tombol_pilih_pustaka" id="tombol_pilih_pustaka" class="btn btn-primary">Pilih Pustaka</button>
                </div>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Pustaka</th>
                    <th>Penulis</th>
                    <th>Perbit</th>
                    <th>Tahun</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $no=0;
                    $jum=0;
         
                    if(!empty($_SESSION["cart_pustaka"])):
                    foreach ($_SESSION["cart_pustaka"] as $item):
                        $no++;
                        $jum+=1;
                ?>
                    <input type="hidden" name="kode_pustaka[]" class="kode_pustaka" value="<?php echo $item["kode_pustaka"]; ?>"/>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $item["judul_pustaka"]; ?></td>
                        <td><?php echo $item["nama_penulis"]; ?></td>
                        <td><?php echo $item["nama_penerbit"]; ?></td>
                        <td><?php echo $item["tahun"]; ?></td>
                       
                        <td><button type="button" kode_pustaka="<?php echo $item["kode_pustaka"]; ?>"  class="hapus_pustaka btn btn-danger btn-circle"  ><i class="fas fa-trash"></i></button></td>
                    </tr>
                <?php 
                    endforeach;
                    endif;
                ?>
                </tbody>
            </table>
            <?php 
            if ($_SESSION["maksimal_peminjaman"] <= $jum){
                echo "<script> document.getElementById('tombol_pilih_pustaka').disabled = true; </script>";
        
                echo"<span class='text-danger'>Telah mencapai batas maksimal peminjaman</span>";
            }
            ?>
        </div>
    </div>
</div>
<script>

    //Fungsi untuk menghapus penyewaan mobil dari cart (keranjang belanja)
    $('.hapus_pustaka').on('click',function(){
        var kode_pustaka = $(this).attr("kode_pustaka");
        var aksi ='hapus_pustaka';
        $.ajax({
            url: 'peminjaman/cart.php',
            method: 'POST',
            data:{kode_pustaka:kode_pustaka,aksi:aksi},
            success:function(data){
                $('#tampil_cart').html(data);
            }
        }); 
    });

    //Fungsi untuk menampilkan pemberitahuan caart masih kosong saat pengguna mengklik tombol selanjutnya
    $('#simpan_peminjaman').on('click',function(){
        var kode_pustaka=$(".kode_pustaka").val();

        if(kode_pustaka==null) {
            alert('Belum ada pustaka yang diilih');
            return false;
        }

    });

    // edit pembayaran
    $('#tombol_pilih_pustaka').on('click',function(){
        var id_pustaka = $(this).attr("id_pustaka");
        var kode_pustaka = $(this).attr("kode_pustaka");
        $.ajax({
            url: 'peminjaman/daftar-pustaka.php',
            method: 'post',
            data: {id_pustaka:id_pustaka},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML='Pilih Pustaka';
            }
        });
        // Membuka modal
        $('#modal').modal('show');
    });
</script>