@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div style="max-width: 800px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
        <div style="padding: 15px 20px; background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2; display: flex; align-items: center;">
            <i class="fas fa-pound-sign fa-2x" style="color: #fff; margin-right: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"></i>
            <h3 style="margin: 0; color: white; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Create Payment</h3>
        </div>

        <div style="padding: 25px;">
            <form id="paymentForm" method="POST" action="{{ url('payments/store') }}" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-clock" style="color: #8e44ad; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Session</label>
                    </div>
                    <input
                        type="text"
                        name="session"
                        id="session"
                        {{-- value="{{ $session->session_name }}" --}}
                        value="{{session('current_session')}}"
                        style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                        readonly
                    >
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-id-card" style="color: #e74c3c; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Candidate ID</label>
                        </div>
                        <input
                            type="text"
                            name="student_id"
                            id="student_id"
                            value="{{ old('student_id') }}"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            placeholder="Enter candidate id"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-user" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Name</label>
                        </div>
                        <input
                            type="text"
                            name="name"
                            id="paymentName"
                            value="{{ old('name') }}"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            readonly
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-hand-holding-usd" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Payment Received</label>
                        </div>
                        <select
                            name="payment_received"
                            id="payment_received"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                            <option value="" disabled {{ old('payment_received') ? '' : 'selected' }}>Select Option</option>
                            <option value="Yes" {{ old('payment_received') === 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ old('payment_received') === 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-pound-sign" style="color: #f39c12; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Amount Received (£)</label>
                        </div>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666;">£</span>
                            <input
                                type="number"
                                name="amount_received"
                                id="amount_received"
                                value="{{ old('amount_received') }}"
                                style="width: 100%; padding: 12px 12px 12px 30px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                                placeholder="Enter amount"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                            >
                        </div>
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-check-circle" style="color: #9b59b6; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Confirmed By</label>
                        </div>
                        <select
                            name="confirmed_by"
                            id="confirmed_by"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                            <option value="" disabled {{ old('confirmed_by') ? '' : 'selected' }}>Select Option</option>
                            <option value="Receipt" {{ old('confirmed_by') === 'Receipt' ? 'selected' : '' }}>Receipt</option>
                            <option value="Manual" {{ old('confirmed_by') === 'Manual' ? 'selected' : '' }}>Manual</option>
                        </select>
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-credit-card" style="color: #1abc9c; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Payment Method</label>
                        </div>
                        <select
                            name="payment_method"
                            id="payment_method"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; transition: all 0.3s;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                        >
                            <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Select a payment method</option>
                            <option value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Cash" {{ old('payment_method') === 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Card Machine" {{ old('payment_method') === 'Card Machine' ? 'selected' : '' }}>Card Machine</option>
                            <option value="Online Transfer" {{ old('payment_method') === 'Online Transfer' ? 'selected' : '' }}>Online Transfer</option>
                        </select>
                        <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                    </div>
                </div>

                <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-sticky-note" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Note</label>
                    </div>
                    <textarea
                        name="note"
                        id="note"
                        style="width: 100%; padding: 12px; border: 1px solid rgb(176, 224, 230); border-radius: 4px; background-color: rgb(240, 248, 255); transition: 0.3s; min-height: 100px; box-shadow: none;"
                        onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                        onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                    >{{ old('note') }}</textarea>
                    <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                </div>

                <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-file-upload" style="color: #d35400; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Upload Receipt</label>
                    </div>
                    <input
                        type="file"
                        name="receipt"
                        id="receipt"
                        style="width: 100%; padding: 12px; border: 1px solid rgb(176, 224, 230); border-radius: 4px; background-color: rgb(240, 248, 255); transition: 0.3s; box-shadow: none;"
                        onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                        onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                    >
                    <div class="error-message" style="color: #e74c3c; margin-top: 5px; display: none;"></div>
                </div>

                <div style="text-align: right;">
                    <button
                        type="submit"
                        id="submitBtn"
                        style="background: #F87A14; border: none; color: white; padding: 12px 25px; font-weight: 600; border-radius: 4px; cursor: pointer; transition: all 0.3s;"
                    >
                        <i class="fas fa-pound-sign" style="margin-right: 8px; color: #fff;"></i>
                        Create Payment
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

        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();
            let isValid = true;

            // Reset all error states
            $('.error-message').hide().text('');
            $('input, select, textarea').each(function() {
                this.style.borderColor = 'rgb(176, 224, 230)';
                this.style.boxShadow = 'none';
            });

            // Student ID validation
            const studentId = $('#student_id').val().trim();
            if (!studentId) {
                showError('#student_id', 'Candidate ID is required');
                isValid = false;
            }

            // Payment Received validation
            const paymentReceived = $('#payment_received').val();
            if (!paymentReceived) {
                showError('#payment_received', 'Please select if payment was received');
                isValid = false;
            }

            // Amount Received validation
            const amountReceived = $('#amount_received').val();
            if (!amountReceived || amountReceived < 0) {
                showError('#amount_received', 'Please enter a valid amount (0 or greater)');
                isValid = false;
            }

            // Confirmed By validation
            const confirmedBy = $('#confirmed_by').val();
            if (!confirmedBy) {
                showError('#confirmed_by', 'Please select confirmation method');
                isValid = false;
            }

            // Payment Method validation
            const paymentMethod = $('#payment_method').val();
            if (!paymentMethod) {
                showError('#payment_method', 'Please select a payment method');
                isValid = false;
            }

            // Note validation (required, must not be empty)
            const note = $('#note').val().trim();
            if (!note) {
                showError('#note', 'Note is required');
                isValid = false;
            } else if (note.length > 255) {
                showError('#note', 'Note must not exceed 255 characters');
                isValid = false;
            }

            // Receipt validation (required, must have a file)
            const receiptInput = document.getElementById('receipt');
            if (receiptInput.files.length === 0) {
                showError('#receipt', 'Receipt file is required');
                isValid = false;
            } else {
                const receipt = receiptInput.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!validTypes.includes(receipt.type)) {
                    showError('#receipt', 'Only JPG, PNG, or PDF files are allowed');
                    isValid = false;
                } else if (receipt.size > maxSize) {
                    showError('#receipt', 'File size must not exceed 2MB');
                    isValid = false;
                }
            }

            if (isValid) {
                this.submit();
            }
        });

        function showError(selector, message) {
            const $element = $(selector);
            $element.css({
                'border-color': '#F87A14',
                'box-shadow': 'none'
            });
            $element.next('.error-message').show().text(message);
        }

        // Success message
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Server-side validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                Toast.fire({
                    icon: 'error',
                    title: '{{ $error }}'
                });
            @endforeach
        @endif
    });





    $('#student_id').on('blur', function() {
    const studentId = $(this).val().trim();

    if (!studentId) {
        return;
    }

    // Add loading state to name field
    $('#paymentName').addClass('loading-name').val('Loading...');

    Swal.fire({
        title: 'Please wait',
        html: 'Fetching student details...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "{{ url('get-student') }}",
        method: 'POST',
        data: {
            student_id: studentId,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            Swal.close();
            $('#paymentName').removeClass('loading-name');

            if (response.success) {
                $('#paymentName').val(response.name);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Student not found'
                });
                $('#paymentName').val('');
            }
        },
        error: function(xhr) {
            Swal.close();
            $('#paymentName').removeClass('loading-name').val('');
            let errorMessage = 'An error occurred';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        }
    });
});
</script>
@endsection
