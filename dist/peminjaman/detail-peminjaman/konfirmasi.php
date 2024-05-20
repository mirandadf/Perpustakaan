<?php
session_start();
if (isset($_POST['konfirmasi'])) {

    include '../../../config/database.php';

    mysqli_query($kon,"START TRANSACTION");

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    $id_detail_peminjaman=input($_POST["id_detail_peminjaman"]);
    $kode_peminjaman=input($_POST["kode_peminjaman"]);
    $kode_pustaka=input($_POST["kode_pustaka"]);
    $status_peminjaman=input($_POST["status_peminjaman"]);
    $kode_anggota=input($_POST["kode_anggota"]);
    $jenis_denda=input($_POST["jenis_denda"]);

    if ($jenis_denda==1){
        $denda=input($_POST["biaya_keterlambatan"]);
    }else {
        $denda=input($_POST["biaya_hilang_kerusakan"]);
    }

    
    if ($status_peminjaman==0){
        $jenis_denda=0;
        $tanggal_pinjam="";
        $tanggal_kembali="";

    } else if ($status_peminjaman==1){
        $jenis_denda=0;
        $tanggal_pinjam=date('Y-m-d');
        $tanggal_kembali="";
    } else if ($status_peminjaman==2){
       
        if ($_POST["tanggal_pinjam"]=='0000-00-00'){
            $tanggal_pinjam=date('Y-m-d');
        }else {
            $tanggal_pinjam=$_POST["tanggal_pinjam"];
        }

        $tanggal_kembali=date('Y-m-d');

    } else if ($status_peminjaman==3){
        $jenis_denda=0;
        $tanggal_pinjam="";
        $tanggal_kembali="";
    }


    if ($status_peminjaman==1){

        include '../../../config/database.php';

        //Mengambil aturan maksimal peminjaman
        $query = mysqli_query($kon, "SELECT maksimal_peminjaman FROM aturan_perpustakaan");
        $result = mysqli_fetch_assoc($query);

        $maksimal_peminjaman=$result['maksimal_peminjaman'];

        //Cek jumlah pustaka yang sedang dipinjam
        $query = mysqli_query($kon, "SELECT * FROM detail_peminjaman d inner join peminjaman p on p.kode_peminjaman=d.kode_peminjaman where d.status='1' and p.kode_anggota='$kode_anggota'");
        $jumlah_sedang_pinjam = mysqli_num_rows($query);

        if ($jumlah_sedang_pinjam>=$maksimal_peminjaman){
            header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&konfirmasi=tolak#bagian_detail_peminjaman");
        }else {

            $sql="update detail_peminjaman set
            status='$status_peminjaman',
            jenis_denda='$jenis_denda',
            denda='$denda',
            tanggal_pinjam='$tanggal_pinjam',
            tanggal_kembali='$tanggal_kembali'
            where id_detail_peminjaman='$id_detail_peminjaman'";
        
            //Mengeksekusi atau menjalankan query diatas
            $konfirmasi=mysqli_query($kon,$sql);
        
        
            if ($status_peminjaman==1){
                //Kurangi stok pustaka saat pustaka dipinjam
                $update_stok=mysqli_query($kon,"update pustaka set stok=stok-1 where kode_pustaka='$kode_pustaka'");
        
            } else if ($status_peminjaman==2){
                //Tambah stok pustaka saat pustaka dikembalikan
                $update_stok=mysqli_query($kon,"update pustaka set stok=stok+1 where kode_pustaka='$kode_pustaka'");
            } 
        
        
            //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
            if ($konfirmasi or $update_stok) {
                mysqli_query($kon,"COMMIT");
                header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&konfirmasi=berhasil#bagian_detail_peminjaman");
            }
            else {
                mysqli_query($kon,"ROLLBACK");
                header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&konfirmasi=gagal#bagian_detail_peminjaman");
            }

        }
    }else {

        $sql="update detail_peminjaman set
        status='$status_peminjaman',
        jenis_denda='$jenis_denda',
        denda='$denda',
        tanggal_pinjam='$tanggal_pinjam',
        tanggal_kembali='$tanggal_kembali'
        where id_detail_peminjaman='$id_detail_peminjaman'";
    
        //Mengeksekusi atau menjalankan query diatas
        $konfirmasi=mysqli_query($kon,$sql);
    
    
        if ($status_peminjaman==1){
             //Kurangi stok pustaka saat pustaka dipinjam
             $update_stok=mysqli_query($kon,"update pustaka set stok=stok-1 where kode_pustaka='$kode_pustaka'");
    
        } else if ($status_peminjaman==2){
            //Tambah stok pustaka saat pustaka dikembalikan
            $update_stok=mysqli_query($kon,"update pustaka set stok=stok+1 where kode_pustaka='$kode_pustaka'");
        } 
    
    
        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($konfirmasi or $update_stok) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&konfirmasi=berhasil#bagian_detail_peminjaman");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../index.php?page=detail-peminjaman&kode_peminjaman=$kode_peminjaman&konfirmasi=gagal#bagian_detail_peminjaman");
        }

    }



}
//----------------------------------------------------------------------------
?>

<form action="peminjaman/detail-peminjaman/konfirmasi.php" method="post">
    <input type="hidden" name="tanggal_pinjam" id="tanggal_pinjam" value="<?php echo $_POST['tanggal_pinjam'];?>"/>
    <input type="hidden" name="id_detail_peminjaman" id="id_detail_peminjaman" value="<?php echo $_POST['id_detail_peminjaman'];?>"/>
    <input type="hidden" name="status" id="status" value="<?php echo $_POST['status'];?>"/>
    <input type="hidden" name="kode_peminjaman" id="kode_peminjaman" value="<?php echo $_POST['kode_peminjaman'];?>"/>
    <input type="hidden" name="kode_pustaka" id="kode_pustaka" value="<?php echo $_POST['kode_pustaka'];?>"/>
    <input type="hidden" name="kode_anggota" id="kode_anggota" value="<?php echo $_POST['kode_anggota'];?>"/>

    <div class="form-group">
    <label for="status_peminjaman">Status:</label>
    <select class="form-control" name="status_peminjaman" id="status_peminjaman">
        <option value="0"<?php echo $_POST['status'] == 0 ? 'selected' : ''; ?> >Belum diambil</option>
        <option value="1"<?php echo $_POST['status'] == 1 ? 'selected' : ''; ?> >Sedang Dipinjam</option>
        <?php if ( $_POST['status'] == 1  or $_POST['status'] == 2 ):?>
        <option value="2"<?php echo $_POST['status'] == 2 ? 'selected' : ''; ?> >Telah Selesai</option>
        <?php endif; ?>
        <option value="3"<?php echo $_POST['status'] == 3 ? 'selected' : ''; ?> >Batal</option>
    </select>
    </div>
    <?php if( $_POST['status'] != 2): ?>
    <div id="tabel_denda">           
        <?php
            include '../../../config/database.php';
                        
            $hasil=mysqli_query($kon,"select waktu_peminjaman,denda_keterlambatan from aturan_perpustakaan limit 1");
            $data = mysqli_fetch_array($hasil);
            $tanggal_sekarang =  date('Y-m-d');
            $tanggal_kembali=date("Y-m-d",strtotime("+".$data['waktu_peminjaman']." day",strtotime($_POST['tanggal_pinjam'])));

            $biaya_keterlambatan=0;

            if ($_POST['tanggal_pinjam']!='0000-00-00'){

            

                if ($tanggal_sekarang >  $tanggal_kembali){

                    $tgl1 = new DateTime($tanggal_sekarang);
                    $tgl2 = new DateTime($tanggal_kembali);
                    $selisih_tanggal = $tgl1->diff($tgl2)->days;
                    $biaya_keterlambatan = $data['denda_keterlambatan']*$selisih_tanggal;
    
                    echo"<div class='alert alert-warning'>Pengembalian pustaka terlambat ".$selisih_tanggal." hari </div>";
    
                }else {
                    echo"<div class='alert alert-info'>Pengembalian pustaka tidak terlambat</div>";
                }
            }


            
        ?>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>Jenis Denda</th> 
                <th>Biaya</th>
            </tr>
            <tr>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="tidak_ada" name="jenis_denda" value="0"  <?php if ($_POST['jenis_denda']=='0') echo "checked"; ?> />
                        <label class="custom-control-label" for="tidak_ada">Tidak ada</label>
                    </div>
                </td> 
                <td>Rp.0</td>
            </tr>
            <?php 
            if ($_POST['tanggal_pinjam']!='0000-00-00'):

                if ($tanggal_sekarang > $tanggal_kembali):
            ?>
            <tr>
                <td>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="keterlambatan" name="jenis_denda" value="1"  <?php if ($_POST['jenis_denda']=='1') echo "checked"; ?> <?php if ($biaya_keterlambatan > 0) echo "checked"; ?> />
                        <label class="custom-control-label" for="keterlambatan">Keterlambatan</label>
                    </div>
                </td>

                <td>
                    Rp. <span id="tampil_denda_keterlambatan"><?php echo number_format($biaya_keterlambatan,0,',','.'); ?></span>
                    <input type="hidden" name="biaya_keterlambatan" id="biaya_keterlambatan" value="<?php echo $biaya_keterlambatan;?>"/>
                </td>
                
            </tr>
            <?php 
                endif;
            endif;
            ?>
            <tr>
                <td>
                <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="hilang_rusak" name="jenis_denda" value="2"  <?php if ($_POST['jenis_denda']=='2') echo "checked"; ?>  />
                        <label class="custom-control-label" for="hilang_rusak">Hilang/rusak</label>
                        
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" class="form-control" id="biaya_hilang_kerusakan" name="biaya_hilang_kerusakan" value="<?php if ($_POST['jenis_denda']=='2') echo $_POST['denda']; ?> " placeholder="Masukan biaya" disabled>  
                    </div>
                    <div class="form-group">
                        <span id="info_denda"></span>
                    </div>
                </td>
            </tr>

            </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    <input type="submit" class="btn btn-primary" id="tombol_submit" name="konfirmasi" value="Submit">
</form>
<script>

    function format_rupiah(nominal){
        var  reverse = nominal.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
         return ribuan	= ribuan.join('.').split('').reverse().join('');
    }
    //Tabel denda pada awalnya disembunyikan
    $('#tabel_denda').hide();


    $('#status_peminjaman').bind('change', function () {
        var status_peminjaman = $("#status_peminjaman").val();
            if (status_peminjaman==2){
            $('#tabel_denda').show(200);
        }else {
            $('#tabel_denda').hide();
        }
   });



   $('#keterlambatan').on('click',function(){
        $( "#biaya_hilang_kerusakan" ).prop( "disabled", true );
    });

    $('#hilang_rusak').on('click',function(){
        $( "#biaya_hilang_kerusakan" ).prop( "disabled", false );
    });

    $('#biaya_hilang_kerusakan').bind('keyup', function () {
        var denda=$("#biaya_hilang_kerusakan").val();
        $("#info_denda").text('Rp.'+format_rupiah(denda));     
    });



    var status = $("#status").val();

    if (status==2){
        $('#tabel_denda').show();
    }else {
        $('#tabel_denda').hide();
    }
    
</script>