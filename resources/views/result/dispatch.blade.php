@extends('layouts.app')

@section('content')
<style>
    .table th, .table td {
    border-bottom: 1px solid #F87A14;
    color: #000000;
    font-size: 0.9rem;
    padding: 0.4rem 0.5rem; /* Reduced padding */
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
}
.table th:nth-child(1), .table td:nth-child(1) { width: 15%; max-width: 15%; } /* Candidate ID */
.table th:nth-child(2), .table td:nth-child(2) { width: 20%; max-width: 20%; } /* Name */
.table th:nth-child(3), .table td:nth-child(3) { width: 25%; max-width: 25%; } /* Email */
.table th:nth-child(4), .table td:nth-child(4) { width: 15%; max-width: 15%; } /* Result Available */
.table th:nth-child(5), .table td:nth-child(5) { width: 15%; max-width: 15%; } /* Status */
.table th:nth-child(6), .table td:nth-child(6) { width: 20%; max-width: 20%; } /* Actions */
</style>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-3">
                        <h5 class="mb-0 fw-bold" style="color: #fff;">
                            <i class="fas fa-users me-2"></i> Candidate Records
                        </h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-3" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn" style="background: #F87A14; color: #fff; border: none;" id="send-all">
                            <i class="fas fa-envelope me-2"></i> Send All Emails
                        </button>
                    </div>

                    <div class="search-container mb-3">
                        <div class="input-group" style="width: 20%; margin-left: auto; margin-right: auto;">
                            <span class="input-group-text" style="background-color: #F87A14;">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text" id="search-candidate-id" class="form-control" placeholder="Search by Candidate ID">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="background-color: #57afbfe6; color: white;">
                                <tr>
                                    {{-- <th class="py-3 px-4"><i class="fas fa-id-badge me-2"></i> ID</th> --}}
                                    <th><i class="fas fa-user-tag me-2"></i> Candidate ID</th>
                                    <th><i class="fas fa-user me-2"></i> Name</th>
                                    <th><i class="fas fa-envelope me-2"></i> Email</th>
                                    <th><i class="fas fa-file-upload me-2"></i> Result Available</th>
                                    <th><i class="fas fa-check-circle me-2"></i> Status</th>
                                    <th><i class="fas fa-cogs me-2"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="candidate-table-body">
                                {{-- {{dd($candidates)}} --}}
                                @foreach ($candidates as $candidate)
                                    @php
                                        $hasResult = isset($results[$candidate->candidate_id]);
                                    @endphp
                                    <tr id="row-{{ $candidate->id }}" class="{{ $candidate->is_sent ? 'table-success' : '' }}">
                                        {{-- <td>{{ $candidate->id }}</td> --}}
                                        <td>{{ $candidate->candidate_id }}</td>
                                        <td>{{ $candidate->candidate_name }}</td>
                                        <td class="email-cell">
                                            @if(empty($candidate->email))
                                                <span style="color: red; background-color: #ffe6e6; padding: 2px 5px;">No Email Found</span>
                                            @else
                                                {{ $candidate->email }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($hasResult)
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Uploaded</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Not Uploaded</span>
                                            @endif
                                        </td>
                                        <td class="status">
                                            <i class="fas {{ $candidate->is_sent ? 'fa-check text-success' : 'fa-clock text-warning' }} me-2"></i>
                                            {{ $candidate->is_sent ? 'Sent' : 'Pending' }}
                                        </td>
                                        <td class="candidate-id-cell" data-id="{{ $candidate->candidate_id }}">
                                            @if (!$candidate->is_sent)
                                                <button class="btn btn-sm btn-primary send-email me-2" data-id="{{ $candidate->candidate_id }}" {{ $hasResult ? '' : 'disabled' }}>
                                                    <i class="fas fa-paper-plane"></i> Send
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-check"></i> Sent
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger send-not-email" data-id="{{ $candidate->candidate_id }}">
                                                <i class="fas fa-times"></i> Ignore
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
<!-- Styles and Scripts remain unchanged -->
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
        max-width: 80%;
        margin: 0 auto;
    }
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #F87A14;
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        font-size: 0.9rem;
    }
    .table td {
        border-bottom: 1px solid #F87A14;
        color: #00000;
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        font-size: 0.9rem;
        padding: 0.4rem 0.5rem; /* Reduced padding */
    }
    .table td:last-child {
        white-space: nowrap; /* Keep action buttons on same line */
        padding: 0.3rem 0.5rem; /* Slightly less padding for actions */
    }
    .table-hover tbody tr:hover {
        background-color: #F87A14;
    }
    .email-cell {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-all;
        max-width: 0;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
        white-space: nowrap;
    }
    .candidate-id-cell {
        white-space: nowrap !important;
    }
    .input-group-text {
        border: 1px solid #F87A14;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let current = 0;
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Search functionality
        $('#search-candidate-id').on('keyup', function() {
            let searchValue = $(this).val().toLowerCase();
            $('#candidate-table-body tr').each(function() {
                let candidateId = $(this).find('td:eq(0)').text().toLowerCase();
                $(this).toggle(candidateId.indexOf(searchValue) > -1);
            });
        });

        // Send All Emails
        $('#send-all').on('click', function() {
            Swal.fire({
                title: 'Please wait...',
                text: 'Sending emails in progress',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            function sendNextBatch() {
                $.ajax({
                    url: '{{ url('/send-emails') }}',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', current: current },
                    success: function(response) {
                        if (response.success) {
                            let $row = $('#row-' + response.candidate_id);
                            $row.removeClass('table-danger').addClass('table-success');
                            $row.find('.status').html('<i class="fas fa-check text-success me-2"></i>Sent');
                            $row.find('.send-email').html('<i class="fas fa-check"></i> Sent')
                                .removeClass('btn-primary').addClass('btn-secondary').prop('disabled', true);

                            current = response.current;
                            if (current < 50 && current < response.total) {
                                sendNextBatch();
                            } else {
                                Swal.fire('Success!', 'All emails sent successfully!', 'success')
                                    .then(() => location.reload());
                            }
                        } else {
                            Swal.fire('Error!', response.message || 'Email sending failed', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Unexpected error occurred', 'error');
                    }
                });
            }
            sendNextBatch();
        });

        // Send Individual Email
        $('.send-email').on('click', function () {
    let candidateId = $(this).data('id');
    Swal.fire({
        title: 'Send Email?',
        text: 'Confirm sending email to this candidate',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, send it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sending Email...',
                text: 'Please wait while the email is being sent.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '{{ url('/send-individual-email') }}/' + candidateId,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    Swal.fire('Success!', response.message, 'success')
                        .then(() => location.reload());
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to send email', 'error');
                }
            });
        }
    });
});


        // Ignore Candidate
        $('.send-not-email').on('click', function() {
            let candidateId = $(this).data('id');
            Swal.fire({
                title: 'Ignore Candidate?',
                text: 'Confirm ignoring candidate ID: ' + candidateId,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ignore!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('/ignore-candidate') }}/' + candidateId,
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            Toast.fire({ icon: 'success', title: 'Candidate ignored successfully' })
                                .then(() => location.reload());
                        },
                        error: function() {
                            Toast.fire({ icon: 'error', title: 'Failed to ignore candidate' });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
