@extends('layout.v_template_dokter')

@section('main-content')
<h3 class="font-weight-bold mx-4">Tambah data Absen </h3>
<!-- Page Heading -->
<div class="mx-4" >
    <form  method="POST" action="{{ route("dataabsen.insert") }}" method="POST" enctype="multipart/form-data" style="width: 95%;">
        @csrf
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="content mr-5">
                    <div class="form-group">
                        <label>Tanggal Awal </label>
                        <input type="date" name="tanggal_awal" class="form-control" value="">
                        <div class="text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Akhir </label>
                        <input type="date" name="tanggal_akhir" class="form-control" value="">
                        <div class="text-danger"></div>
                    </div>
 
                    <div class="form-group">
                        <label>Alasan</label>
                        <input name="alasan" class="form-control" value="">
                        <div class="text-danger"></div>
                    </div>
                </div>
            </div>
        </div>


        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>


@endsection
