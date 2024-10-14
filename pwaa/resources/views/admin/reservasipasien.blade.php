@extends('layout.v_template')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Draft Reservasi dari Pasien</h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pasien</th>
            <th>Lokasi Perawatan</th>
            <th>Nama Dokter</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservasi_rekam_medik as $res)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $res->nama }}</td>
            <td>{{ $res->nama_Lokasi }}</td>
            <td>{{ $res->nama_dokter }}</td>
            <td>{{ $res->tanggal }}</td>
            <td>{{ $res->jam_mulai }}</td>
            <td>
                <div class="d-flex">
                    <!-- Form untuk terima reservasi -->
                    <form action="{{ route('reservasi.terima', [$res->reservasi_id]) }}" method="POST" display="inline">
                        @csrf
                        <button type="submit" class="btn btn-primary mr-2">Terima</button>
                    </form>

                    <!-- Tombol Tolak dengan SweetAlert -->
                    <button type="button" class="btn btn-danger mr-2" onclick="showRejectPopup({{ $res->reservasi_id }})">Tolak</button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- JavaScript untuk SweetAlert -->
<script>
    function showRejectPopup(reservasiId) {
        Swal.fire({
            title: 'Alasan Penolakan',
            input: 'textarea',
            inputLabel: 'Masukkan alasan mengapa reservasi ini ditolak',
            inputPlaceholder: 'Tulis alasan di sini...',
            inputAttributes: {
                'aria-label': 'Tulis alasan di sini'
            },
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
            preConfirm: (alasan) => {
                if (!alasan) {
                    Swal.showValidationMessage('Alasan penolakan harus diisi!');
                } else {
                    // Kirim data ke server menggunakan AJAX
                    return fetch(`/reservasi/admin/tolak/${reservasiId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ alasan: alasan })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Reservasi berhasil ditolak!',
                    icon: 'success'
                }).then(() => {
                    location.reload(); // Reload halaman setelah berhasil
                });
            }
        });
    }
</script>

@endsection
