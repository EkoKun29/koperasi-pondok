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
                        Tambah Barang
                    </button>

                    {{-- @include('penjualan.piutang.create-detail') --}}
<div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                  <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">#</th>
                        <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama Barang</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Harga Jual</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Jumlah</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Keterangan</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Harga</th>
                        <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
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

                    {{-- Bottom Buttons --}}
                    <div class="flex justify-end mt-6">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="submitAll()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

