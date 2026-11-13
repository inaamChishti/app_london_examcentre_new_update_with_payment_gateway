@extends('layouts.app')

@section('content')

<style>
    .tracker-table th,
    .tracker-table td {
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.83rem;
        padding: 0.5rem 0.6rem;
        vertical-align: middle;
    }
    .tracker-table thead th {
        background-color: #57afbfe6;
        color: #fff;
        white-space: nowrap;
        border-bottom: none;
    }
    .badge-sent    { background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; }
    .badge-failed  { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
    .badge-opened  { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; }
    .badge-pending { background:#fef9c3; color:#713f12; border:1px solid #fde047; }
    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
    }
    .pagination .page-link { color: #9c8622; }
    .pagination .page-item.active .page-link {
        background-color: #F87A14;
        border-color: #F87A14;
        color: #fff;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

                {{-- Header --}}
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-3 flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="fas fa-chart-line me-2"></i> Important Message — Email Tracking Report
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-light text-dark px-3 py-2">
                                <i class="fas fa-calendar-alt me-1"></i> Session: {{ $currentSession ?? 'N/A' }}
                            </span>
                            <span class="badge bg-white text-dark px-3 py-2">
                                Total: {{ $logs->total() }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Summary cards --}}
                <div class="card-body pb-2" style="background-color: #f8f9fa;">
                    @php
                        $allLogs     = \App\Models\EmailLog::whereNotNull('tracking_token')->where('session', session('current_session'));
                        $totalSent   = (clone $allLogs)->where('sent_status', 'sent')->count();
                        $totalFailed = (clone $allLogs)->where('sent_status', 'failed')->count();
                        $totalOpened = (clone $allLogs)->whereNotNull('opened_at')->count();
                        $totalPending= (clone $allLogs)->whereNull('opened_at')->where('sent_status','sent')->count();
                    @endphp
                    <div class="row g-3 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center" style="background:#d1fae5; border:1px solid #6ee7b7;">
                                <div class="fw-bold fs-4" style="color:#065f46;">{{ $totalSent }}</div>
                                <div class="small fw-semibold" style="color:#065f46;"><i class="fas fa-paper-plane me-1"></i>Delivered</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center" style="background:#fee2e2; border:1px solid #fca5a5;">
                                <div class="fw-bold fs-4" style="color:#991b1b;">{{ $totalFailed }}</div>
                                <div class="small fw-semibold" style="color:#991b1b;"><i class="fas fa-times-circle me-1"></i>Failed</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center" style="background:#dbeafe; border:1px solid #93c5fd;">
                                <div class="fw-bold fs-4" style="color:#1e40af;">{{ $totalOpened }}</div>
                                <div class="small fw-semibold" style="color:#1e40af;"><i class="fas fa-envelope-open me-1"></i>Opened</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-3 text-center" style="background:#fef9c3; border:1px solid #fde047;">
                                <div class="fw-bold fs-4" style="color:#713f12;">{{ $totalPending }}</div>
                                <div class="small fw-semibold" style="color:#713f12;"><i class="fas fa-clock me-1"></i>Not Opened</div>
                            </div>
                        </div>
                    </div>

                    {{-- Search + info bar --}}
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <p class="mb-0 text-muted small">
                            Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} emails
                        </p>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover tracker-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-id-card me-1"></i> Candidate ID</th>
                                    <th><i class="fas fa-envelope me-1"></i> Email Address</th>
                                    <th><i class="fas fa-heading me-1"></i> Subject</th>
                                    <th><i class="fas fa-calendar-alt me-1"></i> Sent Date</th>
                                    <th><i class="fas fa-circle-check me-1"></i> Sent Status</th>
                                    <th><i class="fas fa-envelope-open me-1"></i> Email Open Date</th>
                                    <th><i class="fas fa-cog me-1"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($logs as $i => $log)
                                <tr>
                                    <td>
                                        @if($log->student_id)
                                            <span class="badge bg-secondary">{{ $log->student_id }}</span>
                                        @elseif($log->candidate_id)
                                            <span class="badge bg-secondary">{{ $log->candidate_id }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                        @if($log->candidate_name)
                                            <div class="text-muted" style="font-size:0.75rem;">{{ $log->candidate_name }}</div>
                                        @endif
                                    </td>

                                    <td>{{ $log->email_address ?? '—' }}</td>

                                    <td style="max-width:180px; word-break:break-word;">{{ $log->subject_line }}</td>

                                    <td style="white-space:nowrap;">
                                        {{ $log->message_date?->format('d/m/Y H:i') ?? '—' }}
                                    </td>

                                    <td>
                                        @if($log->sent_status === 'sent')
                                            <span class="status-badge badge-sent"><i class="fas fa-check-circle me-1"></i>Delivered</span>
                                        @elseif($log->sent_status === 'failed')
                                            <span class="status-badge badge-failed"><i class="fas fa-times-circle me-1"></i>Failed</span>
                                        @else
                                            <span class="status-badge badge-pending">{{ ucfirst($log->sent_status ?? '—') }}</span>
                                        @endif
                                    </td>

                                    <td style="white-space:nowrap;">
                                        @if($log->opened_at)
                                            <span class="status-badge badge-opened">
                                                <i class="fas fa-envelope-open me-1"></i>{{ $log->opened_at->format('d/m/Y H:i') }}
                                            </span>
                                        @else
                                            <span class="status-badge badge-pending"><i class="fas fa-clock me-1"></i> Not Opened</span>
                                        @endif
                                    </td>

                                    <td>
                                        <button type="button"
                                            class="btn btn-sm btn-primary view-msg-btn"
                                            data-log-id="{{ $log->id }}"
                                            data-subject="{{ $log->subject_line }}"
                                            data-name="{{ $log->candidate_name ?? '' }}"
                                            data-email="{{ $log->email_address ?? '' }}"
                                            data-date="{{ $log->message_date?->format('d/m/Y H:i') }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        No tracked emails found for session <strong>{{ $currentSession ?? 'N/A' }}</strong>.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($logs->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden message templates — pixel stripped --}}
@foreach($logs as $log)
    @php
        $safeMessage = preg_replace('/<div[^>]*font-size:1px[^>]*>.*?<\/div>/si', '', $log->message ?? '');
        $safeMessage = preg_replace('/<img[^>]+email-track[^>]*\/?>/si', '', $safeMessage);
    @endphp
    <template id="msg-{{ $log->id }}">{!! $safeMessage !!}</template>
@endforeach

{{-- Modal --}}
<div class="modal fade" id="msgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#F87A14; border-bottom: 4px solid #1976D2; color:#fff;">
                <h5 class="modal-title"><i class="fas fa-envelope me-2"></i><span id="modalSubject"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-md-6"><span class="text-muted small">Recipient:</span><strong id="modalEmail" class="d-block"></strong></div>
                    <div class="col-md-6"><span class="text-muted small">Name:</span><strong id="modalName" class="d-block"></strong></div>
                    <div class="col-md-6"><span class="text-muted small">Sent:</span><strong id="modalDate" class="d-block"></strong></div>
                </div>
                <hr>
                <div id="modalMsg" class="border rounded p-3 bg-white" style="max-height:60vh; overflow-y:auto;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<button id="modalTrigger" class="d-none" data-bs-toggle="modal" data-bs-target="#msgModal"></button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // View modal
    document.querySelectorAll('.view-msg-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id  = this.dataset.logId;
            const tpl = document.getElementById('msg-' + id);
            document.getElementById('modalSubject').textContent = this.dataset.subject || '—';
            document.getElementById('modalEmail').textContent   = this.dataset.email   || '—';
            document.getElementById('modalName').textContent    = this.dataset.name    || '—';
            document.getElementById('modalDate').textContent    = this.dataset.date    || '—';
            document.getElementById('modalMsg').innerHTML       = tpl ? tpl.innerHTML  : '';
            document.getElementById('modalTrigger').click();
        });
    });
});
</script>

@endsection
