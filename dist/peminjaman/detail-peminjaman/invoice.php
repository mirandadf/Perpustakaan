<?php
    require('../../../src/plugin/fpdf/fpdf.php');
    $pdf = new FPDF('P', 'mm','Letter');

    //Membuat Koneksi ke database
    include '../../../config/database.php';


    $query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");    
    $row = mysqli_fetch_array($query);

    $pimpinan=$row['nama_pimpinan'];

    $pdf->AddPage();
    $pdf->Image('../../aplikasi/logo/'.$row['logo'],15,5,25,25);
    $pdf->SetFont('Arial','B',21);
    $pdf->Cell(0,7,strtoupper($row['nama_aplikasi']),0,1,'C');
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,7,$row['alamat'].', Telp '.$row['no_telp'],0,1,'C');
    $pdf->Cell(0,7,$row['website'],0,1,'C');
    $pdf->Cell(10,5,'',0,1);

    //Membuat line (garis)
    $pdf->SetLineWidth(1);
    $pdf->Line(10,31,206,31);
    $pdf->SetLineWidth(0);
    $pdf->Line(10,32,206,32);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,7,'BUKTI PEMINJAMAN',0,1,'C');
    $pdf->SetFont('Arial','B',10);
    if (isset($_GET['kode_peminjaman'])){
        $kode=$_GET['kode_peminjaman'];
    }else {
        $kode="-";
    }
    $pdf->Cell(0,4,'Kode : '.$kode,0,1,'C');

    
    if (isset($_GET['kode_peminjaman']) and $_GET['kode_peminjaman']!=''){
        $kode_peminjaman=$_GET['kode_peminjaman'];
        $sql="select *
        from peminjaman p
        inner join anggota an on an.kode_anggota=p.kode_anggota
        inner join detail_peminjaman dp on dp.kode_peminjaman=p.kode_peminjaman
        inner join pustaka pk on pk.kode_pustaka=dp.kode_pustaka
        where p.kode_peminjaman='$kode_peminjaman'";

    }else if (isset($_GET['kode_anggota'])){
        $kode_anggota=$_GET['kode_anggota'];
        $sql="select *
        from peminjaman p
        inner join anggota an on an.kode_anggota=p.kode_anggota
        inner join detail_peminjaman dp on dp.kode_peminjaman=p.kode_peminjaman
        inner join pustaka pk on pk.kode_pustaka=dp.kode_pustaka
        where p.kode_anggota='$kode_anggota'";
    }


    $hasil=mysqli_query($kon,$sql);
    $data = mysqli_fetch_array($hasil); 

    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,6,'Nama ',0,0);
    $pdf->Cell(31,6,': '.$data['nama_anggota'],0,1);
    $pdf->Cell(30,6,'No Telp ',0,0);
    $pdf->Cell(31,6,': '.$data['no_telp'],0,1);
    $pdf->Cell(30,6,'Email ',0,0);
    $pdf->Cell(31,6,': '.$data['email'],0,1);
    $pdf->Cell(30,6,'Alamat ',0,0);
    $pdf->Cell(31,6,': '.$data['alamat'],0,1);
  
    //Membuat header tabel
    $pdf->Cell(10,3,'',0,1);
    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(8,6,'No',1,0,'C');
    $pdf->Cell(72,6,'Judul Pustaka',1,0,'C');
    $pdf->Cell(20,6,'Pinjam',1,0,'C');
    $pdf->Cell(20,6,'Kembali',1,0,'C');
    $pdf->Cell(32,6,'Status',1,0,'C');
    $pdf->Cell(22,6,'Denda',1,0,'C');
    $pdf->Cell(23,6,'Besaran',1,1,'C');

  
    $pdf->SetFont('Arial','',10);
    $no=0;
        
    if (isset($_GET['kode_peminjaman']) and $_GET['kode_peminjaman']!=''){
        $kode_peminjaman=$_GET['kode_peminjaman'];
        $sql="select * from detail_peminjaman inner join peminjaman on peminjaman.kode_peminjaman=detail_peminjaman.kode_peminjaman
        inner join pustaka on pustaka.kode_pustaka=detail_peminjaman.kode_pustaka where peminjaman.kode_peminjaman='$kode_peminjaman'";
    }else if (isset($_GET['kode_anggota'])){
        $kode_anggota=$_GET['kode_anggota'];
        $sql="select * from detail_peminjaman inner join peminjaman on peminjaman.kode_peminjaman=detail_peminjaman.kode_peminjaman
        inner join pustaka on pustaka.kode_pustaka=detail_peminjaman.kode_pustaka where kode_anggota='$kode_anggota'";
    }

    //Mengambil data dari database
    $hasil = mysqli_query($kon,$sql);
    $jumlah_matkul = mysqli_num_rows($hasil);
    while ($data = mysqli_fetch_array($hasil)){
        $no++;

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
        
        if ($data['jenis_denda']==0){
            $jenis_denda="Tidak ada";
        }else if ($data['jenis_denda']==1) {
            $jenis_denda="Terlambat";
        }else {
            $jenis_denda="Hilang/rusak";
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

        //Menampilkan data
        $pdf->Cell(8,6,$no,1,0);
        $pdf->Cell(72,6,$data['judul_pustaka'],1,0);
        $pdf->Cell(20,6,$tanggal_pinjam,1,0);
        $pdf->Cell(20,6,$tanggal_kembali,1,0,'C');
        $pdf->Cell(32,6, $status,1,0,'C');
        $pdf->Cell(22,6,$jenis_denda,1,0,'C');
        $pdf->Cell(23,6,'Rp.'.number_format($data['denda'],0,',','.'),1,1,'C');
        
      
    }



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
    $pdf->Cell(340,15,'',0,1,'C');
    $pdf->Cell(340,12,tanggal($tanggal),0,1,'C');
    $pdf->Cell(340,0,'Mengetahui Pimpinan',0,1,'C');
    $pdf->Cell(340,7,'',0,1,'C');
    $pdf->Cell(340,50,$pimpinan,0,1,'C');

    $pdf->Output();



?>