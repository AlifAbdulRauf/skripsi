@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->

@foreach ($rekammedik_id as $medik)

<h3 class="font-weight-bold mx-3 mt-5">Rekam Medik {{ $medik->nama. ' (' .$medik->tanggal. ')' }} </h3>


    <table class="table table-bordered mx-3">
        <tbody>
            <tr>
                <th>Dokter</th>
                @foreach($dokter as $dok)
                    @if($medik->dokter_id == $dok->dokter_id)
                        <td>{{ $dok->nama ?? '' }}</td>
                    @endif 
                @endforeach            
            </tr>
            <tr>
                <th>Golongan darah</th>
                <td>{{ $medik->golongan_darah ?? '' }}</td>
            </tr>
            <tr>
                <th>Tekanan darah</th>
                <td>{{ $medik->tekanan_darah ?? '' }}</td>
            </tr>
            <tr>
                <th>Penyakit Jantung</th>
                <td>{{ $medik->penyakit_jantung ?? '' }}</td>
            </tr>
            <tr>
                <th>Diabetes</th>
                <td>{{ $medik->diabetes ?? '' }}</td>
            </tr>
            <tr>
                <th>Hepatitis</th>
                <td>{{ $medik->hepatitis ?? '' }}</td>
            </tr>
            <tr>
                <th>Penyakit lainnya</th>
                <td>{{ $medik->penyakit_lainnya ?? '' }}</td>
            </tr>
            <tr>
                <th>Alergi Makanan</th>
                <td>{{ $medik->alergi_makanan ?? '' }}</td>
            </tr>
            <tr>
                <th>Alergi Obat</th>
                <td>{{ $medik->alergi_obat ?? '' }}</td>
            </tr>
            <tr>
                <th>Keluhan</th>
                <td>{{ $medik->keluhan ?? '' }}</td>
            </tr>
            <tr>
                <th>Perawatan</th>
                @foreach($pasien as $item)
                    @if($item->reservasi_id == $medik->reservasi_id)
                    <td>{{ $item->jenis_Perawatan ?? '' }}</td>
                    @endif   
                @endforeach
            </tr>
            <tr>
                <th>Gigi</th>
                <td>{{ $medik->gigi ?? '' }}</td>
            </tr>
            
            <tr>
                @foreach($pasien as $item)
                    @if($item->reservasi_id == $medik->reservasi_id)
                        <th>Rencana Tindak Lanjut {{ $item->jenis_Perawatan ?? '' }}</th>
                        <td>{{ $item->rencana_tindak_lanjut ?? '-' }}</td>
                    @endif   
                @endforeach
            </tr>
           
        </tbody>
    </table>

@endforeach


@endsection
