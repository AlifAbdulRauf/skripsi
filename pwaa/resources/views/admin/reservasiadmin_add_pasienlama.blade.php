@extends('layout.v_template')

@section('main-content')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/css/pikaday.min.css">
    <h3 class="font-weight-bold mx-4">Form Tambah Reservasi</h3>
    <div class="mx-4">
        <form method="POST" action="{{ route('reservasi.insertps') }}" enctype="multipart/form-data" style="width: 95%;">
            @csrf
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="content mr-5">
                        <div class="form-group">
                            <label>Nama Pasien</label>
                            <select name="pasien_id" id="pasien_id" class="form-control @error('pasien_id') is-invalid @enderror">
                                <option value="">Daftar Pasien</option>
                                @foreach($pasien as $item)
                                    <option value="{{ $item->pasien_id}}" @if(old('pasien_id') == $item->pasien_id) selected @endif>{{ $item->nama . ' (' .  $item->no_Telp . ')' }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @error('pasien_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Perawatan (Pilih maksimal 2)</label>
                            <select name="perawatan_id[]" id="perawatan_id" class="form-control select2-multiple @error('perawatan_id') is-invalid @enderror" multiple>
                                @foreach($perawatan as $item)
                                    <option value="{{ $item->perawatan_id }}" data-estimasi="{{ $item->estimasi_waktu_perawatan }}" @if(in_array($item->perawatan_id, (array) old('perawatan_id'))) selected @endif>{{ $item->jenis_Perawatan }} ({{ $item->estimasi_waktu_perawatan }} menit)</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @error('perawatan_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Lokasi</label>
                            <select name="lokasi_id" id="lokasi_id" class="form-control @error('lokasi_id') is-invalid @enderror">
                                <option value="">Pilih Lokasi</option>
                                @foreach($lokasi as $item)
                                    <option value="{{ $item->lokasi_id }}" @if(old('lokasi_id') == $item->lokasi_id) selected @endif>{{ $item->nama_Lokasi }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @error('lokasi_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Dokter</label>
                            <select name="dokter_id" id="dokter_id" class="form-control @error('dokter_id') is-invalid @enderror" disabled>
                                <option value="">Pilih Dokter</option>
                            </select>
                            <div class="invalid-feedback">
                                @error('dokter_id')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" required disabled>
                            <div class="invalid-feedback">
                                @error('tanggal')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pilih Waktu</label>
                            <select name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" required disabled>
                                <option value="">Pilih Waktu</option>
                            </select>
                            <div class="invalid-feedback">
                                @error('jam_mulai')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/pikaday.min.js"></script>
    <script> 
        document.addEventListener('DOMContentLoaded', function() {
            var lokasiSelect = document.getElementById('lokasi_id');
            var dokterSelect = document.getElementById('dokter_id');
            var tanggalInput = document.getElementById('tanggal');
            var startTimeSelect = document.getElementById('jam_mulai');
            var perawatanSelect = document.getElementById('perawatan_id');
            var datePicker;
        
            function setDatepickerOptions(availableDays) {
                if (datePicker) {
                    datePicker.destroy(); // Destroy the previous instance
                }
        
                var minDate = new Date();
                var maxDate = new Date();
                maxDate.setMonth(maxDate.getMonth() + 3);
        
                var invalidDays = [0, 1, 2, 3, 4, 5, 6].filter(day => !availableDays.includes(day));
                tanggalInput.min = minDate.toISOString().split('T')[0];
                tanggalInput.max = maxDate.toISOString().split('T')[0];
        
                datePicker = new Pikaday({
                    field: tanggalInput,
                    minDate: minDate,
                    maxDate: maxDate,
                    disableDayFn: function(date) {
                        return invalidDays.includes(date.getDay());
                    },
                    onSelect: function(date) {
                        var day = date.getDay();
                        var hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][day];
                        var dokterId = dokterSelect.value;
        
                        if (dokterId) {
                            fetchSesi(dokterId, hari);
                        }
                    }
                });
            }
        
            function fetchDokter(lokasiId) {
                fetch('/api/dokter-by-lokasi?lokasi_id=' + lokasiId)
                    .then(response => response.json())
                    .then(data => {
                        dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
                        data.forEach(function(dokter) {
                            var option = document.createElement('option');
                            option.value = dokter.dokter_id;
                            option.textContent = dokter.nama;
                            dokterSelect.appendChild(option);
                        });
                        dokterSelect.disabled = false;
                    });
            }
        
            function fetchAvailableDays(dokterId) {
                fetch('/api/available-days?dokter_id=' + dokterId)
                    .then(response => response.json())
                    .then(days => {
                        var availableDays = days.map(day => {
                            switch(day) {
                                case 'Senin': return 1;
                                case 'Selasa': return 2;
                                case 'Rabu': return 3;
                                case 'Kamis': return 4;
                                case 'Jumat': return 5;
                                default: return null;
                            }
                        }).filter(day => day !== null);
        
                        setDatepickerOptions(availableDays);
                        tanggalInput.disabled = false;
                    });
            }
        
            function fetchSesi(dokterId, hari) {
                fetch(`/api/sesi-by-dokter-and-hari?dokter_id=${dokterId}&hari=${hari}`)
                    .then(response => response.json())
                    .then(sesi => {
                        var timeslots = [];
                        if (sesi.includes(1)) {
                            for (var hour = 10; hour < 15; hour++) {
                                for (var minute = 0; minute < 60; minute += 30) {
                                    var start = new Date();
                                    start.setHours(hour, minute, 0);
                                    var end = new Date(start.getTime() + 30 * 60000);
                                    timeslots.push({
                                        start: start.toTimeString().substr(0, 5),
                                        end: end.toTimeString().substr(0, 5)
                                    });
                                }
                            }
                        }
                        if (sesi.includes(2)) {
                            for (var hour = 15; hour < 21; hour++) {
                                for (var minute = 0; minute < 60; minute += 30) {
                                    var start = new Date();
                                    start.setHours(hour, minute, 0);
                                    var end = new Date(start.getTime() + 30 * 60000);
                                    timeslots.push({
                                        start: start.toTimeString().substr(0, 5),
                                        end: end.toTimeString().substr(0, 5)
                                    });
                                }
                            }
                        }
        
                        // Fetch booked times
                        var selectedDate = datePicker.toString('YYYY-MM-DD');
                        fetch(`/api/booked-times?dokter_id=${dokterId}&tanggal=${selectedDate}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Jaringan Bermasalah ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(bookedTimes => {
                                console.log('Booked times:', bookedTimes);
                                var availableTimes = timeslots.filter(function(timeslot) {
                                    return !bookedTimes.some(function(booked) {
                                        var bookedStart = new Date(`1970-01-01T${booked.jam_mulai}`);
                                        var bookedEnd = new Date(`1970-01-01T${booked.jam_selesai}`);
                                        var timeslotStart = new Date(`1970-01-01T${timeslot.start}:00`);
                                        var timeslotEnd = new Date(`1970-01-01T${timeslot.end}:00`);


                                        return (timeslotStart >= bookedStart && timeslotStart < bookedEnd) || 
                                               (timeslotEnd > bookedStart && timeslotEnd <= bookedEnd) || 
                                               (timeslotStart <= bookedStart && timeslotEnd >= bookedEnd);
                                    });
                                });

                                startTimeSelect.innerHTML = '<option value="">Pilih Waktu</option>';
                                availableTimes.forEach(function(timeslot) {
                                    var option = document.createElement('option');
                                    option.value = timeslot.start;
                                    option.textContent = timeslot.start + ' - ' + timeslot.end;
                                    startTimeSelect.appendChild(option);
                                });
                                startTimeSelect.disabled = false;
                            })
                            .catch(error => {
                                console.error('Terdapat masalah dalam penegambilan data anda:', error);
                            });
                    });
            }
        
            lokasiSelect.addEventListener('change', function() {
                var lokasiId = this.value;
                if (lokasiId) {
                    fetchDokter(lokasiId);
                } else {
                    dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
                    dokterSelect.disabled = true;
                    tanggalInput.disabled = true;
                }
            });
        
            dokterSelect.addEventListener('change', function() {
                var dokterId = this.value;
                if (dokterId) {
                    fetchAvailableDays(dokterId);
                } else {
                    tanggalInput.disabled = true;
                }
            });
        
            perawatanSelect.addEventListener('change', function() {
                startTimeSelect.innerHTML = '<option value="">Pilih Waktu</option>';
                startTimeSelect.disabled = true;
            });
        });
    </script>  
@endsection
