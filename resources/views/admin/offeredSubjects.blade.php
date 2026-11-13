@extends('layouts.app')

@section('content')
{{-- Cascade maps for JS dependent dropdowns --}}
<script>
    const boardQualMap        = @json($boardQualMap);
    const sessionBoardMap     = @json($sessionBoardMap);
    const sessionBoardQualMap = @json($sessionBoardQualMap);
    const csrfToken           = "{{ csrf_token() }}";
    const baseUrl             = "{{ url('admin/offered-subjects') }}";
    // Pre-fill add modal with current filter values
    const filterSession = "{{ $selectedSession }}";
    const filterBoard   = "{{ $selectedBoard }}";
    const filterQual    = "{{ $selectedQual }}";
</script>

<style>
    /* â”€â”€ Modal overlay â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    .os-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }
    .os-overlay.active { display: flex; }

    .os-modal {
        background: #fff;
        border-radius: 10px;
        width: 100%;
        max-width: 540px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        overflow: hidden;
        animation: osSlideIn .22s ease;
    }
    @keyframes osSlideIn {
        from { transform: translateY(-24px); opacity: 0; }
        to   { transform: translateY(0);     opacity: 1; }
    }

    .os-modal-header {
        background: #F87A14;
        border-bottom: 3px solid #1976D2;
        padding: 14px 20px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .os-modal-header h5 {
        margin: 0; color: #fff; font-weight: 700; font-size: 15px;
    }
    .os-modal-header .os-close {
        background: none; border: none; color: #fff;
        font-size: 20px; cursor: pointer; line-height: 1;
    }
    .os-modal-body { padding: 22px 24px; }

    /* form rows */
    .os-form-row { margin-bottom: 14px; }
    .os-form-row label {
        display: block; font-weight: 600; font-size: 13px;
        color: #2c3e50; margin-bottom: 4px;
    }
    .os-form-row label .os-req { color: #e74c3c; margin-left: 2px; }
    .os-form-row input,
    .os-form-row select {
        width: 100%; padding: 7px 11px;
        border: 1px solid #ced4da; border-radius: 6px;
        font-size: 13px; background: #f8f9fa;
        transition: border-color .2s;
        box-sizing: border-box;
    }
    .os-form-row input:focus,
    .os-form-row select:focus {
        outline: none; border-color: #1976D2; background: #fff;
    }
    .os-form-row .os-error {
        color: #dc3545; font-size: 12px; margin-top: 3px; display: none;
    }
    .os-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }

    .os-modal-footer {
        padding: 12px 24px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex; justify-content: flex-end; gap: 10px;
    }

    /* â”€â”€ Buttons â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    .btn-os-primary {
        padding: 7px 20px; background: #1976D2; color: #fff;
        border: none; border-radius: 6px; font-weight: 600;
        font-size: 13px; cursor: pointer; transition: background .2s;
    }
    .btn-os-primary:hover { background: #1565C0; }
    .btn-os-primary:disabled { background: #90CAF9; cursor: not-allowed; }

    .btn-os-cancel {
        padding: 7px 18px; background: #fff; color: #555;
        border: 1px solid #ced4da; border-radius: 6px;
        font-size: 13px; cursor: pointer;
    }
    .btn-os-cancel:hover { background: #f1f1f1; }

    .btn-os-danger {
        padding: 7px 20px; background: #dc3545; color: #fff;
        border: none; border-radius: 6px; font-weight: 600;
        font-size: 13px; cursor: pointer;
    }
    .btn-os-danger:hover { background: #c82333; }

    /* â”€â”€ Toast â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    #os-toast {
        display: none; position: fixed; bottom: 28px; right: 28px;
        padding: 10px 20px; border-radius: 7px; font-size: 14px;
        color: #fff; z-index: 9999;
        box-shadow: 0 4px 14px rgba(0,0,0,0.18);
        animation: osToastIn .25s ease;
    }
    @keyframes osToastIn {
        from { transform: translateY(14px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    /* â”€â”€ Action buttons in table â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    .btn-edit, .btn-del {
        padding: 4px 9px; border: none; border-radius: 5px;
        font-size: 12px; cursor: pointer; transition: opacity .2s;
    }
    .btn-edit { background: #1976D2; color: #fff; }
    .btn-edit:hover { background: #1565C0; }
    .btn-del  { background: #dc3545; color: #fff; }
    .btn-del:hover  { background: #c82333; }

    /* amount input */
    .amount-input {
        width: 90px; padding: 4px 8px;
        border: 1px solid #ced4da; border-radius: 5px;
        font-size: 13px; text-align: right;
        transition: border-color .2s;
    }
    .save-amount-btn {
        padding: 4px 8px; background: #1976D2; color: #fff;
        border: none; border-radius: 5px; cursor: pointer; font-size: 12px;
    }
    /* Offered Subjects table - same layout as Session Management */
    .os-table-wrap { width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .os-table {
        width:100% !important; min-width:850px; font-size:.9rem !important;
        border-collapse:collapse !important; table-layout:auto !important;
        border:0 !important; color:#444; background:#fff;
    }
    .os-table thead { background:#F87A14 !important; color:#fff !important; }
    .os-table thead tr, .os-table thead th {
        background:#F87A14 !important; color:#fff !important;
    }
    .os-table thead th {
        padding:10px 15px !important; font-size:.9rem !important;
        font-weight:600 !important; text-align:left !important;
        text-transform:none !important; letter-spacing:normal !important;
        border:0 !important; border-bottom:4px solid #1976D2 !important;
        white-space:nowrap;
    }
    .os-table tbody tr { transition:background-color .2s ease; }
    .os-table tbody tr:hover { background:#fff7f0 !important; }
    .os-table tbody td {
        padding:10px 15px !important; vertical-align:middle !important;
        font-size:.85rem !important; text-align:left !important;
        border:0 !important; border-bottom:1px solid #dee2e6 !important;
        line-height:1.45;
    }
    .os-table tbody td:last-child, .os-table thead th:last-child { text-align:left !important; }
    .os-table .amount-input {
        width:90px !important; padding:5px 8px !important;
        border:1px solid #ced4da !important; border-radius:5px !important;
        background:#fff !important; color:#444 !important;
        font:400 13px 'Nunito',sans-serif !important; text-align:right;
    }
    .os-table .amount-input:focus {
        outline:none; border-color:#F87A14 !important;
        box-shadow:0 0 0 2px rgba(248,122,20,.12);
    }
    .os-table .save-amount-btn {
        min-width:30px; min-height:30px; display:inline-flex;
        align-items:center; justify-content:center; background:#28a745 !important;
        color:#fff !important; border:0; border-radius:5px !important;
    }
    .os-table .save-amount-btn:hover { background:#218838 !important; }
    .os-table .btn-edit, .os-table .btn-del {
        width:auto; min-width:30px; min-height:30px; padding:5px 9px !important;
        display:inline-flex; align-items:center; justify-content:center;
        border-radius:5px !important; font-size:12px !important;
    }
    .os-table .btn-edit { background:#28a745 !important; color:#fff !important; }
    .os-table .btn-edit:hover { background:#218838 !important; }
    .os-table .btn-del { background:#dc3545 !important; color:#fff !important; }
    .os-table .btn-del:hover { background:#c82333 !important; }
    @media (max-width:768px) {
        .os-table { min-width:820px; }
        .os-table thead th, .os-table tbody td { padding:9px 11px !important; }
    }
</style>

<div class="container-fluid" style="padding: 20px;">

    {{-- â”€â”€ Page Header â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
    <div style="background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2;
                padding: 14px 20px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display:flex; align-items:center; gap:12px;">
            <i class="fas fa-book-open fa-lg" style="color:#fff;"></i>
            <h3 style="margin:0; color:#fff; font-weight:700; font-size:17px;">Offered Subjects</h3>
        </div>
        <button onclick="openAddModal()"
            style="padding: 7px 18px; background:#fff; color:#F87A14; border:none;
                   border-radius:6px; font-weight:700; font-size:13px; cursor:pointer;
                   display:flex; align-items:center; gap:7px; box-shadow:0 2px 6px rgba(0,0,0,.12);">
            <i class="fas fa-plus"></i> Add Subject
        </button>
    </div>

    <div style="background:#fff; border-radius:0 0 8px 8px;
                box-shadow:0 0.15rem 1.75rem rgba(58,59,69,.15); padding:22px;">

        {{-- â”€â”€ Filter Form â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
        <form method="GET" action="{{ url('admin/offered-subjects') }}" id="filterForm">
            <div style="display:flex; flex-wrap:wrap; gap:14px; align-items:flex-end; margin-bottom:18px;">

                <div style="flex:1; min-width:190px;">
                    <label style="font-weight:600; color:#2c3e50; display:block; margin-bottom:4px; font-size:13px;">
                        <i class="fas fa-calendar-alt" style="color:#F87A14; margin-right:5px;"></i>Session
                    </label>
                    <select name="session" id="sessionSelect"
                        style="width:100%; padding:7px 11px; border:1px solid #ced4da;
                               border-radius:6px; font-size:13px; background:#f8f9fa;">
                        <option value="">-- All Sessions --</option>
                        @foreach($sessions as $s)
                            <option value="{{ $s }}" {{ $selectedSession === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex:1; min-width:170px;">
                    <label style="font-weight:600; color:#2c3e50; display:block; margin-bottom:4px; font-size:13px;">
                        <i class="fas fa-university" style="color:#1976D2; margin-right:5px;"></i>Board
                    </label>
                    <select name="board" id="boardSelect"
                        style="width:100%; padding:7px 11px; border:1px solid #ced4da;
                               border-radius:6px; font-size:13px; background:#f8f9fa;">
                        <option value="">-- All Boards --</option>
                        @foreach($boards as $b)
                            <option value="{{ $b }}" {{ $selectedBoard === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex:1; min-width:170px;">
                    <label style="font-weight:600; color:#2c3e50; display:block; margin-bottom:4px; font-size:13px;">
                        <i class="fas fa-graduation-cap" style="color:#9370DB; margin-right:5px;"></i>Qualification
                    </label>
                    <select name="qualification" id="qualSelect"
                        style="width:100%; padding:7px 11px; border:1px solid #ced4da;
                               border-radius:6px; font-size:13px; background:#f8f9fa;">
                        <option value="">-- All Qualifications --</option>
                        @foreach($qualifications as $q)
                            <option value="{{ $q }}" {{ $selectedQual === $q ? 'selected' : '' }}>{{ $q }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; gap:8px;">
                    <button type="submit"
                        style="padding:7px 18px; background:#F87A14; color:#fff; border:none;
                               border-radius:6px; font-weight:600; cursor:pointer; font-size:13px;">
                        <i class="fas fa-filter" style="margin-right:5px;"></i>Filter
                    </button>
                    <a href="{{ url('admin/offered-subjects') }}"
                        style="padding:7px 14px; background:#6c757d; color:#fff; border-radius:6px;
                               font-weight:600; font-size:13px; text-decoration:none;
                               display:inline-flex; align-items:center; gap:5px;">
                        <i class="fas fa-times"></i>Clear
                    </a>
                </div>
            </div>
        </form>

        {{-- â”€â”€ Result count â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
        <div style="margin-bottom:10px; color:#555; font-size:13px; display:flex; align-items:center; gap:8px;">
            <span>Showing <strong id="rowCount">{{ $subjects->count() }}</strong> subject(s)</span>
            @if($selectedSession)
                <span style="background:#fff3cd; color:#856404; padding:2px 8px; border-radius:4px;">{{ $selectedSession }}</span>
            @endif
            @if($selectedBoard)
                <span style="background:#cfe2ff; color:#084298; padding:2px 8px; border-radius:4px;">{{ $selectedBoard }}</span>
            @endif
            @if($selectedQual)
                <span style="background:#d1e7dd; color:#0a3622; padding:2px 8px; border-radius:4px;">{{ $selectedQual }}</span>
            @endif
        </div>

        {{-- â”€â”€ Table â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ --}}
        <div class="os-table-wrap">
            <table id="subjectsTable" class="os-table">
                <thead>
                    <tr style="background:#F87A14; color:#fff;">
                        <th style="padding:10px 13px; text-align:left; white-space:nowrap;">Exam Board</th>
                        <th style="padding:10px 13px; text-align:left; white-space:nowrap;">Qualification</th>
                        <th style="padding:10px 13px; text-align:left; white-space:nowrap;">Code</th>
                        <th style="padding:10px 13px; text-align:left;">Description</th>
                        <th style="padding:10px 13px; text-align:left; white-space:nowrap;">Amount (&#163;)</th>
                        <th style="padding:10px 13px; text-align:left; white-space:nowrap;">Session</th>
                        <th style="padding:10px 13px; text-align:center; white-space:nowrap; width:100px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="subjectsBody">
                    @forelse($subjects as $i => $sub)
                    <tr data-id="{{ $sub->id }}" style="background:{{ $i%2===0?'#fff':'#f8f9fa' }}; border-bottom:1px solid #e9ecef;">
                        <td style="padding:8px 13px;">
                            <span style="background:#cfe2ff; color:#084298; padding:2px 8px; border-radius:4px; font-size:12px; white-space:nowrap;">
                                {{ $sub->examboard }}
                            </span>
                        </td>
                        <td style="padding:8px 13px;">
                            <span style="background:#d1e7dd; color:#0a3622; padding:2px 8px; border-radius:4px; font-size:12px; white-space:nowrap;">
                                {{ $sub->qualification }}
                            </span>
                        </td>
                        <td style="padding:8px 13px; font-weight:600; color:#333;">{{ $sub->code }}</td>
                        <td style="padding:8px 13px; color:#444;">{{ $sub->description }}</td>
                        <td style="padding:6px 13px;">
                            <div style="display:flex; align-items:center; gap:5px;">
                                <span style="color:#555; font-size:12px;">£</span>
                                <input type="number" min="0" step="0.01" class="amount-input"
                                    data-id="{{ $sub->id }}"
                                    value="{{ $sub->amount !== null ? number_format((float)$sub->amount,2,'.',''): '' }}"
                                    placeholder="0.00" />
                                <button type="button" class="save-amount-btn" data-id="{{ $sub->id }}" title="Save amount">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </td>
                        <td style="padding:8px 13px;">
                            @if($sub->session)
                                <span style="background:#fff3cd; color:#856404; padding:2px 8px; border-radius:4px; font-size:12px; white-space:nowrap;">
                                    {{ $sub->session }}
                                </span>
                            @else
                                <span style="color:#aaa; font-size:12px;">â€”</span>
                            @endif
                        </td>
                        <td style="padding:8px 13px; text-align:center;">
                            <div style="display:flex; gap:5px; justify-content:center;">
                                <button type="button" class="btn-edit" onclick="openEditModal({{ $sub->id }})" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn-del" onclick="openDeleteModal({{ $sub->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="7" style="padding:30px; text-align:center; color:#888;">
                            <i class="fas fa-inbox fa-2x" style="display:block; margin-bottom:8px; color:#ccc;"></i>
                            No subjects found for the selected filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• ADD MODAL â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div class="os-overlay" id="addOverlay">
    <div class="os-modal">
        <div class="os-modal-header">
            <h5><i class="fas fa-plus-circle" style="margin-right:8px;"></i>Add New Subject</h5>
            <button class="os-close" onclick="closeModal('addOverlay')">Ã—</button>
        </div>
        <div class="os-modal-body">
            <div class="os-form-grid">
                <div class="os-form-row">
                    <label>Exam Board <span class="os-req">*</span></label>
                    <input type="text" id="add_examboard" placeholder="e.g. AQA" />
                    <div class="os-error" id="err_add_examboard">Required.</div>
                </div>
                <div class="os-form-row">
                    <label>Qualification <span class="os-req">*</span></label>
                    <input type="text" id="add_qualification" placeholder="e.g. GCSE" />
                    <div class="os-error" id="err_add_qualification">Required.</div>
                </div>
                <div class="os-form-row">
                    <label>Code <span class="os-req">*</span></label>
                    <input type="text" id="add_code" placeholder="e.g. 8700" />
                    <div class="os-error" id="err_add_code">Required.</div>
                </div>
                <div class="os-form-row">
                    <label>Amount (&#163;)</label>
                    <input type="number" id="add_amount" min="0" step="0.01" placeholder="0.00" />
                </div>
            </div>
            <div class="os-form-row">
                <label>Description <span class="os-req">*</span></label>
                <input type="text" id="add_description" placeholder="e.g. English Language" />
                <div class="os-error" id="err_add_description">Required.</div>
            </div>
            <div class="os-form-row">
                <label>Session</label>
                <select id="add_session">
                    <option value="">-- Select Session --</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}">{{ $session }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="os-modal-footer">
            <button class="btn-os-cancel" onclick="closeModal('addOverlay')">Cancel</button>
            <button class="btn-os-primary" id="addSaveBtn" onclick="saveAdd()">
                <i class="fas fa-save" style="margin-right:5px;"></i>Save Subject
            </button>
        </div>
    </div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• EDIT MODAL â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div class="os-overlay" id="editOverlay">
    <div class="os-modal">
        <div class="os-modal-header">
            <h5><i class="fas fa-pen" style="margin-right:8px;"></i>Edit Subject</h5>
            <button class="os-close" onclick="closeModal('editOverlay')">Ã—</button>
        </div>
        <div class="os-modal-body">
            <input type="hidden" id="edit_id" />
            <div class="os-form-grid">
                <div class="os-form-row">
                    <label>Exam Board <span class="os-req">*</span></label>
                    <input type="text" id="edit_examboard" />
                    <div class="os-error" id="err_edit_examboard">Required.</div>
                </div>
                <div class="os-form-row">
                    <label>Qualification <span class="os-req">*</span></label>
                    <input type="text" id="edit_qualification" />
                    <div class="os-error" id="err_edit_qualification">Required.</div>
                </div>
                <div class="os-form-row">
                    <label>Code <span class="os-req">*</span></label>
                    <input type="text" id="edit_code" />
                    <div class="os-error" id="err_edit_code">Required.</div>
                </div>
                <div class="os-form-row">
                    <label>Amount (&#163;)</label>
                    <input type="number" id="edit_amount" min="0" step="0.01" placeholder="0.00" />
                </div>
            </div>
            <div class="os-form-row">
                <label>Description <span class="os-req">*</span></label>
                <input type="text" id="edit_description" />
                <div class="os-error" id="err_edit_description">Required.</div>
            </div>
            <div class="os-form-row">
                <label>Session</label>
                <select id="edit_session">
                    <option value="">-- Select Session --</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}">{{ $session }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="os-modal-footer">
            <button class="btn-os-cancel" onclick="closeModal('editOverlay')">Cancel</button>
            <button class="btn-os-primary" id="editSaveBtn" onclick="saveEdit()">
                <i class="fas fa-save" style="margin-right:5px;"></i>Update Subject
            </button>
        </div>
    </div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• DELETE CONFIRM MODAL â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div class="os-overlay" id="deleteOverlay">
    <div class="os-modal" style="max-width:420px;">
        <div class="os-modal-header" style="background:#dc3545; border-bottom-color:#c82333;">
            <h5><i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>Confirm Delete</h5>
            <button class="os-close" onclick="closeModal('deleteOverlay')">Ã—</button>
        </div>
        <div class="os-modal-body" style="text-align:center; padding:28px 24px;">
            <i class="fas fa-trash-alt fa-3x" style="color:#dc3545; margin-bottom:14px;"></i>
            <p style="font-size:14px; color:#444; margin:0;">
                Are you sure you want to delete<br>
                <strong id="deleteSubjectName" style="color:#333;"></strong>?<br>
                <span style="color:#888; font-size:12px;">This action cannot be undone.</span>
            </p>
            <input type="hidden" id="delete_id" />
        </div>
        <div class="os-modal-footer" style="justify-content:center; gap:14px;">
            <button class="btn-os-cancel" onclick="closeModal('deleteOverlay')">Cancel</button>
            <button class="btn-os-danger" id="deleteConfirmBtn" onclick="confirmDelete()">
                <i class="fas fa-trash" style="margin-right:5px;"></i>Yes, Delete
            </button>
        </div>
    </div>
</div>

{{-- â”€â”€ Toast â”€â”€ --}}
<div id="os-toast"></div>

<script>
// â”€â”€ Row data store (populated from PHP) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
const rowData = {};
@foreach($subjects as $sub)
rowData[{{ $sub->id }}] = {
    examboard:     @json($sub->examboard),
    qualification: @json($sub->qualification),
    code:          @json($sub->code),
    description:   @json($sub->description),
    session:       @json($sub->session ?? ''),
    amount:        {{ $sub->amount !== null ? $sub->amount : 'null' }},
};
@endforeach

// â”€â”€ Toast â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function showToast(msg, type = 'success') {
    const t = document.getElementById('os-toast');
    t.style.background = type === 'success' ? '#28a745' : '#dc3545';
    t.textContent = msg;
    t.style.display = 'block';
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => t.style.display = 'none', 2800);
}

// â”€â”€ Modal helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function openModal(id)  { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
// Close on overlay click
document.querySelectorAll('.os-overlay').forEach(ov => {
    ov.addEventListener('click', e => { if (e.target === ov) ov.classList.remove('active'); });
});

// â”€â”€ Validate field â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function validateField(inputId, errId) {
    const val = document.getElementById(inputId).value.trim();
    const err = document.getElementById(errId);
    if (!val) { err.style.display = 'block'; return false; }
    err.style.display = 'none'; return true;
}
function clearErrors(prefix) {
    ['examboard','qualification','code','description'].forEach(f => {
        const el = document.getElementById(`err_${prefix}_${f}`);
        if (el) el.style.display = 'none';
    });
}

// â”€â”€ ADD â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function openAddModal() {
    clearErrors('add');
    // Pre-fill from active filters
    document.getElementById('add_examboard').value     = filterBoard;
    document.getElementById('add_qualification').value = filterQual;
    document.getElementById('add_session').value       = filterSession;
    document.getElementById('add_code').value          = '';
    document.getElementById('add_description').value   = '';
    document.getElementById('add_amount').value        = '';
    openModal('addOverlay');
    document.getElementById('add_code').focus();
}

function saveAdd() {
    const ok = [
        validateField('add_examboard',     'err_add_examboard'),
        validateField('add_qualification', 'err_add_qualification'),
        validateField('add_code',          'err_add_code'),
        validateField('add_description',   'err_add_description'),
    ].every(Boolean);
    if (!ok) return;

    const btn = document.getElementById('addSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:5px;"></i>Savingâ€¦';

    const payload = {
        examboard:     document.getElementById('add_examboard').value.trim(),
        qualification: document.getElementById('add_qualification').value.trim(),
        code:          document.getElementById('add_code').value.trim(),
        description:   document.getElementById('add_description').value.trim(),
        session:       document.getElementById('add_session').value.trim() || null,
        amount:        document.getElementById('add_amount').value.trim() || null,
    };

    fetch(baseUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal('addOverlay');
            appendRow(data.subject);
            showToast('Subject added successfully.');
        } else {
            showToast('Failed to add subject.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save" style="margin-right:5px;"></i>Save Subject';
    });
}

// â”€â”€ EDIT â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function openEditModal(id) {
    clearErrors('edit');
    const d = rowData[id];
    document.getElementById('edit_id').value            = id;
    document.getElementById('edit_examboard').value     = d.examboard;
    document.getElementById('edit_qualification').value = d.qualification;
    document.getElementById('edit_code').value          = d.code;
    document.getElementById('edit_description').value   = d.description;
    document.getElementById('edit_session').value       = d.session || '';
    document.getElementById('edit_amount').value        = d.amount !== null ? parseFloat(d.amount).toFixed(2) : '';
    openModal('editOverlay');
    document.getElementById('edit_description').focus();
}

function saveEdit() {
    const ok = [
        validateField('edit_examboard',     'err_edit_examboard'),
        validateField('edit_qualification', 'err_edit_qualification'),
        validateField('edit_code',          'err_edit_code'),
        validateField('edit_description',   'err_edit_description'),
    ].every(Boolean);
    if (!ok) return;

    const id  = document.getElementById('edit_id').value;
    const btn = document.getElementById('editSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:5px;"></i>Updatingâ€¦';

    const payload = {
        examboard:     document.getElementById('edit_examboard').value.trim(),
        qualification: document.getElementById('edit_qualification').value.trim(),
        code:          document.getElementById('edit_code').value.trim(),
        description:   document.getElementById('edit_description').value.trim(),
        session:       document.getElementById('edit_session').value.trim() || null,
        amount:        document.getElementById('edit_amount').value.trim() || null,
    };

    fetch(`${baseUrl}/${id}/update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal('editOverlay');
            updateRow(data.subject);
            showToast('Subject updated successfully.');
        } else {
            showToast('Failed to update subject.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save" style="margin-right:5px;"></i>Update Subject';
    });
}

// â”€â”€ DELETE â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function openDeleteModal(id) {
    document.getElementById('delete_id').value = id;
    const d = rowData[id];
    document.getElementById('deleteSubjectName').textContent = `${d.code} â€” ${d.description}`;
    openModal('deleteOverlay');
}

function confirmDelete() {
    const id  = document.getElementById('delete_id').value;
    const btn = document.getElementById('deleteConfirmBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:5px;"></i>Deletingâ€¦';

    fetch(`${baseUrl}/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeModal('deleteOverlay');
            removeRow(id);
            showToast('Subject deleted successfully.');
        } else {
            showToast('Failed to delete subject.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-trash" style="margin-right:5px;"></i>Yes, Delete';
    });
}

// â”€â”€ DOM helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function buildRow(s, isEven) {
    const amountVal = s.amount !== null ? parseFloat(s.amount).toFixed(2) : '';
    const sessionBadge = s.session
        ? `<span style="background:#fff3cd;color:#856404;padding:2px 8px;border-radius:4px;font-size:12px;white-space:nowrap;">${s.session}</span>`
        : `<span style="color:#aaa;font-size:12px;">â€”</span>`;

    rowData[s.id] = {
        examboard: s.examboard, qualification: s.qualification,
        code: s.code, description: s.description,
        session: s.session || '', amount: s.amount,
    };

    const tr = document.createElement('tr');
    tr.dataset.id = s.id;
    tr.style.background = isEven ? '#fff' : '#f8f9fa';
    tr.style.borderBottom = '1px solid #e9ecef';
    tr.innerHTML = `
        <td style="padding:8px 13px;">
            <span style="background:#cfe2ff;color:#084298;padding:2px 8px;border-radius:4px;font-size:12px;white-space:nowrap;">${s.examboard}</span>
        </td>
        <td style="padding:8px 13px;">
            <span style="background:#d1e7dd;color:#0a3622;padding:2px 8px;border-radius:4px;font-size:12px;white-space:nowrap;">${s.qualification}</span>
        </td>
        <td style="padding:8px 13px;font-weight:600;color:#333;">${s.code}</td>
        <td style="padding:8px 13px;color:#444;">${s.description}</td>
        <td style="padding:6px 13px;">
            <div style="display:flex;align-items:center;gap:5px;">
                <span style="color:#555;font-size:12px;">&#163;</span>
                <input type="number" min="0" step="0.01" class="amount-input" data-id="${s.id}" value="${amountVal}" placeholder="0.00" />
                <button type="button" class="save-amount-btn" data-id="${s.id}" title="Save amount"><i class="fas fa-save"></i></button>
            </div>
        </td>
        <td style="padding:8px 13px;">${sessionBadge}</td>
        <td style="padding:8px 13px;text-align:center;">
            <div style="display:flex;gap:5px;justify-content:center;">
                <button type="button" class="btn-edit" onclick="openEditModal(${s.id})" title="Edit"><i class="fas fa-pen"></i></button>
                <button type="button" class="btn-del" onclick="openDeleteModal(${s.id})" title="Delete"><i class="fas fa-trash"></i></button>
            </div>
        </td>`;

    // Bind amount save
    tr.querySelector('.save-amount-btn').addEventListener('click', () => saveAmount(s.id));
    tr.querySelector('.amount-input').addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); saveAmount(s.id); }
    });
    return tr;
}

function appendRow(subject) {
    const tbody = document.getElementById('subjectsBody');
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.remove();
    const isEven = tbody.querySelectorAll('tr').length % 2 === 0;
    tbody.appendChild(buildRow(subject, isEven));
    updateCount(1);
}

function updateRow(subject) {
    const tbody = document.getElementById('subjectsBody');
    const existing = tbody.querySelector(`tr[data-id="${subject.id}"]`);
    if (!existing) return;
    const isEven = existing.style.background === 'rgb(255, 255, 255)';
    const newRow  = buildRow(subject, isEven);
    tbody.replaceChild(newRow, existing);
}

function removeRow(id) {
    const tr = document.querySelector(`tr[data-id="${id}"]`);
    if (tr) tr.remove();
    // Re-stripe remaining rows
    document.querySelectorAll('#subjectsBody tr').forEach((r, i) => {
        r.style.background = i % 2 === 0 ? '#fff' : '#f8f9fa';
    });
    updateCount(-1);
    if (document.querySelectorAll('#subjectsBody tr').length === 0) {
        const empty = document.createElement('tr');
        empty.id = 'emptyRow';
        empty.innerHTML = `<td colspan="7" style="padding:30px;text-align:center;color:#888;">
            <i class="fas fa-inbox fa-2x" style="display:block;margin-bottom:8px;color:#ccc;"></i>No subjects found.</td>`;
        document.getElementById('subjectsBody').appendChild(empty);
    }
}

function updateCount(delta) {
    const el = document.getElementById('rowCount');
    if (el) el.textContent = parseInt(el.textContent) + delta;
}

// â”€â”€ Inline Amount Save â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
function saveAmount(id) {
    const input = document.querySelector(`.amount-input[data-id="${id}"]`);
    const btn   = document.querySelector(`.save-amount-btn[data-id="${id}"]`);
    const amount = input.value.trim();
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`${baseUrl}/${id}/amount`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ amount: amount === '' ? null : amount }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (data.amount !== null) input.value = parseFloat(data.amount).toFixed(2);
            if (rowData[id]) rowData[id].amount = data.amount;
            input.style.borderColor = '#28a745';
            setTimeout(() => input.style.borderColor = '#ced4da', 1500);
            showToast('Amount saved.');
        } else {
            showToast('Failed to save amount.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i>';
    });
}

// Bind amount buttons on page-load rows
document.querySelectorAll('.save-amount-btn').forEach(btn => {
    btn.addEventListener('click', () => saveAmount(btn.dataset.id));
});
document.querySelectorAll('.amount-input').forEach(input => {
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); saveAmount(input.dataset.id); }
    });
});

// â”€â”€ Filter cascade dropdowns â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
const boardSelect   = document.getElementById('boardSelect');
const qualSelect    = document.getElementById('qualSelect');
const sessionSelect = document.getElementById('sessionSelect');
const currentSession = filterSession, currentBoard = filterBoard, currentQual = filterQual;

function updateBoardDropdown(sel, keep) {
    boardSelect.innerHTML = '<option value="">-- All Boards --</option>';
    let list = sel && sessionBoardMap[sel]
        ? sessionBoardMap[sel]
        : Array.from(new Set(Object.values(sessionBoardMap).flat())).sort();
    list.forEach(b => {
        const o = new Option(b, b, false, b === keep);
        boardSelect.add(o);
    });
}

function updateQualDropdown(sel, board, keep) {
    qualSelect.innerHTML = '<option value="">-- All Qualifications --</option>';
    let list;
    if (sel && board && sessionBoardQualMap[sel]?.[board]) list = sessionBoardQualMap[sel][board];
    else if (board && boardQualMap[board])                  list = boardQualMap[board];
    else if (sel && sessionBoardQualMap[sel])               list = Array.from(new Set(Object.values(sessionBoardQualMap[sel]).flat())).sort();
    else                                                    list = Array.from(new Set(Object.values(boardQualMap).flat())).sort();
    list.forEach(q => {
        const o = new Option(q, q, false, q === keep);
        qualSelect.add(o);
    });
}

updateBoardDropdown(currentSession, currentBoard);
updateQualDropdown(currentSession, currentBoard, currentQual);

sessionSelect.addEventListener('change', function () {
    updateBoardDropdown(this.value, '');
    updateQualDropdown(this.value, '', '');
    document.getElementById('filterForm').submit();
});
boardSelect.addEventListener('change', function () {
    updateQualDropdown(sessionSelect.value, this.value, '');
    document.getElementById('filterForm').submit();
});
qualSelect.addEventListener('change', () => document.getElementById('filterForm').submit());

// Escape key closes active modal
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.os-overlay.active').forEach(ov => ov.classList.remove('active'));
    }
});
</script>
@endsection