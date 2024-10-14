@extends('layout.v_template_owner')

@section('main-content')

    <h2>Laporan Keuangan</h2>
    <table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan dan Tahun</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($keuangan  as $data)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $data['month'] }}</td>
                <td>{{ number_format($data['totalIncome'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['totalExpenses'], 0, ',', '.') }}</td>
                <td>{{ number_format($data['profit'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


@endsection

