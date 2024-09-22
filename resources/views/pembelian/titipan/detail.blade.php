@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Detail Barang</h1>
    
    <div class="mx-4" id="modalTambahBarang">
        <a id="createPenjualanNonProduksi" style="text-decoration:none;" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" 
           data-bs-toggle="modal" data-bs-target="#modalTambahBarangForm{{$titipan->uuid}}">Tambah Data</a>
    </div>

    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Qty</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Sisa Siang</th>
                    <th class="border px-4 py-2">Sisa Sore</th>
                    <th class="border px-4 py-2">Sisa Malam</th>
                    <th class="border px-4 py-2">Sisa Akhir</th>
                    <th class="border px-4 py-2">Total Harga</th>
                    <th class="border px-4 py-2">Total Sisa</th>
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
                    <td class="border px-4 py-2">{{ $dtl->sisa_siang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->sisa_sore }}</td>
                    <td class="border px-4 py-2">{{ $dtl->sisa_malam }}</td>
                    <td class="border px-4 py-2">{{ $dtl->sisa_akhir }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal, 2) }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal_sisa, 2) }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('delete-pembelian-titipan-detail', $dtl['id']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang {{ $dtl->nama_barang }} ??')"
                           class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahBarangForm{{$titipan->uuid}}" class="modal fade" tabindex="-1">
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
                        <select id="barang" name="barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
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
                        <label for="sisa_siang">Sisa Siang</label>
                        <input type="number" id="sisa_siang" name="sisa_siang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" value="0">
                    </div>
                    <div class="mb-4">
                        <label for="sisa_sore">Sisa Sore</label>
                        <input type="number" id="sisa_sore" name="sisa_sore" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" value="0">
                    </div>
                    <div class="mb-4">
                        <label for="sisa_malam">Sisa Malam</label>
                        <input type="number" id="sisa_malam" name="sisa_malam" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" value="0">
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
    var barang = $('#barang').val();
    var harga = $('#harga').val();
    var qty = $('#qty').val();
    var sisa_siang = parseInt($('#sisa_siang').val()) || 0;
    var sisa_sore = parseInt($('#sisa_sore').val()) || 0;
    var sisa_malam = parseInt($('#sisa_malam').val()) || 0;

    if (barang === '' || harga === '' || qty === '') {
        alert('Data harus diisi semua!');
        return false;
    }

    var sisa_akhir = qty - sisa_siang - sisa_sore - sisa_malam;
    var subtotal_sisa = sisa_akhir * harga;
    var subtotal = harga * qty;

    $.ajax({
        url: "{{ route('pembelian-titipan-detail-create', ['uuid' => $titipan->uuid]) }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            nama_barang: barang,
            harga: harga,
            qty: qty,
            sisa_siang: sisa_siang,
            sisa_sore: sisa_sore,
            sisa_malam: sisa_malam,
            sisa_akhir: sisa_akhir,
            subtotal_sisa: subtotal_sisa,
            subtotal: subtotal
        },
        success: function(response) {
            if (response.success) {
                var rowCount = $('#datatable-basic tbody tr').length + 1;
                $('#datatable-basic tbody').append(`
                    <tr>
                        <td class="border px-4 py-2">${rowCount}</td>
                        <td class="border px-4 py-2">${response.detail.nama_barang}</td>
                        <td class="border px-4 py-2">${response.detail.qty}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.harga).toFixed(2)}</td>
                        <td class="border px-4 py-2">${response.detail.sisa_siang}</td>
                        <td class="border px-4 py-2">${response.detail.sisa_sore}</td>
                        <td class="border px-4 py-2">${response.detail.sisa_malam}</td>
                        <td class="border px-4 py-2">${response.detail.sisa_akhir}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.subtotal).toFixed(2)}</td>
                        <td class="border px-4 py-2">${parseFloat(response.detail.subtotal_sisa).toFixed(2)}</td>
                        <td class="border px-4 py-2">
                            <a href="/your-delete-route/${response.detail.id}" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                `);

                resetModalForm();

                var modalElement = document.getElementById('modalTambahBarangForm{{$titipan->uuid}}');
                var modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance.hide();
            } else {
                alert('Gagal menambahkan barang! ' + response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Gagal menambahkan barang! ' + (xhr.responseJSON.message || 'Silakan coba lagi.'));
        }
    });
}

function resetModalForm() {
    $('#barang').val('');         
    $('#harga').val('');          
    $('#qty').val('');            
    $('#sisa_siang').val(0);     
    $('#sisa_sore').val(0);       
    $('#sisa_malam').val(0);      
}
</script>
@endpush
