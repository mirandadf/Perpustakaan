
<script>
    $('title').text('Laporan Peminjaman');
</script>
<main>
    <div class="container-fluid">
        <h2 class="mt-4">Laporan Peminjaman</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Daftar Laporan Peminjaman</li>
        </ol>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div id="filter_laporan" class="collapse show">
                    <!-- form -->
                    <form method="post" id="form">
                        <div class="form-row">
                            <div class="col-sm-3">
                            <input type="date" class="form-control" name="dari_tanggal" required>
                            </div>
                            <div class="col-sm-3">
                            <input type="date" class="form-control"  name="sampai_tanggal" required>
                            </div>
                            <div class="col-sm-3">
                            <button  type="button" id="btn-tampil"  class="btn btn-dark"><span class="text"><i class="fas fa-search fa-sm"></i> Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Tampil Laporan -->
        <div id="tampil_laporan">
        
        </div>

        <div id='ajax-wait'>
            <img alt='loading...' src='../src/img/Rolling-1s-84px.png' />
        </div>
        <style>
            #ajax-wait {
                display: none;
                position: fixed;
                z-index: 1999
            }
        </style>
    </div>
</main>
<script>
    //Menampilkan laporan peminjaman dengan menggunakan ajax
    $('#btn-tampil').on('click',function(){
        $( document ).ajaxStart(function() {
        $( "#ajax-wait" ).css({
            left: ( $( window ).width() - 32 ) / 2 + "px", // 32 = lebar gambar
            top: ( $( window ).height() - 32 ) / 2 + "px", // 32 = tinggi gambar
            display: "block"
        })
        })
        .ajaxComplete( function() {
            $( "#ajax-wait" ).fadeOut();
        });

        var data = $('#form').serialize();
            $.ajax({
                type	: 'POST',
                url: 'laporan/peminjaman/tampil-peminjaman.php',
                data: data,
                cache	: false,
                success	: function(data){
                    $("#tampil_laporan").html(data);

                }
            });
    });

</script>