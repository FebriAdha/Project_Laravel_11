@extends('layouts.admin.main')

@section('title', 'Admin Product')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Produk</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">Produk</div>
            </div>
        </div>

        <a href="{{ route('product.create') }}" class="btn btn-icon icon-left btn-primary">
            <i class="fas fa-plus"></i> Produk
        </a>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-md">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Harga Produk</th>
                            <th>Diskon</th>
                            <th>Harga Setelah Diskon</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 0; @endphp
                        @forelse ($products as $item)
                            <tr>
                                <td>{{ ++$no }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->price }} Points</td>
                                <td>
                                    {{ $item->discount ? $item->discount . '%' : 'Tidak ada diskon' }}
                                </td>
                                <td>
                                    @if($item->discount)
                                        {{ $item->price - ($item->price * $item->discount / 100) }} Points
                                    @else
                                        {{ $item->price }} Points
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('product.detail', $item->id) }}" class="badge badge-info">Detail</a>
                                    <a href="{{ route('product.edit', $item->id) }}" class="badge badge-warning">Edit</a>
                                    <a href="#" class="badge badge-danger" data-id="{{ $item->id }}" onclick="confirmDelete({{ $item->id }})">Hapus</a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Produk Kosong</td>
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
    function confirmDelete(productId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menghapus produk ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mengirim permintaan DELETE menggunakan fetch API
                fetch('/product/delete/' + productId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Menampilkan notifikasi sukses jika penghapusan berhasil
                    Swal.fire(
                        'Dihapus!',
                        data.message,
                        'success'
                    );
                    // Memuat ulang halaman setelah penghapusan
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus produk.',
                        'error'
                    );
                });
            }
        });
    }
</script>


@endsection
