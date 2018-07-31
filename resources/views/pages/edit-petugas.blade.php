@extends('layouts.dashboard')
@section('title', 'List petugas')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Edit petugas</h4>
                    </div>
                    <div class="content">
                        <form id="formpetugas" enctype="multipart/form-data">
                            <input type="hidden" class="form-control" value="{{$petugas->id}}">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama Petugas</label>
                                        <input type="text" name="nama_petugas" class="form-control"
                                               placeholder="Nama Petugas" value="{{$petugas->nama_petugas}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Alamat Petugas</label>
                                        <input type="text" name="alamat_petugas" class="form-control"
                                               placeholder="Alamat Petugas" value="{{$petugas->alamat_petugas}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Telp Petugas</label>
                                        <input type="text" name="tlp_petugas" class="form-control"
                                               placeholder="Telp Petugas" value="{{$petugas->tlp_petugas}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Images</label>
                                        <input type="file" name="image" class="btn btn-default" onchange="cekfoto()"
                                               accept="image/*"
                                               id="imgInp">
                                        <br>
                                       <!-- kondisi jika foto tidak diinput maka keluar blank -->
                                        @if($petugas->image != '')
                                            <img src="/assets/foto_petugas/{{$petugas->image}}"
                                                 style="width:250px; height:100%;" class="showempty2" hidden>
                                        @else
                                            <img src="/img/picture.svg" style="width:250px; height:100%;"
                                                 class="showempty2" hidden/>
                                        @endif

                                        <img id="blah" src="#" style="width:250px;height:100%;" class="showempty"
                                             hidden/>
                                        <!-- check box -->
                                        <div class="form-check">
                                            </br>
                                            <label class="form-check-label">
                                                <input class="form-check-input" value="1" type="checkbox"
                                                       name="hapus_foto">
                                                Ingin Menghapus Foto
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control"
                                               placeholder="Email" value="{{$petugas->email}}">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <br>
                                <button class="btn btn-default submit" id="btnUpdate">Update</button>
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
            $('.showempty').hide('slow');
            $('.showempty2').show('slow');
            $('.fototkedit2').show('slow');
            $('.fototkedit').hide('slow');
            // aktifkan class nav member
            $('#nav-dashboard').removeClass('active');
            $('#nav-list-transaction').removeClass('active');
            $('#nav-list-member').removeClass('active');
            $('#nav-list-petugas').addClass('active');
        });

        function cekfoto() {
            $('.showempty').show('slow');
            $('.showempty2').hide('slow');

        }
    </script>
    <script>
        $(document).ready(function () {
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
        });

        // ini adalah proses submit data menggunakan Ajax
        $("#btnUpdate").click(function (event) {
            // kasih ini dong biar gag hard reload
            // console.log($("input[name='image']").val())
            // console.log($("input[name='image']").val())
            var formData = new FormData($('#formpetugas')[0]);
            event.preventDefault();
            $.ajax({
                url: '/api/petugas-update/{{$petugas->id}}', // url edit data                dataType: 'json',
                type: 'post',
                data: formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false,       // The content type used when sending data to the server.
                cache: false,             // To unable request pages to be cached
                processData: false,
                success: function (data, textStatus, jQxhr) {
                    showNotifSuccess();

                    console.log('status =>', textStatus);
                    console.log('data =>', data);
                    // clear validation error messsages
                    $('#errMsg').addClass('hide');
                    $('#errData').html('');
                    // scroll up
                    // $('html, body').animate({
                    //     scrollTop: $("#nav-top").offset().top
                    // }, 2000);
                    // tampilkan pesan sukses
                    // kembali kelist petugas
                    window.location.href = '{{route("page.list-petugas")}}'
                },
                error: function (data, textStatus, errorThrown) {
                    var messages = jQuery.parseJSON(data.responseText);
                    console.log(errorThrown);
                    // $('html, body').animate({
                    //     scrollTop: $("#nav-top").offset().top
                    // }, 2000);
                    // scroll up
                    // tampilkan pesan error
                    $('#errData').html('');
                    $('#errMsg').addClass('alert-warning');
                    $('#errMsg').removeClass('hide');
                    $.each(messages, function (i, val) {
                        $('#errData').append('<p>' + i + ' : ' + val + '</p>')
                        console.log(i, val);
                    });
                    // jangan clear data
                }
            });
        });

        function showNotifSuccess() {
            $.notify({
                icon: 'pe-7s-checklist',
                message: "Edit Data Petugas berhasil disimpan."
            }, {
                type: 'success',
                timer: 4000
            });
        }
    </script>
@endsection