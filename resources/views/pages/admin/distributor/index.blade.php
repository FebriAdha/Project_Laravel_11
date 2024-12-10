@extends('layouts.admin.main')

@section('title', 'Admin Distributor')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Distributor</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">Distributor</div>
            </div>
        </div>

        <div class="row">
            <!-- Actions -->
            <div class="col-md-4 col-sm-3">
                <a href="{{ route('distributor.create') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fas fa-plus"></i> Tambah Distributor
                </a>
                <a href="{{ route('distributor.export') }}" class="btn btn-icon icon-left btn-info">
                    <i class="fas fa-print"></i> Export
                </a>
            </div>
            <!-- Import Form -->
            <div class="col-md-8 col-sm-9">
                <form action="{{ route('distributor.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex align-items-center">
                        <div class="form-group mb-0 mr-2">
                            <div class="custom-file">
                                <input class="custom-file-input" name="file" id="customFile" type="file" required>
                                <label class="custom-file-label" for="customFile">Pilih File Excel</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-icon icon-left btn-primary">
                            <i class="fas fa-plus"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Distributor Table -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Distributor</th>
                            <th>Kota</th>
                            <th>Provinsi</th>
                            <th>Kontak</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 0; @endphp
                        @forelse ($distributor as $item)
                            <tr>
                                <td>{{ ++$no }}</td>
                                <td>{{ $item->nama_distributor }}</td>
                                <td>{{ $item->kota }}</td>
                                <td>{{ $item->provinsi }}</td>
                                <td>{{ $item->kontak }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <a href="{{ route('distributor.edit', $item->id) }}" class="badge badge-warning">Edit</a>
                                    <a href="javascript:void(0)" class="badge badge-danger" data-id="{{ $item->id }}" onclick="confirmDelete({{ $item->id }})">Hapus</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Distributor Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(distributorId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ url("/distributor/delete") }}/' + distributorId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Dihapus!',
                            data.message,
                            'success'
                        ).then(() => {
                            location.reload(); 
                        });
                    } else {
                        Swal.fire(
                            'Gagal!',
                            data.message,
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan saat menghapus data.',
                        'error'
                    );
                });
            }
        });
    }
</script>

@endsection
