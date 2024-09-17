<!-- Modal -->
<div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" onsubmit="" id="createPenjualanPiutang">
                @csrf
                <div class="modal-body">
                    <!-- Nama Barang -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"><b>Nama Barang</b></label>
                        <select id="barang" name="barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                            <option disabled selected>Pilih Barang</option>
                            @foreach($data as $barang)
                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Harga -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"><b>Harga</b></label>
                        <input type="number" id="harga" name="harga" placeholder="Harga" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                    </div>

                    <!-- Qty -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"><b>Qty</b></label>
                        <input type="number" id="qty" name="qty" placeholder="Qty" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700"><b>Keterangan</b></label>
                        <select name="keterangan" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" id="shift">
                            <option disabled selected>Pilih Keterangan</option>
                            <option value="PCS">PCS</option>
                            <option value="PACK">PACK</option>
                            <option value="DUS">DUS</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-danger ms-auto">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
