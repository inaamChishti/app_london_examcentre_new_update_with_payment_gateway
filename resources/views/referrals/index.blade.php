@extends('layouts.app')

@section('content')
<style>
    /* ── Page wrapper ── */
    .referrals-page {
        padding: 20px;
    }

    /* ── Card ── */
    .ref-card {
        background: #fff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* ── Card header ── */
    .ref-card-header {
        background: #F87A14;
        color: #fff;
        padding: 18px 25px;
        border-bottom: 4px solid #1976D2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .ref-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ref-card-header .session-badge {
        background: rgba(0,0,0,0.15);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* ── Add button ── */
    .btn-add-referral {
        background: #333;
        color: #F87A14;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        transition: background 0.25s, transform 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .btn-add-referral:hover {
        background: #111;
        color: #E86A00;
        transform: translateY(-1px);
    }

    /* ── Stats + search strip ── */
    .ref-toolbar {
        padding: 16px 25px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .ref-stat-chip {
        background: #FFF3E0;
        border: 1px solid #FFCC80;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.8rem;
        color: #555;
        font-weight: 600;
    }
    .ref-stat-chip span {
        color: #F87A14;
        font-weight: 700;
    }
    .ref-search-wrap .input-group-text {
        background: #F87A14;
        border: 1px solid #F87A14;
        color: #fff;
    }
    .ref-search-wrap .form-control {
        border: 1px solid #F87A14;
        border-left: none;
        border-radius: 0 8px 8px 0 !important;
    }
    .ref-search-wrap .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(248, 122, 20, 0.35);
        border-color: #E86A00;
    }

    /* ── Table ── */
    .ref-table-wrap {
        padding: 16px 25px 25px;
        background: #fff;
    }
    .ref-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }
    .ref-table thead th {
        background: #FFF8E1;
        color: #555;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 14px;
        border-bottom: 2px solid rgb(213,173,0);
    }
    .ref-table tbody tr {
        background: #fff;
        transition: background 0.15s;
    }
    .ref-table tbody tr:hover {
        background: #FFFDE7;
    }
    .ref-table tbody td {
        padding: 11px 14px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.9rem;
        color: #444;
        vertical-align: middle;
        background: transparent;
    }
    .ref-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── Row number badge ── */
    .row-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        background: #FFC107;
        color: #333;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* ── Action buttons ── */
    .btn-edit-ref {
        background: rgb(213,173,0);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-edit-ref:hover {
        background: rgb(180,145,0);
        transform: translateY(-1px);
    }
    .btn-delete-ref {
        background: #f44336;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-delete-ref:hover {
        background: #c62828;
        transform: translateY(-1px);
    }

    /* ── Empty state ── */
    .ref-empty {
        text-align: center;
        padding: 50px 20px;
        color: #aaa;
        background: #fff;
    }
    .ref-empty i {
        font-size: 3rem;
        color: rgb(213,173,0);
        margin-bottom: 12px;
        display: block;
    }
    .ref-empty p {
        font-size: 1rem;
        margin: 0;
    }

    /* ── Modal overrides ── */
    .ref-modal .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    .ref-modal .modal-header {
        background: rgb(213,173,0);
        border-bottom: 3px solid rgb(180,145,0);
        padding: 14px 20px;
    }
    .ref-modal .modal-title {
        font-weight: 700;
        color: #fff;
        font-size: 1rem;
    }
    .ref-modal .modal-header .close {
        color: #fff;
        opacity: 0.8;
        font-size: 1.4rem;
        padding: 0;
        margin: 0;
        background: none;
        border: none;
        cursor: pointer;
    }
    .ref-modal .modal-header .close:hover { opacity: 1; }
    .ref-modal .modal-body {
        background: #FFFDE7;
        padding: 24px;
    }
    .ref-modal .modal-footer {
        background: #FFF8E1;
        border-top: 1px solid #FFE082;
    }
    .ref-modal .form-label {
        font-weight: 600;
        color: #555;
        font-size: 0.88rem;
    }
    .ref-modal .form-control {
        border: 1px solid #FFE082;
        border-radius: 8px;
        padding: 9px 13px;
        font-size: 0.9rem;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .ref-modal .form-control:focus {
        border-color: rgb(213,173,0);
        box-shadow: 0 0 0 0.2rem rgba(213,173,0,0.3);
        background: #fff;
    }
    .ref-modal .form-text {
        font-size: 0.78rem;
        color: #888;
    }
    .btn-save-ref {
        background: rgb(213,173,0);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 22px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-save-ref:hover {
        background: rgb(180,145,0);
        transform: translateY(-1px);
    }
    .btn-save-ref:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none;
    }
    .btn-cancel-ref {
        background: #eee;
        color: #555;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
    }
    .btn-cancel-ref:hover { background: #ddd; }
</style>

<div class="referrals-page">
    <div class="ref-card">

        {{-- Header --}}
        <div class="ref-card-header">
            <h4>
                <i class="fas fa-share-alt"></i>
                Referrals
                @if($currentSession)
                    <span class="session-badge">{{ $currentSession }}</span>
                @endif
            </h4>
            <button class="btn-add-referral" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add Referral
            </button>
        </div>

        {{-- Toolbar: stats + search --}}
        <div class="ref-toolbar mt-3">
            <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                <div class="ref-stat-chip">Total: <span id="totalCount">{{ $referrals->count() }}</span></div>
                <div class="ref-stat-chip">Session: <span>{{ $currentSession ?? 'N/A' }}</span></div>
            </div>
            <div class="ref-search-wrap">
                <div class="input-group" style="max-width:320px;">
                    <span class="input-group-text" style="border-radius:8px 0 0 8px;"><i class="fas fa-search"></i></span>
                    <input type="text" id="refSearch" class="form-control" placeholder="Search referrals…">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="ref-table-wrap">
            <div id="tableContainer">
                @if($referrals->isEmpty())
                    <div class="ref-empty" id="emptyState">
                        <i class="fas fa-share-alt"></i>
                        <p>No referrals found for this session.<br>Click <strong>Add Referral</strong> to get started.</p>
                    </div>
                @else
                    <table class="ref-table" id="referralsTable">
                        <thead>
                            <tr>
                                <th>Referral Name</th>
                                <th style="width:160px;">Initiated On</th>
                                <th style="width:180px; text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="referralsTbody">
                            @foreach($referrals as $index => $referral)
                            <tr data-id="{{ $referral->id }}" data-name="{{ $referral->name }}">
                                <td class="ref-name-cell">{{ $referral->name }}</td>
                                <td>{{ $referral->created_at->format('d/m/Y') }}</td>
                                <td style="text-align:center;">
                                    <button class="btn-edit-ref me-1"
                                        data-id="{{ $referral->id }}"
                                        data-name="{{ $referral->name }}"
                                        onclick="openEditModal(this)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn-delete-ref"
                                        data-id="{{ $referral->id }}"
                                        onclick="deleteReferral(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="ref-empty" id="emptyState" style="display:none;">
                        <i class="fas fa-search"></i>
                        <p>No referrals match your search.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ADD REFERRAL MODAL  (Bootstrap 4 jQuery style)
══════════════════════════════════════════════════════════ --}}
<div class="modal fade ref-modal" id="addReferralModal" tabindex="-1" role="dialog" aria-labelledby="addReferralModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReferralModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New Referral
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="addErrorAlert" class="alert alert-danger py-2 px-3" style="display:none; font-size:0.85rem; border-radius:8px;"></div>
                <div class="mb-3">
                    <label for="addReferralName" class="form-label">
                        Referral Name <span style="color:red;">*</span>
                    </label>
                    <input type="text" class="form-control" id="addReferralName"
                        placeholder="e.g. Google, Facebook, Word of Mouth…"
                        maxlength="255" autocomplete="off">
                    <small class="form-text">
                        This referral will be linked to the current session:
                        <strong>{{ $currentSession ?? 'N/A' }}</strong>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel-ref" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-save-ref" id="addReferralBtn" onclick="addReferral()">
                    <i class="fas fa-save me-1"></i> Save Referral
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     EDIT REFERRAL MODAL  (Bootstrap 4 jQuery style)
══════════════════════════════════════════════════════════ --}}
<div class="modal fade ref-modal" id="editReferralModal" tabindex="-1" role="dialog" aria-labelledby="editReferralModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReferralModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Referral
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="editErrorAlert" class="alert alert-danger py-2 px-3" style="display:none; font-size:0.85rem; border-radius:8px;"></div>
                <input type="hidden" id="editReferralId">
                <div class="mb-3">
                    <label for="editReferralName" class="form-label">
                        Referral Name <span style="color:red;">*</span>
                    </label>
                    <input type="text" class="form-control" id="editReferralName"
                        placeholder="Referral name…" maxlength="255" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel-ref" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-save-ref" id="editReferralBtn" onclick="updateReferral()">
                    <i class="fas fa-save me-1"></i> Update Referral
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    /* ── SweetAlert2 Toast (same as add-session page) ── */
    window.SwalToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    /* ── Enter key triggers save ── */
    $('#addReferralName').on('keydown', function (e) {
        if (e.key === 'Enter') addReferral();
    });
    $('#editReferralName').on('keydown', function (e) {
        if (e.key === 'Enter') updateReferral();
    });

    /* ── Live search ── */
    $('#refSearch').on('input', function () {
        const query = $(this).val().toLowerCase().trim();
        let visible = 0;

        $('#referralsTbody tr').each(function () {
            const name = ($(this).data('name') || '').toLowerCase();
            const match = name.includes(query);
            $(this).toggle(match);
            if (match) visible++;
        });

        $('#emptyState').toggle(visible === 0);
        reNumberRows();
        $('#totalCount').text(visible);
    });
});

/* ─── helpers ─── */
function setBtnLoading(btnId, loading) {
    const btn = document.getElementById(btnId);
    btn.disabled = loading;
    if (loading) {
        btn.dataset.orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving…';
    } else {
        btn.innerHTML = btn.dataset.orig || btn.innerHTML;
    }
}

function updateTotalCount() {
    const visible = $('#referralsTbody tr:visible').length;
    $('#totalCount').text(visible);
}

function reNumberRows() {
    let n = 1;
    $('#referralsTbody tr:visible').each(function () {
        $(this).find('.row-num').text(n++);
    });
}

function formatDate(isoString) {
    const d = new Date(isoString);
    return String(d.getDate()).padStart(2,'0') + '/' +
           String(d.getMonth()+1).padStart(2,'0') + '/' +
           d.getFullYear();
}

function escapeHtml(str) {
    return $('<div>').text(str).html();
}

/* ─── ENSURE TABLE EXISTS ─── */
function ensureTableExists() {
    if ($('#referralsTable').length === 0) {
        $('#tableContainer').html(`
            <table class="ref-table" id="referralsTable">
                <thead>
                    <tr>
                        <th>Referral Name</th>
                        <th style="width:160px;">Initiated On</th>
                        <th style="width:180px; text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="referralsTbody"></tbody>
            </table>
            <div class="ref-empty" id="emptyState" style="display:none;">
                <i class="fas fa-search"></i>
                <p>No referrals match your search.</p>
            </div>`);
    }
}

/* ─── ADD ─── */
function addReferral() {
    const name = $('#addReferralName').val().trim();
    $('#addErrorAlert').hide();

    if (!name) {
        $('#addErrorAlert').text('Referral name is required.').show();
        $('#addReferralName').focus();
        return;
    }

    setBtnLoading('addReferralBtn', true);

    $.ajax({
        url: '{{ route("referrals.store") }}',
        method: 'POST',
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: JSON.stringify({ name }),
        success: function (data) {
            setBtnLoading('addReferralBtn', false);
            if (data.success) {
                $('#addReferralModal').modal('hide');
                appendRow(data.referral);
                SwalToast.fire({ icon: 'success', title: data.message });
            } else {
                $('#addErrorAlert').text(data.message || 'Something went wrong.').show();
            }
        },
        error: function (xhr) {
            setBtnLoading('addReferralBtn', false);
            const msg = xhr.responseJSON?.message || 'Something went wrong.';
            $('#addErrorAlert').text(msg).show();
        }
    });
}

function appendRow(referral) {
    ensureTableExists();
    const tr = `
        <tr data-id="${referral.id}" data-name="${escapeHtml(referral.name)}">
            <td class="ref-name-cell">${escapeHtml(referral.name)}</td>
            <td>${formatDate(referral.created_at)}</td>
            <td style="text-align:center;">
                <button class="btn-edit-ref me-1"
                    data-id="${referral.id}"
                    data-name="${escapeHtml(referral.name)}"
                    onclick="openEditModal(this)">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-delete-ref"
                    data-id="${referral.id}"
                    onclick="deleteReferral(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    $('#referralsTbody').append(tr);
    $('#emptyState').hide();
    updateTotalCount();
}

/* ─── OPEN ADD MODAL ─── */
function openAddModal() {
    $('#addReferralName').val('');
    $('#addErrorAlert').hide();
    $('#addReferralModal').modal('show');
}

/* ─── EDIT ─── */
function openEditModal(btn) {
    const id   = $(btn).data('id');
    const name = $(btn).data('name');
    $('#editReferralId').val(id);
    $('#editReferralName').val(name);
    $('#editErrorAlert').hide();
    $('#editReferralModal').modal('show');
}

function updateReferral() {
    const id   = $('#editReferralId').val();
    const name = $('#editReferralName').val().trim();
    $('#editErrorAlert').hide();

    if (!name) {
        $('#editErrorAlert').text('Referral name is required.').show();
        return;
    }

    setBtnLoading('editReferralBtn', true);

    $.ajax({
        url: `/referrals/${id}`,
        method: 'PUT',
        contentType: 'application/json',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: JSON.stringify({ name }),
        success: function (data) {
            setBtnLoading('editReferralBtn', false);
            if (data.success) {
                $('#editReferralModal').modal('hide');
                const row = $(`#referralsTbody tr[data-id="${id}"]`);
                row.attr('data-name', data.referral.name);
                row.find('.ref-name-cell').text(data.referral.name);
                row.find('.btn-edit-ref').attr('data-name', data.referral.name);
                SwalToast.fire({ icon: 'success', title: data.message });
            } else {
                $('#editErrorAlert').text(data.message || 'Something went wrong.').show();
            }
        },
        error: function (xhr) {
            setBtnLoading('editReferralBtn', false);
            const msg = xhr.responseJSON?.message || 'Something went wrong.';
            $('#editErrorAlert').text(msg).show();
        }
    });
}

/* ─── DELETE ─── */
function deleteReferral(btn) {
    const id = $(btn).data('id');

    Swal.fire({
        title: 'Delete Referral?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
    }).then(result => {
        if (!result.isConfirmed) return;

        $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: `/referrals/${id}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function (data) {
                if (data.success) {
                    $(`#referralsTbody tr[data-id="${id}"]`).remove();
                    reNumberRows();
                    updateTotalCount();
                    if ($('#referralsTbody tr').length === 0) {
                        $('#emptyState').show();
                    }
                    SwalToast.fire({ icon: 'success', title: data.message });
                } else {
                    $(btn).prop('disabled', false).html('<i class="fas fa-trash"></i>');
                    SwalToast.fire({ icon: 'error', title: data.message || 'Failed to delete.' });
                }
            },
            error: function () {
                $(btn).prop('disabled', false).html('<i class="fas fa-trash"></i>');
                SwalToast.fire({ icon: 'error', title: 'Network error. Please try again.' });
            }
        });
    });
}
</script>
@endsection
