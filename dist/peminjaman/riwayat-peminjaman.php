<div class="table-responsive">
    <table class="table table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead class="text-center">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Kode</th>
                <th rowspan="2">Pustaka</th>
                <th colspan="2">Waktu Peminjaman</th>
                <th rowspan="2">Status</th>
            </tr>
            <tr>
                <th>Mulai</th>
                <th>Selesai</th>
    
            </tr>
        </thead>

        <tbody>
        <?php
            // include database
            include '../../config/database.php';
            
            $kode_anggota=$_POST['kode_anggota'];
            $sql="select p.kode_peminjaman,an.nama_anggota,pk.judul_pustaka,dp.tanggal_pinjam,dp.tanggal_kembali,dp.status
            from peminjaman p
            inner join anggota an  on an.kode_anggota=p.kode_anggota
            inner join detail_peminjaman dp on dp.kode_peminjaman=p.kode_peminjaman
            inner join pustaka pk on pk.kode_pustaka=dp.kode_pustaka
            where an.kode_anggota='$kode_anggota'";
            
            $hasil=mysqli_query($kon,$sql);
            $jumlah = mysqli_num_rows($hasil);

            if ($jumlah==0){
                echo"<div class='alert alert-info'>Anggota ini tidak memiliki riwayat peminjaman sebelumnya.</div>";
            }

           
            $no=0;
            $status="";

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
            <td><?php echo $data['kode_peminjaman']; ?></td>
            <td><?php echo $data['judul_pustaka']; ?></td>
            <td><?php echo $tanggal_pinjam; ?></td>
            <td><?php echo $tanggal_kembali; ?></td>
            <td><?php echo $status; ?></td>
        </tr>
        <!-- bagian akhir (penutup) while -->
        <?php endwhile; ?>

        </tbody>
    </table>
</div>