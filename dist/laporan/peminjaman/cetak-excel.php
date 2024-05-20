<?php
session_start();
    //Koneksi database
    include '../../../config/database.php';
    //Mengambil nama aplikasi
    $query = mysqli_query($kon, "select nama_aplikasi from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);
    //Mengambil tanggal
    $tanggal='';
    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $tanggal=date("d/m/Y",strtotime($_GET["dari_tanggal"]));
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $tanggal= "".date("d/m/Y",strtotime($_GET["dari_tanggal"]))." - ".date("d/m/Y",strtotime($_GET["sampai_tanggal"]))."";
    
    //Membuat file format excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=LAPORAN PEMINJAMAN ".strtoupper($row['nama_aplikasi'])." ".$tanggal.".xls");
?>  
<h2><center>LAPORAN PEMINJAMAN <?php echo strtoupper($row['nama_aplikasi']);?></center></h2>
<h4>Tanggal : <?php echo $tanggal; ?></h4>
<table border="1">
    <thead class="text-center">
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Kode</th>
            <th rowspan="2">Nama Anggota</th>
            <th rowspan="2">Judul Pustaka</th>
            <th colspan="2">Waktu Peminjaman</th>
            <th rowspan="2">Status</th>
        </tr>
        <tr>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
        </tr>
    </thead>

    <tbody>
    <?php
        // include database
        include '../../../config/database.php';
        $kondisi="";

        if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal_pinjam)='".$_GET['dari_tanggal']."' ";
        if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal_pinjam) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
        
        // perintah sql untuk menampilkan laporan penyewaan jika level admin maka sistem hanya akan menampilkan transaksi yang dilakukan admin tersebut
        if ($_SESSION["level"]=="Admin"){
            $id_pengguna=$_SESSION["id_pengguna"];
            $sql="select p.kode_peminjaman,an.nama_anggota,pk.judul_pustaka,dp.tanggal_pinjam,dp.tanggal_kembali,dp.status
            from peminjaman p
            inner join anggota an  on an.kode_anggota=p.kode_anggota
            inner join detail_peminjaman dp on dp.kode_peminjaman=p.kode_peminjaman
            inner join pustaka pk on pk.kode_pustaka=dp.kode_pustaka
            $kondisi and status!='0'
            order by dp.tanggal_pinjam asc";
        }else {
            $sql="select p.kode_peminjaman,an.nama_anggota,pk.judul_pustaka,dp.tanggal_pinjam,dp.tanggal_kembali,dp.status
            from peminjaman p
            inner join anggota an  on an.kode_anggota=p.kode_anggota
            inner join detail_peminjaman dp on dp.kode_peminjaman=p.kode_peminjaman
            inner join pustaka pk on pk.kode_pustaka=dp.kode_pustaka
            $kondisi and status!='0'
            order by dp.tanggal_pinjam asc";
        }
        
        $hasil=mysqli_query($kon,$sql);
        $no=0;
        $status='';
        $tanggal_kembali='';
        //Menampilkan data dengan perulangan while
        while ($data = mysqli_fetch_array($hasil)):
        $no++;
        if ($data['status']==0){
            $status="Belum diambil";
        }else if ($data['status']==1) {
            $status="Sedang Dipinjam";
        }else if ($data['status']==2){
            $status="Telah Selesai";
        }
        else if ($data['status']==3){
            $status="Batal";
        }


        if ($data['tanggal_pinjam']=='0000-00-00'){
            $tanggal_pinjam="";
        }else {
            $tanggal_pinjam=date("d/m/Y",strtotime($data['tanggal_pinjam']));
        }
        if ($data['tanggal_kembali']=='0000-00-00'){
            $tanggal_kembali="";
        }else {
            $tanggal_kembali=date("d/m/Y",strtotime($data['tanggal_kembali']));
        }
    ?>
    <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo $data['kode_peminjaman']; ?> </td>
        <td><?php echo $data['nama_anggota']; ?> </td>
        <td><?php echo $data['judul_pustaka']; ?> </td>
        <td class="text-center"><?php echo $tanggal_pinjam; ?></td>
        <td class="text-center"><?php echo $tanggal_kembali; ?></td>
        <td><?php echo $status; ?></td>
        
    </tr>
    <!-- bagian akhir (penutup) while -->
    <?php endwhile; ?>
    </tbody>
</table>