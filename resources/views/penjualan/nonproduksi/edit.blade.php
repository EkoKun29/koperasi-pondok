<div class="modal modal-blur fade" id="modal-edit-{{ $u->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Data</h5>
            </div>
            <form action="{{ route('scan.update', $u->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">No. Induk</label>
                                <input type="text" name="induk" class="form-control" placeholder="Nomor Induk" value="{{ $u->no_induk }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Seri Awal</label>
                                <input type="text" name="seri_awal" class="form-control" placeholder="Nomor Seri Awal" value="{{ $u->no_seri }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Seri Akhir</label>
                                <input type="text" name="seri_akhir" class="form-control" placeholder="Nomor Seri Akhir" value="{{ $u->no_seri_akhir }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Tanaman</label>
                                <input type="text" name="jenis" class="form-control" placeholder="Jenis Tanaman" value="{{ $u->jenis_tanaman }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas Benih</label>
                                <input type="text" name="kelas" class="form-control" placeholder="Kelas Benih" value="{{ $u->kelas_benih }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Varietas</label>
                                <input type="text" name="varietas" class="form-control" placeholder="Varietas" value="{{ $u->varietas }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Kelompok</label>
                                <input type="text" name="kelompok" class="form-control" placeholder="Nomor Kelompok" value="{{ $u->no_kelompok }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Berat Bersih</label>
                                <input type="text" name="berat" class="form-control" placeholder="Berat Bersih" value="{{ $u->berat_bersih }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Panen</label>
                            <input type="date" name="panen" class="form-control" placeholder="Tanggal Panen" value="{{ $u->tanggal_panen }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai Uji</label>
                                <input type="date" name="uji" class="form-control" placeholder="Tanggal Selesai Uji" value="{{ $u->tanggal_selesai_uji }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Akhir Label</label>
                                <input type="date" name="akhir" class="form-control" placeholder="Tanggal Akhir Label" value="{{ $u->tanggal_akhir_label }}" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label">Kadar Air</label>
                                <input type="number" step="any" name="kadar" class="form-control" placeholder="Kadar Air" value="{{ $u->kadar_air }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Benih Murni</label>
                                <input type="number" step="any" name="benih" class="form-control" placeholder="Benih Murni" value="{{ $u->benih_murni }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Campuran Var Lain</label>
                                <input type="number" step="any" name="campuran" class="form-control" placeholder="Campuran Var Lain" value="{{ $u->camp_var_lain }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kotoran Benih</label>
                                <input type="number" step="any" name="kotoran" class="form-control" placeholder="Kotoran Benih" value="{{ $u->kotoran_benih }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Benih Tanaman Lain / Biji Gulma</label>
                                <input type="number" step="any" name="benih_lain" class="form-control" placeholder="Benih Tanaman Lain" value="{{ $u->benih_tanaman_lain }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Daya Berkecambah</label>
                                <input type="number" step="any" name="daya" class="form-control" placeholder="Daya Berkecambah" value="{{ $u->daya_berkecambah }}" required>
                            </div>
                            {{-- <div class="mb-3">
                                <label class="form-label">Biji Gulma</label>
                                <input type="number" step="any" name="biji" class="form-control" placeholder="Biji Gulma" value="{{ $u->biji_gulma }}" required>
                            </div> --}}
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


