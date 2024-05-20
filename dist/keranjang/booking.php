<script>
    $('title').text('Pengajuan Berhasil');
</script>
<?php
    $tanggal=date('Y-m-d');
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
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Pengajuan Berhasil</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Pengajuan Berhasil</li>
        </ol>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="mt-4">Selamat <?php echo $_SESSION['nama_anggota'];?></h3>
                        <p>Pengajuan peminjaman pustaka pada hari ini <?php echo tanggal($tanggal); ?> telah berhasil. Selanjutnya anda diwajibkan untuk datang pada hari dan jam kerja (maksimal 1 x 48 jam) dengan membawa identitas/kartu anggota lalu tunjukan kode peminjaman berikut ini kepada petugas</p>
                        <h3><span class="badge badge-primary">#<?php echo $_GET['kode_peminjaman'];?></span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                            include '../config/database.php';
                            $kode_peminjaman=$_GET['kode_peminjaman'];
                            //Perintah sql untuk menampilkan semua data pada tabel penulis
                            $sql="select * from pustaka p
                            inner join detail_peminjaman d on d.kode_pustaka=p.kode_pustaka
                            where d.kode_peminjaman='$kode_peminjaman'";

                            $hasil=mysqli_query($kon,$sql);
                            while ($data = mysqli_fetch_array($hasil)):

                        ?>
                        <div class="col-sm-2">
                            <div class="card">
                                <div class="card bg-basic">
                                    <img class="card-img-top" src="../dist/pustaka/gambar/<?php echo $data['gambar_pustaka'];?>"  alt="Card image cap">
                                    <div class="card-body text-center">
                      
    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>