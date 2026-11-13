@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mt-5">
    <div style="background: #F87A14; border-bottom: 4px solid #1976D2; border-radius: 8px 8px 0 0; padding: 15px 20px; margin-bottom: 20px; display: flex; align-items: center;">
        <i class="fas fa-calendar-alt fa-2x" style="color: #fff; margin-right: 15px;"></i>
        <h2 style="margin: 0; color: white; font-weight: 700; font-size: 1.5rem;">Internal Timetable</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <button id="export-button" class="btn mb-3" style="background-color: #F87A14; color: white;">
                <i class="fas fa-file-excel" style="color: #fff;"></i> Export to Excel
            </button>

            <div class="card p-3 mb-3" style="background-color: #f8f9fa;">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Select Fields to Display:</label>
                        <select id="field-selector" class="form-control" multiple>
                            @foreach($availableFields as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card p-3 mb-3" style="background-color: #f8f9fa;">
                <div class="row align-items-center" id="filter-section">
                    <!-- Filters will be dynamically added -->
                </div>
            </div>

            <div class="table-responsive" style="zoom:0.8;">
                <table id="timetable-table" class="table table-bordered table-striped text-center">
                    <thead id="table-header"></thead>
                    <tbody id="table-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table {
        margin: 0 auto;
        background-color: #ffffff;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(87, 175, 191, 0.1);
    }
    .btn:hover {
        background-color: #e86a00 !important;
        color: white !important;
    }
    .card {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .input-group-text {
        border: none;
    }
    .sortable {
        cursor: pointer;
    }
    .sort-asc::after {
        content: ' ↑';
    }
    .sort-desc::after {
        content: ' ↓';
    }
</style>

<script>
$(document).ready(function () {



    const fieldIcons = {
        'student_id': 'fa-id-badge',
        'candidate_name': 'fa-user',
        'exam_board': 'fa-chalkboard-teacher',
        'subject_code': 'fa-barcode',
        'subject_name': 'fa-graduation-cap',
        'date': 'fa-calendar-day',
        'day': 'fa-calendar-week',
        'time': 'fa-clock',
        'duration': 'fa-hourglass',
        'access_arrangement': 'fa-hands-helping',
        'spoken': 'fa-comment',
        'coursework': 'fa-file-alt'
    };

    const fieldLabels = @json($availableFields);
    let sortField = null;
    let sortDirection = 'asc';

    // Store original data as JSON
    const originalData = [
        @foreach ($uniqueResults as $result)
        {
            'student_id': '{{ $result['student_id'] }}',
            'candidate_name': '{{ $result['surname'] }} {{ $result['firstName'] }}',
            'exam_board': '{{ $result['exam_board'] }}',
            'subject_code': '{{ $result['subject_code'] }}',
            'subject_name': '{{ $result['subject_name'] }}',
            'date': '{{ $result['date'] ? strtotime($result['date']) : '' }}',
            'date_display': '{{ $result['date'] }}',
            'day': '{{ $result['day'] }}',
            'time': '{{ $result['time'] }}',
            'duration': '{{ $result['duration'] }}',
            'access_arrangement': '{{ $result['access_arrangement'] == 1 ? 'Yes' : 'No' }}',
            'spoken': '{{ $result['nea'] == 'language' ? 'Yes' : 'No' }}',
            'coursework': '{{ $result['nea'] == 'course work' ? 'Yes' : 'No' }}'
        },
        @endforeach
    ];

    // Initialize Select2
    $('#field-selector').select2({
        placeholder: "Select fields to display",
        width: '100%'
    });

    function updateTableDisplay() {
        const selectedFields = $('#field-selector').val() || [];

        // Update headers
        let headerHtml = '<tr style="background-color: #b99c1e; color: white;">';
        selectedFields.forEach(field => {
            headerHtml += `
                <th data-field="${field}" class="sortable">
                    <i class="fas ${fieldIcons[field]}" style="color: #F87A14;"></i>
                    ${fieldLabels[field]}
                </th>`;
        });
        headerHtml += '</tr>';
        $('#table-header').html(headerHtml);

        // Update filters
        let filterHtml = '';
        selectedFields.forEach(field => {
            filterHtml += `
                <div class="col-md-3 mb-2">
                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #b99c1e; color: white;">
                            <i class="fas ${fieldIcons[field]}" style="color: #00000;"></i>
                        </span>
                        ${field === 'date' ?
                            `<input type="date" id="${field}-filter" class="form-control filter-input" data-field="${field}">` :
                            `<input type="text" id="${field}-filter" class="form-control filter-input" data-field="${field}" placeholder="${fieldLabels[field]}">`
                        }
                    </div>
                </div>`;
        });
        filterHtml += `
            <div class="col-md-3 mb-2">
                <button id="clear-filter" class="btn w-100" style="background-color: #F87A14; color: white;">
                    <i class="fas fa-eraser" style="color: #d1eef5;"></i> Clear Filter
                </button>
            </div>`;
        $('#filter-section').html(filterHtml);

        applyFiltersAndSort();
    }

    // Initial table display
    updateTableDisplay();

    // Handle field selection changes
    $('#field-selector').on('change', function() {
        updateTableDisplay();
    });

    // Export functionality
    $('#export-button').click(function () {
        var wb = XLSX.utils.table_to_book(document.getElementById('timetable-table'));
        XLSX.writeFile(wb, 'timetable.xlsx');
    });

    // Combined filter and sort function
    function applyFiltersAndSort() {
        const selectedFields = $('#field-selector').val() || [];
        let filteredData = [...originalData];

        // Apply filters
        selectedFields.forEach(field => {
            const filterValue = $(`#${field}-filter`).val();
            if (filterValue) {
                filteredData = filteredData.filter(row => {
                    const cellValue = field === 'date' ? row['date_display'] : row[field];
                    if (field === 'date') {
                        try {
                            const filterDate = new Date(filterValue);
                            if (isNaN(filterDate.getTime())) return true;
                            const cellDate = parseDate(cellValue);
                            return cellDate && cellDate.toDateString() === filterDate.toDateString();
                        } catch (e) {
                            return true;
                        }
                    }
                    return cellValue && cellValue.toLowerCase().includes(filterValue.toLowerCase());
                });
            }
        });

        // Sort data
        if (sortField && selectedFields.includes(sortField) && filteredData.length > 0) {
            filteredData.sort((a, b) => {
                const aValue = a[sortField === 'date' ? 'date' : sortField] || '';
                const bValue = b[sortField === 'date' ? 'date' : sortField] || '';

                if (sortField === 'date') {
                    const aNum = parseInt(aValue) || 0;
                    const bNum = parseInt(bValue) || 0;
                    return sortDirection === 'asc' ? aNum - bNum : bNum - aNum;
                }

                return sortDirection === 'asc'
                    ? String(aValue).localeCompare(String(bValue))
                    : String(bValue).localeCompare(String(aValue));
            });
        }

        // Update table body
        let bodyHtml = '';
        if (filteredData.length > 0) {
            filteredData.forEach(row => {
                bodyHtml += '<tr>';
                selectedFields.forEach(field => {
                    const displayValue = field === 'date' ? row['date_display'] : row[field];
                    bodyHtml += `
                        <td data-field="${field}" data-value="${field === 'date' ? (row[field] || '') : (row[field] || '')}">
                            ${displayValue || ''}
                        </td>`;
                });
                bodyHtml += '</tr>';
            });
        } else if (selectedFields.length > 0) {
            bodyHtml = `<tr><td colspan="${selectedFields.length}">No matching records found</td></tr>`;
        }

        $('#table-body').html(bodyHtml);

        // Update sort indicators
        $('.sortable').removeClass('sort-asc sort-desc');
        if (sortField && selectedFields.includes(sortField) && filteredData.length > 0) {
            $(`th[data-field="${sortField}"]`).addClass(`sort-${sortDirection}`);
        }
    }

    function parseDate(dateString) {
        if (!dateString) return null;
        const parts = dateString.split('/');
        return new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
    }

    // Event listeners
    $(document).on('change input', '.filter-input', function() {
        applyFiltersAndSort();
    });

    $(document).on('click', '#clear-filter', function() {
        $('.filter-input').val('');
        applyFiltersAndSort();
    });

    $(document).on('click', '.sortable', function() {
        const newSortField = $(this).data('field');

        if (sortField === newSortField) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortField = newSortField;
            sortDirection = 'asc';
        }

        applyFiltersAndSort();
    });
});

</script>

@endsection
