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

    .export-btn {
        display: none;
        background: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s;
        margin-bottom: 15px;
    }

    .export-btn:hover {
        background: #218838;
    }

    .export-btn i {
        margin-right: 5px;
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
        box-shadow: 0 0 0 3px rgba(248, 122, 20, 0.1);
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
        <i class="fas fa-universal-access"></i>
        <h3>Access Arrangement</h3>
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
                <label for="filterCandidateNumber"><i class="fas fa-id-card"></i> Candidate Number</label>
                <input type="text" id="filterCandidateNumber" placeholder="Search by candidate number...">
            </div>
            <div class="filter-group">
                <label for="filterStudentName"><i class="fas fa-user"></i> Student Name</label>
                <input type="text" id="filterStudentName" placeholder="Search by student name...">
            </div>
            <div class="filter-actions">
                <button class="btn-filter-reset" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </div>
        <div class="filter-results" id="filterResults">
            Showing all records
        </div>
    </div>

    <!-- Export Button -->
    <button class="export-btn" onclick="exportToExcel()">
        <i class="fas fa-file-excel"></i> Export to Excel
    </button>

    <!-- Table Container -->
    <div class="card">
        <div class="table-container">
            @if($students && $students->count() > 0)
                <table class="table" id="accessArrangementTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-card"></i> Candidate Number</th>
                            <th><i class="fas fa-user"></i> Candidate Name</th>
                            <th><i class="fas fa-heartbeat"></i> Medical Condition</th>
                            <th><i class="fas fa-universal-access"></i> Access Arrangement</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->candidate_number ?? $student->student_id ?? 'N/A' }}</td>
                                <td>{{ $student->candidate_name ?? 'N/A' }}</td>
                                <td>{{ $student->medical_condition ?? 'N/A' }}</td>
                                <td>{{ $student->access_arrangement ?? 'Yes' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: center; font-weight: bold;">
                                Total Candidates: <span id="totalCount">{{ $students->count() }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div class="no-data">
                    <i class="fas fa-info-circle" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>No candidates found with Access Arrangement set to Yes.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Get DOM elements
    const filterCandidateNumber = document.getElementById('filterCandidateNumber');
    const filterStudentName = document.getElementById('filterStudentName');
    const filterResults = document.getElementById('filterResults');
    const tableBody = document.getElementById('tableBody');
    const totalCount = document.getElementById('totalCount');
    
    // Store all rows data
    let allRows = [];
    let totalRecords = 0;
    
    // Initialize: Extract data from table
    function initializeFilters() {
        if (!tableBody) return;
        
        const rows = tableBody.querySelectorAll('tr');
        allRows = Array.from(rows);
        totalRecords = allRows.length;
        
        // Update results message on initialization
        if (filterResults && allRows.length > 0) {
            filterResults.textContent = `Showing all ${allRows.length} records`;
        }
    }
    
    // Apply filters
    function applyFilters() {
        if (!tableBody) return;
        
        const candidateNumberValue = filterCandidateNumber.value.toLowerCase().trim();
        const studentNameValue = filterStudentName.value.toLowerCase().trim();
        
        const rows = tableBody.querySelectorAll('tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 4) {
                row.classList.add('table-row-hidden');
                return;
            }
            
            const candidateNumber = cells[0].textContent.trim().toLowerCase();
            const studentName = cells[1].textContent.trim().toLowerCase();
            
            // Apply filters
            let showRow = true;
            
            if (candidateNumberValue && !candidateNumber.includes(candidateNumberValue)) {
                showRow = false;
            }
            
            if (showRow && studentNameValue && !studentName.includes(studentNameValue)) {
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
            if (visibleCount === totalRecords) {
                filterResults.textContent = `Showing all ${totalRecords} records`;
            } else {
                filterResults.textContent = `Showing ${visibleCount} of ${totalRecords} records`;
            }
        }
        
        // Update total count in footer
        if (totalCount) {
            totalCount.textContent = visibleCount;
        }
    }
    
    // Reset filters
    function resetFilters() {
        filterCandidateNumber.value = '';
        filterStudentName.value = '';
        applyFilters();
    }
    
    // Event listeners
    if (filterCandidateNumber) {
        filterCandidateNumber.addEventListener('input', applyFilters);
    }
    
    if (filterStudentName) {
        filterStudentName.addEventListener('input', applyFilters);
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

function exportToExcel() {
    // Load SheetJS library if not already loaded
    if (typeof XLSX === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js';
        script.onload = function() {
            performExport();
        };
        document.head.appendChild(script);
    } else {
        performExport();
    }
}

function performExport() {
    // Get table data (only visible rows)
    const table = document.getElementById('accessArrangementTable');
    if (!table) {
        alert('No data to export');
        return;
    }

    try {
        // Create a new workbook
        const wb = XLSX.utils.book_new();
        
        // Clone the table to export only visible rows
        const clonedTable = table.cloneNode(true);
        const clonedBody = clonedTable.querySelector('tbody');
        
        // Remove hidden rows from cloned table
        if (clonedBody) {
            const rows = clonedBody.querySelectorAll('tr');
            rows.forEach(row => {
                if (row.classList.contains('table-row-hidden')) {
                    row.remove();
                }
            });
        }
        
        // Convert cloned table to worksheet
        const ws = XLSX.utils.table_to_sheet(clonedTable);
        
        // Set column widths
        ws['!cols'] = [
            { wch: 20 }, // Candidate Number
            { wch: 30 }, // Candidate Name
            { wch: 40 }, // Medical Condition
            { wch: 40 }  // Access Arrangement
        ];
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Access Arrangement');
        
        // Generate filename with current date
        const filename = 'Access_Arrangement_Report_' + new Date().toISOString().split('T')[0] + '.xlsx';
        
        // Write file
        XLSX.writeFile(wb, filename);
    } catch (error) {
        console.error('Export error:', error);
        alert('Error exporting to Excel. Please try again.');
    }
}
</script>
@endsection
