<script>
    $('title').text('Denda Saya');
</script>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Denda Saya</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Daftar Peminjaman</li>
        </ol>

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Pustaka</th>
                                <th>Jenis Denda</th>
                                <th>Besaran</th>
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
                            where p.kode_anggota='$kode_anggota' and denda>0
                            order by tanggal_pinjam desc";
                    
                        
                            $hasil=mysqli_query($kon,$sql);
                            $no=0;
                            $total_denda=0;
                            $jenis_denda="";
                            //Menampilkan data dengan perulangan while
                            while ($data = mysqli_fetch_array($hasil)):
                            $no++;

                       
                            $total_denda+=$data['denda'];

                            if ($data['jenis_denda']==0){
                                $jenis_denda="<span class='badge badge-dark'>Tidak ada</span>";
                            }else if ($data['jenis_denda']==1) {
                                $jenis_denda="<span class='badge badge-warning'>Keterlambatan</span>";
                            }else {
                                $jenis_denda="<span class='badge badge-danger'>Hilang/rusak</span>";
                            }
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo tanggal(date('Y-m-d', strtotime($data["tanggal_pinjam"]))); ?></td>
                            <td><?php echo $data['kode_peminjaman']; ?></td>
                            <td><?php echo $data['judul_pustaka']; ?></td>
                            <td><?php echo $jenis_denda; ?></td>
                            <td>Rp. <?php echo number_format($data['denda'],0,',','.'); ?></td>
                        </tr>
                        <!-- bagian akhir (penutup) while -->
                        <?php endwhile; ?>
                        <tr>
                        <th colspan="5">Total Denda</th>
                        <th>Rp. <?php echo number_format($total_denda,0,',','.'); ?></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
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

