<div class="modal modal-blur fade" id="modal-edit-{{ $trj->uuid }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Data</h5>
            </div>
            <form action="{{ route('pembelian-new.update', $trj->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <div class="mb-4">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal" value="{{ $trj->tanggal }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Nama Supplier</b></label>
                                <select id="nama_supplier" name="nama_supplier" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled {{ $trj->nama_supplier ? '' : 'selected' }}>Pilih Supplier</option>
                                    @foreach($db as $dbm)
                                        <option value="{{ $dbm->nama_supplier }}" {{ $dbm->nama_supplier == $trj->nama_supplier ? 'selected' : '' }}>
                                            {{ $dbm->nama_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Masuk Ke-</b></label>
                                <select id="pindah_barang" name="pindah_barang" class="form-input mt-1 block w-full px-3 py-2 text-lg border-2 border-gray-400 rounded-lg" required>
                                    <option disabled {{ $trj->pindah_barang ? '' : 'selected' }}>Pilih Masuk Ke-</option>
                                    @foreach($db as $dbm)
                                        <option value="{{ $dbm->tipe_po }}" {{ $dbm->tipe_po == $trj->pindah_barang ? 'selected' : '' }}>
                                            {{ $dbm->tipe_po }}
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


