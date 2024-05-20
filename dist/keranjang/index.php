<script>
    $('title').text('Keranjang Pustaka');
</script>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Keranjang Pustaka</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Keranjang Pustaka</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php

                            include '../config/database.php';
                            $kode_anggota=$_SESSION['kode_pengguna'];
                            $query1 = mysqli_query($kon, "SELECT * FROM anggota where kode_anggota='$kode_anggota'");
                            $data1 = mysqli_fetch_array($query1);    
                            
                            $query3 = mysqli_query($kon, "SELECT * FROM detail_peminjaman d inner join peminjaman p on d.kode_peminjaman=p.kode_peminjaman where p.kode_anggota='$kode_anggota' and d.status='1'");
                            $jumlah_pinjam = mysqli_num_rows($query3);

                            $query4=mysqli_query($kon,"select maksimal_peminjaman from aturan_perpustakaan limit 1");
                            $data4 = mysqli_fetch_array($query4); 
                            $maksimal_peminjaman=$data4['maksimal_peminjaman']-$jumlah_pinjam;

                            if ($maksimal_peminjaman < 0){
                                $maksimal_peminjaman=0;
                            }

                            $_SESSION["maksimal_peminjaman"]=$maksimal_peminjaman;

                        ?>

                        <?php if ($maksimal_peminjaman!=0){?>
                            <div class="alert alert-info">
                            Hallo <?php echo $data1['nama_anggota'];?> saat ini kamu dapat melakukan peminjaman maksimal sebanyak <?php echo $maksimal_peminjaman; ?> pustaka.
                            </div>
                        <?php }else{ ?>
                            <div class="alert alert-warning">
                                Hallo <?php echo $data1['nama_anggota'];?> saat ini kamu telah mencapai batas maksimal peminjaman. Kembalikan terlebih dahulu pustaka yang sedang dipinjam agar dapat melakukan peminjaman berikutnya.
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <a href="index.php?page=pustaka"  id="tombol_pilih_pustaka" class="btn btn-dark"> Pilih Pustaka</a>
                        </div>
                    </div>
                </div>
                <?php
                    if (isset($_GET['kode_pustaka'])) {

                        $kode_pustaka=$_GET['kode_pustaka'];
                        
                        include '../config/database.php';
                        $sql= "SELECT * from pustaka p
                        inner join penulis s on s.id_penulis=p.penulis
                        inner join penerbit t on t.id_penerbit=p.penerbit
                        inner join kategori_pustaka k on k.id_kategori_pustaka=p.kategori_pustaka
                        where p.kode_pustaka='$kode_pustaka'";

                        $query = mysqli_query($kon,$sql);
                        $data = mysqli_fetch_array($query);

                        $judul_pustaka=$data['judul_pustaka'];
                        $nama_kategori_pustaka=$data['nama_kategori_pustaka'];
                        $nama_penulis=$data['nama_penulis'];
                        $nama_penerbit=$data['nama_penerbit'];
                        $tahun=$data['tahun'];

                    }else {
                        $kode_pustaka="";
                    }

                    if (isset($_GET['aksi'])) {
                        $aksi=$_GET['aksi'];
                    }else {
                        $aksi="";
                    }


                    //Memasukan data ke dalam array
                    if (isset($_GET['aksi'])) {
                    $itemArray = array($data['kode_pustaka']=>array('kode_pustaka'=>$kode_pustaka,'judul_pustaka'=>$judul_pustaka,'nama_kategori_pustaka'=>$nama_kategori_pustaka,'nama_penulis'=>$nama_penulis,'nama_penerbit'=>$nama_penerbit,'tahun'=>$tahun));
                    }
                    switch($aksi){	
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
                                        if($_GET["kode_pustaka"] == $k)
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
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Judul Pustaka</th>
                                    <th>Kategori</th>
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
                                        <td><?php echo $item["kode_pustaka"]; ?></td>
                                        <td><?php echo $item["judul_pustaka"]; ?></td>
                                        <td><?php echo $item["nama_kategori_pustaka"]; ?></td>
                                        <td><?php echo $item["nama_penulis"]; ?></td>
                                        <td><?php echo $item["nama_penerbit"]; ?></td>
                                        <td><?php echo $item["tahun"]; ?></td>
                                        <td><a href="index.php?page=keranjang&kode_pustaka=<?php echo $item['kode_pustaka']; ?>&aksi=hapus_pustaka" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
                                    </tr>
                                <?php 
                                    endforeach;
                                    endif;
                                ?>
                                </tbody>
                            </table>
                            <div id="pesan"> </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php if(!empty($_SESSION["cart_pustaka"])): ?>
                            <a href="keranjang/submit.php" id="ajukan" class="btn btn-success"> Ajukan Sekarang</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php 
    if ($jum<$_SESSION["maksimal_peminjaman"]){
        echo "<script>  $('#tombol_pilih_pustaka').show(); </script>";
        echo "<script>  $('#ajukan').show(); </script>";
    } else if ($jum==$_SESSION["maksimal_peminjaman"]){
    ?>
        <script>  
            $('#tombol_pilih_pustaka').hide(); 
            $('#ajukan').show();
            $('#pesan').html("<span class='text-danger'>Telah mencapai batas maksimal peminjaman</span>"); 
        </script>
    <?php 
    }else {
    ?>
        <script>  
            $('#tombol_pilih_pustaka').hide(); 
            $('#ajukan').hide();
            $('#pesan').html("<span class='text-warning'>Tidak boleh melebihi batas peminjaman. Kurangi salah satu pustaka dalam keranjang</span>"); 
        </script>
    <?php
    }
?>

<script>
   // konfirmasi pengajuan
   $('#ajukan').on('click',function(){
        konfirmasi=confirm("Apakah anda yakin ingin mengajukan peminjaman pustaka ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>



