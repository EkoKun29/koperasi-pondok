<div class="modal modal-blur fade" id="modal-edit-detail{{ $dtl->uuid }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Data Detail</h5>
            </div>
            <form action="{{ route('pembelian-new.update-detail', $dtl->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Nama Barang</b></label>
                                <select id="nama_barang" name="nama_barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled {{ $dtl->nama_barang ? '' : 'selected' }}>Pilih Barang</option>
                                    @foreach($db as $dbm)
                                        <option value="{{ $dbm->nama_produk }}" {{ $dbm->nama_produk == $dtl->nama_produk ? 'selected' : '' }}>
                                            {{ $dbm->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" placeholder="harga" value="{{ $dtl->harga }}" required>
                            </div>
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

