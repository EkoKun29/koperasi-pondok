@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Detail Barang</h1>
    {{-- <div class="mx-4">
        <a style="text-decoration:none;" class="inline-block w-3   px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" href="{{ route('penjualan-piutang.create') }}">Tambah Data</a>
      </div> --}}
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama Barang</th>
                    <th class="border px-4 py-2">Qty</th>
                    <th class="border px-4 py-2">Harga</th>
                    <th class="border px-4 py-2">Keterangan</th>
                    <th class="border px-4 py-2">Total Harga</th>
                    <th class="border px-4 py-2">Aksi</th>
                    
                </tr>
            </thead>
            <tbody>
            <form id="createpenjualanTransfer" action="#" method="POST">                                   
                @csrf
                @method('PUT')
                @foreach($detail as $dtl)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-2">{{ $dtl->nama_barang }}</td>
                    <td class="border px-4 py-2">{{ $dtl->qty }}</td>
                    <td class="border px-4 py-2">{{ $dtl->harga }}</td>
                    <td class="border px-4 py-2">{{ $dtl->keterangan }}</td>
                    <td class="border px-4 py-2">{{ number_format($dtl->subtotal,2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                             <a href="{{ route('delete-penjualan-nonproduksi-detail', $dtl['id']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Barang {{ $dtl->nama_barang }} ??')"
                                value="Delete" class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </form>
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
