@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div style="max-width: 800px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
        <div style="padding: 15px 20px; background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2; display: flex; align-items: center;">
            <i class="fas fa-user-plus fa-2x" style="color: #fff; margin-right: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"></i>
            <h3 style="margin: 0; color: white; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Create User</h3>
        </div>

        <div style="padding: 25px;">
            <form method="POST" action="{{url('store-user')}}">
                <input type="hidden" name="_token" value="Z5AY5SxpD1YkxpWOZlH5gG3QasSOToK5bCffKHyX" autocomplete="off">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-user" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Name</label>
                        </div>
                        <input
                            type="text"
                            class="form-control"
                            name="name"
                            id="name"
                            placeholder="Enter User Name"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-envelope" style="color: #e74c3c; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Email</label>
                        </div>
                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            id="email"
                            placeholder="Enter Email"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-user-tag" style="color: #9b59b6; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Role</label>
                        </div>
                        <select
                            class="form-control"
                            name="role"
                            id="role"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                            <option value="" disabled selected>Choose Role</option>
                            <option value="1">Super Admin</option>
                            <option value="2">Admin</option>
                            <option value="3">Timetable</option>
                        </select>
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-lock" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Password</label>
                        </div>
                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            id="password"
                            placeholder="Enter Password"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>
                </div>

                <div style="text-align: right;">
                    <button
                        type="submit"
                        style="background: #F87A14; border: none; color: white; padding: 12px 25px; font-weight: 600; border-radius: 4px; cursor: pointer; transition: all 0.3s;"
                    >
                        <i class="fas fa-save" style="margin-right: 8px; color: #fff;"></i>
                        Submit
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

        $('form').on('submit', function(e) {
            e.preventDefault();
            let isValid = true;

            // Reset all error states
            $('.error-message').hide().text('');
            $('input, select').each(function() {
                this.style.borderColor = 'rgb(176, 224, 230)';
                this.style.boxShadow = 'none';
            });

            // Name validation
            const name = $('#name').val().trim();
            if (!name) {
                showError('#name', 'Name is required');
                isValid = false;
            }

            // Email validation
            const email = $('#email').val().trim();
            if (!email) {
                showError('#email', 'Email is required');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showError('#email', 'Please enter a valid email address');
                isValid = false;
            }

            // Role validation
            const role = $('#role').val();
            if (!role) {
                showError('#role', 'Please select a role');
                isValid = false;
            }

            // Password validation
            const password = $('#password').val();
            if (!password) {
                showError('#password', 'Password is required');
                isValid = false;
            } else if (password.length < 6) {
                showError('#password', 'Password must be at least 6 characters');
                isValid = false;
            }

            if (isValid) {
                this.submit();
            }
        });

        function showError(selector, message) {
            const $element = $(selector);
            $element.css({
                'border-color': '#e74c3c',
                'box-shadow': 'none'
            });
            $element.next('.error-message').show().text(message);
        }

        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Success message
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Server-side validation errors
        @if($errors->any()))
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
