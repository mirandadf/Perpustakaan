<script>
    $('title').text('Dashboard');
</script>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Dashboard</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <?php if ($_SESSION["level"]=='Karyawan' or $_SESSION["level"]=='karyawan'):?>
        <div class="row">
             <?php 
                include '../config/database.php';
                $hasil=mysqli_query($kon,"select kode_peminjaman from detail_peminjaman");
                $total_peminjaman = mysqli_num_rows($hasil);   
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-dark text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Total Peminjaman</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800"><?php echo $total_peminjaman;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-grip-horizontal fa-2x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <?php 
                $hasil=mysqli_query($kon,"select kode_anggota from anggota");
                $jumlah_anggota = mysqli_num_rows($hasil);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Jumlah Anggota</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800"><?php echo $jumlah_anggota;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <?php 
                $hasil=mysqli_query($kon,"select kode_pustaka from pustaka");
                $jumlah_pustaka = mysqli_num_rows($hasil);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Jumlah Pustaka</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800"><?php echo $jumlah_pustaka;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <?php 
              
                $sql="select SUM(denda) as total_denda from detail_peminjaman";
            
                $hasil=mysqli_query($kon,$sql);
                $data = mysqli_fetch_array($hasil);       
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Total Denda</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800">Rp. <?php echo number_format($data['total_denda'],0,',','.');?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

        <?php  if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'): ?>
        <div class="row">
             <?php
                $kode_anggota=$_SESSION["kode_pengguna"];

                include '../config/database.php';
                $sql="select p.kode_peminjaman from detail_peminjaman d
                inner join peminjaman p on p.kode_peminjaman=d.kode_peminjaman
                where p.kode_anggota='$kode_anggota' and d.status='0'";
                $hasil=mysqli_query($kon,$sql);
                $belum_diambil = mysqli_num_rows($hasil);   
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-dark text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Belum diambil</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800"><?php echo $belum_diambil;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-shopping-bag fa-3x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <?php
                $sql="select p.kode_peminjaman from detail_peminjaman d
                inner join peminjaman p on p.kode_peminjaman=d.kode_peminjaman
                where p.kode_anggota='$kode_anggota' and d.status='1'";
                $hasil=mysqli_query($kon,$sql);
                $sedang_dipinjam = mysqli_num_rows($hasil);   
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Sedang Dipinjam</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800"><?php echo $sedang_dipinjam;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-hourglass-start fa-3x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <?php
                $sql="select p.kode_peminjaman from detail_peminjaman d
                inner join peminjaman p on p.kode_peminjaman=d.kode_peminjaman
                where p.kode_anggota='$kode_anggota' and d.status='2'";
                $hasil=mysqli_query($kon,$sql);
                $telah_selesai = mysqli_num_rows($hasil);   
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Telah Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800"><?php echo $telah_selesai;?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-check-square fa-3x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <?php
             
                $sql="select p.kode_peminjaman from detail_peminjaman d
                inner join peminjaman p on p.kode_peminjaman=d.kode_peminjaman
                where p.kode_anggota='$kode_anggota'";
                $hasil=mysqli_query($kon,$sql);
                $total_peminjaman = mysqli_num_rows($hasil);   
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white text-uppercase mb-1">Total</div>
                        <div class="h5 mb-0 font-weight-bold text-dark-800"><?php echo $total_peminjaman; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-bars fa-3x text-dark-300"></i>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Total Peminjaman Tahun <?php echo date('Y');?>
                    </div>
                    <div class="card-body">
                        <div id="tampil_grafik_transaksi_per_bulan">
                            <!-- Garfik transaksi per bulan di load menggunakan AJAX-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Jumlah Peminjaman Berdasarkan Kategori
                    </div>
                    <div class="card-body">
                        <div id="tampil_grafik_transaksi_per_kategori">
                            <!-- Garfik transaksi per kategori di load menggunakan AJAX-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<script>

    //Load grafik transaksi menggunakan ajax
    $(document).ready(function(){

        $.ajax({
            url: 'dashboard/transaksi_per_bulan.php',
            method: 'POST',
            success:function(data){
                $('#tampil_grafik_transaksi_per_bulan').html(data);
            }
        }); 


        $.ajax({
            url: 'dashboard/transaksi_per_kategori.php',
            method: 'POST',
            success:function(data){
                $('#tampil_grafik_transaksi_per_kategori').html(data);
            }
        }); 

    });

</script>
