@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Detail Barang</h1>
    
    <div class="mx-4" id="modalTambahBarang">
        <a id="createPenjualanPiutang" style="text-decoration:none;" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" 
           data-bs-toggle="modal" data-bs-target="#modalTambahBarangForm{{$pembelian->uuid}}">Tambah Data</a>
    </div>

    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Qty</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Satuan</th>
                    <th class="border px-4 py-2">Total Harga</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $dtl)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $dtl->nama_barang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->qty }}</td>
                    <td class="border px-4 py-2">{{ $dtl->harga }}</td>
                    <td class="border px-4 py-2">{{ $dtl->satuan }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal,2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                            <button data-bs-toggle="modal" data-bs-target="#modal-edit-detail{{ $dtl->uuid }}"
                                class="btn btn-warning btn-sm">Edit</button>

                             <a href="{{ route('delete-pembelian-new-detail', $dtl['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang {{ $dtl->nama_barang }} ??')"
                                class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </td>
                    @include('pembelian_new.edit-detail')
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahBarangForm{{$pembelian->uuid}}" class="modal fade" tabindex="-1">
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
                        <select id="barang" name="barang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Barang</option>
                            @foreach($db as $barang)
                                <option value="{{ $barang->nama_produk }}">{{ $barang->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="harga">Harga</label>
                        <input type="number" id="harga" name="harga" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="qty">Qty</label>
                        <input type="number" id="qty" name="qty" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="barang">Satuan</label>
                        <select id="satuan" name="satuan" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Satuan</option>
                            @foreach($db as $st)
                                <option value="{{ $st->satuan }}">{{ $st->satuan }}</option>
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
    $("#barang").select2({
        dropdownParent: $("#modalTambahBarangForm{{$pembelian->uuid}}")
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
        url: '/pembelian-new/' + uuid + '/edit/detail',
        type: 'GET',
        success: function(response) {
            $('#barang').val(response.barang);
            $('#satuan').val(response.satuan); 

            // Set form action to update the data
            $('#editForm').attr('action', '/pembelian-new/' + uuid + '/detail/update');

            // Show modal
            // $('#editDetailModal').modal('showDetail');

            $("#barang").select2({
                dropdownParent: $('#editDetailModal')
            });
        }
    });
    });
});

function addItem() {
    var barang = $('#barang').val();
    var harga = $('#harga').val();
    var qty = $('#qty').val();
    var satuan = $('#satuan').val();
    var subtotal = harga * qty;

    if (barang == '' || harga == '' || qty == '' || satuan == '') {
        alert('Data harus diisi semua!');
        return false;
    }

    $.ajax({
        url: "{{ route('pembelian-new.store-detail', ['uuid' => $pembelian->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            barang: barang,
            harga: harga,
            qty: qty,
            satuan: satuan,
            subtotal: subtotal
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
                        <td class="border px-4 py-2">${response.detail.harga}</td>
                        <td class="border px-4 py-2">${response.detail.satuan}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.subtotal).toFixed(2)}</td>
                        <td class="border px-4 py-2">
                            <div class="d-flex">
                                <a href="{{ route('delete-pembelian-new-detail', 'PLACEHOLDER_ID') }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang ${response.detail.nama_barang} ??')" class="btn btn-danger btn-sm">Hapus</a>
                            </div>
                        </td>
                    </tr>
                `.replace(/PLACEHOLDER_ID/g, response.detail.id);

                $('#datatable-basic tbody').append(newRow); // Tambahkan baris baru ke tbody

                // Reset dan kosongkan input dalam modal setelah sukses menyimpan data
                resetModalForm();

                // Tutup modal
                var modalElement = document.getElementById('modalTambahBarangForm{{$pembelian->uuid}}');
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
    $('#barang').val('');         // Reset select box barang
    $('#harga').val('');          // Kosongkan input harga
    $('#qty').val('');            // Kosongkan input qty
    $('#satuan').val('');     // Reset select box keterangan
}
</script>
@endpush
