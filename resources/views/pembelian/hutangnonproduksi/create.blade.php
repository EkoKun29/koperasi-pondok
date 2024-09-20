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
                            <label class="block text-sm font-medium text-gray-700"><b>Nama Supplier</b></label>
                            <input type="text" id="nama_supplier" name="nama_supplier" placeholder="Supplier" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Tanggal Jatuh Tempo</b></label>
                            <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" placeholder="tanggal" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
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
                        <table id="tbl_pembelian_hutangnonproduksi" class="table table-bordered table-hover mb-5 overflow-auto">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">#</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Nama Barang</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Harga Jual</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Jumlah</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Keterangan Cehcklist</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Total Harga</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body_pembelian_hutangnonproduksi">
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
            <form id="createPembelianHutangNonProduksi">
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
                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
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
                        <label class="block text-sm font-medium text-gray-700"><b>Keterangan Checklist</b></label>
                        <select name="check_barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="check_barang">
                            <option disabled selected>Pilih Keterangan</option>
                            <option value="Sesuai">Sesuai</option>
                            <option value="Kurang">Kurang</option>
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
<script src="{{ asset('assets/js/navbar-sticky.js') }}"></script>

<script>
    var globalData = [];

    // Tambahkan barang ke tabel
    function addItem() {
        // Ambil nilai dari inputan
        var nama_barang = $('#barang').val();
        var harga = parseFloat($('#harga').val());
        var qty = parseInt($('#qty').val());
        var check_barang = $('#check_barang').val();
        var subtotal = harga * qty;

        // Validasi jika semua input telah diisi
        if (!nama_barang || !harga || !qty || !check_barang) {
            alert('Semua field harus diisi.');
            return;
        }

        // Simpan data ke globalData untuk disubmit nanti
        globalData.push({
            nama_barang: nama_barang,
            harga: harga,
            qty: qty,
            check_barang: check_barang,
            subtotal: subtotal
        });

        // Tambahkan data langsung ke tabel HTML
        var rowCount = $('#tbl_body_pembelian_hutangnonproduksi tr').length;
        $('#tbl_body_pembelian_hutangnonproduksi').append(`
            <tr>
                <td>${rowCount + 1}</td>
                <td>${nama_barang}</td>
                <td>${harga}</td>
                <td>${qty}</td>
                <td>${check_barang}</td>
                <td>${subtotal}</td>
                <td><button class="btn btn-danger" onclick="removeItem(${rowCount})">Hapus</button></td>
            </tr>
        `);

        // Tutup modal setelah barang ditambahkan
        $('#modalTambahBarang').modal('hide');
        $('.modal-backdrop').remove();  // Menghapus backdrop jika masih ada

        // Reset form setelah barang ditambahkan
        $('#createPembelianHutangNonProduksi')[0].reset();

        // Update total pembayaran di halaman
        updateTotal();
    }

    // Menghapus item dari tabel
    function removeItem(index) {
        globalData.splice(index, 1);
        updateTable();
        updateTotal();
    }

    // Update tabel setelah item dihapus
    function updateTable() {
        $('#tbl_body_pembelian_hutangnonproduksi').empty();
        globalData.forEach((item, index) => {
            $('#tbl_body_pembelian_hutangnonproduksi').append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_barang}</td>
                    <td>${item.harga}</td>
                    <td>${item.qty}</td>
                    <td>${item.check_barang}</td>
                    <td>${item.subtotal}</td>
                    <td><button class="btn btn-danger" onclick="removeItem(${index})">Hapus</button></td>
                </tr>
            `);
        });
    }

    // Update total pembayaran
    function updateTotal() {
        var total = globalData.reduce((sum, item) => sum + item.subtotal, 0);
        $('#TotalPembayaran').text("Rp " + total.toLocaleString());
    }

    // Event handler untuk reset form setelah modal ditutup
    $(document).ready(function() {
    $('#modalTambahBarang').on('hidden.bs.modal', function () {
        $('#createPembelianHutangNonProduksi')[0].reset();
    });
});

    // Submit semua data
    function submitAll() { 
        var nama_supplier = $('#nama_supplier').val();
        var tanggal_jatuh_tempo = $('#tanggal_jatuh_tempo').val();
        var totalPembayaran = globalData.reduce((sum, item) => sum + item.subtotal, 0); // Hitung total pembayaran

        if (!nama_supplier || !tanggal_jatuh_tempo || totalPembayaran === 0) {
            alert("Semua field harus diisi dan total pembayaran harus dihitung.");
            return;
        }

        $.ajax({
            url: "{{ route('pembelian-hutangnonproduksi.store') }}",  // Route Laravel untuk penyimpanan
            method: "POST",
            data: {
                nama_supplier: nama_supplier,
                tanggal_jatuh_tempo: tanggal_jatuh_tempo,
                total: totalPembayaran,
                data: globalData,  // Kirim data barang
                _token: "{{ csrf_token() }}"  // Sertakan CSRF token untuk keamanan
            },
            success: function(response) {
                if (response.success) {
                    var uuid = response.uuid;
                    
                    // Redirect ke halaman print dengan UUID
                    window.location.href = "{{ url('pembelian-hutangnonproduksi/print') }}/" + uuid;
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
