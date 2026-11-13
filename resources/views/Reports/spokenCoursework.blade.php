@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 justify-content-center">
        <!-- Language Check Table -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-3">
                        <h5 class="mb-0 fw-bold" style="color: #fff; font-size: 1.1rem;">
                            <i class="fas fa-microphone-alt me-1"></i> Speaking Exams
                        </h5>
                    </div>
                </div>
                <div class="card-body p-3" style="background-color: #f8f9fa;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="background-color: #57afbfe6; color: white;">
                                <tr>
                                    <th class="py-2 px-2"><i class="fas fa-id-card me-1"></i> ID</th>
                                    <th class="py-2 px-2"><i class="fas fa-user me-1"></i> Name</th>
                                    <th class="py-2 px-2"><i class="fas fa-chalkboard-teacher me-1"></i> Exam Board</th>
                                    <th class="py-2 px-2"><i class="fas fa-book me-1"></i> Subject</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($langStudents as $student)
                                    <tr>
                                        <td class="py-2 px-2"><i class="fas fa-id-card me-1 text-primary"></i>{{ $student->student_id }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-user me-1 text-success"></i>{{ $student->first_name . ' ' . $student->surname }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-chalkboard-teacher me-1 text-info"></i>{{ $student->exam_board }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-book me-1 text-danger"></i>{{ $student->subject_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coursework Check Table -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-3">
                        <h5 class="mb-0 fw-bold" style="color: #fff; font-size: 1.1rem;">
                            <i class="fas fa-file-alt me-1"></i> Coursework Exams
                        </h5>
                    </div>
                </div>
                <div class="card-body p-3" style="background-color: #f8f9fa;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="background-color: #85cc95e6; color: white;">
                                <tr>
                                    <th class="py-2 px-2"><i class="fas fa-id-card me-1"></i> ID</th>
                                    <th class="py-2 px-2"><i class="fas fa-user me-1"></i> Name</th>
                                    <th class="py-2 px-2"><i class="fas fa-chalkboard-teacher me-1"></i> Exam Board</th>
                                    <th class="py-2 px-2"><i class="fas fa-book me-1"></i> Subject</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courseworkStudents as $student)
                                    <tr>
                                        <td class="py-2 px-2"><i class="fas fa-id-card me-1 text-primary"></i>{{ $student->student_id }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-user me-1 text-success"></i>{{ $student->first_name . ' ' . $student->surname }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-chalkboard-teacher me-1 text-info"></i>{{ $student->exam_board }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-book me-1 text-danger"></i>{{ $student->subject_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        background-color: #ffffff;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(23, 162, 184, 0.2) !important;
    }
    .table {
        width: 100%;
        margin: 0 auto;
        font-size: 0.8rem;
    }
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #17a2b8;
        white-space: nowrap;
        padding: 0.5rem;
        vertical-align: middle;
    }
    .table td {
        border-bottom: 1px solid #F87A14;
        color: #00000;
        padding: 0.5rem;
        vertical-align: middle;
    }
    /* Specific column adjustments */
    .table th:nth-child(1), .table td:nth-child(1) { width: 15%; } /* ID */
    .table th:nth-child(2), .table td:nth-child(2) { width: 25%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; } /* Name */
    .table th:nth-child(3), .table td:nth-child(3) { width: 20%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; } /* Exam Board */
    .table th:nth-child(4), .table td:nth-child(4) { width: 40%; white-space: normal; word-wrap: break-word; } /* Subject */
    .table-hover tbody tr:hover {
        background-color: #F87A14;
    }
    @media (max-width: 768px) {
        .table {
            font-size: 0.7rem;
        }
        .table th, .table td {
            padding: 0.4rem;
        }
    }
</style>
@endsection
