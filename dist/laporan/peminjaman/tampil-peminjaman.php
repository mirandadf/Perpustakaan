<?php
session_start();
?>
<div class="card mb-4">
    <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode Peminjaman</th>
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

                        if (!empty($_POST["dari_tanggal"]) && empty($_POST["sampai_tanggal"])) $kondisi= "where date(tanggal_pinjam)='".$_POST['dari_tanggal']."' ";
                        if (!empty($_POST["dari_tanggal"]) && !empty($_POST["sampai_tanggal"])) $kondisi= "where date(tanggal_pinjam) between '".$_POST['dari_tanggal']."' and '".$_POST['sampai_tanggal']."'";
                       
                        // perintah sql untuk menampilkan laporan peminjaman jika level admin maka sistem hanya akan menampilkan transaksi yang dilakukan admin tersebut
                        if ($_SESSION["level"]=="Karyawan"){
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
                        $tanggal_kembali="-";
                        //Menampilkan data dengan perulangan while
                        while ($data = mysqli_fetch_array($hasil)):
                        $no++;
                        if ($data['status']==0){
                            $status="<span class='badge badge-dark'>Belum diambil</span>";
                        }else if ($data['status']==1) {
                            $status="<span class='badge badge-primary'>Sedang Dipinjam</span>";
                        }else if ($data['status']==2){
                            $status="<span class='badge badge-success'>Telah Selesai</span>";
                        }
                        else if ($data['status']==3){
                            $status="<span class='badge badge-danger'>Batal</span>";
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
            <a href="laporan/peminjaman/cetak-laporan.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-primary btn-icon-split"><span class="text"><i class="fas fa-print fa-sm"></i> Cetak Invoice</span></a>
            <a href="laporan/peminjaman/cetak-pdf.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-danger btn-icon-pdf"><span class="text"><i class="fas fa-file-pdf fa-sm"></i> Export PDF</span></a>
	        <a href="laporan/peminjaman/cetak-excel.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-success btn-icon-pdf"><span class="text"><i class="fas fa-file-excel fa-sm"></i> Export Excel</span></a>
        </div>
    </div>
</div>