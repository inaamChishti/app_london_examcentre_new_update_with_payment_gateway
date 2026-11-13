@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div style="display: flex; flex-wrap: wrap; margin-right: -10px; margin-left: -10px;">
        <div style="flex: 0 0 100%; max-width: 100%; padding-right: 10px; padding-left: 10px;">
            <div style="position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: none; border-radius: 10px; box-shadow: 0 0.2rem 2rem 0 rgba(58, 59, 69, 0.2); margin-bottom: 20px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="padding: 15px 20px; background-color: #F87A14; border-bottom: 4px solid #1976D2; display: flex; justify-content: space-between; align-items: center; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h6 style="margin: 0; font-weight: 700; font-size: 1.2rem; color: #fff;">
                        <i class="fas fa-calendar-alt" style="margin-right: 8px; color: #fff;"></i> Session Management
                    </h6>
                </div>

                <div style="flex: 1 1 auto; padding: 15px;">
                    <div style="overflow-x: auto;">
                        <table id="sessionTable" style="width: 100%; font-size: 0.9rem; border-collapse: collapse;">
                            <thead style="background-color: #F87A14; color: #fff;">
                                <tr>
                                    {{-- <th style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">ID</th> --}}
                                    <th style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">Session Name</th>
                                    <th style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">Status</th>
                                    <th style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">Initiated On</th>
                                    <th style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                    <tr id="session-row-{{ $session->id }}" style="transition: background-color 0.3s ease;">
                                        {{-- <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">{{ $session->id }}</td> --}}
                                        <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">{{ $session->session_name }}</td>
                                        <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; {{ $session->status == 'active' ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;' }}">
                                                {{ ucfirst($session->status) }}
                                            </span>
                                        </td>
                                        <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                            {{ $session->created_at->diffForHumans() }}
                                        </td>
                                        <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                            <div style="display: flex; gap: 6px;">
                                                <a href="{{ url('edit-session/' . $session->id) }}" title="Edit" style="background-color: #28a745; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                                                    <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                                </a>

                                                <button onclick="confirmDelete('{{ $session->id }}')" title="Delete" style="background-color: #dc3545; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                                                    <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </div>
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


<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: toast => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
</script>

<script>
    function confirmDelete(sessionId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ url('delete-session') }}/" + sessionId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        document.getElementById('session-row-' + sessionId).remove();
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Could not delete session.'
                        });
                    }
                })
                .catch(() => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error deleting session.'
                    });
                });
            }
        });
    }
</script>
@endsection
