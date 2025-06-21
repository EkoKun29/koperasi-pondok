<div class="modal modal-blur fade" id="modal-edit-{{ $bm->uuid }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Data</h5>
            </div>
            <form action="{{ route('barang-masuk.update', $bm->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <div class="mb-4">
                                <label class="form-label">Nota</label>
                                <input type="text" name="nota" class="form-control" placeholder="Nota" value="{{ $bm->nota }}" readonly disabled>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Tanggal Pembelian</label>
                                <input type="date" name="tanggal" class="form-control" placeholder="Tanggal" value="{{ $bm->tanggal }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700"><b>Nama Personil</b></label>
                                <select class="nama-personil" name="nama_personil" style="width: 100%" required>
                                    <option disabled {{ $bm->nama_personil ? '' : 'selected' }}>Pilih Personil</option>
                                    @foreach($data as $dbm)
                                        <option value="{{ $dbm->nama_personil }}" {{ $dbm->nama_personil == $bm->nama_personil ? 'selected' : '' }}>
                                            {{ $dbm->nama_personil }}
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

@push('js')
<script>
$(document).ready(function () {
    // Saat modal dibuka
    $('.modal').on('shown.bs.modal', function () {
        const $modal = $(this);

        // Inisialisasi Select2 dalam modal yang aktif
        $modal.find('.nama-personil').select2({
            dropdownParent: $modal,
            placeholder: 'Pilih Personil',
            allowClear: true,
            width: '100%'
        });
    });
});
</script>
@endpush
