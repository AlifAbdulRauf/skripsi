@extends('layout.v_template_owner')

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
            
            
            <td><a href="{{ route("odatadokter.jadwal", [$dok->dokter_id])}}}">Detail Jadwal</a></td>
        </tr>
        @endforeach

    </tbody>
</table>

@endsection