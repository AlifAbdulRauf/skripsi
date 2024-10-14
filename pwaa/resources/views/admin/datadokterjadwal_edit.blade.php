@extends('layout.v_template')

@section('main-content')
<h3 class="font-weight-bold mx-4">Edit Jadwal Dokter </h3>
<!-- Page Heading -->
<div class="mx-4" >
    <form action="/datadokter/jadwal/update/{{ $datadokter->jadwal_id }}" method="POST" enctype="multipart/form-data" style="width: 95%;">
        @csrf
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="content mr-5">
                    <div class="form-group">
                        <label>Hari</label>
                        <input name="hari" class="form-control" value="{{ $datadokter->hari }}">
                        <div class="text-danger">
                            <!-- Menampilkan pesan error jika ada -->
                            @error('hari')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
    
                    <div class="form-group">
                        <label>Sesi</label>
                        <input name="sesi" class="form-control" value="{{ $datadokter->sesi }}">
                        <div class="text-danger">
                            <!-- Menampilkan pesan error jika ada -->
                            @error('sesi')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="form-group">
            <button class="btn btn-primary btn-sm">Simpan</button>
        </div>
    </form>
</div>

<div class="row mt-4">
    <div class=" col-lg-3 ">
    </div>
    <div class=" col-lg-3 ">
    </div>
    <div class=" col-lg-3 ">
    </div>
    <div class="card col-lg-3 shadow h-100 py-2">   
        <div class="card-body">
            <h5 class="m-0 font-weight-bold text-success ">
                Shift 1  :  10.00 - 15.00
            </h5>
            <h5 class="m-0 h5 font-weight-bold text-success ">
                Shift 2  :  15.00 - 21.00
            </h5>
        </div>
    </div>
</div>


@endsection
