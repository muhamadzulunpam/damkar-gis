
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:1rem; overflow:hidden;">

            {{-- Header warna dinamis --}}
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         id="confirmModalIconWrap"
                         style="width:56px;height:56px;background:rgba(220,53,69,.1);color:#dc3545;">
                        <i class="bi bi-exclamation-triangle-fill fs-4" id="confirmModalIcon"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" id="confirmModalTitle">Konfirmasi</h5>
                        <small class="text-muted" id="confirmModalSubtitle">Tindakan ini tidak dapat dibatalkan</small>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body pt-3 pb-2">
                <p class="mb-2 text-muted" id="confirmModalMessage">
                    Apakah Anda yakin?
                </p>

                <div id="confirmModalDetail"
                     class="bg-light rounded-3 p-3 small text-muted d-none">
                    {{-- detail data (nama, dsb) --}}
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmModalOk">
                    <i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan
                </button>
            </div>

        </div>
    </div>
</div>