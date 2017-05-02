@extends('layouts.app')
@section('title', 'List Book')
@section('content')
<div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">

                <div class="header">
                  <h4 class="title">List Book</h4>
                </div>
                <div class="content">
                  <div class="row">
                    <div class="col-md-8 pull-left">
                      <div class="font-icon-list">
                        <a class="btn btn-default submit" href={{route('page.create-book')}}>
                          <i class="pe-7s-plus"></i>
                        </a>
                        <button class="btn btn-default submit">
                          <i class="pe-7s-refresh"></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-md-4 pull-right">
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Pencarian..." value="">
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive table-full-width">
                    <table class="table table-hover table-striped">
                      <thead>
                        <th>Kode Buku</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                      @foreach ($books as $book)
                          <tr>
                            <td>{{ $book->kode_buku }}</td>
                            <td>{{ $book->judul }}</td>
                            <td>{{ $book->pengarang }}</td>
                            <td>{{ $book->aksi }}</td>
                            <td>
                              <a class="btn btn-default" href={{route('page.edit-book',['id' => $book->id])}}>
                                <i class="pe-7s-pen"></i>
                              </a>
                              <button class="btn btn-danger">
                                <i class="pe-7s-trash"></i>
                              </button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
@endsection
@section('scripts')
@endsection