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

<!-- Page content -->
<div class="container mx-auto mt-6">
    <div class="w-full">
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6">
                <div class="flex">
                    <div class="w-full md:w-1/2">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Nama Pembeli</b></label>
                            <input type="text" id="nama_pembeli" name="nama_pembeli" placeholder="Pembeli" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Nama Personil</b></label>
                            <select id="nama_personil" name="nama_personil" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                                <option disabled selected>Pilih Personil</option>
                                @foreach($data as $barang)
                                    <option value="{{ $barang->nama_personil }}">{{ $barang->nama_personil }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Shift</b></label>
                            <select name="shift" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="shift">
                                <option disabled selected>Pilih Shift</option>
                                <option value="Pagi">Pagi</option>
                                <option value="Sore">Sore</option>
                                <option value="Malam">Malam</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 p-6 rounded">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Total Pembayaran</b></label>
                            <h1 class="text-2xl font-bold mt-2" id="TotalPembayaran">Rp. 0</h1>
                        </div>
                    </div>
                </div>

                <!-- Add Product Button -->
                <button class="btn inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-purple-700 to-pink-500 hover:scale-102" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                    Tambah Barang
                </button>

                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table id="tbl_penjualan_piutang" class="table table-bordered table-hover mb-5 overflow-auto">
                            <thead>
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">#</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Nama Barang</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Harga Jual</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Jumlah</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Keterangan</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Total Harga</th>
                                    </tr>
                            </thead>
                            <tbody id="tbl_body_penjualan_piutang">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bottom Buttons -->
                <div class="flex justify-end mt-6">
                    <button class="btn btn-primary" type="button" onclick="submitAll()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Section for Add Product -->
<div id="modalTambahBarang" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createPenjualanPiutang"> <!-- Form Tambah Barang -->
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Input Barang -->
                    <div class="mb-4">
                        <label for="barang">Nama Barang</label>
                        <select id="barang" name="barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Barang</option>
                            @foreach($data as $barang)
                                <option value="{{ $barang->nama_barang }}" >{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="harga">Harga</label>
                        <input type="number" id="harga" name="harga" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="qty">Qty</label>
                        <input type="number" id="qty" name="qty" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Keterangan</b></label>
                            <select name="keterangan" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="keterangan">
                                <option disabled selected>Pilih Keterangan</option>
                                <option value="Dus">Dus</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Pack">Pack</option>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addItem()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Contoh path yang benar -->
<link rel="stylesheet" href="{{ asset('../../assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('../../assets/css/perfect-scrollbar.css') }}">
<script src="{{ asset('../../assets/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('../../assets/js/sidenav-burger.js') }}"></script>
<script src="{{ asset('../../assets/js/navbar-sticky.js') }}"></script>

<script>
    var globalData = [];
    var totalPembayaran = 0;

    // Tambahkan barang ke tabel
    // Tambahkan barang ke tabel
function addItem() {
    var nama_barang = $('#barang').val();
    var harga = parseFloat($('#harga').val());
    var qty = parseInt($('#qty').val());
    var keterangan = $('#keterangan').val();
    var subtotal = harga * qty;

    if (!nama_barang || !harga || !qty || !keterangan) {
        alert('Semua field harus diisi.');
        return;
    }

    // Tambahkan data ke globalData
    globalData.push({
        nama_barang: nama_barang,
        harga: harga,
        qty: qty,
        keterangan: keterangan,
        subtotal: subtotal
    });

    // Tambahkan data ke tabel
    var rowCount = $('#tbl_body_penjualan_piutang tr').length;
    $('#tbl_body_penjualan_piutang').append(`
        <tr>
            <td>${rowCount + 1}</td>
            <td>${nama_barang}</td>
            <td>${harga}</td>
            <td>${qty}</td>
            <td>${keterangan}</td>
            <td>${subtotal}</td>
            <td><button class="btn btn-danger" onclick="removeItem(${rowCount})">Hapus</button></td>
        </tr>
    `);

    // Tutup modal dengan metode Bootstrap
    $('#modalTambahBarang').modal('hide');

    // Pastikan backdrop juga dihilangkan
    $('.modal-backdrop').remove(); // Hapus backdrop jika masih ada

    // Reset form setelah menambahkan item
    $('#createPenjualanPiutang')[0].reset();

    // Update total pembayaran
    updateTotal();
}


    // Render ulang tabel
    function updateTable() {
        var tbody = $('#tbl_body_penjualan_piutang');
        tbody.empty();

        globalData.forEach(function(item, index) {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_barang}</td>
                    <td>${item.harga}</td>
                    <td>${item.qty}</td>
                    <td>${item.keterangan}</td>
                    <td>${item.subtotal}</td>
                    <td><button class="btn btn-danger" onclick="removeItem(${index})">Hapus</button></td>
                </tr>
            `);
        });
    }

    // Menghapus item dari tabel
    function removeItem(index) {
        globalData.splice(index, 1);
        updateTable();
        updateTotal();
    }

    // Update total pembayaran
    function updateTotal() {
        totalPembayaran = globalData.reduce((sum, item) => sum + item.subtotal, 0);
        $('#TotalPembayaran').text("Rp " + totalPembayaran.toLocaleString());
    }

    // Event handler untuk reset form setelah modal ditutup
    $('#modalTambahBarang').on('hidden.bs.modal', function () {
        $('#createPenjualanPiutang')[0].reset();
    });

// Submit semua data
function submitAll() {
    var nama_pembeli = $('#nama_pembeli').val();
    var nama_personil = $('#nama_personil').val();
    var shift = $('#shift').val();
    var totalPembayaran = globalData.reduce((sum, item) => sum + item.subtotal, 0); // Pastikan totalPembayaran diatur dengan benar
    var data = globalData;

    if (!nama_pembeli || !nama_personil || !shift || !totalPembayaran) {
        alert("Semua field harus diisi dan total pembayaran harus dihitung.");
        return;
    }

    $.ajax({
        url: "{{ route('penjualan-piutang.store') }}",
        method: "POST",
        data: {
            nama_pembeli: nama_pembeli,
            nama_personil: nama_personil,
            shift: shift,
            total: totalPembayaran,
            data: data,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.success) {
                window.location.href = "{{ route('penjualan-piutang.print') }}";
            } else {
                alert("Gagal menyimpan data.");
            }
        },
        error: function(xhr, status, error) {
            console.log("Error details:", xhr.responseText);
            alert("Terjadi kesalahan. Silakan coba lagi.");
        }
    });
}
</script>
@endpush
