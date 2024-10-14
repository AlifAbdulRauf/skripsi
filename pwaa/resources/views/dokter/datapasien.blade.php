@extends('layout.v_template_dokter')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Pasien </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>no</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Nama Pasien</th>
            <th>Lokasi Perawatan</th>
            <th>Pekerjaan</th>
            <th>Rekam Medik</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($reservasi_rekam_medik as $res)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $res->tanggal}}</td> 
            <td>{{ $res->jam_mulai}}</td> 
            <td>{{ $res->nama}}</td> 
            <td>{{ $res->nama_Lokasi}}</td>
            <td>{{ $res->pekerjaan}}</td>
            <td><a href="{{ route("dokter.rekampasien", [$res->pasien_id]) }}">Rekam Medik</a></td>
            <td>
                <div class="d-flex">
                    <a href="/ddatapasien/edit/{{ $res->pasien_id }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('ddatapasien.destroy', [$res->pasien_id]) }}" method="POST" display="inline" onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data pasien ini juga akan ikut terhapus');">
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

{{-- <a type="submit" class="btn btn-success mx-auto" href="/ddatapasien/add" style="font-size: 16px; font-weight: bold;">Tambahkan Data pasien</a> --}}


@endsection