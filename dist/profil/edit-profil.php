<?php
session_start();
    if (isset($_POST['simpan_profil'])) {
        //Include file koneksi, untuk koneksikan ke database
        include '../../config/database.php';
        session_start();
        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'){
           
            $id_anggota=$_POST["id_anggota"];
            $nama=input($_POST["nama"]);
            $no_telp=input($_POST["no_telp"]);
            $email=input($_POST["email"]);
            $tempat_lahir=input($_POST["tempat_lahir"]);
            $tanggal_lahir=input($_POST["tanggal_lahir"]);
            $alamat=input($_POST["alamat"]);
            $jenis_kelamin=input($_POST["jenis_kelamin"]);
          
            $foto_saat_ini=$_POST['foto_saat_ini'];
            $foto_baru = $_FILES['foto_baru']['name'];
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
            $x = explode('.', $foto_baru);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['foto_baru']['size'];
            $file_tmp = $_FILES['foto_baru']['tmp_name'];

            if (!empty($foto_baru)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    //Mengupload foto baru
                    move_uploaded_file($file_tmp,'../anggota/foto/'.$foto_baru);

                    //Menghapus foto lama, foto yang dihapus selain foto default
                    if ($foto_saat_ini!='foto_default.png'){
                        unlink("../anggota/foto/".$foto_saat_ini);
                    }
                    
                    $sql="update anggota set
                    nama_anggota='$nama',
                    email='$email',
                    no_telp='$no_telp',
                    tempat_lahir='$tempat_lahir',
                    tanggal_lahir='$tanggal_lahir',
                    alamat='$alamat',
                    jenis_kelamin='$jenis_kelamin',
                    foto='$foto_baru'
                    where id_anggota=$id_anggota";
                }
            }else {
                $sql="update anggota set
                nama_anggota='$nama',
                email='$email',
                no_telp='$no_telp',
                tempat_lahir='$tempat_lahir',
                tanggal_lahir='$tanggal_lahir',
                alamat='$alamat',
                jenis_kelamin='$jenis_kelamin'
                where id_anggota=$id_anggota";
            }

            //Mengeksekusi query 
            $update=mysqli_query($kon,$sql);

            $id_pengguna=$_POST["id_pengguna"];
            $username=input($_POST["username_baru"]);

            $ambil_password=mysqli_query($kon,"select password from pengguna where id_pengguna=$id_pengguna limit 1");
            $data = mysqli_fetch_array($ambil_password);

            if ($data['password']==$_POST["password"]){
                $password=input($_POST["password"]);
            }else {
                $password=md5(input($_POST["password"]));
            }

            //Update username dan password di tabel pengguna
            $sql="update pengguna set
            username='$username',
            password='$password'
            where id_pengguna=$id_pengguna";
            $update_pengguna=mysqli_query($kon,$sql);

        }

        if ($_SESSION["level"]=='Karyawan' or $_SESSION["level"]=='karyawan'){
           
            $id_karyawan=$_POST["id_karyawan"];
            $nama=input($_POST["nama"]);
            $no_telp=input($_POST["no_telp"]);
            $email=input($_POST["email"]);
            $jk=input($_POST["jk"]);
            $alamat=input($_POST["alamat"]);

            $foto_saat_ini=$_POST['foto_saat_ini'];
            $foto_baru = $_FILES['foto_baru']['name'];
            $ekstensi_diperbolehkan	= array('png','jpg','jpeg','gif');
            $x = explode('.', $foto_baru);
            $ekstensi = strtolower(end($x));
            $ukuran	= $_FILES['foto_baru']['size'];
            $file_tmp = $_FILES['foto_baru']['tmp_name'];

            
            if (!empty($foto_baru)){
                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    //Mengupload foto baru
                    move_uploaded_file($file_tmp,'../karyawan/foto/'.$foto_baru);


                    //Menghapus foto lama, foto yang dihapus selain foto default
                    if ($foto_saat_ini!='foto_default.png'){
                        unlink("../karyawan/foto/".$foto_saat_ini);
                    }
                    
                    $sql="update karyawan set
                    nama_karyawan='$nama',
                    email='$email',
                    no_telp='$no_telp',
                    alamat='$alamat',
                    jk='$jk',
                    foto='$foto_baru'
                    where id_karyawan=$id_karyawan";
                }
            }else {
                $sql="update karyawan set
                nama_karyawan='$nama',
                email='$email',
                no_telp='$no_telp',
                alamat='$alamat',
                jk='$jk'
                where id_karyawan=$id_karyawan";
            }

            
            //Mengeksekusi query 
            $update=mysqli_query($kon,$sql);

       
            $id_pengguna=$_POST["id_pengguna"];
            $username=input($_POST["username_baru"]);

            $ambil_password=mysqli_query($kon,"select password from pengguna where id_pengguna=$id_pengguna limit 1");
            $data = mysqli_fetch_array($ambil_password);

            if ($data['password']==$_POST["password"]){
                $password=input($_POST["password"]);
            }else {
                $password=md5(input($_POST["password"]));
            }

            //Update username dan password di tabel pengguna
            $sql="update pengguna set
            username='$username',
            password='$password'
            where id_pengguna=$id_pengguna";
            $update_pengguna=mysqli_query($kon,$sql);

        }

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($update and $update_pengguna) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../dist/index.php?page=profil&edit=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../dist/index.php?page=profil&edit=gagal");
        }
        
    }
?>

