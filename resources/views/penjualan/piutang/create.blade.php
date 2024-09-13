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
    <!-- Header -->
    <div class="pb-6 font-bold capitalize">
        <div class="container mx-auto">
            <div>
                <div class="flex justify-between items-center">
                    <h6 class="h2 font-bold capitalize">Masukkan Data Penjualan Piutang</h6>
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
                                <input type="text" id="nama_pembeli" name="nama_pembeli" placeholder="Pembeli" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Nama Personil</b></label>
                                <input type="text" id="nama_personil" name="nama_personil" placeholder="Penjual" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
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
                    <a style="text-decoration: none;" href="#" class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase align-middle transition-all ease-in border-0 rounded-lg select-none shadow-soft-md bg-150 bg-x-25 leading-pro bg-gradient-to-tl from-purple-700 to-pink-500 hover:shadow-soft-2xl hover:scale-102" data-bs-toggle="modal" data-bs-target="#modal-report">
                        Tambah Barang
                    </a>

                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table id="tbl_penjualan_piutang" class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">#</th>
                                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Nama Barang</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Harga Jual</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Jumlah</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Keterangan</th>
                                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs text-slate-400 opacity-70">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bottom Buttons -->
                    <div class="flex justify-end mt-6">
                        <button class="inline-block w-3 px-6 py-2 my-4 text-xs font-bold text-center text-white uppercase bg-gradient-to-tl from-blue-600 to-cyan-400 hover:scale-102" onclick="submitAll()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Section -->
    @include('penjualan.piutang.create-detail')

@endsection
