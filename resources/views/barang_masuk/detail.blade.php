@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Detail Barang</h1>
    
    <div class="mx-4" id="modalTambahBarang">
        <a id="createPenjualanPiutang" style="text-decoration:none;" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" 
           data-bs-toggle="modal" data-bs-target="#modalTambahBarangForm{{$barangMasuk->uuid}}">Tambah Data</a>
    </div>

    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Qty</th>
                    <th class="border px-4 py-2">Satuan</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailBarangMasuk as $dtl)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $dtl->nama_barang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->qty }}</td>
                    <td class="border px-4 py-2">{{ $dtl->satuan }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                            <button data-bs-toggle="modal" data-bs-target="#modal-edit-detail{{ $dtl->uuid }}"
                                class="btn btn-warning btn-sm ml-2">Edit</button>

                             <a href="{{ route('delete-barang-masuk-detail', $dtl['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang {{ $dtl->nama_barang }} ??')"
                                class="btn btn-danger btn-sm ml-2">Hapus</a>
                        </div>
                    </td>
                    @include('barang_masuk.edit-detail')
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahBarangForm{{$barangMasuk->uuid}}" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createPenjualanPiutangForm">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>    
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="barang">Nama Barang</label>
                        <select id="nama_barang" name="nama_barang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Barang</option>
                            @foreach($db as $barang)
                                <option value="{{ $barang->nama_produk }}">{{ $barang->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="qty">Qty</label>
                        <input type="number" id="qty" name="qty" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="barang">Satuan</label>
                        <select id="satuan" name="satuan" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Satuan</option>
                            @foreach($db as $satuan)
                                <option value="{{ $satuan->satuan }}">{{ $satuan->satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addItem()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $("#nama_barang").select2({
        dropdownParent: $("#modalTambahBarangForm{{$barangMasuk->uuid}}")
    });
    if ($.fn.DataTable.isDataTable('#datatable-basic')) {
        $('#datatable-basic').DataTable().destroy();
    }

    $('#datatable-basic').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
        }
    });

    $('.editButton').on('click', function() {
    var uuid = $(this).data('id');
    $.ajax({
        url: '/barang-masuk/' + uuid + '/edit/detail',
        type: 'GET',
        success: function(response) {
            $('#nama_barang').val(response.nama_barang);
            $('#satuan').val(response.satuan); 

            // Set form action to update the data
            $('#editForm').attr('action', '/barang-masuk/' + uuid + '/detail/update');

            // Show modal
            // $('#editDetailModal').modal('showDetail');

            $("#nama_barang").select2({
                dropdownParent: $('#editDetailModal')
            });
        }
    });
    });
});

function addItem() {
    var nama_barang = $('#nama_barang').val();
    var qty = $('#qty').val();
    var satuan = $('#satuan').val();

    if (nama_barang == '' || qty == '' || satuan == '') {
        alert('Data harus diisi semua!');
        return false;
    }

    $.ajax({
        url: "{{ route('barang-masuk.store-detail', ['uuid' => $barangMasuk->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            nama_barang: nama_barang,
            qty: qty,
            satuan: satuan,
        },
        success: function(response) {
            if (response.success) {
                var rowCount = $('#datatable-basic tbody tr').length + 1;  // Hitung jumlah baris di tabel
                
                // Tambahkan data baru ke tabel
                var newRow = `
                    <tr>
                        <td class="border px-4 py-2">${rowCount}</td>
                        <td class="border px-4 py-2">${response.detail.nama_barang}</td>
                        <td class="border px-4 py-2">${response.detail.qty}</td>
                        <td class="border px-4 py-2">${response.detail.satuan}</td>
                        <td class="border px-4 py-2">
                            <div class="d-flex">
                                <a href="{{ route('delete-barang-masuk-detail', 'PLACEHOLDER_ID') }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang ${response.detail.nama_barang} ??')" class="btn btn-danger btn-sm">Hapus</a>
                            </div>
                        </td>
                    </tr>
                `.replace(/PLACEHOLDER_ID/g, response.detail.id);

                $('#datatable-basic tbody').append(newRow); // Tambahkan baris baru ke tbody

                // Reset dan kosongkan input dalam modal setelah sukses menyimpan data
                resetModalForm();

                // Tutup modal
                var modalElement = document.getElementById('modalTambahBarangForm{{$barangMasuk->uuid}}');
                var modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance.hide();
            } else {
                alert('Gagal menambahkan barang!');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Gagal menambahkan barang!');
        }
    });
}

// Fungsi untuk me-reset form di dalam modal
function resetModalForm() {
    $('#nama_barang').val('');         // Kosongkan input barang
    $('#qty').val('');            // Kosongkan input qty
    $('#satuan').val('');     // Reset select box keterangan
}
</script>
@endpush
