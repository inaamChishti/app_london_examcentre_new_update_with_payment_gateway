@extends('layouts.app')

@section('content')
<style>
    .email-logs-table th,
    .email-logs-table td {
        border-bottom: 1px solid #F87A14;
        color: #000000;
        font-size: 0.85rem;
        padding: 0.45rem 0.5rem;
        vertical-align: middle;
    }

    .email-logs-table th { white-space: nowrap; }

    .id-badge {
        font-size: 0.75rem;
        padding: 0.2rem 0.45rem;
        border-radius: 4px;
        background: #e8f4f8;
        color: #333;
        display: inline-block;
        margin-bottom: 2px;
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
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-3 flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="fas fa-envelope-open-text me-2"></i> Email Logs
                        </h5>
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-calendar-alt me-1"></i> Session: {{ $currentSession ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-3" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <p class="mb-0 text-muted small">
                            Showing {{ $emailLogs->firstItem() ?? 0 }}–{{ $emailLogs->lastItem() ?? 0 }} of {{ $emailLogs->total() }} emails (100 per page)
                        </p>
                        <div class="input-group" style="max-width: 280px;">
                            <span class="input-group-text" style="background-color: #F87A14;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchEmailLogs" class="form-control form-control-sm" placeholder="Search table...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle email-logs-table">
                            <thead style="background-color: #57afbfe6; color: white;">
                                <tr>
                                    <th><i class="fas fa-id-card me-1"></i> ID</th>
                                    <th><i class="fas fa-user me-1"></i> Name</th>
                                    <th><i class="fas fa-calendar me-1"></i> Date</th>
                                    <th><i class="fas fa-heading me-1"></i> Subject</th>
                                    <th><i class="fas fa-cog me-1"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="emailLogsTableBody">
                                @forelse ($emailLogs as $log)
                                    <tr>
                                        <td>
                                            @if ($log->student_id)
                                                <span class="id-badge"><strong>Student:</strong> {{ $log->student_id }}</span><br>
                                            @endif
                                            @if ($log->candidate_id)
                                                <span class="id-badge"><strong>Candidate:</strong> {{ $log->candidate_id }}</span><br>
                                            @endif
                                            @if ($log->uci_number)
                                                <span class="id-badge"><strong>UCI:</strong> {{ $log->uci_number }}</span>
                                            @endif
                                            @if (!$log->student_id && !$log->candidate_id && !$log->uci_number)
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->candidate_name ?? '—' }}</td>
                                        <td>{{ $log->message_date?->format('d/m/Y H:i') }}</td>
                                        <td>{{ $log->subject_line }}</td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-primary view-message-btn"
                                                data-log-id="{{ $log->id }}"
                                                data-subject="{{ $log->subject_line }}"
                                                data-name="{{ $log->candidate_name ?? '' }}"
                                                data-date="{{ $log->message_date?->format('d/m/Y H:i') }}">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            No email logs found for session <strong>{{ $currentSession ?? 'N/A' }}</strong>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($emailLogs->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $emailLogs->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($emailLogs as $log)
    <template id="log-message-{{ $log->id }}">{!! $log->message !!}</template>
@endforeach

<button id="emailModalTrigger" type="button" class="d-none"
    data-bs-toggle="modal" data-bs-target="#emailMessageModal"></button>

<div class="modal fade" id="emailMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F87A14; color: #fff;">
                <h5 class="modal-title"><i class="fas fa-envelope me-2"></i><span id="modalSubject"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>Name:</strong> <span id="modalCandidate"></span></p>
                <p class="mb-3 text-muted small"><strong>Sent:</strong> <span id="modalDate"></span></p>
                <hr>
                <div id="modalMessage" class="border rounded p-3 bg-white" style="max-height: 60vh; overflow-y: auto;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchEmailLogs');
    const tableBody = document.getElementById('emailLogsTableBody');

    if (searchInput && tableBody) {
        searchInput.addEventListener('keyup', function () {
            const term = this.value.toLowerCase();
            tableBody.querySelectorAll('tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    }

    document.querySelectorAll('.view-message-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const logId = this.dataset.logId;
            const tpl = document.getElementById('log-message-' + logId);

            document.getElementById('modalSubject').textContent = this.dataset.subject || '';
            document.getElementById('modalCandidate').textContent = this.dataset.name || '—';
            document.getElementById('modalDate').textContent = this.dataset.date || '';
            document.getElementById('modalMessage').innerHTML = tpl ? tpl.innerHTML : '';

            // Use data-bs-toggle trigger instead of bootstrap global
            document.getElementById('emailModalTrigger').click();
        });
    });
});
</script>
@endsection
