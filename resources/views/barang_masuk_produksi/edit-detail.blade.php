<div class="modal modal-blur fade" id="modal-edit-detail{{ $dtl->uuid }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Data Detail</h5>
            </div>
            <form action="{{ route('barang-masuk-produksi.update-detail', $dtl->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <label for="barang">Nama Barang</label>
                            <select id="nama_barang_{{ $dtl->uuid }}" name="nama_barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg">
                                <option disabled {{ $dtl->nama_barang ? '' : 'selected' }}>Pilih Barang</option>
                                @foreach($db as $barang)
                                    <option value="{{ $barang->nama_produk }}" {{ $barang->nama_produk == $dtl->nama_barang ? 'selected' : '' }}>
                                        {{ $barang->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mb-4">
                                <label class="form-label">Qty</label>
                                <input type="number" name="qty" class="form-control" placeholder="Qty" value="{{ $dtl->qty }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Satuan</b></label>
                                <select id="satuan" name="satuan" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled {{ $dtl->satuan ? '' : 'selected' }}>Pilih Satuan</option>
                                    @foreach($db as $dbm)
                                        <option value="{{ $dbm->satuan }}" {{ $dbm->satuan == $dtl->satuan ? 'selected' : '' }}>
                                            {{ $dbm->satuan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-outline-danger" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary ms-auto">
                        Simpan
                    </button>
            </form>
        </div>
    </div>
</div>

