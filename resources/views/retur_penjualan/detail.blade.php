@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Retur Penjualan Detail</h1>

    <div class="mx-4" id="modalTambahBarang">
        <a id="createPenjualanPiutang" style="text-decoration:none;" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" 
           data-bs-toggle="modal" data-bs-target="#modalTambahBarangForm{{$retur->uuid}}">Tambah Data</a>
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
                    <th class="border px-4 py-2">Subtotal</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="itemList">
                @foreach($detailRetur as $dtl)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $dtl->nama_barang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->qty }}</td>
                    <td class="border px-4 py-2">{{ $dtl->harga }}</td>
                    <td class="border px-4 py-2">{{ $dtl->satuan }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal, 2) }}</td>
                    <td class="border px-4 py-2">
                    <div class="d-flex">
                        <a href="javascript:void(0)" onclick="openEditModal({{ $dtl->id }}, '{{ $dtl->nama_barang }}', {{ $dtl->qty }}, {{ $dtl->harga }}, '{{ $dtl->satuan }}')" class="btn btn-warning btn-sm ml-2">Edit</a>
                        <a href="{{ route('delete-retur-penjualan-detail', $dtl['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang {{ $dtl->nama_barang }} ??')" class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div id="modalTambahBarangForm{{$retur->uuid}}" class="modal fade" tabindex="-1">
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
                        <select id="barang" name="nama_barang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
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
                        <label for="satuan">Satuan</label>
                        <select id="satuan" name="satuan" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Satuan</option>
                            @foreach($db as $barang)
                                <option value="{{ $barang->satuan }}">{{ $barang->satuan }}</option>
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


<!-- Modal for Editing Data -->
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
                        <label for="editBarang">Nama Barang</label>
                        <select id="editBarang" name="editBarang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            @foreach($data as $barang)
                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="editHarga">Harga</label>
                        <input type="number" id="editHarga" name="editHarga" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="editQty">Qty</label>
                        <input type="number" id="editQty" name="editQty" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="editSatuan">Satuan</label>
                        <select id="editSatuan" name="editSatuan" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            @foreach($db as $barang)
                                <option value="{{ $barang->satuan }}">{{ $barang->satuan }}</option>
                            @endforeach
                        </select>
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
        dropdownParent: $("#modalTambahBarangForm{{$retur->uuid}}")
    });

    $("#editBarang").select2({
        dropdownParent: $("#modalEditBarang")
    });

    $("#editSatuan").select2({
        dropdownParent: $("#modalEditBarang")
    });

    if ($.fn.DataTable.isDataTable('#datatable-basic')) {
        $('#datatable-basic').DataTable().destroy();
    }

    $('#datatable-basic').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
        }
    });
});

function addItem() {
    var nama_barang = $('#barang').val();
    var harga = $('#harga').val();
    var qty = $('#qty').val();
    var satuan = $('#satuan').val();
    var subtotal = harga * qty;

    // Validate that all required fields are filled
    if (nama_barang === '' || harga === '' || qty === '' || satuan === '') {
        alert('Semua data harus diisi!');
        return false;
    }

    // Perform an AJAX request to add the item
    $.ajax({
        url: "{{ route('retur-penjualan.store-detail', ['uuid' => $retur->uuid]) }}",  // Update to your actual route
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            nama_barang: nama_barang,
            harga: harga,
            qty: qty,
            satuan: satuan,
            subtotal: subtotal
        },
        success: function(response) {
            if (response.success) {
                // Append the new item to the table dynamically
                var newRow = `
                    <tr>
                        <td class="border px-4 py-2">${$('#itemList tr').length + 1}</td>
                        <td class="border px-4 py-2">${response.detail.nama_barang}</td>
                        <td class="border px-4 py-2">${response.detail.qty}</td>
                        <td class="border px-4 py-2">${response.detail.harga}</td>
                        <td class="border px-4 py-2">${response.detail.satuan}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.subtotal).toFixed(2)}</td>
                        <td class="border px-4 py-2">
                            <div class="d-flex">
                                <a href="javascript:void(0);" onclick="openEditModal(${response.detail.id}, '${response.detail.nama_barang}', ${response.detail.qty}, ${response.detail.harga}, '${response.detail.satuan}')" 
                                   class="btn btn-warning btn-sm ml-2">Edit</a>
                                <a href="{{ route('delete-retur-penjualan-detail', 'id_placeholder') }}" 
                                   id="btn-delete-post" 
                                   onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang ${response.detail.nama_barang} ??')" 
                                   class="btn btn-danger btn-sm">Hapus</a>
                            </div>
                        </td>
                    </tr>
                `;
                $('#itemList').append(newRow);

                // Reset the modal form and close the modal
                resetModalForm();
                var modalElement = document.getElementById('modalTambahBarangForm{{$retur->uuid}}');
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

function resetModalForm() {
    $('#barang').val('').trigger('change');  // Reset select2 dropdown
    $('#harga').val('');
    $('#qty').val('');
    $('#satuan').val('').trigger('change');
}


function openEditModal(id, nama_barang, qty, harga, satuan) {
     $('#editItemId').val(id);
    $('#editBarang').val(nama_barang).trigger('change');
    $('#editQty').val(qty);
    $('#editHarga').val(harga);
    $('#editSatuan').val(satuan).trigger('change');

    var modalElement = document.getElementById('modalEditBarang');
    var modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
}

function updateItem() {
    var id = $('#editItemId').val();
    var nama_barang = $('#editBarang').val();
    var harga = $('#editHarga').val();
    var qty = $('#editQty').val();
    var satuan = $('#editSatuan').val();
    var subtotal = harga * qty;

    if (nama_barang == '' || harga == '' || qty == '' || satuan == '') {
        alert('Data harus diisi semua!');
        return false;
    }

    $.ajax({
        url: "{{ route('retur-penjualan.detail.update', ['uuid' => $retur->uuid]) }}",
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
            nama_barang: nama_barang,
            harga: harga,
            qty: qty,
            satuan: satuan,
            subtotal: subtotal
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Gagal memperbarui barang!');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Gagal memperbarui barang!');
        }
    });
}
</script>
@endpush
