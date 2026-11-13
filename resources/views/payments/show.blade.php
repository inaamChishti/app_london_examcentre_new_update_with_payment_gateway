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
                                <i class="fas fa-money-check-alt me-2"></i> Payment Records
                            </h5>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4" style="background-color: #f8f9fa;">
                        <div class="table-responsive">
                            <table id="paymentTable" class="table table-hover align-middle">
                                <thead style="background-color: #57afbfe6; color: white;">
                                    <tr>
                                        <th class="py-3 px-4"><i class="fas fa-user me-2"></i> Student ID</th>
                                        <th class="py-3 px-4"><i class="fas fa-user-circle me-2"></i> Name</th>
                                        <th class="py-3 px-4"><i class="fas fa-pound-sign me-2"></i> Amount</th>
                                        <th class="py-3 px-4"><i class="fas fa-user-check me-2"></i> Confirmed By</th>
                                        <th class="py-3 px-4"><i class="fas fa-credit-card me-2"></i> Payment Method</th>
                                          <th class="py-3 px-4"><i class="fas fa-credit-card me-2"></i> Initated at</th>

                                        <th class="py-3 px-4"><i class="fas fa-cogs me-2"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payment as $pay)
                                        <tr>
                                            <td class="py-3 px-4"><i
                                                    class="fas fa-user me-2 text-primary"></i>{{ $pay->student_id ?? '-' }}
                                            </td>
                                            <td class="py-3 px-4"><i
                                                    class="fas fa-user-circle me-2 text-success"></i>{{ $pay->name ?? '-' }}
                                            </td>
                                            <td class="py-3 px-4"><i
                                                    class="fas fa-pound-sign me-2 text-warning"></i>{{ $pay->amount_received ?? '-' }}
                                            </td>
                                            <td class="py-3 px-4"><i
                                                    class="fas fa-user-check me-2 text-danger"></i>{{ $pay->confirmed_by ?? '-' }}
                                            </td>
                                            <td class="py-3 px-4"><i
                                                    class="fas fa-credit-card me-2 text-primary"></i>{{ $pay->payment_method ?? '-' }}
                                            </td>
                                            <td class="py-3 px-4">
                {{ $pay->created_at ? $pay->created_at->format('d/m/Y') : '-' }}
            </td>
                                            <td class="py-3 px-4">
                                                <div class="btn-group" role="group">
                                                    @if ($pay->receipt_path && file_exists(public_path($pay->receipt_path)))
                                                        @php
                                                            $encoded = base64_encode($pay->receipt_path);
                                                        @endphp
                                                        <a href="{{ route('file.view', $encoded) }}"
                                                           target="_blank"
                                                           class="btn btn-sm btn-info"
                                                           title="View Receipt">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary"
                                                                title="No Receipt"
                                                                disabled>
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('payments.edit', $pay->id) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('payments.destroy', $pay->id) }}" method="POST"
                                                        class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-3 px-4 text-center" style="color: #F87A14;">
                                                <i class="fas fa-info-circle me-2"></i>No payment records available.
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

    <style>
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            background-color: #ffffff;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(23, 162, 184, 0.2) !important;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin: 0;
            table-layout: auto;
        }

        .table th {
            font-weight: 600;
            border-bottom: 2px solid #17a2b8;
            white-space: nowrap;
            font-size: 0.9rem;
            padding: 12px 8px;
            vertical-align: middle;
        }

        .table td {
            border-bottom: 1px solid #b2e4ede6;
            color: #17a2b8;
            font-size: 0.9rem;
            padding: 12px 8px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Student ID Column */
        .table th:nth-child(1),
        .table td:nth-child(1) {
            min-width: 100px;
            max-width: 150px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Name Column */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            min-width: 150px;
            max-width: 250px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Amount Column */
        .table th:nth-child(3),
        .table td:nth-child(3) {
            min-width: 80px;
            max-width: 120px;
            white-space: normal;
            text-align: center;
        }

        /* Confirmed By Column */
        .table th:nth-child(4),
        .table td:nth-child(4) {
            min-width: 120px;
            max-width: 180px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Payment Method Column */
        .table th:nth-child(5),
        .table td:nth-child(5) {
            min-width: 120px;
            max-width: 180px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* Initiated At Column */
        .table th:nth-child(6),
        .table td:nth-child(6) {
            min-width: 100px;
            max-width: 130px;
            white-space: normal;
            text-align: center;
        }

        /* Actions Column */
        .table th:nth-child(7),
        .table td:nth-child(7) {
            min-width: 150px;
            max-width: 200px;
            white-space: nowrap;
            text-align: center;
        }

        .table-hover tbody tr:hover {
            background-color: #b2e4ede6;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin: 2px;
        }

        .table td .btn-group {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 4px;
        }
    </style>



    <script>
        // Ensure DOM is fully loaded
        $(document).ready(function() {
            // Toaster Configuration
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


            // Handle delete form submission
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This payment will be deleted permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(form).attr('action'),
                            method: 'POST',
                            data: $(form).serialize(),
                            success: function(response) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Payment deleted successfully!'
                                });
                                $(form).closest('tr').fadeOut(300, function() {
                                    $(this).remove();
                                });
                            },
                            error: function(xhr) {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Failed to delete payment: ' + (xhr
                                        .responseJSON?.message ||
                                        'Unknown error')
                                });
                            }
                        });
                    }
                });
            });

            // Show session success message
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Show server-side validation errors
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
