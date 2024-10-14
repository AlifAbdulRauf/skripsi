@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr><th>no</th>
            <th>Hari</th>
            <th>Sesi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datajadwal as $jadwal)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $jadwal->hari}}</td> 
            <td>{{ $jadwal->sesi}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

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