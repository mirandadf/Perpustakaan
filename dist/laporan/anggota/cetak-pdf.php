<?php
session_start();
    require('../../../src/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('L', 'mm','Letter');

    //Membuat Koneksi ke database akademik
    include '../../../config/database.php';


    $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);
    $pdf->SetTitle("DATA PUSTAKA ".strtoupper($row['nama_aplikasi']));
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

    $pdf->Cell(0,7,'LAPORAN ANGGOTA',0,1,'C');
    $pdf->Cell(10,4,'',0,1);
    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(7,6,'No',1,0,'C');
    $pdf->Cell(13,6,'Kode',1,0,'C');
    $pdf->Cell(75,6,'Nama',1,0,'C');
    $pdf->Cell(40,6,'Email',1,0,'C');
    $pdf->Cell(30,6,'No Telp',1,0,'C');
    $pdf->Cell(75,6,'Alamat',1,0,'C');
    $pdf->Cell(20,6,'Status',1,1,'C');

    $pdf->SetFont('Arial','',9);
    $kondisi="";

    $status="";
                              
    if ($_GET['kata_kunci']=='aktif' or $_GET['kata_kunci']=='AKTIF'){
        $status='1';
    }else {
        $status='0';
    }
    $kata_kunci=$_GET['kata_kunci'];
    $sql="select *
    from anggota a
    inner join pengguna p on p.kode_pengguna=a.kode_anggota
    where kode_anggota like'%".$kata_kunci."%' or nama_anggota like'%".$kata_kunci."%' or email like'%".$kata_kunci."%' or status='".$status."'
    ";

    $hasil=mysqli_query($kon,$sql);
    $no=1;
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):


        $pdf->Cell(7,6,$no,1,0);
        $pdf->Cell(13,6,$data['kode_anggota'],1,0);
        $pdf->Cell(75,6,substr($data['nama_anggota'],0,48),1,0);
        $pdf->Cell(40,6,$data['email'],1,0);
        $pdf->Cell(30,6,$data['no_telp'],1,0);
        $pdf->Cell(75,6,$data['alamat'],1,0);
        $pdf->Cell(20,6,$data['status'],1,1);
   
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