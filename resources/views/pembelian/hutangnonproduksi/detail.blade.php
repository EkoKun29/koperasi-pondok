@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Detail Barang</h1>
    
    <div class="mx-4" id="modalTambahBarang">
        <a id="createPenjualanNonProduksi" style="text-decoration:none;" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" 
           data-bs-toggle="modal" data-bs-target="#modalTambahBarangForm{{$hutangnonproduksi->uuid}}">Tambah Data</a>
    </div>

    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Qty</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Keterangan</th>
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
                    <td class="border px-4 py-2">{{ number_format($dtl->harga, 2) }}</td>
                    <td class="border px-4 py-2">{{ $dtl->check_barang }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal, 2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                             <a href="{{ route('delete-pembelian-hutangnonproduksi-detail', $dtl->id) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang {{ $dtl->nama_barang }} ??')"
                                class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahBarangForm{{$hutangnonproduksi->uuid}}" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createPenjualanNonProduksiForm">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>    
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="barang">Nama Barang</label>
                        <select id="barang" name="barang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                            <option disabled selected>Pilih Barang</option>
                            @foreach($data as $barang)
                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
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
                        <label class="block text-sm font-medium text-gray-700"><b>Cek Barang</b></label>
                        <select name="check_barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="check_barang" required>
                            <option disabled selected>Pilih Cek Barang</option>
                            <option value="Sesuai">Sesuai</option>
                            <option value="Kurang">Kurang</option>
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
        dropdownParent: $("#modalTambahBarangForm{{$hutangnonproduksi->uuid}}")
    });
    // Cek dan hancurkan DataTable jika sudah ada
    if ($.fn.DataTable.isDataTable('#datatable-basic')) {
        $('#datatable-basic').DataTable().destroy();
    }
    
    // Inisialisasi DataTable
    $('#datatable-basic').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
        }
    });
});

function addItem() {
    var barang = $('#barang').val();
    var harga = $('#harga').val();
    var qty = $('#qty').val();
    var check_barang = $('#check_barang').val();
    var subtotal = harga * qty;

    // Validasi input
    if (barang == '' || harga == '' || qty == '' || check_barang == '') {
        alert('Data harus diisi semua!');
        return false;
    }

    $.ajax({
        url: "{{ route('pembelian-hutangnonproduksi-detail-create', ['uuid' => $hutangnonproduksi->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            nama_barang: barang,
            harga: harga,
            qty: qty,
            check_barang: check_barang,
            subtotal: subtotal
        },
        success: function(response) {
            if (response.success) {
                var rowCount = $('#datatable-basic tbody tr').length + 1;  // Hitung jumlah baris di tabel
                
                // Tambahkan data baru ke tabel
                $('#datatable-basic tbody').append(`
                    <tr>
                        <td class="border px-4 py-2">${rowCount}</td>
                        <td class="border px-4 py-2">${response.detail.nama_barang}</td>
                        <td class="border px-4 py-2">${response.detail.qty}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.harga).toFixed(2)}</td>
                        <td class="border px-4 py-2">${response.detail.check_barang}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.subtotal).toFixed(2)}</td>
                        <td class="border px-4 py-2">
                            <div class="d-flex">
                                <a href="{{ route('delete-pembelian-hutangnonproduksi-detail', 'PLACEHOLDER_ID') }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang ${response.detail.nama_barang} ??')" class="btn btn-danger btn-sm">Hapus</a>
                            </div>
                        </td>
                    </tr>
                `.replace(/PLACEHOLDER_ID/g, response.detail.id));

                // Reset dan kosongkan input dalam modal setelah sukses menyimpan data
                resetModalForm();

                // Tutup modal
                var modalElement = document.getElementById('modalTambahBarangForm{{$hutangnonproduksi->uuid}}');
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
    $('#check_barang').val('');   // Kosongkan input check_barang
}
</script>
@endpush
