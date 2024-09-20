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
    <h1 class="text-xl font-semibold mb-4">Pembelian Hutang Non Produksi</h1>
    <div class="mx-4">
        <a style="text-decoration:none;" class="inline-block w-3   px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" href="{{ route('pembelian-hutangnonproduksi.create') }}">Tambah Data</a>
      </div>
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">Nota</th>
                    <th class="border px-4 py-2">Nama Koperasi</th>
                    <th class="border px-4 py-2">Tanggal</th>
                    <th class="border px-4 py-2">Nama Supplier</th>
                    <th class="border px-4 py-2">Tanggal Jatuh Tempo</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hutangnonproduksi as $hnp)
                <tr>
                    <td class="border px-4 py-2">{{ $hnp->no_nota }}</td>
                    <td class="border px-4 py-2">{{ $hnp->nama_koperasi }}</td>
                    <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($hnp->created_at)->format('d-m-Y') }}</td>
                    <td class="border px-4 py-2">{{ $hnp->nama_supplier }}</td>
                    <td class="border px-4 py-2">{{ $hnp->tanggal_jatuh_tempo }}</td>
                    <td class="border px-4 py-2">{{ number_format($hnp->total,2) }}</td>
                    <td class="border px-4 py-2">
                        <div class="d-flex">
                              <a href="{{ route('pembelian-hutangnonproduksi.detail', $hnp['uuid']) }}"
                                class="btn btn-info btn-sm">Detail</a>
                             <a href="{{ route('delete-pembelian-hutangnonproduksi', $hnp['uuid']) }}" id="btn-delete-post" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data {{ $hnp->no_nota }} Ini ??')"
                                value="Delete" class="btn btn-danger btn-sm">Hapus</a>
                              <a href="{{ route('pembelian-hutangnonproduksi.print', $hnp['uuid']) }}"
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
