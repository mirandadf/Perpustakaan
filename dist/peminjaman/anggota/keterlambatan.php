<script>
    $('title').text('Keterlambatan');
</script>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Keterlambatan</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Daftar Peminjaman</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <?php
                include '../config/database.php';
                    $query = mysqli_query($kon, "select * from aturan_perpustakaan limit 1");    
                    $row = mysqli_fetch_array($query);
                    $waktu_peminjaman=$row['waktu_peminjaman'];
                    $denda_keterlambatan=$row['denda_keterlambatan'];
                ?>
                <p>Perpustakaan menerapkan aturan waktu peminjaman pustaka selama <?php echo $waktu_peminjaman; ?> hari dihitung sejak tanggal peminjaman. Apabila melebihi waktu tersebut maka akan dikenakan denda keterlambatan sebesar Rp.<?php echo number_format($denda_keterlambatan,0,',','.');?>/Hari.</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Pustaka</th>
                                <th>Status</th>
                                <th>Tanggal Pinjam</th>
                                <th>Harus Kembali</th>
                                <th>Terlambat</th>
                                <th>Perkiraan Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // include database
                            include '../config/database.php';
                         
                            $kode_anggota = $_SESSION["kode_pengguna"];
                            $sql="select *
                            from peminjaman p
                            inner join detail_peminjaman d on d.kode_peminjaman=p.kode_peminjaman
                            inner join pustaka k on k.kode_pustaka=d.kode_pustaka
                            where p.kode_anggota='$kode_anggota' and status='1'
                            order by tanggal_pinjam desc";
                        
                            $hasil=mysqli_query($kon,$sql);
                            $no=0;
                            $total_denda=0;
                            $jenis_denda="";
                            $status="";
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                            $no++;
                            $tanggal_sekarang = date ('Y-m-d');
                            $tanggal_hrs_kembali = date("Y-m-d", strtotime("+".$waktu_peminjaman." day", strtotime($data['tanggal_pinjam'])));
                        	
                            if ($tanggal_hrs_kembali<$tanggal_sekarang){
                                $tgl1 = new DateTime($tanggal_hrs_kembali);
                                $tgl2 = new DateTime( $tanggal_sekarang);
                                $terlambat = $tgl2->diff($tgl1)->days;
                            }else {
                                $terlambat=0;
                            }
                    
                      
                            if ($data['status']==0){
                                $status="<span class='badge badge-dark'>Belum diambil</span>";
                            }else if ($data['status']==1) {
                                $status="<span class='badge badge-primary'>Sedang Dipinjam</span>";
                            }else if ($data['status']==2){
                                $status="<span class='badge badge-success'>Telah Selesai</span>";
                            }
                            else if ($data['status']==3){
                                $status="<span class='badge badge-danger'>Batal</span>";
                            }
                 
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['kode_peminjaman']; ?></td>
                            <td><?php echo $data['judul_pustaka']; ?></td>
                            <td><?php echo $status; ?></td>
                            <td class="text-center"><?php echo  tanggal(date("Y-m-d",strtotime($data['tanggal_pinjam']))); ?></td>
                            <td class="text-center"><?php echo  tanggal(date("Y-m-d",strtotime($tanggal_hrs_kembali))); ?></td>
                            <td><?php echo $terlambat." hari"; ?></td>
                            <td>Rp. <?php echo number_format($terlambat*$denda_keterlambatan,0,',','.'); ?></td>
                        </tr>
                        <!-- bagian akhir (penutup) while -->
                        <?php endwhile; ?>
                        <tr>
                        </tbody>
                    </table>
                </div>
                <span class="text-danger">Harap diperhatikan tanggal pengembalian agar tidak dikenakan denda.</span>
            </div>
        </div>
    </div>
</main>

<?php 
    //Membuat format tanggal
    function tanggal($tanggal)
    {
        $bulan = array (1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $split = explode('-', $tanggal);
        return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }
?>

