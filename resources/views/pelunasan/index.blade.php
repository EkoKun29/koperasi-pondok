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
    <h1 class="text-xl font-semibold mb-4">Pelunasan</h1>
@if(isset($apiError))
    <div class="alert alert-warning">
        {{ $apiError }}
    </div>
@endif
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
                    <th class="border px-4 py-2">Tanggal Pelunasan</th>
                    <th class="border px-4 py-2">Nama Konsumen</th>
                    <th class="border px-4 py-2">Penyetor</th>
                    <th class="border px-4 py-2">Nota Penjualan Piutang</th>
                    <th class="border px-4 py-2">Tanggal Penjualan Piutang</th>
                    <th class="border px-4 py-2">Sisa Piutang Sebelumnya</th>
                    <th class="border px-4 py-2">Tunai</th>
                    <th class="border px-4 py-2">Transfer</th>
                    <th class="border px-4 py-2">Bank</th>
                    <th class="border px-4 py-2">Sisa Piutang Akhir</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pelunasan as $p)
                    <tr>
                        <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                        <td class="border px-4 py-2">{{ $p->no_nota }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y') }}</td>
                        <td class="border px-4 py-2">{{ $p->nama_konsumen }}</td>
                        <td class="border px-4 py-2">{{ $p->penyetor }}</td>
                        <td class="border px-4 py-2">{{ $p->nota_penjualan_piutang }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($p->tanggal_penjualan_piutang)->format('d-m-Y') }}</td>
                        <td class="border px-4 py-2">{{ $p->sisa_piutang_sebelumnya }}</td>
                        <td class="border px-4 py-2">{{ $p->tunai }}</td>
                        <td class="border px-4 py-2">{{ $p->transfer }}</td>
                        <td class="border px-4 py-2">{{ $p->bank }}</td>
                        <td class="border px-4 py-2">{{ $p->sisa_piutang_akhir }}</td>
                        <td class="border px-4 py-2">
                        <div class="d-flex">
                            <a href="javascript:void(0);" data-id="{{ $p['uuid'] }}" class="btn btn-primary btn-sm editButton">Edit</a>
                            <a href="{{ route('delete-pelunasan', $p['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"
                                value="Delete" class="btn btn-danger btn-sm">Hapus</a>
                            {{-- <a href="{{ route('pelunasan.print', $p['uuid']) }}" class="btn btn-secondary btn-sm">Print</a> --}}
                        </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="addPelunasanModal" tabindex="-1" role="dialog" aria-labelledby="addPelunasanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                                <label for="add_nama_personil" class="block text-sm font-medium text-gray-700"><b>Nama Personil</b></label>
                                <select id="add_nama_personil" name="nama_personil" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Personil</option>
                                    @foreach($data as $barang)
                                        <option value="{{ $barang->nama_personil }}">{{ $barang->nama_personil }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="add_nama_konsumen" class="block text-sm font-medium text-gray-700"><b>Nama Konsumen</b></label>
                                <select id="add_nama_konsumen" name="nama_konsumen" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Konsumen</option>
                                    @foreach($dataKonsumen as $konsumen)
                                        <option value="{{ $konsumen['konsumen'] }}">{{ $konsumen['konsumen'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="add_nota_penjualan_piutang" class="block text-sm font-medium text-gray-700"><b>Nota Penjualan Piutang</b></label>
                                <select id="add_nota_penjualan_piutang" name="nota_penjualan_piutang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Nota Penjualan</option>
                                    @foreach($dataKonsumen as $konsumen)
                                        <option value="{{ $konsumen['no_nota'] }}">{{ $konsumen['no_nota'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="add_tanggal_penjualan_piutang" class="block text-sm font-medium text-gray-700"><b>Tanggal Penjualan Piutang</b></label>
                                <select id="add_tanggal_penjualan_piutang" name="tanggal_penjualan_piutang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Tanggal Penjualan Piutang</option>
                                    @foreach($dataKonsumen as $konsumen)
                                        @if($konsumen['tanggal_valid'])
                                            @php
                                                $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $konsumen['tanggal']);
                                            @endphp
                                            <option value="{{ $tanggal->format('d-m-Y') }}">{{ $tanggal->format('Y-m-d') }}</option>
                                        @else
                                            <option disabled>{{ $konsumen['tanggal'] ?? 'Tanggal Tidak Valid' }} (Invalid)</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_sisa_piutang_sebelumnya" class="block text-sm font-medium text-gray-700"><b>Sisa Piutang Sebelumnya</b></label>
                                <input type="number" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="add_sisa_piutang_sebelumnya" name="sisa_piutang_sebelumnya" required readonly>
                            </div>

                            <div class="mb-3">
                                <label for="add_tunai" class="block text-sm font-medium text-gray-700"><b>Tunai</b></label>
                                <input type="number" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="add_tunai" name="tunai">
                            </div>

                            <div class="mb-3">
                                <label for="add_transfer" class="block text-sm font-medium text-gray-700"><b>Transfer</b></label>
                                <input type="number" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="add_transfer" name="transfer">
                            </div>

                            <div class="mb-3">
                                <label for="add_bank" class="block text-sm font-medium text-gray-700"><b>Bank</b></label>
                                <select name="bank" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="add_bank">
                                    <option disabled selected>Pilih Bank</option>
                                    <option value="BRI">BRI</option>
                                    <option value="BNI">BNI</option>
                                    <option value="MANDIRI">MANDIRI</option>
                                    <option value="BANK JATENG">BANK JATENG</option>
                                </select>
                            </div>
                        </div>
                        </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal Structure -->
<div class="modal fade" id="editPelunasanModal" tabindex="-1" role="dialog" aria-labelledby="editPelunasanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPelunasanModalLabel">Edit Pelunasan</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPelunasanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nama_personil" class="block text-sm font-medium text-gray-700"><b>Nama Personil</b></label>
                                <select id="edit_nama_personil" name="nama_personil" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Personil</option>
                                    @foreach($data as $barang)
                                        <option value="{{ $barang->nama_personil }}">{{ $barang->nama_personil }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_nama_konsumen" class="block text-sm font-medium text-gray-700"><b>Nama Konsumen</b></label>
                                <select id="edit_nama_konsumen" name="nama_konsumen" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Konsumen</option>
                                    @foreach($dataKonsumen as $konsumen)
                                        <option value="{{ $konsumen['konsumen'] }}">{{ $konsumen['konsumen'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_nota_penjualan_piutang" class="block text-sm font-medium text-gray-700"><b>Nota Penjualan Piutang</b></label>
                                <select id="edit_nota_penjualan_piutang" name="nota_penjualan_piutang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Nota Penjualan</option>
                                    @foreach($dataKonsumen as $konsumen)
                                        <option value="{{ $konsumen['no_nota'] }}">{{ $konsumen['no_nota'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_tanggal_penjualan_piutang" class="block text-sm font-medium text-gray-700"><b>Tanggal Penjualan Piutang</b></label>
                                <select id="edit_tanggal_penjualan_piutang" name="tanggal_penjualan_piutang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled selected>Pilih Tanggal Penjualan Piutang</option>
                                    @foreach($dataKonsumen as $konsumen)
                                        @if($konsumen['tanggal_valid'])
                                            @php
                                                $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', $konsumen['tanggal']);
                                            @endphp
                                            <option value="{{ $tanggal->format('d-m-Y') }}">{{ $tanggal->format('Y-m-d') }}</option>
                                        @else
                                            <option disabled>{{ $konsumen['tanggal'] ?? 'Tanggal Tidak Valid' }} (Invalid)</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_sisa_piutang_sebelumnya" class="block text-sm font-medium text-gray-700"><b>Sisa Piutang Sebelumnya</b></label>
                                <input type="number" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="edit_sisa_piutang_sebelumnya" name="sisa_piutang_sebelumnya" required readonly>
                            </div>

                            <div class="mb-3">
                                <label for="edit_tunai" class="block text-sm font-medium text-gray-700"><b>Tunai</b></label>
                                <input type="number" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="edit_tunai" name="tunai">
                            </div>

                            <div class="mb-3">
                                <label for="edit_transfer" class="block text-sm font-medium text-gray-700"><b>Transfer</b></label>
                                <input type="number" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="edit_transfer" name="transfer">
                            </div>

                            <div class="mb-3">
                                <label for="edit_bank" class="block text-sm font-medium text-gray-700"><b>Bank</b></label>
                                <select name="bank" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="edit_bank">
                                    <option selected value="">Pilih Bank</option>
                                    <option value="BRI">BRI</option>
                                    <option value="BNI">BNI</option>
                                    <option value="MANDIRI">MANDIRI</option>
                                    <option value="BANK JATENG">BANK JATENG</option>
                                </select>
                            </div>
                        </div>
                    </div>
                   <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
@push('js')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable jika diperlukan
        if ($.fn.DataTable.isDataTable('#datatable-basic')) {
            $('#datatable-basic').DataTable().destroy();
        }

        $('#datatable-basic').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
            }
        });

        // Trigger Edit Modal with pre-filled data
        $('.editButton').on('click', function() {
            var uuid = $(this).data('id');
            var url = "{{ route('pelunasan.update', ':uuid') }}"; // Prepare the route
            url = url.replace(':uuid', uuid); // Replace placeholder with actual UUID

            $('#editPelunasanForm').attr('action', url); // Set the action attribute of the form

            // AJAX call to get the pelunasan details by UUID
            $.ajax({
                url: '/pelunasan/' + uuid + '/edit', // Your route for fetching pelunasan details
                method: 'GET',
                success: function(data) {
                    // Helper function to format date
                    function formatDate(dateString) {
                        var parts = dateString.split('-'); // Split the date string by hyphen
                        return parts[2] + '-' + parts[1] + '-' + parts[0]; // Rearrange to DD-MM-YYYY
                    }

                    // Populate modal fields with fetched data
                    $('#edit_nama_personil').val(data.penyetor).trigger('change');
                    $('#edit_nama_konsumen').val(data.nama_konsumen).trigger('change');
                    $('#edit_nota_penjualan_piutang').val(data.nota_penjualan_piutang).trigger('change');
                    
                    // Format tanggal_penjualan_piutang from 'YYYY-MM-DD' to 'DD-MM-YYYY'
                    var formattedDate = formatDate(data.tanggal_penjualan_piutang);
                    $('#edit_tanggal_penjualan_piutang').val(formattedDate).trigger('change');

                    $('#edit_sisa_piutang_sebelumnya').val(data.sisa_piutang_sebelumnya);
                    $('#edit_tunai').val(data.tunai);
                    $('#edit_transfer').val(data.transfer);
                    $('#edit_bank').val(data.bank).trigger('change');

                    // Update form action to include the pelunasan UUID for update
                    $('#editPelunasanForm').attr('action', '/pelunasan/' + uuid);
                    
                    // Show the modal
                    $('#editPelunasanModal').modal('show');
                },
                error: function() {
                    alert('Gagal mengambil data pelunasan.');
                }
            });
        });


        // Initialize Select2 for the edit modal
        $('#edit_nama_personil, #edit_nama_konsumen, #edit_nota_penjualan_piutang, #edit_tanggal_penjualan_piutang, #edit_bank').select2({
            dropdownParent: $('#editPelunasanModal')
        });

        // Fetch sisa_piutang when fields in add modal are selected
        $('#add_nama_konsumen, #add_tanggal_penjualan_piutang, #add_nota_penjualan_piutang').on('change', function() {
            let konsumen = $('#add_nama_konsumen').val();
            let tanggal = $('#add_tanggal_penjualan_piutang').val();
            let no_nota = $('#add_nota_penjualan_piutang').val();

            // Ensure all fields are filled
            if (konsumen && tanggal && no_nota) {
                $.ajax({
                    url: '/pelunasan-sisa', // Replace with the correct route
                    method: 'GET',
                    data: {
                        konsumen: konsumen,
                        tanggal: tanggal,
                        no_nota: no_nota
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.sisa_piutang) {
                            $('#add_sisa_piutang_sebelumnya').val(data.sisa_piutang);
                        } else {
                            $('#add_sisa_piutang_sebelumnya').val(0); // Set to 0 if none exists
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil data sisa piutang.');
                    }
                });
            }
        });

        // Initialize Select2 for the add modal
        $("#add_nama_personil, #add_nama_konsumen, #add_tanggal_penjualan_piutang, #add_nota_penjualan_piutang").select2({
            dropdownParent: $("#addPelunasanModal")
        });
    });
</script>
@endpush

