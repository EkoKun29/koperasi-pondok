@extends('layouts.app')

@section('content')
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
                            <h2 class="card-title"><b>DATA BARANG</b></h2>
                            <div style="float: right">
                                <a href="{{ route('barang.sync') }}" class="btn btn-primary">Sinkronisasi Data</a>
                            </div>
                            <br>
                        </div>
                         <div class="table-responsive">
                                <table id="datatable-basic" class="table-auto border-collapse w-full">
                                    <thead>
                                        <tr class="text-left bg-gray-200">
                                            <th class="border px-4 py-2">#</th>
                                            <th class="border px-4 py-2">Nama Barang</th>
                                            <th class="border px-4 py-2">Nama Personil</th>
                                            <th class="border px-4 py-2">Nama Penitip</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barangs as $barang)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="border px-4 py-2">{{ $barang->nama_barang }}</td>
                                            <td class="border px-4 py-2">{{ $barang->nama_personil }}</td>
                                            <td class="border px-4 py-2">{{ $barang->nama_penitip }}</td>
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