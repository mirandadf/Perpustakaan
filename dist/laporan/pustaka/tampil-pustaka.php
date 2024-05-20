<?php
session_start();
?>
<div class="card mb-4">
    <div class="card-body">
            <div class="table-responsive">
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
                        $kata_kunci=$_POST['kata_kunci'];
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
            <a href="laporan/pustaka/cetak-laporan.php?kata_kunci=<?php if (!empty($_POST["kata_kunci"])) echo $_POST["kata_kunci"]; ?>" target='blank' class="btn btn-primary btn-icon-split"><span class="text"><i class="fas fa-print fa-sm"></i> Cetak Invoice</span></a>
            <a href="laporan/pustaka/cetak-pdf.php?kata_kunci=<?php if (!empty($_POST["kata_kunci"])) echo $_POST["kata_kunci"]; ?>" target='blank' class="btn btn-danger btn-icon-pdf"><span class="text"><i class="fas fa-file-pdf fa-sm"></i> Export PDF</span></a>
	        <a href="laporan/pustaka/cetak-excel.php?kata_kunci=<?php if (!empty($_POST["kata_kunci"])) echo $_POST["kata_kunci"]; ?>" target='blank' class="btn btn-success btn-icon-pdf"><span class="text"><i class="fas fa-file-excel fa-sm"></i> Export Excel</span></a>
        </div>
    </div>
</div>