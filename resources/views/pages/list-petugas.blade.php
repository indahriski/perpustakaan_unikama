@extends('layouts.dashboard')
@section('title', 'List petugas')
@section('content')
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous"> -->
<div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="header">
                  <h4 class="title"><b>List Petugas</b></h4>
                </div>
                <div class="content">
                  <div class="row">
                    <div class="col-md-8 pull-left">
                      <div class="font-icon-list">
                        <a class="btn btn-success submit" href={{route('page.create-petugas')}}>
                          <i class="pe-7s-add-user" style="font-size: 25px;"></i></a>
                        <a class="btn btn-danger submit"href="/api/cetak_petugas" target="_blank">
                          <i class="pe-7s-bottom-arrow" style="font-size: 25px;"></i></a>
                        <a class="btn btn-info submit"href={{route( 'page.list-petugas')}}>
                          <i class="pe-7s-refresh-2" style="font-size: 25px;"></i></a>
                      </div>
                    </div>
                   <div class="col-md-4 pull-right">
                    <form method="GET" action="{{route('page.list-petugas')}}"> 
                     <div class="form-group">
                      <input type="text" class="form-control" style="border: 1px solid #18CDD4;" name="search" placeholder="Pencarian berdasarkan Nama Petugas" value="">
                      </div>
                      </form>
                    </div>
                  </div>
                  <div class="table-responsive table-full-width">
                    <table class="table table-hover table-striped">
                      <thead>
                        <!-- <th><p class="text text-success">Nama Petugas</p></th> -->
                        <th>Nama Petugas</th>
                        <th>Alamat Petugas</th>
                        <th>Telp Petugas</th>
                        <th>Images</th> 
                        <th>Email</th> 
                        <th></th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                      @foreach ($petugas as $row)
                          <tr>
                            <td>{{ $row->nama_petugas }}</td>
                            <td>{{ $row->alamat_petugas }}</td>
                            <td>{{ $row->tlp_petugas }}</td>
                            <td> 

                            <!-- kondisi untuk menghapus foto -->
                            @if($row->image != '')
                            <img src = "/assets/foto_petugas/{{$row->image}}" style="width:150px; height:100%;">
                            @else
                            <img  src="/img/picture.svg" style="width:150px; height:100%;"/>  
                            @endif
                            
                            </td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->aksi }}</td>
                            <td>

<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:118px">
    Pilih Aksi
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="min-width:3rem; ">
    <li>
    <a class="dropdown-item btn" onClick="KirimEmail('{{$row->id}}')">Kirim Email</a>
          </li>
    <li>
    <a class="dropdown-item btn" onClick="KirimSms('{{$row->id}}')">Kirim SMS</a>
    </li>
    <li>
    <a class="dropdown-item btn" href="/api/cetak_petugas1/{{$row->id}}" target="_blank">Print Data</a>
    </li>
    <li>
    <a class="dropdown-item btn" href="{{route('page.edit-petugas',['id' => $row->id])}}">Update Data</a>
    </li>
    <li>
    <a class="dropdown-item btn" onClick="deleteData('{{$row->id}}')">Delete Data</a>
    </li>

  </ul>
</div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 text-center">
      <!--pagination-->
      {{$petugas->links()}}
    </div>
          </div>
        </div>
@endsection
@section('scripts')
<script>
  function deleteData(petugasId){
    console.log(petugasId);
    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this imaginary file!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel plx!",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(isConfirm){
      if (isConfirm) {
        // delete data using ajax
        $.ajax({
          url: "/api/petugas/" + petugasId,
          type: 'DELETE',
          success: function( data, textStatus, jQxhr ){
            console.log(data);
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
            location.reload();
          },
          error: function( data, textStatus, jQxhr ){
            swal("Internal Server Error", "Whooops something went wrong!", "error");
            location.reload();
          }
        });

      } else {
        swal("Cancelled", "Your imaginary file is safe :)", "error");
      }
    });
  };


  function KirimEmail(petugasId){
    console.log(petugasId);
    swal({
      title: "Peringatan!",
      text: "Apakah Kamu Yakin Ingin Mengirim Email!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Kirim",
      cancelButtonText: "Batal",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(isConfirm){
      if (isConfirm) {
        // email data using ajax
        $.ajax({
          url: "/api/kirim-email/" + petugasId,
          type: 'get',
          success: function( data, textStatus, jQxhr ){
            console.log(data);
            swal("Mengirim Email", "Mengirim Email Telah Berhasil.", "success");
            location.reload();
          },
          error: function( data, textStatus, jQxhr ){
            swal("Kirim Email Gagal", "Ada Sesuatu yang Salah!", "error");
            location.reload();
          }
        });

      } else {
        swal("Batal", "Mengirim Email Telah Dibatalkan :)", "error");
      }
    });
  };

  //kirim sms
  function KirimSms(petugasId){
    console.log(petugasId);
    swal({
      title: "Peringatan!",
      text: "Apakah Kamu Yakin Ingin Mengirim SMS!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Kirim",
      cancelButtonText: "Batal",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(isConfirm){
      if (isConfirm) {
        // email data using ajax
        $.ajax({
          url: "/api/kirim-sms/" + petugasId,
          type: 'get',
          success: function( data, textStatus, jQxhr ){
            console.log(data);
            swal("Mengirim SMS", "Mengirim SMS Telah Berhasil.", "success");
            // location.reload();
          },
          error: function( data, textStatus, jQxhr ){
            swal("Kirim SMS Gagal", "Ada Sesuatu yang Salah!", "error");
            // location.reload();
          }
        });

      } else {
        swal("Batal", "Mengirim SMS Telah Dibatalkan :)", "error");
      }
    });
  };


</script>
@endsection