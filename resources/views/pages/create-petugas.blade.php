@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="alert alert-dismissible hide" id="errMsg" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <span id="errData"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="header">
                   
                    <h4 class="title" id="cobak">Create Petugas</h4>  <!-- scrol keatas -->
                    </div>
                    <div class="content">
                        <form id="formpetugas" enctype="multipart/form-data"> <!-- upload juga -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama Petugas</label>
                                        <input type="text" name="nama_petugas" class="form-control"
                                               placeholder="Nama Petugas" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Alamat Petugas</label>
                                        <input type="text" name="alamat_petugas" class="form-control"
                                               placeholder="Alamat Petugas" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Telp Petugas</label>
                                        <input type="text" name="tlp_petugas" class="form-control"
                                               placeholder="Telp Petugas" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Images</label>
                                        <!-- onchange="cekfoto()" = mengecek foto  -->
                                        <!-- accept="image/*" = mengeluarkan semua bentuk image -->
                                        <input type="file" name="image" class="btn btn-default" id="imgInp" onchange="cekfoto()" accept="image/*">
                                    </div>
                                    <img id="blah" src="#" style="width:250px;height:100%;" class="showempty" hidden/>
                                    <!-- foto blank -->
                                    <img  src="/img/picture.svg" style="width:250px;height:100%;" class="showempty2" hidden/>  
                                </div>
                            </div>

                             <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control"
                                               placeholder="Email" value="">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <br>
                                <button class="btn btn-default submit" id="btnSimpan">Simpan</button>
                                <button class="btn btn-default submit" id="btnSimpanKembali">Simpan & Kembali</button>
                                <a class="btn btn-default submit" href={{route('page.list-petugas')}}>Kembali</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            // hidden foto blank
            $('.showempty').hide('slow');
            $('.showempty2').show('slow');

            //upload imgaes
            function readURL(input) {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#blah').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imgInp").change(function () {
                readURL(this);
            });
            // stop
        });
        
        // menampilkan foto di create
        function cekfoto() {
    $('.showempty').show('slow');
    $('.showempty2').hide('slow');
}

        //scroll ke atas
        var h4 = $("#cobak").position();
        
        // ini adalah proses submit data menggunakan Ajax
        $("#btnSimpan").click(function (event) {

            // kasih ini dong biar gag hard reload
            event.preventDefault();
            $.ajax({
                url: '{{route("petugas.store")}}', // url post data
                dataType: 'json',
                async: false,
                type: 'post',
                processData: false,
                contentType: false,
                data: new FormData($("#formpetugas")[0]),
                success: function (data, textStatus, jQxhr) {
                    console.log('status =>', textStatus);
                    console.log('data =>', data);

                    // clear validation error messsages
                    $('#errMsg').addClass('hide');
                    $('#errData').html('');
                    
                    // tampilkan pesan sukses
                    showNotifSuccess();
                    
                    // clear data inputan
                    $('#formpetugas').find("input[type=text], textarea").val("");
                    // kembali kelist petugas
                },
                error: function (data, textStatus, errorThrown) {
                    var messages = jQuery.parseJSON(data.responseText);
                    console.log(errorThrown);
                    
                    // tampilkan pesan error
                    $('#errData').html('');
                    $('#errMsg').addClass('alert-warning');
                    $('#errMsg').removeClass('hide');
                    $.each(messages, function (i, val) {
                        $('#errData').append('<p>' + i + ' : ' + val + '</p>')
                        console.log(i, val);
                    });
                    // scrol ke atas
                    $('div,h4').animate({
                        scrollTop: h4.top
                    }, 5);
                    return false;

                    // jangan clear data
                }
            });
        });
        $("#btnSimpanKembali").click(function (event) {
            // kasih ini dong biar gag hard reload
            event.preventDefault();
            // kasih ini dong biar gag hard reload
            event.preventDefault();
            $.ajax({
                url: '{{route("petugas.store")}}', // url post data
                dataType: 'json',
                async: false,
                type: 'post',
                processData: false,
                contentType: false,
                data: new FormData($("#formpetugas")[0]),

                success: function (data, textStatus, jQxhr) {
                    console.log('status =>', textStatus);
                    console.log('data =>', data);

                    // clear validation error messsages
                    $('#errMsg').addClass('hide');
                    $('#errData').html('');

                    // tampilkan pesan sukses
                    showNotifSuccess();

                    // clear data inputan
                    $('#formpetugas').find("input[type=text], textarea").val("");
                    // kembali kelist petugas
                    window.location.replace('{{route("page.list-petugas")}}');
                },
                error: function (data, textStatus, errorThrown) {
                    var messages = jQuery.parseJSON(data.responseText);
                    console.log(errorThrown);
                
                    // tampilkan pesan error
                    $('#errData').html('');
                    $('#errMsg').addClass('alert-warning');
                    $('#errMsg').removeClass('hide');
                    $.each(messages, function (i, val) {
                        $('#errData').append('<p>' + i + ' : ' + val + '</p>')
                        console.log(i, val);
                    });

                    //scroll ke atas
                    $('div,h4').animate({
                        scrollTop: h4.top
                    }, 5);
                    return false;
                    // jangan clear data
                }
            });
        });

        function showNotifSuccess() {
            $.notify({
                icon: 'pe-7s-checklist',
                message: "Data Petugas berhasil disimpan."
            }, {
                type: 'success',
                timer: 4000
            });
        }
    </script>
@endsection