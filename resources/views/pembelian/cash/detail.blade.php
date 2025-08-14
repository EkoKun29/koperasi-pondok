@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Pembelian Detail Barang Cash</h1>
    
    <div class="mx-4" id="modalTambahBarang">
        <a id="createPenjualanNonProduksi" style="text-decoration:none;" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" 
           data-bs-toggle="modal" data-bs-target="#modalTambahBarangForm{{$beli_cash->uuid}}">Tambah Data</a>
    </div>

    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Qty</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Cek Barang</th>
                    <th class="border px-4 py-2">Keterangan Barang</th>
                    <th class="border px-4 py-2">Total Harga</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="itemList">
                @foreach($detail as $dtl)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $dtl->nama_barang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->qty }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->harga, 2) }}</td>
                    <td class="border px-4 py-2">{{ $dtl->cek_barang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->keterangan }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal, 2) }}</td>
                    <td class="border px-4 py-2">
                        <a href="javascript:void(0);" onclick="openEditModal({{ $dtl->id }}, '{{ $dtl->nama_barang }}', {{ $dtl->qty }}, {{ $dtl->harga }}, '{{ $dtl->cek_barang }}', '{{ $dtl->keterangan }}')" class="btn btn-warning btn-sm ml-2">Edit</a>
                        <a href="{{ route('delete-pembelian-cash-detail', $dtl['id']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang {{ $dtl->nama_barang }} ??')" class="btn btn-danger btn-sm ml-2">Hapus</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahBarangForm{{$beli_cash->uuid}}" class="modal fade" tabindex="-1">
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
                        <select name="cek_barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="cek_barang" required>
                            <option disabled selected>Pilih Cek Barang</option>
                            <option value="Sesuai">Sesuai</option>
                            <option value="Kurang">Kurang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"><b>Keterangan Barang</b></label>
                        <select name="keterangan" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="keterangan">
                            <option disabled selected>Pilih Keterangan</option>
                            <option value="Produksi">Produksi</option>
                            <option value="Non Produksi">Non Produksi</option>
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

<div id="modalEditBarang" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editPenjualanNonProduksiForm">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>    
                <div class="modal-body">
                    <input type="hidden" id="editItemId">
                    <div class="mb-4">
                        <label for="editBarang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <select id="editBarang" name="editBarang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            @foreach($data as $barang)
                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="editHarga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" id="editHarga" name="editHarga" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="editQty" class="block text-sm font-medium text-gray-700">Qty</label>
                        <input type="number" id="editQty" name="editQty" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="editCekBarang" class="block text-sm font-medium text-gray-700"><b>Cek Barang</b></label>
                        <select name="editCekBarang" id="editCekBarang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Cek Barang</option>
                            <option value="Sesuai" {{ old('cek_barang', $dtl->cek_barang ?? '') == 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                            <option value="Kurang" {{ old('cek_barang', $dtl->cek_barang ?? '') == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="editKeterangan" class="block text-sm font-medium text-gray-700"><b>Keterangan</b></label>
                        <select name="editKeterangan" id="editKeterangan" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Keterangan</option>
                            <option value="Produksi" {{ old('keterangan', $dtl->keterangan ?? '') == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                            <option value="Non Produksi" {{ old('keterangan', $dtl->keterangan ?? '') == 'Non Produksi' ? 'selected' : '' }}>Non Produksi</option>
                        </select>
                    </div>      
                </div>                          
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="updateItem()">Update</button>
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
        dropdownParent: $("#modalTambahBarangForm{{$beli_cash->uuid}}")
    });

    $("#editBarang").select2({
        dropdownParent: $("#modalEditBarang")
    });

    // Check and destroy DataTable if already exists
    if ($.fn.DataTable.isDataTable('#datatable-basic')) {
        $('#datatable-basic').DataTable().destroy();
    }

    // Initialize DataTable
    $('#datatable-basic').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
        }
    });
});

// Function to add item
function addItem() {
    var barang = $('#barang').val();
    var harga = $('#harga').val();
    var qty = $('#qty').val();
    var cek_barang = $('#cek_barang').val();
    var keterangan = $('#keterangan').val();
    var subtotal = harga * qty;

    // Validate input
    if (barang == '' || harga == '' || qty == '' || cek_barang == '') {
        alert('All fields must be filled!');
        return false;
    }

    $.ajax({
        url: "{{ route('pembelian-cash-detail-create', ['uuid' => $beli_cash->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            nama_barang: barang,
            harga: harga,
            qty: qty,
            cek_barang: cek_barang,
            keterangan: keterangan,
            subtotal: subtotal
        },
        success: function(response) {
            if (response.success) {
                var rowCount = $('#itemList tr').length + 1;  // Count the number of rows
                
                // Append new data to the table
                $('#itemList').append(`
                    <tr>
                        <td class="border px-4 py-2">${rowCount}</td>
                        <td class="border px-4 py-2">${response.detail.nama_barang}</td>
                        <td class="border px-4 py-2">${response.detail.qty}</td>
                        <td class="border px-4 py-2">${response.detail.harga}</td>
                        <td class="border px-4 py-2">${response.detail.cek_barang}</td>
                        <td class="border px-4 py-2">${response.detail.keterangan}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.subtotal).toFixed(2)}</td>
                        <td class="border px-4 py-2">
                            <div class="d-flex">
                                <a href="javascript:void(0);" onclick="openEditModal(${response.detail.id}, '${response.detail.nama_barang}', ${response.detail.qty}, ${response.detail.harga}, '${response.detail.cek_barang}', '${response.detail.keterangan}')" 
                                   class="btn btn-warning btn-sm ml-2">Edit</a>
                                <a href="{{ route('delete-pembelian-cash-detail', '') }}/${response.detail.id}" 
                                   onclick="return confirm('Are you sure you want to delete ${response.detail.nama_barang}?')" 
                                   class="btn btn-danger btn-sm">Delete</a>
                            </div>
                        </td>
                    </tr>
                `);

                // Reset modal form and close modal
                resetModalForm();
                var modalElement = $('#modalTambahBarangForm{{$beli_cash->uuid}}');
                modalElement.modal('hide');
            } else {
                alert('Failed to add item!');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Failed to add item!');
        }
    });
}

// Function to reset modal form
function resetModalForm() {
    $('#barang').val('').trigger('change');  // Reset select2 dropdown
    $('#harga').val('');
    $('#qty').val('');
    $('#cek_barang').val('').trigger('change');
    $('#keterangan').val('');
}

// Function to open edit modal
function openEditModal(id, nama_barang, qty, harga, cek_barang, keterangan) {
    $('#editItemId').val(id);
    $('#editBarang').val(nama_barang).trigger('change');
    $('#editQty').val(qty);
    $('#editHarga').val(harga);
    
    // Set the selected value for cek_barang
    $('#editCekBarang').val(cek_barang).trigger('change');
    
    // Set the selected value for keterangan
    $('#editKeterangan').val(keterangan).trigger('change');
    
    $('#modalEditBarang').modal('show');
}

// Function to update item
function updateItem() {
    var id = $('#editItemId').val();
    var barang = $('#editBarang').val();
    var harga = $('#editHarga').val();
    var qty = $('#editQty').val();
    var cek_barang = $('#editCekBarang').val();
    var keterangan = $('#editKeterangan').val();
    var subtotal = harga * qty;

    if (barang == '' || harga == '' || qty == '' || cek_barang == '' || keterangan == '') {
        alert('All fields must be filled!');
        return false;
    }

    $.ajax({
        url: "{{ route('pembelian-cash-update-detail', ['uuid' => $beli_cash->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
            barang: barang,
            harga: harga,
            qty: qty,
            cek_barang: cek_barang,
            keterangan: keterangan,
            subtotal: subtotal
        },
        success: function(response) {
            if (response.success) {
                location.reload();  // Refresh page after successful update
            } else {
                alert('Failed to update item!');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Failed to update item!');
        }
    });
}
</script>
@endpush
