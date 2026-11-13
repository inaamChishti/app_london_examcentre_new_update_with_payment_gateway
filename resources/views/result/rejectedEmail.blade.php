@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-4">
                        <h5 class="mb-0 fw-bold" style="color: #fff;">
                            <i class="fas fa-users-slash me-2"></i> Ignored Candidates
                        </h5>

                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                    <div class="table-responsive">
                        <table class="table align-middle" style="max-width: 80%; margin: 0 auto;">
                            <thead style=" color: white;">
                                <tr>
                                    <th class="py-2 px-2"><i class="fas fa-id-badge me-1"></i>ID</th>
                                    <th class="py-2 px-2"><i class="fas fa-user me-1"></i>Candidate ID</th>
                                    <th class="py-2 px-2"><i class="fas fa-user-circle me-1"></i>Name</th>
                                    <th class="py-2 px-2"><i class="fas fa-envelope me-1"></i>Email</th>
                                    <th class="py-2 px-2"><i class="fas fa-cogs me-1"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody id="candidate-table-body">
                                @foreach ($candidates as $candidate)
                                    <tr id="row-{{ $candidate->id }}" class="{{ $candidate->is_sent ? '#F87A14' : '' }}">
                                        <td class="py-2 px-2"><i class="fas fa-id-badge me-1 text-primary"></i>{{ $candidate->id }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-user me-1 text-success"></i>{{ $candidate->candidate_id }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-user-circle me-1 text-info"></i>{{ $candidate->candidate_name }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-envelope me-1 text-warning"></i>{{ $candidate->email }}</td>
                                        <td class="py-2 px-2 candidate-id-cell" data-id="{{ $candidate->candidate_id }}">
                                            <button class="btn btn-danger send-not-email" data-id="{{ $candidate->candidate_id }}">
                                                <i class="fas fa-undo me-1"></i>Undo
                                            </button>
                                        </td>
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
        background-color: #ffffff;
    }
    .table th {
        font-weight: 600;
        white-space: nowrap;
        font-size: 1.1rem;
    }
    .table td {
        border-bottom: 1px solid #b2e4ede6;
        white-space: nowrap;
        font-size: 1rem;
    }
    .btn {
        padding: 0.375rem 0.75rem;
    }
</style>

<script>
    $(document).ready(function() {
        // Toast Configuration
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

        $('.send-not-email').on('click', function() {
            var candidateId = $(this).data('id');
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to undo this action?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, undo it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('undo-reject') }}/' + candidateId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                row.removeClass('table-success');
                                Toast.fire({
                                    icon: 'success',
                                    title: 'The action has been undone successfully!'
                                });
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Unable to undo the action!'
                                });
                            }
                            location.reload();
                        },
                        error: function() {
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong!'
                            });
                            location.reload();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
