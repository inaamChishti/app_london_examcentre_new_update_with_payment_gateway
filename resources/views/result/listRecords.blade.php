@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
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
                            <i class="fas fa-users me-2"></i> Candidate Management
                        </h5>
                        <button type="button" class="btn btn-success" id="btnAddCandidate">
                            <i class="fas fa-plus me-2"></i>Add Candidate
                        </button>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover align-middle" style="max-width: 60%; margin: 0 auto;">
                            <thead style="background-color: #F87A14; color: white;">
                                <tr>
                                    <th class="py-2 px-2"><i class="fas fa-id-badge me-1"></i>ID</th>
                                    <th class="py-2 px-2"><i class="fas fa-user me-1"></i>Candidate ID</th>
                                    <th class="py-2 px-2"><i class="fas fa-user-circle me-1"></i>Name</th>
                                    <th class="py-2 px-2"><i class="fas fa-envelope me-1"></i>Email</th>
                                    <th class="py-2 px-2"><i class="fas fa-id-card me-1"></i>UCI Number</th>
                                    <th class="py-2 px-2"><i class="fas fa-mail-bulk me-1"></i>Email Status</th>
                                    <th class="py-2 px-2"><i class="fas fa-cogs me-1"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidates as $candidate)
                                    <tr>
                                        <td class="py-2 px-2"><i class="fas fa-id-badge me-1 text-primary"></i>{{ $loop->iteration }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-user me-1 text-success"></i>{{ $candidate->candidate_id }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-user-circle me-1 text-info"></i>{{ $candidate->candidate_name }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-envelope me-1 text-warning"></i>{{ $candidate->email }}</td>
                                        <td class="py-2 px-2"><i class="fas fa-id-card me-1 text-primary"></i>{{ $candidate->uci_number }}</td>
                                        <td class="py-2 px-2">
                                            <i class="fas fa-mail-bulk me-1 text-danger"></i>
                                            {{ $candidate->is_sent == 1 ? 'sent' : ($candidate->is_sent == 3 ? 'ignored' : 'not sent') }}
                                        </td>
                                        <td class="py-2 px-2">
                                            <a href="{{ url('edit-candidate/'.$candidate->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ url('delete-candidate/'.$candidate->id) }}" class="btn btn-sm btn-danger delete-candidate" title="Delete" data-id="{{ $candidate->id }}">
                                                <i class="fas fa-trash"></i>
                                            </a>
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

<!-- Add Candidate Modal -->
<div id="addCandidateModal" class="modal mt-5">
    <div class="modal-dialog" style="background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <div class="modal-content">
            <form action="{{ url('add-candidate') }}" method="POST" id="addCandidateForm">
                @csrf
                <div class="modal-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <h5 class="modal-title" style="color: #fff;"><i class="fas fa-user-plus me-2"></i>Add Candidate</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body" style="background-color: #f8f9fa;">
                    <div class="form-group">
                        <label for="candidateId"><i class="fas fa-user me-2"></i>Candidate ID</label>
                        <input type="text" class="form-control" id="candidateId" name="candidate_id" required>
                    </div>
                    <div class="form-group">
                        <label for="uciNumber"><i class="fas fa-id-card me-2"></i>UCI Number</label>
                        <input type="text" class="form-control" id="uciNumber" name="uci_number" required>
                    </div>
                    <div class="form-group">
                        <label for="candidateName"><i class="fas fa-user-circle me-2"></i>Candidate Name</label>
                        <input type="text" class="form-control" id="candidateName" name="candidateName" required>
                    </div>
                    <div class="form-group">
                        <label for="candidateEmail"><i class="fas fa-envelope me-2"></i>Candidate Email</label>
                        <input type="text" class="form-control" id="candidateEmail" name="candidateEmail" required>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #b2e4ede6;">
                    <button type="submit" class="btn " style="background:#F87A14"><i class="fas fa-save me-2"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Overlay -->
<div id="overlay"></div>

<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        background-color: #ffffff;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(23, 162, 184, 0.2) !important;
    }
    .table th {
        font-weight: 600;
        border-bottom: 2px solid black;
        white-space: nowrap;
        font-size: 0.8rem;
    }
    .table td {
        border-bottom: 1px solid #b2e4ede6;
        color: black;
        white-space: nowrap;
        font-size: 0.8rem;
    }
    .table-hover tbody tr:hover {
        background-color: #b2e4ede6;
    }
    .btn-sm {
        padding: 0.15rem 0.4rem;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
    }
    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 999;
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

        // Initialize DataTable
        var table = $('#example').DataTable({
            "paging": false
        });

        // Show modal
        $('#btnAddCandidate').click(function() {
            $('#addCandidateModal').fadeIn();
            $('#overlay').fadeIn();
        });

        // Hide modal
        $('.modal .close, #overlay').click(function() {
            $('#addCandidateModal').fadeOut();
            $('#overlay').fadeOut();
        });

        // Handle form submission
        $('#addCandidateForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Candidate added successfully!'
                    });
                    $('#addCandidateModal').fadeOut();
                    $('#overlay').fadeOut();
                    // Optionally refresh the table
                    location.reload();
                },
                error: function(xhr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to add candidate: ' + (xhr.responseJSON?.message || 'Unknown error')
                    });
                }
            });
        });

        // Handle delete
        $('.delete-candidate').click(function(e) {
            e.preventDefault();
            const url = $(this).attr('href');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This candidate will be deleted permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'GET', // Adjust to DELETE if your route supports it
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Candidate deleted successfully!'
                            });
                            $(e.target).closest('tr').fadeOut(300, function() {
                                $(this).remove();
                            });
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Failed to delete candidate: ' + (xhr.responseJSON?.message || 'Unknown error')
                            });
                        }
                    });
                }
            });
        });

        // Show session success message
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Show validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                Toast.fire({
                    icon: 'error',
                    title: '{{ $error }}'
                });
            @endforeach
        @endif
    });
</script>
@endsection
