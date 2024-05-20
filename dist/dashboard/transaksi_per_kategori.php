<?php
session_start();
?>
<canvas id="peminjaman_berdasarkan_kategori" width="100%" height="60"></canvas>
<?php
    $tahun=date('Y');

    include '../../config/database.php';

    if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'){

        $kode_anggota=$_SESSION["kode_pengguna"];
        $sql="select k.nama_kategori_pustaka,count(*) as total
        from detail_peminjaman d 
        inner join peminjaman pj on d.kode_peminjaman=pj.kode_peminjaman
        inner join pustaka p on p.kode_pustaka=d.kode_pustaka
        inner join kategori_pustaka k on k.id_kategori_pustaka=p.kategori_pustaka
        where YEAR(tanggal_pinjam)='$tahun' and pj.kode_anggota='$kode_anggota'
        group by k.nama_kategori_pustaka";
      }else {
        $sql="select k.nama_kategori_pustaka,count(*) as total
        from detail_peminjaman d 
        inner join pustaka p on p.kode_pustaka=d.kode_pustaka
        inner join kategori_pustaka k on k.id_kategori_pustaka=p.kategori_pustaka
        where YEAR(tanggal_pinjam)='$tahun'
        group by k.nama_kategori_pustaka";
      }

    $hasil=mysqli_query($kon,$sql);
    while ($data = mysqli_fetch_array($hasil)) {
        $nama_kategori_pustaka[]=$data['nama_kategori_pustaka'];
        $total[] = $data['total'];

    }
?>


<script>
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Pie Chart
    var ctx = document.getElementById("peminjaman_berdasarkan_kategori");
    var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels:  <?php echo json_encode($nama_kategori_pustaka); ?>,
      datasets: [{
        data:  <?php echo json_encode($total); ?>,
        backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745','#53ff1a','#ff9900','#7300e6','#75a3a3','#99994d','#ac3939','#66b3ff','#ac7339','#ff00ff'],
      }],
    },
    });
</script>
