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
                            <i class="fas fa-users me-2"></i> User Management
                        </h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-hover align-middle">
                            <thead style="background-color: #57afbfe6; color: white;">
                                <tr>
                                    {{-- <th class="py-3 px-4"><i class="fas fa-id-badge me-2"></i> ID</th> --}}
                                    <th class="py-3 px-4"><i class="fas fa-user me-2"></i> Name</th>
                                    <th class="py-3 px-4"><i class="fas fa-envelope me-2"></i> Email</th>
                                    <th class="py-3 px-4"><i class="fas fa-user-shield me-2"></i> Role</th>
                                    <th class="py-3 px-4"><i class="fas fa-clock me-2"></i> Created At</th>
                                    <th class="py-3 px-4"><i class="fas fa-cogs me-2"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        {{-- <td class="py-3 px-4"><i class="fas fa-id-badge me-2 text-primary"></i>{{ $user->id }}</td> --}}
                                        <td class="py-3 px-4"><i class="fas fa-user me-2 text-success"></i>{{ $user->name }}</td>
                                        <td class="py-3 px-4"><i class="fas fa-envelope me-2 text-warning"></i>{{ $user->email }}</td>
                                        <td class="py-3 px-4">
                                            <i class="fas fa-user-shield me-2 text-danger"></i>
                                            @if($user->is_admin == 1)
                                                Super Admin
                                            @elseif($user->is_admin == 2)
                                                Admin
                                            @elseif($user->is_admin == 3)
                                            Timetable
                                            @else
                                            unknown
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <i class="fas fa-clock me-2 text-primary"></i>
                                            {{ $user->created_at->diffForHumans() }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <a href="{{ url('/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-warning me-2" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ url('/users/' . $user->id . '/permissions') }}" class="btn btn-sm btn-info me-2" title="Permissions">
                                                <i class="fas fa-key"></i>
                                            </a>
                                            <form action="{{ url('/users/' . $user->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-3 px-4 text-center" style="color: #17a2b8;">
                                            <i class="fas fa-info-circle me-2"></i>No users available.
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
    .table {
        max-width: 80%;
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
</style>

<script>
    $(document).ready(function () {
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

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This user will be deleted permanently!',
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
                                title: 'User deleted successfully!'
                            });
                            $(form).closest('tr').fadeOut(300, function() {
                                $(this).remove();
                            });
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Failed to delete user: ' + (xhr.responseJSON?.message || 'Unknown error')
                            });
                        }
                    });
                }
            });
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

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
