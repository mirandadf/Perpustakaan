<?php
session_start();
if (isset($_POST['ubah_profil_aplikasi'])) {
    //Include file koneksi, untuk koneksikan ke database
    include '../../config/database.php';
    
    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        mysqli_query($kon,"START TRANSACTION");
        $id=$_POST["id"];
        $nama_aplikasi=input($_POST["nama_aplikasi"]);
        $nama_pimpinan=input($_POST["nama_pimpinan"]);
        $no_telp=input($_POST["no_telp"]);
        $alamat=input($_POST["alamat"]);
        $website=input($_POST["website"]);
        $logo_sebelumnya=input($_POST["logo_sebelumnya"]);
        $logo = $_FILES['logo']['name'];
        $ekstensi_diperbolehkan	= array('png','jpg','jpeg');
        $x = explode('.', $logo);
        $ekstensi = strtolower(end($x));
        $ukuran	= $_FILES['logo']['size'];
        $file_tmp = $_FILES['logo']['tmp_name'];	

        if (!empty($logo)){
            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){

                //Mengupload logo yang baru
                move_uploaded_file($file_tmp, 'logo/'.$logo);

                //Menghapus logo sebelumya
                unlink("logo/".$logo_sebelumnya);
                

                $sql="update profil_aplikasi set
                nama_aplikasi='$nama_aplikasi',
                nama_pimpinan='$nama_pimpinan',
                no_telp='$no_telp',
                alamat='$alamat',
                website='$website',
                logo='$logo'
                where id=$id";

            }
        }else {
            $sql="update profil_aplikasi set
            nama_aplikasi='$nama_aplikasi',
            nama_pimpinan='$nama_pimpinan',
            no_telp='$no_telp',
            alamat='$alamat',
            website='$website'
            where id=$id";

        }

        //Mengeksekusi/menjalankan query 
        $update_profil_aplikasi=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($update_profil_aplikasi) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../dist/index.php?page=aplikasi&edit-aplikasi=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../dist/index.php?page=aplikasi&edit-aplikasi=gagal");
        }

    }

}
?>


<?php

if (isset($_POST['ubah_aturan_perpustakaan'])) {
    //Include file koneksi, untuk koneksikan ke database
    include '../../config/database.php';
    
    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST"){



        mysqli_query($kon,"START TRANSACTION");
        $id=$_POST["id"];
        $waktu_peminjaman=input($_POST["waktu_peminjaman"]);
        $maksimal_peminjaman=input($_POST["maksimal_peminjaman"]);
        $denda_keterlambatan=input($_POST["denda_keterlambatan"]);
        $denda_kehilangan_rusak=input($_POST["denda_kehilangan_rusak"]);

        $hasil=mysqli_query($kon,"select * from aturan_perpustakaan limit 1");
        $jumlah_row=mysqli_num_rows($hasil);

        if ($jumlah_row<=0){
            $sql="insert into aturan_perpustakaan (waktu_peminjaman,maksimal_peminjaman,denda_keterlambatan) values
            ('$waktu_peminjaman','$maksimal_peminjaman','$denda_keterlambatan')";
        }else {
            $sql="update aturan_perpustakaan set
            waktu_peminjaman='$waktu_peminjaman',
            maksimal_peminjaman='$maksimal_peminjaman',
            denda_keterlambatan='$denda_keterlambatan'";
        }

    

        //Mengeksekusi/menjalankan query 
        $update_aturan=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($update_aturan) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../dist/index.php?page=aplikasi&edit-aturan=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../dist/index.php?page=aplikasi&edit-aturan=gagal");
        }

    }

}
?>