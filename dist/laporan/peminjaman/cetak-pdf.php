<?php
session_start();
    require('../../../src/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('L', 'mm','Letter');

    //Membuat Koneksi ke database akademik
    include '../../../config/database.php';


    $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);

    $pimpinan=$row['nama_pimpinan'];

    $pdf->AddPage();
    $pdf->Image('../../aplikasi/logo/'.$row['logo'],15,5,30,30);
    $pdf->SetFont('Arial','B',21);
    $pdf->Cell(0,7,strtoupper($row['nama_aplikasi']),0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,7,$row['alamat'].', Telp '.$row['no_telp'],0,1,'C');
    $pdf->Cell(0,7,$row['website'],0,1,'C');
    $pdf->Cell(10,7,'',0,1);

    //Membuat line (garis)
    $pdf->SetLineWidth(1);
    $pdf->Line(10,31,270,31);
    $pdf->SetLineWidth(0);
    $pdf->Line(10,32,270,32);

    $pdf->SetFont('Arial','B',14);

    $pdf->Cell(0,7,'LAPORAN PEMINJAMAN',0,1,'C');

    $tanggal='';
    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])){
        $tanggal=date("d/m/Y",strtotime($_GET["dari_tanggal"]));
    }
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])){
        $tanggal=date("d/m/Y",strtotime($_GET["dari_tanggal"]))." - ".date("d/m/Y",strtotime($_GET["sampai_tanggal"]));
    }

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(17,6,'Tanggal :  ',0,0);
    $pdf->Cell(30,6,$tanggal,0,1);

    $pdf->Cell(10,2,'',0,1);
 
    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(10,6,'No',1,0,'C');
    $pdf->Cell(15,6,'Kode',1,0,'C');
    $pdf->Cell(50,6,'Anggota',1,0,'C');
    $pdf->Cell(100,6,'Pustaka',1,0,'C');
    $pdf->Cell(25,6,'Tgl Pinjam',1,0,'C');
    $pdf->Cell(25,6,'Tgl Kembali',1,0,'C');
    $pdf->Cell(35,6,'Status',1,1,'C');

    $pdf->SetFont('Arial','',10);
    $kondisi="";

    if (!empty($_GET["dari_tanggal"]) && empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal_pinjam)='".$_GET['dari_tanggal']."' ";
    if (!empty($_GET["dari_tanggal"]) && !empty($_GET["sampai_tanggal"])) $kondisi= "where date(tanggal_pinjam) between '".$_GET['dari_tanggal']."' and '".$_GET['sampai_tanggal']."'";
    
    // perintah sql untuk menampilkan laporan peminjaman jika level admin maka sistem hanya akan menampilkan transaksi yang dilakukan admin tersebut
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
    $no=1;
    $status='';
    $tanggal_kembali='';
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):

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

        $pdf->Cell(10,6,$no,1,0);
        $pdf->Cell(15,6,$data['kode_peminjaman'],1,0);
        $pdf->Cell(50,6,substr($data['nama_anggota'],0,17),1,0);
        $pdf->Cell(100,6,substr($data['judul_pustaka'],0,89),1,0);
        $pdf->Cell(25,6,$tanggal_pinjam,1,0);
        $pdf->Cell(25,6,$tanggal_kembali,1,0);
        $pdf->Cell(35,6, $status,1,1);
        $no++;
    endwhile;

    //Membuat format peulisan tanggal
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

    //Menampilkan keterangan tambahan
    $tanggal=date('Y-m-d');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(460,15,'',0,1,'C');
    $pdf->Cell(460,12,tanggal($tanggal),0,1,'C');
    $pdf->Cell(460,0,'Mengetahui Ketua',0,1,'C');
    $pdf->Cell(460,7,'',0,1,'C');
    $pdf->Cell(460,50,$pimpinan,0,1,'C');
    
    $pdf->Output();
?>