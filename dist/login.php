<?php
    $pesan="";

        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    session_start();

    include "../config/database.php";

    $username = input($_POST["username"]);
    $password = input(md5($_POST["password"]));

     //Query untuk cek pada tabel pengguna yang dijoinkan dengan tabel karyawan
     $tabel_karyawan= "select * from pengguna p
     inner join karyawan k on k.kode_karyawan=p.kode_pengguna
     where username='".$username."' and password='".$password."' limit 1";

     $cek_tabel_karyawan = mysqli_query ($kon,$tabel_karyawan);
     $karyawan = mysqli_num_rows($cek_tabel_karyawan);

    //Query untuk cek pada tabel pengguna yang dijoinkan dengan tabel anggota
    $tabel_anggota= "select * from pengguna p
    inner join anggota m on m.kode_anggota=p.kode_pengguna
    where username='".$username."' and password='".$password."' limit 1";

    $cek_tabel_anggota = mysqli_query ($kon,$tabel_anggota);
    $anggota = mysqli_num_rows($cek_tabel_anggota);

    if ($karyawan>0){

        $row = mysqli_fetch_assoc($cek_tabel_karyawan);

        if ($row["status"]==1){
            
            //menyimpan data karyawan dalam session
            $_SESSION["id_pengguna"]=$row["id_pengguna"];
            $_SESSION["kode_pengguna"]=$row["kode_pengguna"];
            $_SESSION["nama_karyawan"]=$row["nama_karyawan"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["level"]=$row["level"];
            $_SESSION["foto"]=$row["foto"];
            $_SESSION["nip"]=$row["nip"];

            header("Location:index.php?page=dashboard");

        }else {
            $pesan="<div class='alert alert-warning'><strong>Gagal!</strong> Status pengguna tidak aktif.</div>";
        }

    } else if ($anggota>0){

        $row = mysqli_fetch_assoc($cek_tabel_anggota);

        if ($row["status"]==1){
            
            //menyimpan data Anggota dalam session
            $_SESSION["id_pengguna"]=$row["id_pengguna"];
            $_SESSION["kode_pengguna"]=$row["kode_pengguna"];
            $_SESSION["nama_anggota"]=$row["nama_anggota"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["level"]=$row["level"];
            $_SESSION["foto"]=$row["foto"];
     
            header("Location:index.php?page=dashboard");

        }else {
            $pesan="<div class='alert alert-warning'><strong>Gagal!</strong> Status pengguna tidak aktif.</div>";
        }

    }else {
            $pesan="<div class='alert alert-danger'><strong>Error!</strong> Username dan password salah.</div>";
        }
    }
?>
<!DOCTYPE html>
<?php 
    include '../config/database.php';
    $hasil=mysqli_query($kon,"select * from profil_aplikasi order by nama_aplikasi desc limit 1");
    $data = mysqli_fetch_array($hasil); 
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $data['nama_aplikasi'];?></title>
        <link href="../src/templates/css/styles.css" rel="stylesheet" />
        <script src="../src/js/font-awesome/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-dark">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">

                                     <div class="card-body">
                                        <center><img src="aplikasi/logo/<?php echo $data['logo'];?>" id="preview" width="35%"></center>
                                        <h3 class="text-center font-weight-bold my-4"><?php echo ucwords($data['nama_aplikasi']);?></h3>
                                    <?php 	if ($_SERVER["REQUEST_METHOD"] == "POST") echo $pesan; ?>
                                    <?php 
                                            if (isset($_GET['daftar'])) {
                                                if ($_GET['daftar']=='berhasil'){
                                                    echo"<div class='alert alert-success'><strong>Berhasil!</strong> Pendaftaran akun berhasil.</div>";
                                                }   
                                            }
                                        ?>
                                    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                                            <div class="form-group">
                                                <label class="small mb-1" >Username</label>
                                                <input class="form-control py-4"  name="username" type="text" placeholder="Masukan Username" />
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" >Password</label>
                                                <input class="form-control py-4" name="password" type="password" placeholder="Masukan Password" />
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button class="btn btn-warning" type="submit">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="daftar.php">Belum mempunyai akun? Daftar sekarang!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; <?php echo $data['nama_aplikasi'];?> <?php echo date('Y');?></div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="../src/js/jquery/jquery-3.5.1.min.js"></script>
        <script src="../src/plugin/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../src/js/scripts.js"></script>
    </body>
</html>
