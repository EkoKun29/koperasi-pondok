@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-xl font-semibold mb-4">Penjualan Piutang</h1>
    <div class="mx-4">
        <a class="inline-block w-3   px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" href="{{ route('penjualan-piutang.create') }}">Tambah Data</a>
      </div>
    <div class="table-responsive">
        <table id="datatable-basic" class="table-auto border-collapse w-full">
            <thead>
                <tr class="text-left bg-gray-200">
                    <th class="border px-4 py-2">No Nota</th>
                    <th class="border px-4 py-2">Tanggal Transaksi</th>
                    <th class="border px-4 py-2">Nama Pembeli</th>
                    <th class="border px-4 py-2">Nama Koperasi</th>
                    <th class="border px-4 py-2">Nama Personil</th>
                    <th class="border px-4 py-2">Shift</th>
                    <th class="border px-4 py-2">Total Pembayaran</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach($transactions as $transaction)
                <tr>
                    <td class="border px-4 py-2">{{ $transaction->no_nota }}</td>
                    <td class="border px-4 py-2">{{ $transaction->tanggal }}</td>
                    <td class="border px-4 py-2">{{ $transaction->nama_konsumen }}</td>
                    <td class="border px-4 py-2">{{ number_format($transaction->total, 2) }}</td>
                    <td class="border px-4 py-2">{{ number_format($transaction->kembalian, 2) }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('transactions.show', $transaction->id) }}" class="text-blue-600 hover:underline">View</a>
                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="text-green-600 hover:underline ml-2">Edit</a>
                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
  $(document).ready(function() {
      $('#datatable-basic').DataTable({
          "language": {
              "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/English.json"
          }
      });
  });
</script>
@endpush
