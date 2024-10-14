@extends('layout.v_template')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Dokter </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>no </th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Jadwal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datadokter as $dok)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $dok->nama}}</td>
            <td>
                @php
                    $nomor_hp = $dok->nomor_hp;
                    if (substr($nomor_hp, 0, 2) == '08') {
                        $nomor_hp = '628' . substr($nomor_hp, 2);
                    }
                @endphp
                {{ $nomor_hp }}
            </td>
            <td><a href="/datadokter/jadwal/{{ $dok->dokter_id }}">Detail Jadwal</a></td>
            <td>
                <div class="d-flex">
                    <a href="/datadokter/edit/{{ $dok->dokter_id }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('datadokter.destroy', [$dok->dokter_id]) }}" method="POST" display="inline"
                        onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data dokter ini seperti rekam medik, jadwal dan lainnya juga akan ikut terhapus');">
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

{{-- <a type="submit" class="btn btn-success mx-auto" href="/datadokter/add"
    style="font-size: 16px; font-weight: bold;">Tambahkan Data Dokter</a> --}}
@endsection