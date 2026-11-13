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
                            <i class="fas fa-users me-2"></i> Candidate Records
                        </h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                    <!-- Search inputs -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" style="background-color: #F87A14; color: white;">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" id="searchById" class="form-control" placeholder="Search by Candidate ID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" style="background-color: #F87A14; color: white;">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" id="searchByUci" class="form-control" placeholder="Search by UCI Number">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="background-color: #57afbfe6; color: white;">
                                <tr>
                                    <th class="py-3 px-4"><i class="fas fa-id-card me-2"></i> Candidate ID</th>
                                    <th class="py-3 px-4"><i class="fas fa-user me-2"></i> Candidate Name</th>
                                    <th class="py-3 px-4"><i class="fas fa-envelope me-2"></i> Email</th>
                                    <th class="py-3 px-4"><i class="fas fa-hashtag me-2"></i> UCI Number</th>
                                    <th class="py-3 px-4"><i class="fas fa-calendar me-2"></i> Session</th>
                                    <th class="py-3 px-4"><i class="fas fa-cogs me-2"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($candidates as $candidate)
                                    <tr class="candidate-row">
                                        <td class="py-3 px-4 candidate-id">{{ $candidate->candidate_id }}</td>
                                        <td class="py-3 px-4 candidate-name">{{ $candidate->candidate_name }}</td>
                                        <td class="py-3 px-4">{{ $candidate->email }}</td>
                                        <td class="py-3 px-4 uci-number">{{ $candidate->uci_number }}</td>
                                        <td class="py-3 px-4">{{ $candidate->session }}</td>
                                        <td class="py-3 px-4">
                                            <a href="{{ url('edit-candidate/'.$candidate->id) }}" class="btn btn-sm btn-warning me-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ url('delete-candidate/'.$candidate->id) }}" class="btn btn-sm btn-danger" title="Edit">
                                                <i class="fas fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-3 px-4 text-center" style="color: #17a2b8;">
                                            <i class="fas fa-info-circle me-2"></i>No candidate records available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
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
    .table {
        margin: 0 auto;
    }
    .table th {
        font-weight: 600;
        border-bottom: 2px solid #17a2b8;
        white-space: nowrap;
        font-size: 0.9rem;
    }
    .table td {
        border-bottom: 1px solid #b2e4ede6;
        color: #17a2b8;
        white-space: nowrap;
        font-size: 0.9rem;
    }
    .table-hover tbody tr:hover {
        background-color: #b2e4ede6;
    }
    .btn-sm {
        padding: 0.2rem 0.5rem;
    }
    .form-control:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 0.25rem rgba(23, 162, 184, 0.25);
    }
    /* Ensure SweetAlert2 toasts are visible */
    .swal2-container {
        z-index: 9999 !important;
    }
</style>



<script>
    $(document).ready(function () {
        // Toaster Configuration using SweetAlert2 (move this up)
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

        // Check for success message after page load
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Search functionality (unchanged)
        $('#searchById').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.candidate-row').each(function() {
                const candidateId = $(this).find('.candidate-id').text().toLowerCase();
                $(this).toggle(candidateId.includes(searchTerm));
            });
        });

        $('#searchByUci').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.candidate-row').each(function() {
                const uciNumber = $(this).find('.uci-number').text().toLowerCase();
                $(this).toggle(uciNumber.includes(searchTerm));
            });
        });
    });
</script>
@endsection
