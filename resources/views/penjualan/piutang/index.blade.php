@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Penjualan Piutang</h1>
    <div class="mx-4">
        <a style="text-decoration:none;" class="inline-block w-3   px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" href="{{ route('penjualan-piutang.create') }}">Tambah Data</a>
      </div>
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">Nota</th>
                    <th class="border px-4 py-2">Nama Koperasi</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Nama Personil</th>
                    <th class="border px-4 py-2">Shift</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Total</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($piutang as $piu)
                <tr>
                    <td class="border px-4 py-2">{{ $piu->no_nota }}</td>
                    <td class="border px-4 py-2">{{ $piu->nama_koperasi }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($piu->created_at)->format('d-m-Y') }}</td>
                    <td class="border px-4 py-2">{{ $piu->nama_personil }}</td>
                    <td class="border px-4 py-2">{{ $piu->shift }}</td>
                    <td class="border px-4 py-2">{{ number_format($piu->total,2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                              <a href="#"
                                class="btn btn-info btn-sm">Detail</a>
                             <a href="{{ route('delete-penjualan-piutang', $piu['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data {{ $piu->no_nota }} Ini ??')"
                                value="Delete" class="btn btn-danger btn-sm">Hapus</a>
                              <a href="#"
                                class="btn btn-secondary btn-sm">Print</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
