<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <!-- Custom styles for this template -->
  <link href="../../../src/templates/css/styles.css" rel="stylesheet">
  <link href="../../../src/plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <title>LAPORAN PUSTAKA</title>
</head>
    <body onload="window.print();">
        <?php
        include '../../../config/database.php';
   
        $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
        $row = mysqli_fetch_array($query);
        ?>
        <div class="container-fluid">
            <div class="card">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-sm-2 float-left">
                    <img src="../../aplikasi/logo/<?php echo $row['logo']; ?>" width="95px" alt="brand"/>
                    </div>
                    <div class="col-sm-10 float-left">
                        <h3><?php echo strtoupper($row['nama_aplikasi']);?></h3>
                        <h6><?php echo $row['alamat'].', Telp '.$row['no_telp'];?></h6>
                        <h6><?php echo $row['website'];?></h6>
                    </div>
                </div>
            </div>
                <div class="card-body">
                    <!--rows -->
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Penulis</th>
                                        <th>Penerbit</th>
                                        <th>Jumlah Stok</th>
                                        <th>Posisi Rak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    // include database
                                    include '../../../config/database.php';
                                    $kondisi="";
                                    $kata_kunci=$_GET['kata_kunci'];
                                    $sql="select *
                                    from pustaka p
                                    inner join penerbit t on t.id_penerbit=p.penerbit
                                    inner join kategori_pustaka k on k.id_kategori_pustaka=p.kategori_pustaka
                                    inner join penulis s on s.id_penulis=p.penulis
                                    where p.kode_pustaka like'%".$kata_kunci."%' or p.judul_pustaka like'%".$kata_kunci."%' or nama_kategori_pustaka like'%".$kata_kunci."%' or nama_penulis like'%".$kata_kunci."%' or nama_penerbit like'%".$kata_kunci."%'
                                    ";
                                    
                                    $hasil=mysqli_query($kon,$sql);
                                    $no=0;
                                    $status='';
                                    $tanggal_kembali="-";
                                    //Menampilkan data dengan perulangan while
                                    while ($data = mysqli_fetch_array($hasil)):
                                    $no++;
                
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $data['kode_pustaka']; ?> </td>
                                    <td><?php echo $data['judul_pustaka']; ?> </td>
                                    <td><?php echo $data['nama_kategori_pustaka']; ?> </td>
                                    <td><?php echo $data['nama_penulis']; ?> </td>
                                    <td><?php echo $data['nama_penerbit']; ?> </td>
                                    <td><?php echo $data['stok']; ?> </td>
                                    <td><?php echo $data['rak']; ?> </td>
                                </tr>
                                <!-- bagian akhir (penutup) while -->
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>