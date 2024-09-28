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
    <h1 class="text-xl font-semibold mb-4">Pengajuan PO</h1>
    <div class="mx-4">
        <a href="javascript:;" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#addPelunasanModal" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102">
            Tambah Data
        </a>
      </div>
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">No Nota</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Pengaju</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>
{{-- 
<!-- Modal Structure -->
<div class="modal fade" id="addPelunasanModal" tabindex="-1" role="dialog" aria-labelledby="addPelunasanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Make the modal larger -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPelunasanModalLabel">Tambah Pelunasan</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addPelunasanForm" method="POST" action="{{ route('pelunasan.store') }}">
                    @csrf
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_konsumen" class="form-label">Nama Konsumen</label>
                                <input type="text" class="form-control" id="nama_konsumen" name="nama_konsumen" required>
                            </div>
                            <div class="mb-3">
                                <label for="penyetor" class="form-label">Penyetor</label>
                                <select id="penyetor" name="penyetor" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                                    <option disabled selected>Pilih Personil</option>
                                    @foreach($data as $barang)
                                        <option value="{{ $barang->nama_personil }}">{{ $barang->nama_personil }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="nota_penjualan_piutang" class="form-label">Nota Penjualan Piutang</label>
                                <input type="text" class="form-control" id="nota_penjualan_piutang" name="nota_penjualan_piutang" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_penjualan_piutang" class="form-label">Tanggal Penjualan Piutang</label>
                                <input type="date" class="form-control" id="tanggal_penjualan_piutang" name="tanggal_penjualan_piutang" required>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">      
                            <div class="mb-3">
                                <label for="sisa_piutang_sebelumnya" class="form-label">Sisa Piutang Sebelumnya</label>
                                <input type="number" class="form-control" id="sisa_piutang_sebelumnya" name="sisa_piutang_sebelumnya" required>
                            </div>
                            <div class="mb-3">
                                <label for="cicilan" class="form-label">Cicilan</label>
                                <input type="number" class="form-control" id="cicilan" name="cicilan">
                            </div>
                            <div class="mb-3">
                                <label for="tunai" class="form-label">Tunai</label>
                                <input type="number" class="form-control" id="tunai" name="tunai">
                            </div>
                            <div class="mb-3">
                                <label for="bank" class="form-label">Bank</label>
                                <input type="text" class="form-control" id="bank" name="bank" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
  @endsection

@push('js')
<script>

    $(document).ready(function() {
        function initializeDataTable() {
            // Destroy any existing DataTable instance
            if ($.fn.DataTable.isDataTable('#datatable-basic')) {
                $('#datatable-basic').DataTable().destroy();
            }
            // $("#penyetor").select2({
            // dropdownParent: $("#addPelunasanForm")
            // });
            // Reinitialize DataTable
            $('#datatable-basic').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
                }
            });
        }
    
        // Initialize the DataTable when the page is loaded
        initializeDataTable();
    
        // After adding new data, reinitialize the DataTable
        // $('#form-add-data').on('submit', function(e) {
        //     e.preventDefault();
    
        //     // Assuming you are adding data via AJAX
        //     $.ajax({
        //         url: $(this).attr('action'),
        //         method: 'POST',
        //         data: $(this).serialize(),
        //         success: function(response) {
        //             // Assuming you add new row dynamically here
    
        //             // Reinitialize the DataTable to reflect the new data
        //             initializeDataTable();
        //         },
        //         error: function(xhr) {
        //             console.log('Error:', xhr.responseText);
        //         }
        //     });
        // });
    });
    
    </script>
    
@endpush
