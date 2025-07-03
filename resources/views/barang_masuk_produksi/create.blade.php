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
<div id="modalInduk" class="container mx-auto mt-6">
    <div class="w-full">
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6">
                <div class="flex">
                    <div class="w-full md:w-1/2">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Tanggal Pembelian</b></label>
                            <input type="date" id="tanggal" name="tanggal" placeholder="Tanggal" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"><b>Nama Personil</b></label>
                            <select id="nama_personil" name="nama_personil" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                                <option disabled selected>Pilih Personil</option>
                                @foreach($data as $dbm)
                                    <option value="{{ $dbm->nama_personil }}">{{ $dbm->nama_personil }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-purple-700 to-pink-500 hover:scale-102" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                    Tambah Barang
                </button>

                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table id="tbl_barang_terjual" class="table table-bordered table-hover mb-5 overflow-auto">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">#</th>
                                    <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Nama Barang</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">QTY</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Satuan</th>
                                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body_barang_terjual">
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


<div id="modalTambahBarang" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createBarangTerjual">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="barang">Nama Barang</label>
                        <select id="barang" name="barang" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Barang</option>
                            @foreach($db as $barang)
                                <option value="{{ $barang->nama_produk }}">{{ $barang->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="qty">Qty</label>
                        <input type="number" id="qty" name="qty" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="barang">Satuan</label>
                        <select id="satuan" name="satuan" style="width: 100%" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Satuan</option>
                            @foreach($db as $satuan)
                                <option value="{{ $satuan->satuan }}">{{ $satuan->satuan }}</option>
                            @endforeach
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
        var qty = parseInt($('#qty').val());
        var satuan = $('#satuan').val();

        // Validasi jika semua input telah diisi
        if (!nama_barang || !qty || !satuan) {
            alert('Semua field harus diisi.');
            return;
        }

        // Simpan data ke globalData untuk disubmit nanti
        globalData.push({
            nama_barang: nama_barang,
            qty: qty,
            satuan: satuan,
        });

        // Tambahkan data langsung ke tabel HTML
        var rowCount = $('#tbl_body_barang_terjual tr').length;
        $('#tbl_body_barang_terjual').append(`
            <tr>
                <td>${rowCount + 1}</td>
                <td>${nama_barang}</td>
                <td>${qty}</td>
                <td>${satuan}</td>
                <td><button class="btn btn-danger" onclick="removeItem(${rowCount})">Hapus</button></td>
            </tr>
        `);

        // Tutup modal setelah barang ditambahkan
        $('#modalTambahBarang').modal('hide');
        $('#modalInduk').modal('hide');
        $('.modal-backdrop').remove(); 

        // Reset form setelah barang ditambahkan
        $('#createBarangTerjual')[0].reset();

    }

    // Menghapus item dari tabel
    function removeItem(index) {
        globalData.splice(index, 1);
        updateTable();
    }

    // Update tabel setelah item dihapus
    function updateTable() {
        $('#tbl_body_barang_terjual').empty();
        globalData.forEach((item, index) => {
            $('#tbl_body_barang_terjual').append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_barang}</td>
                    <td>${item.qty}</td>
                    <td>${item.satuan}</td>
                    <td><button class="btn btn-danger" onclick="removeItem(${index})">Hapus</button></td>
                </tr>
            `);
        });
    }

    // Update total pembayaran
    

    $(document).ready(function() {
    $("#barang").select2({
    dropdownParent: $("#modalTambahBarang")
    });
    $("#satuan").select2({
    dropdownParent: $("#modalTambahBarang")
    });
    $("#nama_personil").select2({
    dropdownParent: $("#modalInduk")
    });
    $('#modalTambahBarang').on('hidden.bs.modal', function () {
        $('#createBarangTerjual')[0].reset();
    });
    $('#modalInduk').on('hidden.bs.modal', function () {
        $('#createBarangTerjual')[0].reset();
    });
});

    // Submit semua data
    function submitAll() { 
        var nama_personil = $('#nama_personil').val();
        var tanggal = $('#tanggal').val();

        if (!nama_personil || !tanggal) {
            alert("Semua field harus diisi ");
            return;
        }

        $.ajax({
            url: "{{ route('barang-masuk-produksi.store') }}",
            method: "POST",
            contentType: "application/json",
            headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },      
            data: JSON.stringify({
                nama_personil: nama_personil,
                tanggal: tanggal,
                data: globalData,
            }),
            success: function(response) {
                if (response.success) {
                    var uuid = response.uuid;
                    window.location.href = "{{ url('barang-masuk-produksi/print') }}/" + uuid;
                } else {
                    alert("Gagal menyimpan data.");
                }
            },
            error: function(xhr, status, error) {
                console.log("Status:", status);
                console.log("Error:", error);
                console.log("Response Text:", xhr.responseText);
                alert("Terjadi kesalahan. Silakan coba lagi.");
            }
        });
    }
</script>

@endpush
