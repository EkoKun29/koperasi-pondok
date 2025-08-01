@extends('layouts.app')

@section('content')
<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="true">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
        <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                <li class="flex items-center pl-4 xl:hidden">
                    <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand text-slate-500" sidenav-trigger>
                        <div class="w-4.5 overflow-hidden">
                            <i class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                            <i class="ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                            <i class="ease-soft relative block h-0.5 rounded-sm bg-slate-500 transition-all"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Retur Pembelian</h1>
    <div class="mx-4">
        <a style="text-decoration:none;" class="inline-block w-3   px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" href="{{ route('retur-pembelian.create') }}">Tambah Data</a>
    </div>
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">Nota Retur</th>
                    <th class="border px-4 py-2">Tgl Retur</th>
                    <th class="border px-4 py-2">Personil</th>
                    <th class="border px-4 py-2">Nota Barang Masuk</th>
                    <th class="border px-4 py-2">Tgl Barang Masuk</th>
                    <th class="border px-4 py-2">Nama Supplier</th>
                    <th class="border px-4 py-2">Aksi</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($retur as $ret)
                <tr>
                    <td class="border px-4 py-2">{{ $ret->nota_retur }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($ret->tanggal)->format('d-m-Y') }}</td>
                    <td class="border px-4 py-2">{{ $ret->nama_personil }}</td>
                    <td class="border px-4 py-2">{{ $ret->nota_barang_masuk }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($ret->tgl_barang_masuk)->format('d-m-Y') }}</td>
                    <td class="border px-4 py-2">{{ $ret->nama_supplier }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                            <a href="{{ route('retur-pembelian.detail', $ret['uuid']) }}"
                                class="btn btn-info btn-sm ml-2">Detail</a>
                            <a href="javascript:void(0);" data-id="{{ $ret['uuid'] }}" class="btn btn-primary btn-sm ml-2 editButton">Edit</a>
                            <a href="{{ route('retur-pembelian.print', $ret['uuid']) }}"
                                class="btn btn-secondary btn-sm ml-2">Print</a>
                            <a href="{{ route('delete-retur-pembelian', $ret['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data {{ $ret->nota_retur }} Ini ??')"
                                value="Delete" class="btn btn-danger btn-sm ml-2">Hapus</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Retur Pembelian</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="flex flex-wrap -mx-2">
                    <div class="w-full md:w-1/2 px-2">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Tanggal Retur</b></label>
                            <input type="date" id="tanggal" name="tanggal" placeholder="Tanggal"
                                class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                        </div>

                        <div class="mb-4">
                            <label for="nama_personil" class="block text-sm font-medium text-gray-700">
                                <b>Nama Personil</b>
                            </label>
                            <select id="nama_personil" class="nama-personil" name="nama_personil" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                <option disabled selected>Pilih Personil</option>
                                @foreach($data as $barang)
                                    <option value="{{ $barang->nama_personil }}">{{ $barang->nama_personil }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="nama_supplier" class="block text-sm font-medium text-gray-700">
                                <b>Supplier</b>
                            </label>
                            <input type="text" id="nama_supplier" name="nama_supplier"
                                class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg"
                                placeholder="Masukkan Nama Supplier" required>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="w-full md:w-1/2 px-2">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Nota Barang Masuk</b></label>
                            <select id="nota_barang_masuk" class="nota-penjualan" name="nota_barang_masuk" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                <option disabled selected>Pilih Nota Barang Masuk</option>
                                @foreach($dataNoNota as $no_nota)
                                    <option value="{{ $no_nota }}">{{ $no_nota }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Tanggal Barang Masuk</b></label>
                            <input type="date" id="tgl_barang_masuk" name="tgl_barang_masuk" placeholder="Tanggal Penjualan"
                                class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                        </div>
                    
                    </div>
                </div>

                 <br>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
                    
                   
                </form>
            </div>
        </div>
    </div>
</div>



@push('js')
<script>
 $(document).ready(function() {
    // Initialize DataTable if not already
    if ($.fn.DataTable.isDataTable('#datatable-basic')) {
        $('#datatable-basic').DataTable().destroy();
    }
    
    
    // DataTable Initialization
    $('#datatable-basic').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
        }
    });

    // Handle Edit Button Click
$('.editButton').on('click', function() {
    var uuid = $(this).data('id');

    // Send AJAX request to get data for the selected item
    $.ajax({
        url: '/retur-pembelian/' + uuid + '/edit',
        type: 'GET',
        success: function(response) {
            // Populate modal fields with the fetched data
            $('#nama_supplier').val(response.nama_supplier); // Update input field for Nama Konsumen
            $('#nama_personil').val(response.nama_personil);
            $('#tanggal').val(response.tanggal);
            $('#nota_barang_masuk').val(response.nota_barang_masuk);
            $('#tgl_barang_masuk').val(response.tgl_barang_masuk);

            $('#editTotal').val(response.total);

            // Set form action to update the data
            $('#editForm').attr('action', '/retur-pembelian/' + uuid);

            // Show modal
            $('#editModal').modal('show');

            $('.modal').on('shown.bs.modal', function () {
        const $modal = $(this);

        // Inisialisasi Select2 dalam modal yang aktif
        $modal.find('.nota-penjualan').select2({
            dropdownParent: $('#editModal'),
            placeholder: 'Pilih Nota Penjualan',
            allowClear: true,
            width: '100%'
        });

        $modal.find('.nama-personil').select2({
            dropdownParent: $('#editModal'),
            placeholder: 'Pilih Nama Personil',
            allowClear: true,
            width: '100%'
        });
    });

            $("#nama_personil").select2({
                dropdownParent: $('#editModal')
            });
            
            $("#nota_barang_masuk").select2({
                dropdownParent: $('#editModal')
            });
        }
    });
});

});
</script>
@endpush
