@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Perizinan </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr><th>No</th>
            <th>Nama</th>
            <th>Tanggal Awal</th>
            <th>Tanggal Akhir</th>
            <th>Alasan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($izin_user_owner as $izin)
            <tr>
                <td scope="row">{{ $loop->iteration }}</td>
                <td>{{ $izin->dokter->nama }}</td>    
                <td>{{ $izin->tanggal_awal }}</td>
                <td>{{ $izin->tanggal_akhir }}</td>
                <td>{{ $izin->alasan }}</td>    
                <td>
                    <div class="d-flex">
                            <form action="{{  route("owner.izin.terima", [$izin->izin_id ]) }}" method="POST" display="inline">
                                @csrf
                                <button type="submit" class="btn btn-primary mr-2">Terima</button>
                            </form>
                            <button type="button" class="btn btn-danger mr-2" onclick="showRejectPopup({{$izin->izin_id }})">Tolak</button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- JavaScript untuk SweetAlert -->
<script>
    function showRejectPopup(izinId) {
        Swal.fire({
            title: 'Alasan Penolakan',
            input: 'textarea',
            inputLabel: 'Masukkan alasan mengapa izin ini ditolak',
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
                    return fetch(`/owner/izin/tolak/${izinId}`, {
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
                    text: 'Izin berhasil ditolak!',
                    icon: 'success'
                }).then(() => {
                    location.reload(); // Reload halaman setelah berhasil
                });
            }
        });
    }
</script>
@endsection
