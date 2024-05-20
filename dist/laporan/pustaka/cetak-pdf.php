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

    $pdf->Cell(0,7,'LAPORAN PUSTAKA',0,1,'C');
    $pdf->Cell(10,4,'',0,1);
    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(7,6,'No',1,0,'C');
    $pdf->Cell(13,6,'Kode',1,0,'C');
    $pdf->Cell(75,6,'Judul',1,0,'C');
    $pdf->Cell(25,6,'Kategori',1,0,'C');
    $pdf->Cell(50,6,'Penulis',1,0,'C');
    $pdf->Cell(55,6,'Penerbit',1,0,'C');
    $pdf->Cell(15,6,'Stok',1,0,'C');
    $pdf->Cell(20,6,'Posisi Rak',1,1,'C');

    $pdf->SetFont('Arial','',9);
    $kondisi="";

    $kata_kunci=$_GET['kata_kunci'];
    $sql="select *
    from pustaka p
    inner join penerbit t on t.id_penerbit=p.penerbit
    inner join kategori_pustaka k on k.id_kategori_pustaka=p.kategori_pustaka
    inner join penulis s on s.id_penulis=p.penulis
    where p.kode_pustaka like'%".$kata_kunci."%' or p.judul_pustaka like'%".$kata_kunci."%' or nama_kategori_pustaka like'%".$kata_kunci."%' or nama_penulis like'%".$kata_kunci."%' or nama_penerbit like'%".$kata_kunci."%'
    ";

    $hasil=mysqli_query($kon,$sql);
    $no=1;
    $status='';
    $tanggal_kembali='';
    //Menampilkan data dengan perulangan while
    while ($data = mysqli_fetch_array($hasil)):


        $pdf->Cell(7,6,$no,1,0);
        $pdf->Cell(13,6,$data['kode_pustaka'],1,0);
        $pdf->Cell(75,6,substr($data['judul_pustaka'],0,48),1,0);
        $pdf->Cell(25,6,$data['nama_kategori_pustaka'],1,0);
        $pdf->Cell(50,6,$data['nama_penulis'],1,0);
        $pdf->Cell(55,6,$data['nama_penerbit'],1,0);
        $pdf->Cell(15,6,$data['stok'],1,0);
        $pdf->Cell(20,6,$data['rak'],1,1);
   
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