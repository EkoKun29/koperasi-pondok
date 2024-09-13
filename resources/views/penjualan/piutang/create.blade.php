@extends('layouts.app')

@section('content')
    <!-- Header -->
    <div class="bg-primary pb-6">
        <div class="container mx-auto">
            <div>
                <div class="flex justify-between items-center">
                    <h6 class="h2 text-black">Masukkan Data Penjualan Piutang</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Page content -->
    <div class="container mx-auto mt-6">
        <!-- Table -->
        <div class="w-full">
            <div class="bg-white shadow-md rounded-lg">
                <!-- Card header -->
                <div class="p-6">
                    <div class="flex">
                        <div class="w-full md:w-1/2">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Nama Pembeli</b></label>
                                <input type="text" id="nama_pembeli" name="nama_pembeli" placeholder="Pembeli" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" style="border-color: grey;">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Nama Personil</b></label>
                                <input type="text" id="nama_personil" name="nama_personil" placeholder="Penjual" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" style="border-color: grey;">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Shift</b></label>
                                <select name="shift" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="shift" style="border: 2px solid grey;border-radius: 8px;">
                                    <option disabled selected>Pilih Shift</option>
                                    <option value="Pagi">Pagi</option>
                                    <option value="Sore">Sore</option>
                                    <option value="Malam">Malam</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-center h-screen">
                        <div class="w-full md:w-1/2 bg-white p-6 shadow rounded">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Total Pembayaran</b></label>
                                <h1 class="text-2xl font-bold mt-2" id="TotalPembayaran">Rp. 0</h1>
                            </div>
                        </div>
</div>

                    </div>

                    {{-- Add Product Button --}}
                    <button type="button" class="inline-block w-3   px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" data-toggle="modal"
                            data-target="#createDetailModal">
                        Tambah
                    </button>

                    {{-- @include('penjualan.piutang.create-detail') --}}

                    <div class="mt-4">
                        <table id="tbl_penjualan_cash" class="table-auto w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2">#</th>
                                    <th class="border px-4 py-2">Nama Barang</th>
                                    <th class="border px-4 py-2">Harga Jual</th>
                                    <th class="border px-4 py-2">Jumlah</th>
                                    <th class="border px-4 py-2">Keterangan</th>
                                    <th class="border px-4 py-2">Total Harga</th>
                                    <th class="border px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body_penjualan_cash">
                            </tbody>
                        </table>
                    </div>

                    {{-- Bottom Buttons --}}
                    <div class="flex justify-end mt-6">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="submitAll()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- <script>
        @push('js')
        // START TO MINIFY VIEW
        $('body').removeClass('g-sidenav-show nav-open g-sidenav-pinned')
        $('body').addClass('g-sidenav-hidden')
        // END TO MINIFY VIEW

        var nama_konsumen = '';
        var totalPembayaran = 0;
        var bayar = 0;
        var kembalian = 0;
        var globalData = [];
        var data_barang = @json($data); // Menyimpan data BARANG

        // MENDAPATKAN NAMA KONSUMEN
        $('#nama_konsumen').change(function() {
            nama_konsumen = $('#nama_konsumen').val();
        });

        // SUBMIT ALL WITH POST
        function submitAll() {
            if (globalData.length == 0) {
                alert('Data tidak boleh kosong!');
                return;
            }

            $.ajax({
                url: "{{ route('penjualan-piutang.store') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: {
                    nama_konsumen: nama_konsumen,
                    total: totalPembayaran,
                    kembalian: kembalian,
                    data: globalData
                },
            }).done(function(data) {
                alert('Data berhasil disimpan');
                location.reload();
            }).fail(function(err) {
                alert('Kesalahan pada data. Harap hubungi IT');
            });
        }

        // MENGHAPUS ROW
        $("#tbl_penjualan_cash").on('click', '.btnDelete', function() {
            var data = $(this).closest('tr');
            var id = data.find('#no').text() - 1;
            data.remove();

            let index = globalData.findIndex(function(field) {
                return field.id == id;
            });

            globalData.splice(index, 1);
            totalHarga();
        });

        // MENENTUKAN TOTAL 
        function totalHarga() {
            totalPembayaran = globalData.reduce(function(acc, obj) {
                return acc + obj.subtotal;
            }, 0);
            $('#TotalPembayaran').text(formatRupiah(totalPembayaran));
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(number);
        }

        // CREATE PENJUALAN
        $('#createPenjualanCash').on("submit", function(event) {
            event.preventDefault();
            var harga = $("[name='harga']").val();
            var qty = $("[name='qty']").val();
            var diskon = $("[name='diskon']").val();
            var nama_barang = $("[name='barang']").val();
            var subtotal = (harga * qty) - diskon;

            if (subtotal < 0) {
                alert('Subtotal tidak boleh lebih kecil dari 0');
                return false;
            }

            var formPenjualanCash = $('#createPenjualanCash');
            var formValues = formPenjualanCash.serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            formValues['subtotal'] = subtotal;
            formValues['id'] = globalData.length;

            globalData.push(formValues);
            formPenjualanCash.trigger("reset");
            $('#createDetailModal').modal('hide');

            var newRow = $("<tr>").append(
                $("<td id='no'>").text(formValues.id + 1),
                $("<td>").text(formValues.barang),
                $("<td>").text(harga),
                $("<td>").text(formValues.qty),
                $("<td>").text(formValues.diskon),
                $("<td>").text(formValues.subtotal),
                $("<td>").html('<button class="btn btn-sm btn-danger btnDelete"><i class="fas fa-trash"></i></button>')
            );
            $("#tbl_body_penjualan_cash").append(newRow);

            totalHarga();
        });
    </script>
@endpush --}}
