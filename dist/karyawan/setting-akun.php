<?php
session_start();
    if (isset($_POST['submit'])) {
        //Include file koneksi, untuk koneksikan ke database
        include '../../config/database.php';

        //Memulai transaksi
        mysqli_query($kon,"START TRANSACTION");
        
        //Fungsi untuk mencegah inputan karakter yang tidak sesuai
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
      
        $kode_pengguna=input($_POST["kode_pengguna"]);
        $username=input($_POST["username"]);
        $level="Karyawan";

        //Mengambil password
        $ambil_password=mysqli_query($kon,"select password from pengguna where kode_pengguna='$kode_pengguna' limit 1");
        $data = mysqli_fetch_array($ambil_password);
        if ($data['password']==$_POST["password"]){
            $password=input($_POST["password"]);
        }else {
            $password=md5(input($_POST["password"]));
        }
        
        $sql="update pengguna set
       username='$username',
       password='$password',
       level='$level'
       where kode_pengguna='$kode_pengguna'";

        //Menyimpan ke tabel pengguna
        $setting_pengguna=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($setting_pengguna) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../dist/index.php?page=karyawan&setting-akun=berhasil");
        }
        else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../dist/index.php?page=karyawan&setting-akun=gagal");
        }
        
    }

    //-------------------------------------------------------------------------------------------

    $kode_karyawan=$_POST["kode_karyawan"];
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM pengguna where kode_pengguna='$kode_karyawan'");
    $data = mysqli_fetch_array($query); 
    $username=$data['username'];
    $password=$data['password'];

    
    if ($username==null){
        echo"<div class='alert alert-warning'>Username dan password belum di set up.</div>";
    }

?>
<form action="karyawan/setting-akun.php" method="post">

<input name="kode_pengguna" value="<?php echo $kode_karyawan; ?>" type="hidden" class="form-control">

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Username:</label>
                <input name="username" value="<?php echo $username; ?>"  id="username" type="text" class="form-control" placeholder="Masukan username" required>
                <div id="info_username"> </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Password:</label>
                <input name="password" value="<?php echo $password; ?>" type="password" class="form-control" placeholder="Masukan password" required>
            </div>
        </div>
    </div>
    <br>
    <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
</form>


<script>
    //Cek ketersediaan username
    $("#username").bind('keyup', function () {

        var username = $('#username').val();

        $.ajax({
            url: 'karyawan/cek-username.php',
            method: 'POST',
            data:{username:username},
            success:function(data){
                $('#info_username').show();
                $('#info_username').html(data);
            }
        }); 

    });
</script>
