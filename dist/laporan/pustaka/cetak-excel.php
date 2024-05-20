<?php
session_start();
    //Koneksi database
    include '../../../config/database.php';
    //Mengambil nama aplikasi
    $query = mysqli_query($kon, "select nama_aplikasi from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);

    //Membuat file format excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=LAPORAN PUSTAKA ".strtoupper($row['nama_aplikasi']).".xls");
?>  
<h2><center>LAPORAN PUSTAKA <?php echo strtoupper($row['nama_aplikasi']);?></center></h2>
<table border="1">
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