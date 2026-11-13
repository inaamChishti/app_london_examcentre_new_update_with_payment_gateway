@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header" style="background-color: #F87A14; border-bottom: 2px solid #fff;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-4">
                        <h5 class="mb-0 fw-bold" style="color: #fff;">
                            <i class="fas fa-calendar-alt me-2"></i> Exam Datesheets
                        </h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" style="background-color: #57afbfe6; border-color: #17a2b8; color: white;">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="exam-board" class="form-label fw-semibold" style="color: #000000;">Exam Board</label>
                            <select class="form-select rounded-2 shadow-sm" id="exam-board" style="border-color: #F87A14;">
                                <option value="">Select Exam Board</option>
                                @foreach($examBoards as $board)
                                    <option value="{{ $board }}">{{ $board }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date-control" class="form-label fw-semibold" style="color: #000000;">Exam Date</label>
                            <input type="date" class="form-control rounded-2 shadow-sm" id="date-control" style="border-color: #F87A14;">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="datesheetTable" class="table table-hover align-middle">
                            <thead style="background-color: #57afbfe6; color: white;">
                                <tr>
                                    <th class="py-3 px-4"><i class="fas fa-book me-2"></i> Paper Title</th>
                                    <th class="py-3 px-4"><i class="fas fa-code me-2"></i> Exam Code</th>
                                    <th class="py-3 px-4"><i class="fas fa-calendar-day me-2"></i> Exam Date</th>
                                    <th class="py-3 px-4"><i class="fas fa-clock me-2"></i> Duration</th>
                                    <th class="py-3 px-4"><i class="fas fa-sun me-2"></i> Session</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        background-color: #ffffff;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(23, 162, 184, 0.2) !important;
    }
    .form-select, .form-control {
        border-color: #57afbfe6;
        background-color: #ffffff;
    }
    .form-select:focus, .form-control:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
    }
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #17a2b8;
    }
    .table td {
        border-bottom: 1px solid #b2e4ede6;
        color: #17a2b8;
    }
    .table-hover tbody tr:hover {
        background-color: #b2e4ede6;
    }
</style>

<!-- Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#57afbfe6',
            color: 'white'
        });

        // Initialize DataTable
        var table = $('#datesheetTable').DataTable({
            searching: false,
            lengthChange: false,
            pageLength: 10,
            info: false,
            order: [[2, 'asc']],
            language: {
                emptyTable: '<span style="color: #17a2b8;"><i class="fas fa-info-circle me-2"></i>No datesheets available. Please select an exam board.</span>'
            },
            columnDefs: [
                { width: '30%', targets: 0 },
                { width: '20%', targets: 1 },
                { width: '20%', targets: 2 },
                { width: '15%', targets: 3 },
                { width: '15%', targets: 4 }
            ]
        });

        // Fetch Data Function
        function fetchData(examBoard, date) {
            if (!examBoard) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Exam Board Required',
                    text: 'Please select an exam board first',
                    confirmButtonColor: '#17a2b8',
                    background: '#b2e4ede6'
                });
                return;
            }

            $.ajax({
                url: "{{ url('get-board') }}",
                method: "GET",
                data: { examBoard: examBoard, date: date },
                beforeSend: function() {
                    table.clear().draw();
                    table.row.add([
                        '<div class="text-center py-4" style="color: #17a2b8;"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</div>',
                        '', '', '', ''
                    ]).draw();
                },
                success: function(response) {
                    table.clear().draw();

                    if (!response.length) {
                        table.row.add([
                            '<span style="color: #17a2b8;"><i class="fas fa-exclamation-circle me-2"></i>No data found</span>',
                            '', '', '', ''
                        ]).draw();
                        return;
                    }

                    $.each(response, function(index, subject) {
                        table.row.add([
                            '<i class="fas fa-book me-2 text-primary"></i>' + (subject.paper_title || '-'),
                            '<i class="fas fa-code me-2 text-success"></i>' + (subject.exam_code || '-'),
                            '<i class="fas fa-calendar-day me-2 text-info"></i>' + (subject.date || '-'),
                            '<i class="fas fa-clock me-2 text-warning"></i>' + (subject.duration || '-'),
                            '<i class="fas fa-sun me-2 text-danger"></i>' + (subject.exam_session || '-')
                        ]).draw();
                    });
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error loading datesheet data'
                    });
                }
            });
        }

        // Event Listeners
        $('#exam-board, #date-control').on('change', function() {
            fetchData($('#exam-board').val(), $('#date-control').val());
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        confirmButtonColor: '#17a2b8',
        background: '#b2e4ede6',
        color: '#17a2b8'
    });
</script>
@endif
@endsection
