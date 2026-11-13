@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 1400px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .header {
        background: #F87A14;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        color: white;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 4px solid #1976D2;
    }

    .header h3 {
        white-space: nowrap;
        margin: 0;
    }

    .header i {
        margin-right: 10px;
        font-size: 1.3rem;
        color: #ffffff;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        background: white;
    }

    .table-container {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .table th, .table td {
        padding: 12px 15px;
        border: 1px solid #eee;
        text-align: left;
    }

    .table th {
        background: #F87A14;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
    }

    .table tr:nth-child(even) {
        background: #f9f9f9;
    }

    .table tr:hover {
        background: #f5f5f5;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #666;
        font-size: 1.1rem;
    }

    .session-info {
        background: #e3f2fd;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .session-info i {
        color: #1976d2;
    }

    .filter-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
    }

    .filter-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.95rem;
        background: white;
        transition: border-color 0.3s;
    }

    .filter-group input:focus {
        outline: none;
        border-color: #F87A14;
        box-shadow: 0 0 0 3px rgba(213, 173, 0, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
    }

    .btn-filter-reset {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s;
        background: #6c757d;
        color: white;
    }

    .btn-filter-reset:hover {
        background: #5a6268;
    }

    .filter-results {
        margin-top: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
        font-size: 0.9rem;
        color: #666;
    }

    .table-row-hidden {
        display: none;
    }
</style>

<div class="container">
    <!-- Header -->
    <div class="header">
        <i class="fas fa-check-circle"></i>
        <h3>Candidate Approval Report</h3>
    </div>

    <!-- Session Info -->
    <div class="session-info">
        <i class="fas fa-calendar-alt"></i>
        <strong>Current Session:</strong> {{ $currentSession ?? 'Not Set' }}
    </div>

    <!-- Filter Container -->
    <div class="filter-container">
        <div class="filter-row">
            <div class="filter-group">
                <label for="filterCandidateId"><i class="fas fa-id-card"></i> Candidate ID</label>
                <input type="text" id="filterCandidateId" placeholder="Search by candidate ID...">
            </div>
            <div class="filter-group">
                <label for="filterCandidateName"><i class="fas fa-user"></i> Candidate Name</label>
                <input type="text" id="filterCandidateName" placeholder="Search by candidate name...">
            </div>
            <div class="filter-actions">
                <button class="btn-filter-reset" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </div>
        <div class="filter-results" id="filterResults">
            Showing all records | Showing all {{ $totalCandidates ?? 0 }} candidates
        </div>
    </div>

    <!-- Table Container -->
    <div class="card">
        <div class="table-container">
            @if($approvedCandidates && $approvedCandidates->count() > 0)
                <table class="table" id="approvalReportTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-card"></i> Candidate ID</th>
                            <th><i class="fas fa-user"></i> Candidate Name</th>
                            <th><i class="fas fa-calendar-check"></i> Approval Date</th>
                            <th><i class="fas fa-pound-sign"></i> Fee Received</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($approvedCandidates as $candidate)
                            <tr>
                                <td>{{ $candidate->student_id ?? 'N/A' }}</td>
                                <td>{{ $candidate->candidate_name ?? 'N/A' }}</td>
                                <td>{{ $candidate->approval_date ? \Carbon\Carbon::parse($candidate->approval_date)->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                <td>{{ $candidate->fee_received !== null ? '£' . number_format((float) $candidate->fee_received, 2) : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: center; font-weight: bold;">
                                Total Approved Candidates: <span id="totalCount">{{ $approvedCandidates->count() }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="no-data">
                    <i class="fas fa-info-circle" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>No approved candidates found for the current session.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Get DOM elements
    const filterCandidateId = document.getElementById('filterCandidateId');
    const filterCandidateName = document.getElementById('filterCandidateName');
    const filterResults = document.getElementById('filterResults');
    const tableBody = document.getElementById('tableBody');
    const totalCount = document.getElementById('totalCount');
    
    // Store all rows data
    let allRows = [];
    let totalRecords = 0;
    let totalCandidatesCount = {{ $totalCandidates ?? 0 }}; // Store total from server
    
    // Initialize: Extract data from table
    function initializeFilters() {
        if (!tableBody) return;
        
        const rows = tableBody.querySelectorAll('tr');
        allRows = Array.from(rows);
        totalRecords = allRows.length;
        
        // Update results message on initialization
        if (filterResults && allRows.length > 0) {
            filterResults.textContent = `Showing all ${allRows.length} records | Showing all ${totalCandidatesCount} candidates`;
        }
    }
    
    // Apply filters
    function applyFilters() {
        if (!tableBody) return;
        
        const candidateIdValue = filterCandidateId.value.toLowerCase().trim();
        const candidateNameValue = filterCandidateName.value.toLowerCase().trim();
        
        const rows = tableBody.querySelectorAll('tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 4) {
                row.classList.add('table-row-hidden');
                return;
            }
            
            const candidateId = cells[0].textContent.trim().toLowerCase();
            const candidateName = cells[1].textContent.trim().toLowerCase();
            
            // Apply filters
            let showRow = true;
            
            if (candidateIdValue && !candidateId.includes(candidateIdValue)) {
                showRow = false;
            }
            
            if (showRow && candidateNameValue && !candidateName.includes(candidateNameValue)) {
                showRow = false;
            }
            
            // Show/hide row
            if (showRow) {
                row.classList.remove('table-row-hidden');
                visibleCount++;
            } else {
                row.classList.add('table-row-hidden');
            }
        });
        
        // Update results message
        if (filterResults) {
            let recordsText;
            if (visibleCount === totalRecords) {
                recordsText = `Showing all ${totalRecords} records`;
            } else {
                recordsText = `Showing ${visibleCount} of ${totalRecords} records`;
            }
            
            // Always show total candidates count
            filterResults.textContent = `${recordsText} | Showing all ${totalCandidatesCount} candidates`;
        }
        
        // Update total count in footer (show filtered count, not total)
        if (totalCount) {
            totalCount.textContent = visibleCount;
        }
    }
    
    // Reset filters
    function resetFilters() {
        filterCandidateId.value = '';
        filterCandidateName.value = '';
        applyFilters();
    }
    
    // Event listeners
    if (filterCandidateId) {
        filterCandidateId.addEventListener('input', applyFilters);
    }
    
    if (filterCandidateName) {
        filterCandidateName.addEventListener('input', applyFilters);
    }
    
    // Make resetFilters available globally
    window.resetFilters = resetFilters;
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFilters);
    } else {
        initializeFilters();
    }
})();
</script>
@endsection
