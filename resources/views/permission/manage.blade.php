@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <!-- Card Header -->
                    <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                        <div class="d-flex justify-content-between align-items-center py-2 px-4">
                            <h5 class="mb-0 fw-bold" style="color: #fff;">
                                <i class="fas fa-key me-2"></i> Manage Permissions for {{ $user->username ?? $user->name }}
                            </h5>
                            <div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{ url('view-users') }}" class="btn"
                                    style="border: 2px solid #fff; color: #fff; background: #1976D2;">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4" style="background-color: #f8f9fa;">
                        <form action="{{ url('store/permission/access') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                                @foreach ([
            // Main Section
            'dashboard', // Main > Dashboard

            // Management Section > Students
            'add_new_student', // Management > Students > Add New Student
            'student_requests', // Management > Students > Student Requests
            'approved_requests', // Management > Students > Approved Requests
            'view_datesheet', // Management > Students > View Datesheet
            'generate_timetable', // Management > Students > Generate Time Table

            // Management Section > Exams
            'seat_tags', // Management > Exams > Seat Tags
            'send_important_message',
            'email_report', // Management > Exams > Email Tracking Report
            'email_logs', // Exams / Results > Email Logs

            // Management Section > Payments
            'create_payment', // Management > Payments > Create Payment
            'view_payments', // Management > Payments > View Payments

            // Management Section > Session
            'add_session', // Management > Session > Add Session
            'view_sessions', // Management > Session > View Sessions

            // Exams Section > Results
            'view_sheet_data', // Exams > Results > View Sheet Data
            'upload_result_pdf', // Exams > Results > Upload Result PDF
            'send_results', // Exams > Results > Send Results
            'modify_records', // Exams > Results > Modify Records
            // 'modify_email', // Exams > Results > Modify Email
            'ignored_emails', // Exams > Results > Ignored Emails

            // Reports Section
            'exam_report', // Reports > Exam Report
            'exam_report_by_subject', // Reports > Exam Report by Subject
            'internal_timetable', // Reports > Internal Timetable
            'spoken_coursework', // Reports > Spoken and Course Work
            'search_candidate', // Reports > Search Candidate
            'access_arrangement_report', // Reports > Access Arrangement Report
            'candidate_approval_report', // Reports > Candidate Approval Report

            // Referrals
            'referrals', // Management > Referrals
            'referral_report', // Management > Referral Report

            // Settings Section > Users
            'create_user', // Settings > Users > Create User
            'view_users', // Settings > Users > View Users

            // Settings Section > Help
            // 'help', // Settings > Help
        ] as $name)
                                    <div class="col">
                                        <div class="card h-100">
                                            <div
                                                class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                                                <label for="{{ $name }}" class="form-label text-center mb-2"
                                                    style="color: #00000;">
                                                    {{ ucwords(str_replace('_', ' ', $name)) }}
                                                </label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="{{ $name }}" id="{{ $name }}"
                                                        @if (is_array(@$permission->page_name) && array_key_exists($name, $permission->page_name)) checked @endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #F87A14; border-color: #F87A14;">
                                    <i class="fas fa-save me-2"></i> Save Permissions
                                </button>
                            </div>
                        </form>
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

        .form-check-input:checked {
            background-color: #F87A14;
            border-color: #F87A14;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(248, 122, 20, 0.25);
        }

        .btn-outline-secondary:hover {
            background-color: #1976D2;
            color: white;
        }
    </style>

    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
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

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    Toast.fire({
                        icon: 'error',
                        title: '{{ $error }}'
                    });
                @endforeach
            @endif
        });
    </script>
@endsection
