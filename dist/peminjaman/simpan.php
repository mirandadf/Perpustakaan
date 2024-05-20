<?php
    //Memulai session dan koneksi database
    session_start();
    include '../../config/database.php';



        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");

        $query = mysqli_query($kon, "SELECT max(id_peminjaman) as id_peminjaman_terbesar FROM peminjaman");
        $data = mysqli_fetch_array($query);
        $id_peminjaman = $data['id_peminjaman_terbesar'];
        $id_peminjaman++;
        $kode_peminjaman = sprintf("%05s", $id_peminjaman);
        
        $kode_anggota=$_GET['kode_anggota'];
        $tanggal_pinjam=date('Y-m-d');
        $status="1";
    
        $simpan_tabel_peminjaman=mysqli_query($kon,"insert into peminjaman (kode_peminjaman,kode_anggota,tanggal) values ('$kode_peminjaman','$kode_anggota','$tanggal_pinjam')");
    


        //Simpan detail transaksi
        if(!empty($_SESSION["cart_pustaka"])):
            foreach ($_SESSION["cart_pustaka"] as $item):
                $kode_pustaka=$item['kode_pustaka'];
                $simpan_detail_peminjaman=mysqli_query($kon,"insert into detail_peminjaman (kode_peminjaman,kode_pustaka,tanggal_pinjam,status) values ('$kode_peminjaman','$kode_pustaka','$tanggal_pinjam','$status')");
            
                $ambil_pustaka= mysqli_query($kon, "SELECT stok FROM pustaka where kode_pustaka='$kode_pustaka'");
                $data = mysqli_fetch_array($ambil_pustaka); 
                $stok=$data['stok']-1;
    
                //Update stok pustaka
                $update_stok=mysqli_query($kon,"update pustaka set stok=$stok where kode_pustaka='$kode_pustaka'");
            
            endforeach;
        endif;

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi beberapa query diatas
        if ($simpan_tabel_peminjaman and $simpan_detail_peminjaman and $update_stok) {
            //and $simpan_detail_peminjaman and $update_stok  and $simpan_aktivitas
            //Jika semua query berhasil, lakukan commit
            mysqli_query($kon,"COMMIT");

            //Kosongkan kerangjang belanja
            unset($_SESSION["cart_pustaka"]);
            header("Location:../index.php?page=daftar-peminjaman&add=berhasil");
        }
        else {
            //Jika ada query yang gagal, lakukan rollback
            mysqli_query($kon,"ROLLBACK");

            //Kosongkan kerangjang belanja
            unset($_SESSION["cart_pustaka"]);
            header("Location:../index.php?page=daftar-peminjaman&add=gagal");
        }
    

?>
