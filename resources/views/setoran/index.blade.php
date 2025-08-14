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
    <h1 class="text-xl font-semibold mb-4">Setoran</h1>
    <div class="mx-4">
        <a href="javascript:;" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#addSetoranModal" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102">
            Tambah Data
        </a>
      </div>
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama Koperasi</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Penyetor</th>
                    <th class="border px-4 py-2">Penerima</th>
                    <th class="border px-4 py-2">Nominal</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($setoran as $s)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $s->nama_koperasi }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($s->tanggal)->format('d-m-Y') }}</td>
                    <td class="border px-4 py-2">{{ $s->penyetor }}</td>
                    <td class="border px-4 py-2">{{ $s->penerima }}</td>
                    <td class="border px-4 py-2">{{ number_format($s->nominal,2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                             <a href="{{ route('delete-setoran', $s['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"
                                value="Delete" class="btn btn-danger btn-sm ml-2">Hapus</a>
                                <a href="javascript:void(0);" data-id="{{ $s['uuid'] }}" class="btn btn-primary btn-sm editButton ml-2">Edit</a>
                              <a href="{{ route('setoran.print', $s['uuid']) }}"
                                class="btn btn-secondary btn-sm ml-2">Print</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="addSetoranModal" tabindex="-1" role="dialog" aria-labelledby="addSetoranModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addSetoranModalLabel">Tambah Setoran</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="addSetoranForm" method="POST" action="{{ route('setoran.store') }}">
            @csrf
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700"><b>Nama Penyetor</b></label>
                <select id="penyetor" name="penyetor" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                  <option disabled selected>Pilih Personil</option>
                  @foreach($data as $barang)
                    <option value="{{ $barang->nama_personil }}">{{ $barang->nama_personil }}</option>
                  @endforeach
                </select>
              </div>
            <div class="mb-3">
              <label for="penerima" class="form-label">Penerima</label>
              <input type="text" class="form-control" id="penerima" name="penerima">
            </div>
            <div class="mb-3">
              <label for="tanggal" class="form-label">Tanggal</label>
              <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
              <label for="nominal" class="form-label">Nominal</label>
              <input type="number" class="form-control" id="nominal" name="nominal" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endsection

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Setoran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="editTanggal" class="block text-sm font-medium text-gray-700">
                            <b>Tanggal</b>
                        </label>
                        <input type="date" id="editTanggal" name="tanggal" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    
                    <!-- Nama Personil Dropdown -->
                    <div class="mb-4">
                        <label for="editPenyetor" class="block text-sm font-medium text-gray-700">
                            <b>Nama Personil</b>
                        </label>
                        <select id="editPenyetor" name="penyetor" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Personil</option>
                            @foreach($data as $barang)
                                <option value="{{ $barang->nama_personil }}">{{ $barang->nama_personil }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="editPenerima" class="block text-sm font-medium text-gray-700">
                            <b>Nama Penerima</b>
                        </label>
                        <input type="text" id="editPenerima" name="penerima" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" placeholder="Masukkan Nama Penerima" required>
                    </div>
                    
                    <!-- Total -->
                    <div class="mb-4">
                        <label for="editNominal" class="block text-sm font-medium text-gray-700">
                            <b>Nominal</b>
                        </label>
                        <input type="number" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="editNominal" name="nominal" step="0.01" required>
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
    function initializeDataTable() {
        // Destroy any existing DataTable instance
        if ($.fn.DataTable.isDataTable('#datatable-basic')) {
            $('#datatable-basic').DataTable().destroy();
        }

        // Reinitialize DataTable
        $('#datatable-basic').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
            }
        });
    }

    $("#penyetor").select2({
    dropdownParent: $("#addSetoranModal")
    });

    // Initialize the DataTable when the page is loaded
    initializeDataTable();

    // After adding new data, reinitialize the DataTable
    $('#form-add-data').on('submit', function(e) {
        e.preventDefault();

        // Assuming you are adding data via AJAX
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Assuming you add new row dynamically here

                // Reinitialize the DataTable to reflect the new data
                initializeDataTable();
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    });

    $('.editButton').on('click', function() {
    var uuid = $(this).data('id');

    // Send AJAX request to get data for the selected item
    $.ajax({
        url: '/setoran/' + uuid + '/edit',
        type: 'GET',
        success: function(response) {
            // Populate modal fields with the fetched data
            $('#editTanggal').val(response.tanggal); // Update input field for Nama Pembeli
            $('#editPenyetor').val(response.penyetor);
            $('#editPenerima').val(response.penerima);
            $('#editNominal').val(response.nominal);

            // Set form action to update the data
            $('#editForm').attr('action', '/setoran/' + uuid);

            // Show modal
            $('#editModal').modal('show');

            $("#editPenyetor").select2({
                dropdownParent: $('#editModal')
            });
        }
    });
});
});

</script>
@endpush
