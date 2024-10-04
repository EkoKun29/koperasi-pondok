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
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success" id="myAlert">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title"><b>DATA BUKU PIUTANG </b></h2>
                            <div style="float: right">
                                <a href="{{ route('buku-piutang.sync') }}" class="btn btn-primary">Sinkronisasi Data</a>
                            </div>
                            <br>
                        </div>
                         <div class="table-responsive">
                                <table id="datatable-basic" class="table-auto border-collapse w-full">
                                    <thead>
                                        <tr class="text-left bg-gray-200">
                                            <th class="border px-4 py-2">#</th>
                                            <th class="border px-4 py-2">Konsumen</th>
                                            <th class="border px-4 py-2">Tanggal</th>
                                            <th class="border px-4 py-2">NO Nota</th>
                                            <th class="border px-4 py-2">Sisa Piutang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barangs as $barang)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="border px-4 py-2">{{ $barang->konsumen }}</td>
                                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($barang->tanggal)->format('d/m/Y') }}</td>
                                            <td class="border px-4 py-2">{{ $barang->no_nota }}</td>
                                            <td class="border px-4 py-2">{{ $barang->sisa_piutang }}</td>
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

</script>
@endpush