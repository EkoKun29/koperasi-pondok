@extends('layouts.app')

@section('content')
<div id="content">
<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="true">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
          <nav>
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
              <li class="text-sm leading-normal">
                <a class="opacity-50 text-slate-700" style="text-decoration: none;" href="javascript:;">Pages</a>
              </li>
              <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/'] font-bold capitalize" aria-current="page">Pembelian Global</li>
            </ol>
          </nav>

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
    <h1 class="text-xl font-semibold mb-4">Pembelian Global</h1>
    <div class="mx-4">
        <a style="text-decoration:none;" class="inline-block w-3   px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" href="{{ route('pembelian-new.create') }}">Tambah Data</a>
      </div>
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">Tgl Pembelian</th>
                    <th class="border px-4 py-2">Tgl Input</th>
                    <th class="border px-4 py-2">No Nota</th>
                    <th class="border px-4 py-2">Nama Supplier</th>
                    <th class="border px-4 py-2">Nama Personil</th>
                    <th class="border px-4 py-2">Ket. Pembayaran</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian as $trj)
                <tr>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($trj->tanggal)->format('d-m-Y') }}
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($trj->created_at)->format('d-m-Y') }}
                    <td class="border px-4 py-2">{{ $trj->nota}}</td>
                    <td class="border px-4 py-2">{{ $trj->nama_supplier }}</td>
                    <td class="border px-4 py-2">{{ $trj->nama_personil }}</td>
                    <td class="border px-4 py-2">{{ $trj->ket_pembayaran }}</td>
                    <td class="border px-4 py-2">{{ number_format($trj->total,2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                          
                              <a href="{{ route('pembelian-new.detail', $trj['uuid']) }}"
                                class="btn btn-info btn-sm ml-2">Detail</a>

                              <a href="javascript:void(0);" data-id="{{ $trj['uuid'] }}" class="btn btn-primary btn-sm ml-2 editButton">Edit</a>

                              <a href="{{ route('pembelian-new.print', $trj['uuid']) }}"
                                class="btn btn-secondary btn-sm ml-2">Print</a>

                              <a href="{{ route('delete-pembelian-new', $trj['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data {{ $trj->no_nota }} Ini ??')"
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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Pembelian</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                                <label for="nota" class="form-label">Nota</label>
                                <input type="text" id="nota" name="nota" class="form-control" placeholder="Nota"readonly disabled>
                            </div>
                            <div class="mb-4">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" name="tanggal" class="form-control" placeholder="Tanggal" required>
                            </div>
                            <div class="mb-4">
                                <label for="nama_supplier" class="block text-sm font-medium text-gray-700"><b>Nama Supplier</b></label>
                                <select id="nama_supplier" name="nama_supplier" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled>Pilih Supplier</option>
                                    @foreach($db as $dbm)
                                        <option value="{{ $dbm->nama_supplier }}">
                                            {{ $dbm->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="nama_personil" class="block text-sm font-medium text-gray-700"><b>Masuk Ke-</b></label>
                                <select id="nama_personil" name="nama_personil" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled>Pilih Masuk Ke-</option>
                                    @foreach($data as $dbm)
                                        <option value="{{ $dbm->nama_personil }}">
                                            {{ $dbm->nama_personil }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                            <label for="ket_pembayaran" class="block text-sm font-medium text-gray-700"><b>Keterangan Pembayaran</b></label>
                            <select id="ket_pembayaran" name="ket_pembayaran" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                                <option disabled>Pilih Keterangan Pembayaran</option>
                                <option value="Tunai">Tunai</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                            </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
 $(document).ready(function() {
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

$('.editButton').on('click', function() {
    var uuid = $(this).data('id');

    // Send AJAX request to get data for the selected item
    $.ajax({
        url: '/pembelian-new/' + uuid + '/edit',
        type: 'GET',
        success: function(response) {
            // Populate modal fields with the fetched data
            let date = new Date(response.tanggal);
            let formattedDate = date.toISOString().split('T')[0];

            $('#tanggal').val(formattedDate);
            $('#nama_supplier').val(response.nama_supplier);
            $('#nama_personil').val(response.nama_personil);
            $('#ket_pembayaran').val(response.ket_pembayaran);

            // Set form action to update the data
            $('#editForm').attr('action', '/pembelian-new/' + uuid);

            // Show modal
            $('#editModal').modal('show');

            $("#nama_personil").select2({
                dropdownParent: $('#editModal')
            });

             $("#nama_supplier").select2({
                dropdownParent: $('#editModal')
            });
        }
    });
});

});

</script>
@endpush
