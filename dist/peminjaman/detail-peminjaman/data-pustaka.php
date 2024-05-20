<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>No</th>
        <th>Judul Pustaka</th>
        <th>Kode Peminjaman</th>
        <th>Tanggal Pinjam</th>
    </tr>
    </thead>
    <tbody>
    <?php
      include '../../../config/database.php';
        $kode_anggota=$_POST['kode_anggota'];
        // Menampilkan detail penyewaan
        $sql1="select * from detail_peminjaman inner join peminjaman on peminjaman.kode_peminjaman=detail_peminjaman.kode_peminjaman
        inner join pustaka on pustaka.kode_pustaka=detail_peminjaman.kode_pustaka where peminjaman.kode_anggota='$kode_anggota' and detail_peminjaman.status='1'";
        $result=mysqli_query($kon,$sql1);
        $no=0;
        $status="";
        $jenis_denda="";
        $tanggal_kembali="";
        //Menampilkan data dengan perulangan while
        while ($ambil = mysqli_fetch_array($result)):
        $no++;

    ?>
    <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo $ambil['judul_pustaka']; ?></td>
        <td><?php echo $ambil['kode_peminjaman']; ?></td>
        <td class="text-center"><?php echo tanggal(date("Y-m-d",strtotime($ambil['tanggal_pinjam']))); ?></td>
    </tr>
        <?php endwhile;?>
    </tbody>
</table>


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