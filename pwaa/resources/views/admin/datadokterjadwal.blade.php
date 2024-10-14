@extends('layout.v_template')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Detail Jadwal Dokter </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr><th>no</th>
            <th>Hari</th>
            <th>Sesi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datajadwal as $jadwal)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $jadwal->hari}}</td> 
            <td>{{ $jadwal->sesi}}</td>
            <td>
                <div class="d-flex">
                    <a href="/datadokter/jadwal/edit/{{ $jadwal->jadwal_id }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('dokterjadwal.destroy', [$jadwal->jadwal_id]) }}" method="POST" display="inline" onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data ini seperti rekam medik, jadwal dan lainnya juga akan ikut terhapus');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<a type="submit" class="btn btn-success mx-auto" href="/datadokter/jadwal/add/{{ $datadokter->dokter_id}}" style="font-size: 16px; font-weight: bold;">Tambahkan Data Jadwal Dokter</a>
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