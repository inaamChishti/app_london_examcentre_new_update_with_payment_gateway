@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
        <div style="padding: 15px 20px; background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2; display: flex; align-items: center;">
            <i class="fas fa-calendar-plus fa-2x" style="color: #fff; margin-right: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"></i>
            <h3 style="margin: 0; color: white; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Add New Session</h3>
        </div>

        <div style="padding: 25px;">
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
                    <span style="flex-grow: 1;">{{ session('success') }}</span>
                    <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #155724; cursor: pointer;">×</button>
                </div>
            @endif

            <form id="sessionForm">
                @csrf

                <!-- Session Name Field -->
                <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-calendar-alt" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Session Name</label>
                    </div>
                    <input
                        type="text"
                        name="session_name"
                        id="session_name"
                        style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                        placeholder="Enter session name (e.g., Summer 2024 Exams)"
                        required
                        onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                        onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                    >
                </div>

                <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-toggle-on" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Status</label>
                    </div>
                    <select
                        name="status"
                        id="status"
                        style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                        required
                        onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                        onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                    >
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div style="text-align: right;">
                    <button
                        type="button"
                        id="submitBtn"
                        style="background: #F87A14; border: none; color: white; padding: 12px 25px; font-weight: 600; border-radius: 4px; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-save" style="margin-right: 8px; color: #fff;"></i>
                        Save Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



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

    $('#submitBtn').click(function () {
        let formData = $('#sessionForm').serialize(); // Fixed the selector

        $.ajax({
            url: "{{ url('store-session') }}",
            method: 'POST',
            data: formData,
            success: function (response) {
                Toast.fire({
                    icon: 'success',
                    title: response.message || 'Session saved successfully!'
                });

                $('#sessionForm')[0].reset();
            },
            error: function (xhr) {
                let errorMsg = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Toast.fire({
                    icon: 'error',
                    title: errorMsg
                });
            }
        });
    });
});
</script>
@endsection
