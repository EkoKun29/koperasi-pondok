@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Detail Barang</h1>
    
    <div class="mx-4" id="modalTambahBarang">
        <a id="createPenjualanPiutang" style="text-decoration:none;" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" 
           data-bs-toggle="modal" data-bs-target="#modalTambahBarangForm{{$penjualanAcara->uuid}}">Tambah Data</a>
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
                @foreach($details as $dtl)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $dtl->nama_barang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->qty }}</td>
                    <td class="border px-4 py-2">{{ $dtl->harga }}</td>
                    <td class="border px-4 py-2">{{ $dtl->keterangan }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal,2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                            <a href="javascript:void(0)" onclick="openEditModal({{ $dtl->id }}, '{{ $dtl->nama_barang }}', {{ $dtl->qty }}, {{ $dtl->harga }}, '{{ $dtl->keterangan }}')" class="btn btn-warning btn-sm ml-2">Edit</a>
                            <button class="btn btn-danger btn-sm ml-2 btn-delete-detail" data-uuid="{{ $dtl->uuid }}">Hapus</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahBarangForm{{$penjualanAcara->uuid}}" class="modal fade" tabindex="-1">
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
                        <label for="keterangan">Satuan</label>
                        <select id="keterangan" name="keterangan" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
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
                            @foreach($db as $barang)
                                <option value="{{ $barang->nama_produk }}">{{ $barang->nama_produk }}</option>
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
                        <label for="editKeterangan">Satuan</label>
                        <select id="editKeterangan" name="editKeterangan" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Satuan</option>
                            @foreach($db as $satuan)
                                <option value="{{ $satuan->satuan }}">{{ $satuan->satuan }}</option>
                            @endforeach
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
        dropdownParent: $("#modalTambahBarangForm{{$penjualanAcara->uuid}}")
    });

    $("#keterangan").select2({
        dropdownParent: $("#modalTambahBarangForm{{$penjualanAcara->uuid}}")
    });

    $("#editBarang").select2({
                dropdownParent: $('#modalEditBarang')
            });

    $("#editKeterangan").select2({
                dropdownParent: $('#modalEditBarang')
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

$(document).on('click', '.btn-delete-detail', function () {
    var uuid = $(this).data('uuid');

    if (confirm('Yakin ingin menghapus data ini?')) {
        $.ajax({
            url: '/penjualan-acara/detail/delete/' + uuid,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                alert(response.message);
                // reload tabel atau hapus baris dari DOM
            },
            error: function (xhr) {
                alert('Gagal menghapus data.');
            }
        });
    }
});


function addItem() {
    var barang = $('#barang').val();
    var harga = $('#harga').val();
    var qty = $('#qty').val();
    var keterangan = $('#keterangan').val();
    var subtotal = harga * qty;

    if (barang == '' || harga == '' || qty == '' || keterangan == '') {
        alert('Data harus diisi semua!');
        return false;
    }

    $.ajax({
        url: "{{ route('penjualan-acara-detail-create', ['uuid' => $penjualanAcara->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            barang: barang,
            harga: harga,
            qty: qty,
            keterangan: keterangan,
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
                        <td class="border px-4 py-2">${response.detail.keterangan}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.subtotal).toFixed(2)}</td>
                        <td class="border px-4 py-2">
                            <div class="d-flex">
                                <a href="{{ route('delete-penjualan-acara-detail', 'PLACEHOLDER_ID') }}" class="btn-delete-detail" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang ${response.detail.nama_barang} ??')" class="btn btn-danger btn-sm">Hapus</a>
                            </div>
                        </td>
                    </tr>
                `.replace(/PLACEHOLDER_ID/g, response.detail.id);

                $('#datatable-basic tbody').append(newRow); // Tambahkan baris baru ke tbody

                // Reset dan kosongkan input dalam modal setelah sukses menyimpan data
                resetModalForm();

                // Tutup modal
                var modalElement = document.getElementById('modalTambahBarangForm{{$penjualanAcara->uuid}}');
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
    $('#keterangan').val('');     // Reset select box keterangan
}

function openEditModal(id, nama_barang, qty, harga, keterangan) {
    $('#editItemId').val(id);
    $('#editBarang').val(nama_barang).trigger('change');
    $('#editQty').val(qty);
    $('#editHarga').val(harga);
    $('#editKeterangan').val(keterangan).trigger('change');
    
    var modalElement = document.getElementById('modalEditBarang');
    var modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
}

function updateItem() {
    var id = $('#editItemId').val();
    var barang = $('#editBarang').val();
    var harga = $('#editHarga').val();
    var qty = $('#editQty').val();
    var keterangan = $('#editKeterangan').val();
    var subtotal = harga * qty;

    if (barang == '' || harga == '' || qty == '' || keterangan == '') {
        alert('Data harus diisi semua!');
        return false;
    }

    $.ajax({
        url: "{{ route('penjualan-acara-update-detail', ['uuid' => $penjualanAcara->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            id: id,
            nama_barang: barang,
            harga: harga,
            qty: qty,
            keterangan: keterangan,
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
