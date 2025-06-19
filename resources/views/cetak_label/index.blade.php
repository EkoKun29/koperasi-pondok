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

    <br>
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            @if(session('syncing'))
                <div id="loading-alert" style="
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background-color: #d1d5db;
                    color: #111827;
                    padding: 16px 24px;
                    border-radius: 8px;
                    font-weight: bold;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                    z-index: 9999;">
                    ⏳ Sedang menyinkronkan data...
                </div>
            @endif



            {{-- <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success" id="myAlert">
                        {{ session('success') }}
                    </div>
                @endif
            </div> --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title"><b>DATA LABEL </b></h2>
                            <div class="w-full md:w-1/2 px-2">
                                <div id="modalTambahBarang" class="mb-4">
                                    <div class="flex items-center gap-2">
                                        <select id="tanggal" class="form-input block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                                            <option disabled selected>Pilih Tanggal</option>
                                            @foreach($unique as $label)
                                                <option value="{{ $label->tanggal }}">{{ $label->tanggal }}</option>
                                            @endforeach
                                        </select>

                                        <button id="printButton" class="btn btn-secondary">Print</button>
                                    </div>
                                </div>
                            </div>
                            {{-- <div style="float: right">
                                <a href="{{ route('cetak-label.sync') }}" class="btn btn-primary">Sinkronisasi Data</a>
                            </div>
                            <br> --}}
                        </div>
                        <div class="table-responsive">
                                <table id="datatable-basic" class="table-auto border-collapse w-full">
                                    <thead>
                                        <tr class="text-left bg-gray-200">
                                            <th class="border px-4 py-2">#</th>
                                            <th class="border px-4 py-2">Tanggal</th>
                                            <th class="border px-4 py-2">Label</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($labels as $l)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($l->tanggal)->format('d-m-Y') }}</td>
                                            <td class="border px-4 py-2">{{ $l->label }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                      
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </section>

    </div>
@endsection

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
        });
        $(document).ready(function() {
            $("#tanggal").select2({
            dropdownParent: $("#modalTambahBarang")
            });
            $('#modalTambahBarang').on('hidden.bs.modal', function () {
                $('#createPenjualanPiutang')[0].reset();
            });                                 
        });

        setTimeout(() => {
        const alert = document.getElementById("loading-alert");
        if (alert) alert.remove();
    }, 2000); // hilang setelah 2 detik


document.getElementById('printButton').addEventListener('click', function () {
        const tanggal = document.getElementById('tanggal').value;
        if (!tanggal) {
            alert('Pilih tanggal dulu!');
            return;
        }

        // Arahkan ke URL cetak dalam halaman yang sama
        window.location.href = `/cetak-label/print?tanggal=${tanggal}`;
    });
</script>
@endpush