<?php
session_start();
?>
<canvas id="transaksi_per_bulan" width="100%" height="60"></canvas>
<?php
    include '../../config/database.php';
    $tahun=date('Y');

    $label = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

    for($bulan = 1;$bulan <= 12;$bulan++)
    {
      //Jika level user adalah Admin maka transaksi yang ditampilkan hanya transaksi yang dilakukan admin tersebut
      if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'){

        $kode_anggota=$_SESSION["kode_pengguna"];
        $sql="select MONTH(tanggal_pinjam) as bulan,count(*) as total from detail_peminjaman d
        inner join peminjaman p on p.kode_peminjaman=d.kode_peminjaman
        where MONTH(tanggal_pinjam)='$bulan' and YEAR(tanggal_pinjam)='$tahun' and p.kode_anggota='$kode_anggota'
        group by bulan";
      }else {
        $sql="select MONTH(tanggal_pinjam) as bulan,count(*) as total from detail_peminjaman d
        inner join peminjaman p on p.kode_peminjaman=d.kode_peminjaman
        where MONTH(tanggal_pinjam)='$bulan' and YEAR(tanggal_pinjam)='$tahun' 
        group by bulan";
      }
    
        $hasil=mysqli_query($kon,$sql);
        $data=mysqli_fetch_array($hasil);
        
        if (isset($data['total'])!=0){
            $total[] = $data['total'];
        }else {
            $total[] = 0;
        }
    }
?>

<script>
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart
var ctx = document.getElementById("transaksi_per_bulan");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($label); ?>,
    datasets: [{
      label: "Jumlah Pustaka",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data:  <?php echo json_encode($total); ?>,
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 16
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          maxTicksLimit: 12
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});
</script>

