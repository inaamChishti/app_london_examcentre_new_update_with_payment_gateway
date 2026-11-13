@extends('layouts.app')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .container {
        max-width: 1400px;
        margin: 20px auto;
        padding: 0 15px;
    }

    /* Select2 custom styling to match the page theme */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ddd;
        border-radius: 5px;
        min-height: 45px;
        padding: 5px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #F87A14;
        box-shadow: 0 0 0 3px rgba(213, 173, 0, 0.1);
        outline: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #F87A14;
        border: none;
        color: white;
        border-radius: 4px;
        padding: 2px 8px;
        margin-top: 5px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
        border: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .filter-group .select2-container {
        width: 100% !important;
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

    .table tfoot {
        background: #f8f9fa;
        font-weight: bold;
    }

    .table tfoot td {
        border-top: 2px solid #F87A14;
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

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.95rem;
        background: white;
        transition: border-color 0.3s;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #F87A14;
        box-shadow: 0 0 0 3px rgba(213, 173, 0, 0.1);
    }

    .filter-group select[multiple] {
        min-height: 140px;
        padding: 8px;
    }

    .filter-group select[multiple] option {
        padding: 8px 10px;
    }

    .filter-help {
        display: block;
        margin-top: 6px;
        color: #666;
        font-size: 0.8rem;
    }

    .filter-help-inline {
        font-size: 0.6rem;
        color: #aaa;
        font-weight: 400;
        margin-left: 5px;
        letter-spacing: -0.2px;
        display: inline-block;
        white-space: nowrap;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
    }

    .btn-filter {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-filter-reset {
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

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        border-radius: 8px;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #F87A14;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        margin-left: 15px;
        font-size: 1.1rem;
        color: #333;
        font-weight: 600;
    }

    .card {
        position: relative;
    }
</style>

<div class="container">
    <!-- Header -->
    <div class="header">
        <i class="fas fa-chart-bar"></i>
        <h3>Exam Report by Subject</h3>
    </div>

    <!-- Session Info -->
    <div class="session-info">
        <i class="fas fa-calendar-alt"></i>
        <strong>Current Session:</strong> {{ session('current_session') ?? 'Not Set' }}
    </div>

    <!-- Filter Container -->
    <div class="filter-container">
        <div class="filter-row">
            <div class="filter-group">
                <label for="filterExamBoard"><i class="fas fa-building"></i> Exam Board</label>
                <select id="filterExamBoard">
                    <option value="">All Exam Boards</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filterQualification">
                    <i class="fas fa-graduation-cap"></i> Qualification
                    <span class="filter-help-inline">(Select one or more qualifications. Leave blank to show all.)</span>
                </label>
                <select id="filterQualification" multiple size="6"></select>
            </div>
            <div class="filter-group">
                <label for="filterSubject"><i class="fas fa-book"></i> Subject Name</label>
                <input type="text" id="filterSubject" placeholder="Search by subject name or code...">
            </div>
        </div>
        <div class="filter-row" style="margin-top: 15px;">
            <div class="filter-group">
                <label for="filterDateFrom"><i class="fas fa-calendar-alt"></i> Approval Date From</label>
                <input type="date" id="filterDateFrom">
            </div>
            <div class="filter-group">
                <label for="filterDateTo"><i class="fas fa-calendar-alt"></i> Approval Date To</label>
                <input type="date" id="filterDateTo">
            </div>
            <div class="filter-actions">
                <button class="btn-filter btn-filter-reset" onclick="resetFilters()">
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
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading data...</div>
        </div>
        <div class="table-container">
            @if($reportData && $reportData->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-building"></i> Exam Board</th>
                            <th><i class="fas fa-graduation-cap"></i> Qualification</th>
                            <th><i class="fas fa-book"></i> Subject Name / Code</th>
                            <th><i class="fas fa-users"></i> No. of Candidates</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($reportData as $row)
                            <tr>
                                <td>{{ $row->exam_board ?? 'N/A' }}</td>
                                <td>{{ $row->qualification ?? 'N/A' }}</td>
                                <td>{{ $row->subject_name_or_code ?? 'N/A' }}</td>
                                <td style="text-align: center;">{{ $row->no_of_candidates }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <i class="fas fa-info-circle" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                    <p>No exam data found for the current session.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Get DOM elements
    const filterExamBoard = document.getElementById('filterExamBoard');
    const filterQualification = document.getElementById('filterQualification');
    const filterSubject = document.getElementById('filterSubject');
    const filterDateFrom = document.getElementById('filterDateFrom');
    const filterDateTo = document.getElementById('filterDateTo');
    const filterResults = document.getElementById('filterResults');
    const tableBody = document.getElementById('tableBody');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Store all rows data
    let allRows = [];
    let uniqueExamBoards = new Set();
    let uniqueQualifications = new Set();
    let totalCandidatesCount = {{ $totalCandidates ?? 0 }}; // Store total from server

    function getSelectedQualificationValues() {
        if (!filterQualification) return [];

        // For Select2, it's easier to use jQuery if available, or just get values from the element
        return $(filterQualification).val() || [];
    }

    function setQualificationOptions(qualifications, selectedQualifications = []) {
        if (!filterQualification) return;

        // Save current selection if not provided
        const currentSelection = selectedQualifications.length > 0 ? selectedQualifications : getSelectedQualificationValues();

        // Clear existing options
        $(filterQualification).empty();

        // Add options
        Array.from(qualifications).sort().forEach(qual => {
            const isSelected = currentSelection.includes(qual);
            const option = new Option(qual, qual, false, isSelected);
            $(filterQualification).append(option);
        });

        // Initialize Select2 if not already done
        if (!$(filterQualification).hasClass('select2-hidden-accessible')) {
            $(filterQualification).select2({
                placeholder: "Select one or more qualifications",
                allowClear: true,
                width: '100%'
            });
        }
        
        // Always trigger change to notify Select2 and any listeners
        $(filterQualification).trigger('change');
    }
    
    // Initialize: Extract data from table and populate filters
    function initializeFilters() {
        if (!tableBody) return;

        const currentExamBoard = filterExamBoard.value;
        const currentQualifications = getSelectedQualificationValues();
        const rows = tableBody.querySelectorAll('tr');
        allRows = Array.from(rows);
        
        // Reset Sets to avoid duplicates
        uniqueExamBoards.clear();
        uniqueQualifications.clear();
        
        // Extract unique values for filters
        allRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 3) {
                const examBoard = cells[0].textContent.trim();
                const qualification = cells[1].textContent.trim();
                
                if (examBoard && examBoard !== 'N/A') {
                    uniqueExamBoards.add(examBoard);
                }
                if (qualification && qualification !== 'N/A') {
                    uniqueQualifications.add(qualification);
                }
            }
        });
        
        // Clear existing options (except "All" option)
        filterExamBoard.innerHTML = '<option value="">All Exam Boards</option>';
        
        // Populate Exam Board dropdown
        Array.from(uniqueExamBoards).sort().forEach(board => {
            const option = document.createElement('option');
            option.value = board;
            option.textContent = board;
            filterExamBoard.appendChild(option);
        });

        if (currentExamBoard && uniqueExamBoards.has(currentExamBoard)) {
            filterExamBoard.value = currentExamBoard;
        } else {
            filterExamBoard.value = '';
        }

        updateQualificationFilter(currentQualifications, false);
        
        // Ensure Select2 is initialized
        if (!$(filterQualification).hasClass('select2-hidden-accessible')) {
            $(filterQualification).select2({
                placeholder: "Select one or more qualifications",
                allowClear: true,
                width: '100%'
            });
        }
        
        // Attach change listener once if not already attached
        $(filterQualification).off('change.filter').on('change.filter', function() {
            applyClientFilters();
        });
        
        // Update results message on initialization
        if (filterResults && allRows.length > 0) {
            filterResults.innerHTML = `Showing all ${allRows.length} records | Showing all ${totalCandidatesCount} candidates`;
        }
    }
    
    // Show loading indicator
    function showLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.add('active');
        }
    }
    
    // Hide loading indicator
    function hideLoading() {
        if (loadingOverlay) {
            loadingOverlay.classList.remove('active');
        }
    }
    
    // Load data from server with filters
    function loadFilteredData() {
        const dateFrom = filterDateFrom.value;
        const dateTo = filterDateTo.value;
        
        // If date range is selected, fetch from server
        if (dateFrom || dateTo) {
            // Show loading indicator
            showLoading();
            
            const params = new URLSearchParams();
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);
            
            fetch(`{{ url('exam-report-by-subject') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateTable(data.reportData);
                // Update total candidates count from server
                if (data.totalCandidates !== undefined) {
                    totalCandidatesCount = data.totalCandidates;
                }
                // Update filter results with server total if provided
                if (data.totalCandidates !== undefined && filterResults) {
                    const recordCount = data.reportData && data.reportData.length > 0 ? data.reportData.length : 0;
                    filterResults.innerHTML = `Showing all ${recordCount} records | Showing all ${data.totalCandidates} candidates`;
                }
                applyClientFilters();
                hideLoading();
            })
            .catch(error => {
                console.error('Error loading data:', error);
                hideLoading();
                alert('Error loading data. Please try again.');
            });
        } else {
            // No date filter, use client-side filtering
            applyClientFilters();
        }
    }
    
    // Update table with new data
    function updateTable(data) {
        if (!tableBody) return;
        
        tableBody.innerHTML = '';
        
        if (data && data.length > 0) {
            data.forEach(row => {
                const candidates = parseInt(row.no_of_candidates) || 0;
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.exam_board || 'N/A'}</td>
                    <td>${row.qualification || 'N/A'}</td>
                    <td>${row.subject_name_or_code || 'N/A'}</td>
                    <td style="text-align: center;">${candidates}</td>
                `;
                tableBody.appendChild(tr);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 40px;">No data found</td></tr>';
        }
        
        // Update filter results with record count
        const recordCount = data && data.length > 0 ? data.length : 0;
        
        if (filterResults) {
            filterResults.innerHTML = `Showing all ${recordCount} records | Showing all ${totalCandidatesCount} candidates`;
        }
        
        // Re-initialize filters with new data
        initializeFilters();
    }
    
    // Client-side filter function (for Exam Board, Qualification, Subject Name)
    function applyClientFilters() {
        const examBoardValue = filterExamBoard.value.toLowerCase().trim();
        const qualificationValues = getSelectedQualificationValues()
            .map(value => value.toLowerCase().trim());
        const subjectValue = filterSubject.value.toLowerCase().trim();
        
        if (!tableBody) return;
        
        // Perform filtering
        performClientFiltering(examBoardValue, qualificationValues, subjectValue);
    }
    
    // Perform actual client-side filtering
    function performClientFiltering(examBoardValue, qualificationValues, subjectValue) {
        if (!tableBody) return;
        
        // Show loading spinner
        showLoading();
        
        // Use a small timeout to allow UI to update and show the spinner
        setTimeout(() => {
            const rows = tableBody.querySelectorAll('tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 4) {
                    // Check if it's the "No data found" row
                    if (cells.length === 1 && cells[0].colSpan === 4) {
                        row.classList.remove('table-row-hidden');
                        return;
                    }
                    row.classList.add('table-row-hidden');
                    return;
                }
                
                const examBoard = cells[0].textContent.trim().toLowerCase();
                const qualification = cells[1].textContent.trim().toLowerCase();
                const subject = cells[2].textContent.trim().toLowerCase();
                
                // Apply filters
                let showRow = true;
                
                if (examBoardValue && examBoard !== examBoardValue) {
                    showRow = false;
                }
                
                if (showRow && qualificationValues.length > 0 && !qualificationValues.includes(qualification)) {
                    showRow = false;
                }
                
                if (showRow && subjectValue && !subject.includes(subjectValue)) {
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
            
            // Update results message with record count
            const totalCount = Array.from(rows).filter(r => r.querySelectorAll('td').length >= 4).length;
            let recordsText;
            if (visibleCount === totalCount) {
                recordsText = `Showing all ${totalCount} records`;
            } else {
                recordsText = `Showing ${visibleCount} of ${totalCount} records`;
            }
            
            // Get filtered candidate count from server
            getFilteredCandidateCount(recordsText);
            
            // Hide spinner
            hideLoading();
        }, 50);
    }
    
    // Get filtered candidate count from server
    function getFilteredCandidateCount(recordsText) {
        const params = new URLSearchParams();
        
        // Get actual filter values
        const examBoardSelect = document.getElementById('filterExamBoard');
        const subjectInput = document.getElementById('filterSubject');
        const dateFrom = document.getElementById('filterDateFrom').value;
        const dateTo = document.getElementById('filterDateTo').value;
        const selectedQualifications = getSelectedQualificationValues();
        
        if (examBoardSelect && examBoardSelect.value) {
            params.append('exam_board', examBoardSelect.value);
        }
        selectedQualifications.forEach(value => params.append('qualification[]', value));
        if (subjectInput && subjectInput.value) {
            params.append('subject', subjectInput.value);
        }
        if (dateFrom) {
            params.append('date_from', dateFrom);
        }
        if (dateTo) {
            params.append('date_to', dateTo);
        }
        
        // Call server to get filtered candidate count
        fetch(`{{ url('get-filtered-candidate-count') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const filteredCandidateCount = data.count || 0;
            const totalCandidateCount = totalCandidatesCount;
            
            // Update display with both filtered and total counts
            let candidatesText;
            if (filteredCandidateCount === totalCandidateCount) {
                candidatesText = `Showing all ${totalCandidateCount} candidates`;
            } else {
                candidatesText = `Showing ${filteredCandidateCount} of ${totalCandidateCount} candidates`;
            }
            
            if (filterResults) {
                filterResults.innerHTML = `${recordsText} | ${candidatesText}`;
            }
        })
        .catch(error => {
            console.error('Error getting filtered candidate count:', error);
            // Fallback to just records count
            if (filterResults) {
                filterResults.innerHTML = `${recordsText} | No. Of Candidate: <span id="totalCandidatesDisplay">${totalCandidatesCount}</span>`;
            }
        });
    }
    
    // Main filter function
    function applyFilters() {
        loadFilteredData();
    }
    
    // Reset filters - reload page to original state
    function resetFilters() {
        window.location.href = '{{ url("exam-report-by-subject") }}';
    }
    
    // Update qualification dropdown based on selected exam board
    function updateQualificationFilter(preferredQualifications = getSelectedQualificationValues(), shouldApplyFilters = true) {
        const selectedBoard = filterExamBoard.value.toLowerCase().trim();
        const availableQuals = new Set();
        
        if (!selectedBoard) {
            uniqueQualifications.forEach(qual => availableQuals.add(qual));
        } else {
            allRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 2) {
                    const examBoard = cells[0].textContent.trim().toLowerCase();
                    const qualification = cells[1].textContent.trim();
                    
                    if (examBoard === selectedBoard && qualification && qualification !== 'N/A') {
                        availableQuals.add(qualification);
                    }
                }
            });
        }

        const validSelections = preferredQualifications.filter(qual => availableQuals.has(qual));
        setQualificationOptions(availableQuals, validSelections);

        if (shouldApplyFilters) {
            applyClientFilters();
        }
    }
    
    // Event listeners
    filterExamBoard.addEventListener('change', function() {
        updateQualificationFilter();
    });
    
    // filterQualification change is handled via Select2 listener in initializeFilters
    
    filterSubject.addEventListener('input', applyClientFilters);
    
    filterDateFrom.addEventListener('change', applyFilters);
    
    filterDateTo.addEventListener('change', applyFilters);
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeFilters);
    } else {
        initializeFilters();
    }
    
    // Make resetFilters available globally
    window.resetFilters = resetFilters;
})();
</script>
@endsection
