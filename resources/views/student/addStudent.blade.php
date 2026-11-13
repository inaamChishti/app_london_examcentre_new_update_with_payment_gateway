@extends('layouts.app')

@section('content')

    <head>
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Load jQuery before Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        @include('layouts.adminDashboard.style')
    </head>
    <div class="container-fluid" style="padding: 20px;">
        <div class=""
            style="max-width: 900px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
            <div class="mainHeadning"
                style="padding: 15px 20px; background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2; display: flex; align-items: center;">
                <i class="fas fa-user-graduate fa-2x "
                    style="color: #fff; margin-right: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"></i>
                <h3 style="margin: 0; color: white; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Exam
                    Registration Form</h3>
            </div>


            <div style="padding: 25px;">
                @if (session('success'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: '{{ session('success') }}',
                                confirmButtonColor: '#57afbfd6'
                            });
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: '{{ session('error') }}',
                                confirmButtonColor: '#57afbfd6'
                            });
                        });
                    </script>
                @endif


                <form method="POST" action="{{ url('submit-student') }}" enctype="multipart/form-data" id="examForm">
                    @csrf

                    <!-- General Information -->
                    <div class="row">

                        <input type="hidden" name="current_domain" id="current_domain">
                        <!-- Session -->
                        <div style="margin-bottom: 25px;">


                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-calendar-alt sessionFAS"
                                    style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Session</label>
                            </div>
                            {{-- {{dd($sessions,$activeSession)}}
                        <select class="form-select" id="session" name="session" required
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                            <option value="" disabled>Select Session</option>

                            <option value="{{ $activeSession->session_name }}"
                                {{ $activeSession->session_name ? 'selected' : '' }}>
                                {{ $activeSession->session_name }}
                            </option>

                        </select> --}}
                            <select class="form-select" id="session" name="session" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                <option value="" disabled>Select Session</option>

                                @foreach ($sessions as $session)
                                    <option value="{{ $session->session_name }}"
                                        {{ $session->session_name === 'Summer 2027' ? 'selected' : '' }}>
                                        {{ $session->session_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-user sessionFAS"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Surname</label>
                            </div>
                            <input type="text" class="form-control" id="surname" name="surname"
                                placeholder="Enter Surname" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-user sessionFAS"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">First Name</label>
                            </div>
                            <input type="text" class="form-control" id="firstName" name="firstName"
                                placeholder="Enter First Name" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-birthday-cake sessionFAS"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Date of Birth</label>
                            </div>
                            <input type="date" class="form-control" id="dob" name="dob" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <!-- Gender -->
                    <div style="margin-bottom: 25px;">
                        <div style="display flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-venus-mars sessionFAS"
                                style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Gender</label>
                        </div>
                        <select class="form-select" id="gender" name="gender" required
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <!-- Address Information -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-home sessionFAS"
                                style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Address in the UK - Line 1</label>
                        </div>
                        <input type="text" class="form-control" id="addressLine1" name="addressLine1"
                            placeholder="Enter Address Line 1"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-home sessionFAS"
                                style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Address in the UK - Line 2</label>
                        </div>
                        <input type="text" class="form-control" id="addressLine2" name="addressLine2"
                            placeholder="Enter Address Line 2"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-map-marker-alt sessionFAS"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Postcode</label>
                            </div>
                            <input type="text" class="form-control" id="postcode" name="postcode"
                                placeholder="Enter Postcode"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-badge sessionFAS"
                                    style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">UCI Number</label>
                            </div>
                            <input type="text" class="form-control" id="uciNumber" name="uciNumber"
                                placeholder="Enter UCI Number"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-phone sessionFAS"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Contact Number</label>
                            </div>
                            <input type="tel" class="form-control" id="contactNumber" name="contactNumber"
                                placeholder="Enter Contact Number"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-envelope sessionFAS"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Email Address</label>
                            </div>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter Email Address"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"
                                oninput="validateEmail()">

                            <span id="emailError"
                                style="color: red; font-size: 0.9em; display: none; margin-top: 5px;"></span>

                            <script>
                                function validateEmail() {
                                    const emailInput = document.getElementById('email');
                                    const emailError = document.getElementById('emailError');
                                    const emailValue = emailInput.value.trim();
                                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email regex

                                    if (emailValue === '') {
                                        emailError.textContent = 'Email is required';
                                        emailError.style.display = 'block';
                                        emailInput.style.borderColor = 'red';
                                    } else if (!emailValue.includes('@')) {
                                        emailError.textContent = 'Email must contain @ symbol';
                                        emailError.style.display = 'block';
                                        emailInput.style.borderColor = 'red';
                                    } else if (!emailPattern.test(emailValue)) {
                                        emailError.textContent = 'Please enter a valid email address';
                                        emailError.style.display = 'block';
                                        emailInput.style.borderColor = 'red';
                                    } else {
                                        emailError.style.display = 'none';
                                        emailInput.style.borderColor = '#b0e0e6';
                                    }
                                }
                            </script>
                        </div>
                    </div>

                    <!-- Emergency Contact and ULN -->
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-phone-square sessionFAS"
                                    style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Emergency Contact Number</label>
                            </div>
                            <input type="tel" class="form-control" id="emergencyContact" name="emergencyContact"
                                placeholder="Enter Emergency Contact Number"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-card-alt sessionFAS"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">ULN</label>
                            </div>
                            <input type="text" class="form-control" id="uln" name="uln"
                                placeholder="Enter ULN"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <!-- Medical Condition -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-notes-medical sessionFAS"
                                style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Medical Condition (if any)</label>
                        </div>
                        <textarea class="form-control" id="medicalCondition" name="medicalCondition" placeholder="Enter Medical Condition"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"></textarea>
                    </div>

                    <!-- Access Arrangement -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-universal-access sessionFAS"
                                style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Access Arrangement</label>
                        </div>
                        <select class="form-select" id="accessArrangement" name="accessArrangement"
                            onchange="toggleExplanation()"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                            <option value="" disabled selected>Select Access Arrangement</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        <div id="explanationArea" style="display: none; margin-top: 15px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-comment sessionFAS"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Please Specify Access Arrangement</label>
                            </div>
                            <textarea class="form-control" id="explanation" name="access_arrangement_explanation"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'"></textarea>
                        </div>
                    </div>

                    <!-- Practical Endorsement -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-certificate sessionFAS"
                                style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Practical Endorsement</label>
                        </div>
                        <select class="form-select" id="practicalEndorsement" name="practicalEndorsement"
                            onchange="toggleExplanationtwo()"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                            <option value="" disabled selected>Select Practical Endorsement</option>
                            <option value="LondonExamCentre">London Exam Centre’s</option>
                            <option value="carry_forward">Carry Forward</option>
                            <option value="not_required">Not Required</option>
                        </select>
                        <div id="practicalExplanationArea" style="display: none;"></div>
                    </div>

                    <!-- Course Work -->
                    <div style="margin-bottom: 25px;">
                        <div id="courseWorkExplanationArea" style="display: none; margin-top: 15px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-badge sessionFAS"
                                    style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">UCI Number</label>
                            </div>
                            <input type="text" class="form-control" name="courseWork_UCI" id="courseWorkExplanation"
                                placeholder="UCI Number"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                        </div>
                    </div>

                    <!-- Predicted Grades & UCAS Reference -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-university sessionFAS"
                                style="color: #8e44ad; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Do you need predicted grades and a UCAS reference for university applications?</label>
                        </div>
                        <select class="form-select" name="need_predicted_grades"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                            <option value="" disabled selected>Select an option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- Mock Exams -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-pencil-alt sessionFAS"
                                style="color: #16a085; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Do you need to take mock exams?</label>
                        </div>
                        <select class="form-select" name="need_mock_exams"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                            <option value="" disabled selected>Select an option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <!-- Exam Table -->
                    {{-- <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-table sessionFAS"
                            style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Exam Details</label>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                        <thead>
                            <tr style="background: #f0f8ff;">
                                <th
                                    style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                    Exam Board</th>
                                <th
                                    style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 15%;">
                                    Course Type</th>
                                <th
                                    style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 16%;">
                                    Subject Name</th>
                                <th
                                    style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                    Subject Code</th>
                                <th
                                    style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                    Tier</th>
                                <th
                                    style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                    NEA</th>
                                <th
                                    style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 16%;">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="mainsec" style="background: #ffffff; border: 1px solid #b0e0e6;">
                                <!-- Board Dropdown -->
                                <td>
                                    <select class="form-select exam-board" name="examBoard[]"
                                        onchange="updateCourseTypes(this)">
                                        <option value="" disabled selected>Board</option>
                                        @if (isset($subjectsBySession[$activeSession->session_name]['boards']))
                                            @foreach ($subjectsBySession[$activeSession->session_name]['boards'] as $board)
                                                @if (!($activeSession->session_name === 'Autumn 2026' && $board === 'CIE'))
                                                    <option value="{{ $board }}">{{ $board }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </td>

                                <!-- Course Type Dropdown -->
                                <td>
                                    <select class="form-select course-type" name="courseType[]"
                                        onchange="updateSubjects(this)">
                                        <option value="" disabled selected>Course Type</option>
                                    </select>
                                </td>

                                <!-- Subject Name Dropdown -->
                                <td>
                                    <select class="form-select subject-name" name="subjectName[]">
                                        <option value="" disabled selected>Subject Name</option>
                                    </select>
                                </td>

                                <!-- Subject Code Input (Readonly) -->
                                <td>
                                    <input type="text" class="form-control subject-code" name="subjectCode[]"
                                        readonly
                                        style="width: 100%; padding: 8px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                        onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                        onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                </td>

                                <!-- Tier Dropdown -->
                                <td style="padding: 8px; vertical-align: middle; height: 50px; text-align: center;">
                                    <select class="form-select tire" name="tire[]"
                                        style="width: 100%; padding: 8px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                        onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                        onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                        <option value="N/A">N/A</option>
                                        <option value="Foundation Tier">Foundation Tier</option>
                                        <option value="Higher Tier">Higher Tier</option>
                                    </select>
                                </td>

                                <!-- NEA Dropdown -->
                                <td style="padding: 8px; vertical-align: middle; height: 50px; text-align: center;">
                                    <select class="form-select nea" name="nea[]"
                                        style="width: 100%; padding: 8px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                        onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                        onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                        <option value="N/A">N/A</option>
                                        <option value="course work">Course Work</option>
                                        <option value="language">Speaking Exam</option>
                                    </select>
                                </td>

                                <!-- Action Button -->
                                <td style="padding: 8px; vertical-align: middle; height: 50px; text-align: center;">
                                    <button class="btn btn-primary add-row-button mainHeadning"
                                        style="width: 100%; font-size: 11px; background-color: #57afbfd6; border: none; padding: 8px; color: white;"
                                        type="button"><i class="fas fa-plus "
                                            style="margin-right: 5px;"></i>Add</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="appendSec" style="margin-top: 15px;"></div>
                </div> --}}

                    <!-- Exam Table -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-table sessionFAS"
                                style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Exam Details</label>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                            <thead>
                                <tr style="background: #f0f8ff;">
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                        Exam Board</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 15%;">
                                        Course Type</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 16%;">
                                        Subject Name</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                        Subject Code</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                        Tier</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                        NEA</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 16%;">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="mainsec" style="background: #ffffff; border: 1px solid #b0e0e6;">
                                    <!-- Board Dropdown -->
                                    <td>
                                        <select class="form-select exam-board" name="examBoard[]"
                                            onchange="updateCourseTypes(this)">
                                            <option value="" disabled selected>Board</option>
                                            @if (isset($subjectsBySession[$activeSession->session_name]['boards']))
                                                @foreach ($subjectsBySession[$activeSession->session_name]['boards'] as $board)
                                                    @if (!($activeSession->session_name === 'Autumn 2026' && $board === 'CIE'))
                                                        <option value="{{ $board }}">{{ $board }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>

                                    <!-- Course Type Dropdown -->
                                    <td>
                                        <select class="form-select course-type" name="courseType[]"
                                            onchange="updateSubjects(this)">
                                            <option value="" disabled selected>Course Type</option>
                                        </select>
                                    </td>

                                    <!-- Subject Name Dropdown -->
                                    <td>
                                        <select class="form-select subject-name" name="subjectName[]">
                                            <option value="" disabled selected>Subject Name</option>
                                        </select>
                                    </td>

                                    <!-- Subject Code Input (Readonly) -->
                                    <td>
                                        <input type="text" class="form-control subject-code" name="subjectCode[]"
                                            readonly
                                            style="width: 100%; padding: 8px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                    </td>

                                    <!-- Tier Dropdown -->
                                    <td style="padding: 8px; vertical-align: middle; height: 50px; text-align: center;">
                                        <select class="form-select tire" name="tire[]"
                                            style="width: 100%; padding: 8px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                            <option value="N/A">N/A</option>
                                            <option value="Foundation Tier">Foundation Tier</option>
                                            <option value="Higher Tier">Higher Tier</option>
                                        </select>
                                    </td>

                                    <!-- NEA Dropdown -->
                                    <td style="padding: 8px; vertical-align: middle; height: 50px; text-align: center;">
                                        <select class="form-select nea" name="nea[]"
                                            style="width: 100%; padding: 8px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                                            <option value="N/A">N/A</option>
                                            <option value="course work">Course Work</option>
                                            <option value="language">Speaking Exam</option>
                                        </select>
                                    </td>

                                    <!-- Action Button -->
                                    <td style="padding: 8px; vertical-align: middle; height: 50px; text-align: center;">
                                        <button class="btn btn-primary add-row-button mainHeadning"
                                            style="width: 100%; font-size: 11px; background-color: #F87A14; border: none; padding: 8px; color: white;"
                                            type="button"><i class="fas fa-plus"
                                                style="margin-right: 5px;"></i>Add</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="appendSec" style="margin-top: 15px;"></div>
                    </div>

                    <!-- File Uploads -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-camera sessionFAS"
                                style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Photo</label>
                        </div>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-passport sessionFAS"
                                style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">ID Document (Passport, BRP, Driving
                                License)</label>
                        </div>
                        <input type="file" class="form-control" id="idDocument" name="idDocument"
                            accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-file-alt sessionFAS"
                                style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Supporting Documents for Access
                                Arrangements</label>
                        </div>
                        <input type="file" class="form-control" id="supportingDocuments" name="supportingDocuments"
                            multiple accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;"
                            onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                            onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                    </div>

                    <!-- Additional Documents -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-folder-plus sessionFAS"
                                style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label class="" style="font-weight: 600; color: #2c3e50;">Additional Documents</label>
                        </div>
                        <div id="additionalDocsContainer"></div>
                        <button id="addDoc" class="btn btn-primary mainHeadning" type="button"
                            style="background-color: #F87A14; border: none; padding: 12px 15px; font-size: 12px; margin-top: 10px; color: white;">
                            <i class="fas fa-plus " style="margin-right: 5px;"></i>Add Additional Document
                        </button>
                    </div>

                    <div class="row" style="margin-bottom: 25px;">
                        {{-- <div class="col-md-12">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <i class="fas fa-file-contract" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
            <label style="font-weight: 600; color: #2c3e50;">Terms and Conditions</label>
        </div>
        <div style="margin-bottom: 15px;">
            <input type="checkbox" id="termsCheckbox" name="termsCheckbox">
            <label for="termsCheckbox" style="font-weight: 500; color: #2c3e50;">I agree to the Terms and Conditions</label>
        </div>
    </div> --}}
                        <!-- Terms and Conditions Checkbox Section -->
                        <div
                            style="margin-bottom: 24px; display: flex; flex-direction: column; gap: 12px; font-family: 'Roboto', sans-serif;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <label style="font-weight: 500; font-size: 16px; color: #1c2526; flex: 0 0 auto;">Terms &
                                    Conditions</label>

                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" id="termsCheckbox" name="termsCheckbox"
                                    style="width: 16px; height: 16px; accent-color: #0066cc; margin: 0; cursor: pointer; border-radius: 4px;">
                                <label for="termsCheckbox" style="font-size: 14px; color: #1c2526; cursor: pointer;">I
                                    agree to the <span style="color: #1c2526; font-weight: 500;">Terms &
                                        Conditions</span></label>
                                <a href="#" onclick="openTermsModal(event)"
                                    style="font-size: 14px; color: #0066cc; text-decoration: none; font-weight: 500; transition: color 0.2s; margin-left: 8px;">(View
                                    Terms)</a>
                            </div>
                        </div>

                        <!-- Terms and Conditions Modal -->
                        <div id="termsModal"
                            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.75); z-index: 1000; overflow-y: auto; backdrop-filter: blur(5px);">
                            <div
                                style="zoom:0.8;background: #ffffff; max-width: 960px; margin: 40px auto; border-radius: 16px; box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25); padding: 32px; font-family: 'Roboto', sans-serif; position: relative;">
                                <span onclick="closeTermsModal()"
                                    style="position: absolute; top: 1px; right: 20px; font-size: 32px; color: #b30000; cursor: pointer; font-weight: bold; transition: color 0.2s; line-height: 1;">×</span>
                                <div
                                    style="display: grid; grid-template-columns: 1fr; gap: 24px; max-height: 85vh; overflow-y: auto; padding-right: 16px; scrollbar-width: thin; scrollbar-color: #0066cc #e8ecef;">
                                    <h1
                                        style="font-size: 32px; color: #1c2526; margin: 0 0 16px; font-weight: 600; border-bottom: 3px solid #e6f0fa; padding-bottom: 12px; letter-spacing: -0.5px;">
                                        Exam Centre Terms and Conditions</h1>

                                    <div>
                                        <h2
                                            style="font-size: 22px; color: #0066cc; margin: 0 0 12px; font-weight: 500; letter-spacing: -0.2px;">
                                            Syllabus/Subject/Unit/Option Codes</h2>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">You
                                            will find Syllabus/Unit Code/Option Codes on each subject specification on the
                                            relevant Examination Board sites.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Please ensure you give the exact title of each module/unit and its entry code,
                                            as per the specifications.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Please note that it is your responsibility to provide us with the correct codes
                                            as London Exam Centre cannot be held responsible for any mistakes made on the registration
                                            form.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Please state the tier (Higher or Foundation) for GCSE/IGCSE exams if applicable
                                            to your subject.</p>
                                    </div>

                                    <div>
                                        <h2
                                            style="font-size: 22px; color: #0066cc; margin: 0 0 12px; font-weight: 500; letter-spacing: -0.2px;">
                                            Entry Fee and Deadline(s)</h2>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">Our
                                            aim at London Exam Centre is to keep prices competitive whilst taking into account any
                                            changes to the exam system and regulations.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Candidates must submit their entries in an accurate and timely way to enable
                                            London Exam Centre to issue statements of entry and examination results on time. Any entry
                                            submission that does not comply with entry rules will be rejected.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            London Exam Centre actively encourages candidates to submit their entries on time. This
                                            avoids incurring additional costs and allows us sufficient time to review the
                                            entry.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Candidates should be aware that London Exam Centre may refuse late and very late entries for
                                            some qualifications. This will happen if there is a risk to the integrity and
                                            security of the examination or assessment, in the processing of timely results.
                                        </p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">The
                                            Late Exam Entry Deadline for all qualifications begins to apply from the start
                                            of February. Very Late Fees will apply from the start of March. For precise Late
                                            Fee and Very Late Fee dates, contact the Exams Officer. Double or Triple exam
                                            fee rates are charged for Late Fees or Very Late Fees. Please see further
                                            information under our ‘Fees and Deadlines’ section of our website.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Once the entry deadline has passed, we can attempt to register you with the exam
                                            board. However, for very late entries, if the exam board does not accept a
                                            candidate’s entry, London Exam Centre will update the candidate within 48 hours.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">The
                                            table below provides key information on entry terminology:</p>
                                        <table
                                            style="width: 100%; border-collapse: collapse; margin: 16px 0; background: #f9fbfd; border-radius: 8px; overflow: hidden;">
                                            <thead>
                                                <tr style="background: #e6f0fa;">
                                                    <th
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #1c2526; font-weight: 500; width: 30%;">
                                                        Term used in Ts and Cs</th>
                                                    <th
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #1c2526; font-weight: 500; width: 70%;">
                                                        Definition of Term used</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #37474f;">
                                                        Entry Deadline</td>
                                                    <td
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #37474f;">
                                                        The final date that entries can be received and processed by London Exam Centre
                                                        Learning without incurring additional charges.</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #37474f;">
                                                        Late Fee Date</td>
                                                    <td
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #37474f;">
                                                        The date from which all entries and amendments will incur a Late Fee
                                                        charge. The Late Fee is equivalent to 2 times the Entry Fee for that
                                                        qualification (or unit).</td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #37474f;">
                                                        Very Late Fee Date</td>
                                                    <td
                                                        style="border: 1px solid #ccd6dd; padding: 14px; font-size: 15px; color: #37474f;">
                                                        The date from which all entries and amendments will incur a very
                                                        Late Fee charge. The Very Late Fee is equivalent to 3 times the
                                                        Entry Fee for that qualification (or unit). Please note, any
                                                        application processed at/after this stage can still be withdrawn
                                                        subject to T&C’s BUT no refunds will be issued.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div>
                                        <h2
                                            style="font-size: 22px; color: #0066cc; margin: 0 0 12px; font-weight: 500; letter-spacing: -0.2px;">
                                            Statement of Entry</h2>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">A
                                            Statement of Entry will be given to candidates once the registration and payment
                                            have been processed. Candidates must check this carefully for any errors and
                                            notify the examination officer at London Exam Centre as soon as possible.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">For
                                            convenience, an individual examination timetable will also be issued to the
                                            candidate. If a candidate is registered for two examinations and they clash on
                                            the timetable (i.e., same day and time), then the candidate will take one
                                            examination after the other. They will be given a short break between exams and
                                            will be supervised by centre staff.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Please note, for all examinations taken at London Exam Centre, candidates must bring their
                                            Statement of Entry and photo identification (passport/driver’s license); these
                                            will be checked by the invigilator before the exam.</p>
                                    </div>

                                    <div>
                                        <h2
                                            style="font-size: 22px; color: #0066cc; margin: 0 0 12px; font-weight: 500; letter-spacing: -0.2px;">
                                            Chargeable Amendments</h2>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            London Exam Centre can change the personal details for candidates entered for exam
                                            qualifications, up to the Certificate Printing Deadlines at an additional charge
                                            of £35.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">If
                                            a candidate’s personal details are amended after the Certificate Printing
                                            Deadlines, a charge will be applied to reflect the additional time and materials
                                            required to issue a revised certificate. Please note that personal details may
                                            only be amended up to 12 months after certificate issue. If candidates wish to
                                            amend personal details after this date, they will need to follow the replacement
                                            certificate process.</p>
                                    </div>

                                    <div>
                                        <h2
                                            style="font-size: 22px; color: #0066cc; margin: 0 0 12px; font-weight: 500; letter-spacing: -0.2px;">
                                            Entry Withdrawal</h2>
                                             <h5><b>A full refund (minus admin fee of 10%) is available if cancelled before the standard entry deadline.</b></h5>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            After the first entry deadline, candidates cannot receive a refund for any
                                            absences or withdrawal from exams, for any reason. Refunds will not be issued,
                                            and you may incur a further admin fee.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            Candidates may withdraw their exam registration before the first entry deadline
                                            of the exam entry; however, this will incur a charge/deduction of £25 per entry.
                                        </p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            There is no refund given for any withdrawal from Late Fee Entries or Very Late
                                            Fee Entries, for any reason.</p>
                                    </div>

                                    <div>
                                        <h2
                                            style="font-size: 22px; color: #0066cc; margin: 0 0 12px; font-weight: 500; letter-spacing: -0.2px;">
                                            Re-Marking</h2>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">If
                                            you feel your paper was not marked correctly, you can request to have it
                                            remarked. This is referred to as ‘Reviews of Results’ (RoR). There are options
                                            when it comes to remarking:</p>
                                        \n<ul
                                            style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px 24px;">
                                            <li style="margin-bottom: 10px;">A clerical check – where the paper is checked
                                                to see if any marks were missed during counting. An examiner will review the
                                                paper/recording again to identify genuine marking errors or unreasonable
                                                marking.</li>
                                        </ul>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">It
                                            is important to note that your grade can go down as well as up.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">A
                                            review is the standard speed service for any student.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">If
                                            the student has a university place pending, we advise that you request a
                                            priority review of marking (summer series only).</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">You
                                            can request these services for individual units or components.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">We
                                            at London Exam Centre charge an administration fee plus the exam boards’ fee for carrying
                                            out the review of marking; for this service, fees can vary depending on the exam
                                            board.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">You
                                            can only request a review of marking once for a paper. To do this, fill in the
                                            student written consent form on our website (Post Results Services Booklet June
                                            2018), and keep it for six months after the outcome of the enquiry or subsequent
                                            appeal.</p>
                                    </div>

                                    <div>
                                        <h2
                                            style="font-size: 22px; color: #0066cc; margin: 0 0 12px; font-weight: 500; letter-spacing: -0.2px;">
                                            Re-Marking Timescale</h2>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            <strong>Priority review of marking (Service P2):</strong> Up to 15 days
                                        </p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            <strong>Review of marking (Service 2):</strong> Up to 20 days
                                        </p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            <strong>Clerical check (Service 1):</strong> Up to 10 days
                                        </p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">The
                                            exam board will email our administrator. In the unlikely event that your outcome
                                            is a change of two grades or more, you can ask for feedback from certain exam
                                            boards.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">
                                            London Exam Centre’s Exams Officer can request for the exam board to return your marked
                                            question paper, on your behalf. This service is sometimes called an ‘Access to
                                            Script’ request, which comes at an additional cost.</p>
                                        <p style="font-size: 15px; color: #37474f; line-height: 1.8; margin: 0 0 12px;">All
                                            correspondence relating to the above should be sent to: <a
                                                href="mailto:exams@londonexamcentre.co.uk"
                                                style="color: #0066cc; text-decoration: none; font-weight: 500; transition: color 0.2s;">exams@londonexamcentre.co.uk</a>
                                        </p>
                                    </div>
                                </div>
                                <div style="text-align: right; margin-top: 20px;">
                                    <button onclick="closeTermsModal()"
                                        style="background: #b30000; border: none; color: #ffffff; padding: 12px 28px; border-radius: 8px; font-size: 15px; font-weight: 500; cursor: pointer; transition: background 0.2s; font-family: 'Roboto', sans-serif;">Close</button>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const canvas = document.getElementById('signatureCanvas');
                                const ctx = canvas.getContext('2d');
                                const termsCheckbox = document.getElementById('termsCheckbox');
                                const clearButton = document.getElementById('clearSignature');
                                const submitButton = document.getElementById('submitButton');
                                const signatureInput = document.getElementById('signature');
                                let isDrawing = false;
                                let hasSignature = false;
                                let dpr = 1;

                                // Adjust canvas size for high-DPI displays (mobile compatibility)
                                function resizeCanvas() {
                                    dpr = window.devicePixelRatio || 1;
                                    canvas.width = Math.round(canvas.offsetWidth * dpr);
                                    canvas.height = Math.round(canvas.offsetHeight * dpr);
                                    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                                    ctx.lineWidth = 2;
                                    ctx.strokeStyle = '#2c3e50';
                                    ctx.lineCap = 'round';
                                    ctx.lineJoin = 'round';
                                }

                                resizeCanvas();
                                window.addEventListener('resize', resizeCanvas);

                                // Disable canvas by default
                                canvas.style.pointerEvents = 'none';
                                canvas.style.opacity = '0.5';
                                canvas.style.touchAction = 'none';
                                canvas.style.backgroundColor = '#f0f8ff';
                                clearButton.disabled = true;
                                submitButton.style.display = 'none';

                                const signatureMsg = document.getElementById('signatureDisabledMsg');

                                // Enable/disable canvas based on terms checkbox
                                termsCheckbox.addEventListener('change', function() {
                                    if (this.checked) {
                                        canvas.style.pointerEvents = 'auto';
                                        canvas.style.opacity = '1';
                                        canvas.style.backgroundColor = '#ffffff';
                                        clearButton.disabled = false;
                                        if (signatureMsg) signatureMsg.style.display = 'none';
                                    } else {
                                        canvas.style.pointerEvents = 'none';
                                        canvas.style.opacity = '0.5';
                                        canvas.style.backgroundColor = '#f0f8ff';
                                        clearButton.disabled = true;
                                        ctx.clearRect(0, 0, canvas.offsetWidth, canvas.offsetHeight);
                                        signatureInput.value = '';
                                        hasSignature = false;
                                        submitButton.style.display = 'none';
                                        if (signatureMsg) signatureMsg.style.display = 'block';
                                    }
                                });

                                // Get canvas coordinates for both mouse and touch events
                                function getCanvasCoordinates(event) {
                                    const rect = canvas.getBoundingClientRect();
                                    const clientX = event.clientX;
                                    const clientY = event.clientY;
                                    const x = clientX - rect.left;
                                    const y = clientY - rect.top;

                                    return {
                                        x,
                                        y
                                    };
                                }

                                function isPointInsideCanvas(point) {
                                    return point.x >= 0 && point.y >= 0 && point.x <= canvas.offsetWidth && point.y <= canvas.offsetHeight;
                                }

                                function clampPoint(point) {
                                    return {
                                        x: Math.max(0, Math.min(point.x, canvas.offsetWidth)),
                                        y: Math.max(0, Math.min(point.y, canvas.offsetHeight))
                                    };
                                }

                                // Start drawing
                                function startDrawing(event) {
                                    if (!termsCheckbox.checked) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Action Required',
                                            text: 'Please accept the Terms and Conditions to enable signature.',
                                            confirmButtonColor: '#57afbfd6',
                                            confirmButtonText: 'OK'
                                        });
                                        return;
                                    }
                                    const point = getCanvasCoordinates(event);
                                    if (!isPointInsideCanvas(point)) return;
                                    isDrawing = true;
                                    const coords = clampPoint(point);
                                    ctx.beginPath();
                                    ctx.moveTo(coords.x, coords.y);
                                    event.preventDefault();
                                }

                                // Draw on canvas
                                function draw(event) {
                                    if (!isDrawing || !termsCheckbox.checked) return;
                                    const coords = clampPoint(getCanvasCoordinates(event));
                                    ctx.lineTo(coords.x, coords.y);
                                    ctx.stroke();
                                    hasSignature = true;
                                    updateSignature();
                                    event.preventDefault();
                                }

                                // Stop drawing
                                function stopDrawing() {
                                    if (isDrawing) {
                                        isDrawing = false;
                                        updateSignature();
                                        if (signatureInput.value && termsCheckbox.checked) {
                                            submitButton.style.display = 'block';
                                        }
                                    }
                                }

                                // Update hidden signature input
                                function updateSignature() {
                                    signatureInput.value = hasSignature ? canvas.toDataURL('image/png') : '';
                                    if (!hasSignature) submitButton.style.display = 'none';
                                }

                                // Mouse events
                                if (window.PointerEvent) {
                                    canvas.addEventListener('pointerdown', function(event) {
                                        startDrawing(event);
                                        if (isDrawing) canvas.setPointerCapture(event.pointerId);
                                    });
                                    canvas.addEventListener('pointermove', draw);
                                    canvas.addEventListener('pointerup', stopDrawing);
                                    canvas.addEventListener('pointercancel', stopDrawing);
                                    canvas.addEventListener('pointerleave', stopDrawing);
                                } else {
                                    canvas.addEventListener('mousedown', startDrawing);
                                    canvas.addEventListener('mousemove', draw);
                                    canvas.addEventListener('mouseup', stopDrawing);
                                    canvas.addEventListener('mouseout', stopDrawing);
                                    canvas.addEventListener('touchstart', function(event) {
                                        if (event.touches && event.touches.length) {
                                            startDrawing(event.touches[0]);
                                        }
                                    }, { passive: false });
                                    canvas.addEventListener('touchmove', function(event) {
                                        if (event.touches && event.touches.length) {
                                            draw(event.touches[0]);
                                        }
                                    }, { passive: false });
                                    canvas.addEventListener('touchend', stopDrawing);
                                    canvas.addEventListener('touchcancel', stopDrawing);
                                }

                                // Clear signature
                                clearButton.addEventListener('click', () => {
                                    ctx.clearRect(0, 0, canvas.offsetWidth, canvas.offsetHeight);
                                    hasSignature = false;
                                    signatureInput.value = '';
                                    submitButton.style.display = 'none';
                                });

                                // Validate on form submission
                                submitButton.addEventListener('click', function(e) {
                                    if (!termsCheckbox.checked) {
                                        e.preventDefault();
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Terms Not Accepted',
                                            text: 'Please accept the Terms and Conditions before submitting the form.',
                                            confirmButtonColor: '#57afbfd6',
                                            confirmButtonText: 'OK'
                                        });
                                        return;
                                    }
                                    if (!signatureInput.value || signatureInput.value === canvas.toDataURL('image/png')) {
                                        e.preventDefault();
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Missing Signature',
                                            text: 'Please provide a signature before submitting the form.',
                                            confirmButtonColor: '#57afbfd6',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                });
                            });
                        </script>
                        <script>
                            let scrollPosition = 0;

                            function openTermsModal(event) {
                                event.preventDefault(); // Prevent default link behavior
                                scrollPosition = window.scrollY; // Save current scroll position
                                document.body.style.position = 'fixed'; // Lock body scroll
                                document.body.style.top = `-${scrollPosition}px`; // Offset body to maintain position
                                document.body.style.width = '100%'; // Prevent width shift
                                document.getElementById('termsModal').style.display = 'block';
                            }

                            function closeTermsModal() {
                                document.getElementById('termsModal').style.display = 'none';
                                document.body.style.position = ''; // Restore body scroll
                                document.body.style.top = ''; // Clear offset
                                window.scrollTo(0, scrollPosition); // Restore saved scroll position
                            }
                        </script>
                        <div class="col-md-6">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-calendar-alt"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Form Fill Date</label>
                            </div>
                            <input type="text" class="form-control" id="formFillDate" name="formFillDate" readonly
                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-6">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-signature"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Signature</label>
                            </div>
                            <canvas id="signatureCanvas"
                                style="border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; width: 100%; height: 150px;"
                                disabled></canvas>
                            <input type="hidden" id="signature" name="signature">
                            <div id="signatureDisabledMsg" style="font-size: 12px; color: #e74c3c; margin-top: 5px;">Signature is disabled until
                                Terms and Conditions are accepted.</div>
                            <button type="button" id="clearSignature" class="btn btn-secondary"
                                style="margin-top: 10px; background-color: #e74c3c; border: none; padding: 8px 15px; font-size: 12px; color: white;"
                                disabled>
                                <i class="fas fa-eraser" style="margin-right: 5px;"></i>Clear Signature
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div style="text-align: right;">
                        <button type="submit" class="btn btn-success submitNow mainHeadning" id="submitButton"
                            style="background: #F87A14; border: none; color: white; padding: 12px 25px; font-weight: 600; border-radius: 4px; cursor: pointer; transition: all 0.3s;">
                            <i class="fas fa-save" style="margin-right: 8px;"></i>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    @include('student.historySpecialExam.publicPageForHistoryPaperJs')
    @include('student.historySpecialExam.AgeValidation')
    <script>
        $(document).ready(function() {
            // Prevent enter key submission
            $('#examForm input').on('keypress', function(e) {
                if (e.which === 13) {
                    alert('');
                    e.preventDefault();
                    console.log('Enter key pressed, submission prevented.');
                }
            });

            // Prevent default form submission
            $('#examForm').on('submit', function(e) {
                e.preventDefault();
                console.log('Form submission blocked. Only .submitNow click should trigger submission.');
            });

            // Data from PHP
            const subjectsBySession = @json($subjectsBySession);
            let isRowAdded = false;

            // Update exam details based on session change
            $('#session').on('change', function() {
                const selectedSession = $(this).val();
                const sessionData = subjectsBySession[selectedSession] || {
                    boards: [],
                    courseTypesByBoard: {},
                    subjectsByBoardAndCourse: {}
                };

                // Reset the main row
                const mainRow = $('.mainsec');
                const boardSelect = mainRow.find('.exam-board');
                const courseTypeSelect = mainRow.find('.course-type');
                const subjectSelect = mainRow.find('.subject-name');
                const codeInput = mainRow.find('.subject-code');

                // Clear existing options
                boardSelect.html('<option value="" disabled selected>Board</option>');
                courseTypeSelect.html('<option value="" disabled selected>Course Type</option>');
                subjectSelect.html('<option value="" disabled selected>Subject Name</option>');
                codeInput.val('');

                // Populate boards for selected session
                if (sessionData.boards) {
                    sessionData.boards.forEach(board => {
                        // Hide CIE when Autumn 2026 is selected
                        if (selectedSession === 'Autumn 2026' && board === 'CIE') return;
                        boardSelect.append(new Option(board, board));
                    });
                }

                // Clear appended rows
                $('.appendSec').empty();
                isRowAdded = false;
            });

            // Update course types based on selected board
            function updateCourseTypes(selectElement) {
                const board = selectElement.value;
                const row = selectElement.closest('tr');
                const courseTypeSelect = row.querySelector('.course-type');
                const subjectSelect = row.querySelector('.subject-name');
                const codeInput = row.querySelector('.subject-code');
                const selectedSession = $('#session').val();
                const sessionData = subjectsBySession[selectedSession] || {};

                // Reset dependent fields
                courseTypeSelect.innerHTML = '<option value="" disabled selected>Course Type</option>';
                subjectSelect.innerHTML = '<option value="" disabled selected>Subject Name</option>';
                codeInput.value = '';

                // Populate course types
                if (board && sessionData.courseTypesByBoard && sessionData.courseTypesByBoard[board]) {
                    sessionData.courseTypesByBoard[board].forEach(courseType => {
                        courseTypeSelect.add(new Option(courseType, courseType));
                    });
                }
            }

            // Update subjects based on selected course type
            function updateSubjects(selectElement) {
                const board = selectElement.closest('tr').querySelector('.exam-board').value;
                const courseType = selectElement.value;
                const subjectSelect = selectElement.closest('tr').querySelector('.subject-name');
                const codeInput = selectElement.closest('tr').querySelector('.subject-code');
                const selectedSession = $('#session').val();
                const sessionData = subjectsBySession[selectedSession] || {};

                // Reset subjects and code
                subjectSelect.innerHTML = '<option value="" disabled selected>Subject Name</option>';
                codeInput.value = '';

                // Populate subjects
                if (board && courseType && sessionData.subjectsByBoardAndCourse?.[board]?.[courseType]) {
                    Object.entries(sessionData.subjectsByBoardAndCourse[board][courseType]).forEach(([name,
                        code
                    ]) => {
                        const option = new Option(name, name);
                        option.dataset.code = code;
                        subjectSelect.add(option);
                    });
                }

                // Update subject code when subject is selected
                subjectSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    codeInput.value = selectedOption?.dataset?.code || '';
                });
            }

            // Add new row functionality
            function addNewRow() {
                const newRow = document.querySelector('.mainsec').cloneNode(true);
                const appendSection = document.querySelector('.appendSec');

                const rowContainer = document.createElement('div');
                rowContainer.className = 'row-container';
                rowContainer.style.marginBottom = '10px';

                const newTable = document.createElement('table');
                newTable.style.width = '100%';
                newTable.style.borderCollapse = 'collapse';
                newTable.style.tableLayout = 'fixed';
                newTable.style.border = '1px solid #b0e0e6';
                newTable.appendChild(newRow);

                rowContainer.appendChild(newTable);
                appendSection.appendChild(rowContainer);

                // Reset select values and input
                const selects = newRow.querySelectorAll('select');
                selects.forEach(select => select.selectedIndex = 0);
                newRow.querySelector('.subject-code').value = '';

                // Populate boards for the selected session
                const selectedSession = $('#session').val();
                const sessionData = subjectsBySession[selectedSession] || {
                    boards: []
                };
                const boardSelect = newRow.querySelector('.exam-board');
                boardSelect.innerHTML = '<option value="" disabled selected>Board</option>';
                if (sessionData.boards) {
                    sessionData.boards.forEach(board => {
                        // Hide CIE when Autumn 2026 is selected
                        if (selectedSession === 'Autumn 2026' && board === 'CIE') return;
                        boardSelect.append(new Option(board, board));
                    });
                }

                // Change Add button to Delete button
                const addButton = newRow.querySelector('.add-row-button');
                addButton.innerHTML = '<i class="fas fa-trash"></i> Delete';
                addButton.style.backgroundColor = '#e74c3c';
                addButton.onclick = function() {
                    rowContainer.remove();
                };

                isRowAdded = true;
            }

            // Event delegation for dynamic rows
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('exam-board')) {
                    updateCourseTypes(e.target);
                } else if (e.target.classList.contains('course-type')) {
                    updateSubjects(e.target);
                } else if (e.target.classList.contains('subject-name')) {
                    const currentRow = e.target.closest('tr');
                    const board = currentRow.querySelector('.exam-board').value;
                    const courseType = currentRow.querySelector('.course-type').value;
                    const subject = e.target.value;

                    if (board && courseType && subject) {
                        if (checkDuplicateSubject(board, courseType, subject, currentRow)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Duplicate Subject',
                                text: `The subject "${subject}" with exam board "${board}" and course type "${courseType}" has already been added.`,
                                confirmButtonColor: '#57afbfd6',
                                confirmButtonText: 'OK'
                            });
                            e.target.selectedIndex = 0;
                            currentRow.querySelector('.subject-code').value = '';
                        }
                    }
                }
            });

            // Add row button click
            document.querySelector('.add-row-button').addEventListener('click', function() {
                const currentRow = this.closest('tr');
                const board = currentRow.querySelector('.exam-board').value;
                const courseType = currentRow.querySelector('.course-type').value;
                const subject = currentRow.querySelector('.subject-name').value;

                if (board && courseType && subject && checkDuplicateSubject(board, courseType, subject,
                        currentRow)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Subject',
                        text: `The subject "${subject}" with exam board "${board}" and course type "${courseType}" has already been added.`,
                        confirmButtonColor: '#57afbfd6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                addNewRow();
            });

            // Handle delete for dynamically added rows
            document.querySelector('.appendSec').addEventListener('click', function(e) {
                if (e.target.closest('.add-row-button')) {
                    e.target.closest('.row-container').remove();
                    isRowAdded = document.querySelector('.appendSec').querySelector('.row-container') !==
                        null;
                }
            });

            // Additional documents functionality
            const addButton = document.getElementById('addDoc');
            const container = document.getElementById('additionalDocsContainer');

            function createDocumentRow(showRemoveButton = true) {
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'input-group mb-2';
                newInputGroup.style.display = 'flex';
                newInputGroup.style.alignItems = 'center';

                let removeButtonHtml = '';
                if (showRemoveButton) {
                    removeButtonHtml = `
                <button class="btn btn-danger remove-doc" type="button" style="margin-left: 10px; padding: 12px 15px; font-size: 12px; height: 44px; border: none;">
                    <i class="fas fa-trash" style="margin-right: 5px;"></i>Remove
                </button>
            `;
                }

                newInputGroup.innerHTML = `
            <input type="file" class="form-control" name="additional_docs[]" accept=".pdf, .jpg, .jpeg, .png" style="flex-grow: 1; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;" onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'" onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
            ${removeButtonHtml}
        `;

                return newInputGroup;
            }

            const initialRow = createDocumentRow(false);
            container.appendChild(initialRow);

            addButton.addEventListener('click', function() {
                const newRow = createDocumentRow(true);
                container.appendChild(newRow);
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-doc') || e.target.closest('.remove-doc')) {
                    const inputGroup = e.target.closest('.input-group');
                    if (inputGroup && container.querySelectorAll('.input-group').length > 1) {
                        inputGroup.remove();
                    }
                }
            });

            // Toggle access arrangement explanation
            window.toggleExplanation = function() {
                const accessArrangement = document.getElementById('accessArrangement').value;
                const explanationArea = document.getElementById('explanationArea');
                explanationArea.style.display = accessArrangement === 'yes' ? 'block' : 'none';
            };

            // Toggle practical endorsement explanation
            window.toggleExplanationtwo = function() {
                const practicalEndorsement = document.getElementById('practicalEndorsement').value;
                const explanationArea = document.getElementById('practicalExplanationArea');
                explanationArea.style.display = practicalEndorsement === 'carry_forward' ? 'block' : 'none';
            };

            // Check for duplicate subjects
            function checkDuplicateSubject(board, courseType, subject, currentRow) {
                const allRows = document.querySelectorAll('.mainsec, .appendSec .mainsec');
                let isDuplicate = false;

                allRows.forEach(row => {
                    if (row !== currentRow) {
                        const rowBoard = row.querySelector('.exam-board').value;
                        const rowCourseType = row.querySelector('.course-type').value;
                        const rowSubject = row.querySelector('.subject-name').value;

                        if (rowBoard && rowCourseType && rowSubject &&
                            rowBoard === board && rowCourseType === courseType && rowSubject === subject) {
                            isDuplicate = true;
                        }
                    }
                });

                return isDuplicate;
            }

            // Main submit handler
            $('.submitNow').on('click', function(e) {
                e.preventDefault();
                console.log('Submit button clicked, starting validation process');

                const form = document.getElementById('examForm');
                const uciNumber = $('#uciNumber').val().trim();
                const accessArrangement = $('#accessArrangement').val();
                const supportingDocuments = $('#supportingDocuments')[0].files;

                // Subject code validation
                let subjectCodeValid = true;
                const subjectCodeInputs = document.querySelectorAll('.subject-code');
                subjectCodeInputs.forEach(input => {
                    input.style.borderColor = '#b0e0e6';
                    if (!input.value.trim()) {
                        input.style.borderColor = 'red';
                        subjectCodeValid = false;
                    }
                });

                if (!subjectCodeValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Subject Codes',
                        text: 'Please fill in all Subject Code fields. Empty fields are highlighted in red.',
                        confirmButtonColor: '#57afbfd6',
                        confirmButtonText: 'OK'
                    });
                    const firstEmpty = document.querySelector('.subject-code[style*="border-color: red"]');
                    if (firstEmpty) {
                        firstEmpty.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                    return;
                }

                // Personal details validation
                let personalDetailsValid = true;
                const surnameInput = document.getElementById('surname');
                const firstNameInput = document.getElementById('firstName');
                const dobInput = document.getElementById('dob');
                const genderInput = document.getElementById('gender');
                const missingFields = [];

                [surnameInput, firstNameInput, dobInput, genderInput].forEach(input => {
                    input.style.borderColor = '#b0e0e6';
                });

                if (!surnameInput.value.trim()) {
                    surnameInput.style.borderColor = 'red';
                    missingFields.push('Surname');
                    personalDetailsValid = false;
                }
                if (!firstNameInput.value.trim()) {
                    firstNameInput.style.borderColor = 'red';
                    missingFields.push('First Name');
                    personalDetailsValid = false;
                }
                if (!dobInput.value) {
                    dobInput.style.borderColor = 'red';
                    missingFields.push('Date of Birth');
                    personalDetailsValid = false;
                }
                if (!genderInput.value) {
                    genderInput.style.borderColor = 'red';
                    missingFields.push('Gender');
                    personalDetailsValid = false;
                }

                if (!personalDetailsValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Personal Details',
                        text: `Please fill in the following fields: ${missingFields.join(', ')}. Empty fields are highlighted in red.`,
                        confirmButtonColor: '#57afbfd6',
                        confirmButtonText: 'OK'
                    });
                    const firstEmptyPersonal = [surnameInput, firstNameInput, dobInput, genderInput].find(
                        input => input.style.borderColor === 'red');
                    if (firstEmptyPersonal) {
                        firstEmptyPersonal.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                    return;
                }

                // Access arrangement validation
                if (accessArrangement === 'yes' && supportingDocuments.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Documents',
                        text: 'You have selected "Yes" for Access Arrangement. Please upload supporting documents in PDF, PNG, JPG, or JPEG format.',
                        confirmButtonColor: '#57afbfd6',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // File type validation
                if (supportingDocuments.length > 0) {
                    const allowedTypes = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
                    for (let i = 0; i < supportingDocuments.length; i++) {
                        if (!allowedTypes.includes(supportingDocuments[i].type)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid File Type',
                                text: 'Supporting documents must be in PDF, PNG, JPG, or JPEG format.',
                                confirmButtonColor: '#57afbfd6',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                    }
                }

                // Submit form directly without UCI validation
                submitForm(form);
            });

            // Form submission function
            function submitForm(form) {
                console.log('Submitting form...');
                Swal.fire({
                    title: 'Please Wait',
                    text: 'Form is submitting...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData(form);
                const submitButton = $('.submitNow');
                submitButton.prop('disabled', true);

                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Your form has been successfully submitted. Our team will contact you very soon.',
                            confirmButtonColor: '#57afbfd6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            form.reset();
                            isRowAdded = false;
                            $('.appendSec').empty();
                            $('#additionalDocsContainer').html('');
                            $('#additionalDocsContainer').append(createDocumentRow(false));
                            if (window.parent !== window) {
                                window.parent.postMessage({
                                    type: 'formSubmission',
                                    status: 'success',
                                    message: 'Form submitted successfully'
                                }, '*');
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.close();
                        submitButton.prop('disabled', false);
                        let errorMessage = 'An error occurred while submitting the form.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#57afbfd6',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            // Keep session alive
            setInterval(() => {
                fetch("{{ url('/keep-alive') }}", {
                    method: 'GET',
                    credentials: 'include'
                }).catch(() => console.log("Keep-alive failed"));
            }, 1 * 60 * 1000);

            // Email validation
            function validateEmail() {
                const emailInput = document.getElementById('email');
                const emailError = document.getElementById('emailError');
                const emailValue = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (emailValue === '') {
                    emailError.textContent = 'Email is required';
                    emailError.style.display = 'block';
                    emailInput.style.borderColor = 'red';
                } else if (!emailValue.includes('@')) {
                    emailError.textContent = 'Email must contain @ symbol';
                    emailError.style.display = 'block';
                    emailInput.style.borderColor = 'red';
                } else if (!emailPattern.test(emailValue)) {
                    emailError.textContent = 'Please enter a valid email address';
                    emailError.style.display = 'block';
                    emailInput.style.borderColor = 'red';
                } else {
                    emailError.style.display = 'none';
                    emailInput.style.borderColor = '#b0e0e6';
                }
            }

            $('#email').on('input', validateEmail);



            // Terms modal handling
            let scrollPosition = 0;
            window.openTermsModal = function(event) {
                event.preventDefault();
                scrollPosition = window.scrollY;
                document.body.style.position = 'fixed';
                document.body.style.top = `-${scrollPosition}px`;
                document.body.style.width = '100%';
                document.getElementById('termsModal').style.display = 'block';
            };

            window.closeTermsModal = function() {
                document.getElementById('termsModal').style.display = 'none';
                document.body.style.position = '';
                document.body.style.top = '';
                window.scrollTo(0, scrollPosition);
            };

            // Prevent right-click
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uciInput = document.getElementById('uciNumber');
        const sessionSelect = document.getElementById('session');
        const submitButton = document.getElementById('submitButton');

        // Disable button by default until validation done
        submitButton.disabled = true;
        submitButton.style.opacity = "0.6";
        submitButton.style.cursor = "not-allowed";

        // Message span
        let msgSpan = document.createElement('span');
        msgSpan.id = "uciMessage";
        msgSpan.style.display = "block";
        msgSpan.style.marginTop = "5px";
        uciInput.parentNode.appendChild(msgSpan);

        uciInput.addEventListener('blur', function() {
            const uciNumber = uciInput.value.trim();
            const session = sessionSelect.value;

            // Clear old messages
            msgSpan.textContent = "";
            msgSpan.style.color = "";

            if (uciNumber && session) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ url("validate-uci-number") }}', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        const response = JSON.parse(xhr.responseText);

                        if (xhr.status === 200 && response.success) {
                            // ✅ Available
                            msgSpan.textContent = response.message;
                            msgSpan.style.color = "green";

                            // Enable button
                            submitButton.disabled = false;
                            submitButton.style.opacity = "1";
                            submitButton.style.cursor = "pointer";
                        }
                        else if (xhr.status === 409) {
                            // ❌ Already exists
                            msgSpan.textContent = response.message;
                            msgSpan.style.color = "red";

                            // Disable button
                            submitButton.disabled = true;
                            submitButton.style.opacity = "0.6";
                            submitButton.style.cursor = "not-allowed";
                        }
                        else {
                            // Other errors
                            msgSpan.textContent = "Something went wrong. Try again.";
                            msgSpan.style.color = "red";

                            // Disable button
                            submitButton.disabled = true;
                            submitButton.style.opacity = "0.6";
                            submitButton.style.cursor = "not-allowed";
                        }
                    }
                };

                const data = JSON.stringify({
                    uciNumber: uciNumber,
                    session: session
                });
                xhr.send(data);
            }
        });
    });
</script>
@endsection
