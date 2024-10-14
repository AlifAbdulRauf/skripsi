@extends('layout.v_template')
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Ketidakhadiran</h1>
        <a href="{{ route('absences.create') }}" class="btn btn-primary">Tambah Ketidakhadiran</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Alasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach($absences as $absence) --}}
                    <tr>
                        <td>{{ "absence->date" }}</td>
                        <td>{{ "absence->reason" }}</td>
                        <td>
                            <a href="{{ route('absences.edit', "absence") }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('absences.destroy', "absence") }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>
@endsection

