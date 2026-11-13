<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Dashboard') }}</title>

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

<style>
.lec-booking{margin:26px 0 32px;color:#142d50;font-family:Nunito,Arial,sans-serif}
.lec-booking-hero{padding:24px 28px;color:#fff;border-radius:16px 16px 0 0;background:linear-gradient(110deg,#1556a1 0%,#286eb8 62%,#174b8c 100%);position:relative;overflow:hidden}
.lec-booking-hero h2{margin:0 0 6px;font-size:clamp(22px,3vw,30px);font-weight:800;color:#fff}
.lec-booking-hero p{margin:0;color:#eaf3ff;font-size:14px}
.lec-booking-grid{display:grid;grid-template-columns:minmax(0,1.8fr) minmax(290px,.92fr);gap:18px;padding:18px;background:#f4f7fb;border:1px solid #e1e9f4;border-top:0;border-radius:0 0 16px 16px;align-items:start}
.lec-select-panel,.lec-basket-panel{background:#fff;border:1px solid #e0e8f2;border-radius:15px;box-shadow:0 5px 18px rgba(23,61,105,.07)}
.lec-panel-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:17px 19px;border-bottom:1px solid #e9eef5}
.lec-panel-heading h3{margin:0;color:#15365f;font-size:18px;font-weight:800}
.lec-panel-heading .lec-icon{color:#1d63aa;font-size:20px;margin-right:9px}
.lec-select-body{padding:18px}
.lec-field-label{display:block;margin:0 0 7px;color:#25466e;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.45px}
.lec-field{width:100%;min-height:45px;padding:10px 13px;border:1px solid #ccd8e7;border-radius:9px;background:#fff;color:#18385f;font-size:14px;font-weight:700}
.lec-field:focus{outline:none;border-color:#2c70b8;box-shadow:0 0 0 3px rgba(44,112,184,.12)}
.lec-field-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
.lec-current-subject{margin-top:16px;padding:16px;border:1px solid #d7e5f6;border-radius:12px;background:linear-gradient(110deg,#f1f6ff,#e8f1ff)}
.lec-current-top{display:flex;justify-content:space-between;align-items:center;gap:12px}
.lec-current-title{font-size:17px;font-weight:800;color:#173c6c}
.lec-current-price{font-size:23px;font-weight:900;color:#153d70;white-space:nowrap}
.lec-current-meta{margin-top:3px;font-size:12px;color:#536a84}
.lec-option-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px}
.lec-option-block{min-width:0}
.lec-option-title{margin:0 0 8px;color:#193c68;font-size:13px;font-weight:800}
.lec-choice{display:flex;align-items:flex-start;gap:9px;padding:10px 11px;margin:6px 0;min-height:42px;border:1px solid #d9e1ec;border-radius:9px;cursor:pointer;background:#fff;color:#304964;font-size:12px}
.lec-choice:has(input:checked){border-color:#3676b9;background:#edf5ff;color:#153d70;box-shadow:inset 0 0 0 1px #3676b9}
.lec-choice input{margin-top:2px;accent-color:#2165a9}
.lec-info{margin-top:12px;padding:10px 12px;border-radius:9px;background:#edf4fc;color:#395a7d;font-size:11px;line-height:1.5}
.lec-add-row{display:flex;align-items:center;justify-content:flex-end;gap:15px;margin-top:15px;padding-top:14px;border-top:1px solid #d8e5f5}
.lec-add-row strong{color:#153d70;font-size:24px}
.lec-add-btn,.lec-continue-btn{border:0;border-radius:9px;padding:12px 18px;color:#fff;background:#1e60a7;font-weight:800;font-size:13px;cursor:pointer;transition:.2s}
.lec-add-btn:hover,.lec-continue-btn:hover{background:#164c88;transform:translateY(-1px)}
.lec-basket-count{padding:5px 10px;border-radius:20px;background:#e8f0fb;color:#194b82;font-size:11px;font-weight:800;white-space:nowrap}
.lec-basket-body{padding:12px 15px 16px}
.lec-basket-empty{padding:26px 12px;text-align:center;color:#71839a;font-size:13px}
.lec-basket-empty i{display:block;margin-bottom:9px;font-size:27px;color:#8da8c8}
.lec-basket-item{position:relative;padding:12px 36px 12px 10px;border-bottom:1px solid #e9eef5}
.lec-basket-item:last-child{border-bottom:0}
.lec-basket-item h4{margin:0 0 3px;color:#173b67;font-size:13px;font-weight:800}
.lec-basket-item .lec-item-meta{color:#657b95;font-size:11px}
.lec-basket-item .lec-item-options{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.lec-item-pill{padding:4px 7px;border-radius:6px;background:#eef2f7;color:#4a6079;font-size:10px;font-weight:700}
.lec-item-price{float:right;margin-left:8px;color:#173b67;font-size:13px;font-weight:900}
.lec-remove-item{position:absolute;top:11px;right:2px;border:0;background:transparent;color:#6b7d91;font-size:15px;cursor:pointer}
.lec-remove-item:hover{color:#c0392b}
.lec-basket-total{display:flex;align-items:center;justify-content:space-between;gap:10px;margin:9px 0 13px;padding:13px 12px;border-radius:10px;background:#e8f1ff;color:#183e70;font-weight:800}
.lec-basket-total strong{font-size:22px;white-space:nowrap}
.lec-basket-note{margin:0 0 12px;color:#687e96;font-size:10px;line-height:1.5}
.lec-continue-btn{display:block;width:100%;padding:13px 15px}
.lec-feature-strip{display:flex;justify-content:space-around;flex-wrap:wrap;gap:10px;padding:15px 8px 4px;color:#274c75;font-size:11px;font-weight:800}
.lec-feature-strip span{display:flex;align-items:center;gap:7px}
.lec-feature-strip i{color:#2869ad;font-size:17px}
#candidate-details{scroll-margin-top:24px}
@media(max-width:850px){.lec-booking-grid{grid-template-columns:1fr}.lec-field-grid{grid-template-columns:1fr}}
@media(max-width:560px){.lec-booking-hero{padding:19px 17px}.lec-booking-grid{padding:10px}.lec-select-body{padding:13px}.lec-option-grid{grid-template-columns:1fr}.lec-current-top{align-items:flex-start;flex-direction:column;gap:4px}.lec-add-row{align-items:stretch;flex-direction:column}.lec-add-row strong{text-align:right}.lec-add-btn{width:100%}.lec-feature-strip{justify-content:flex-start;padding-left:14px}}
</style>

</head>
<div class="container-fluid" style="padding: 20px;">
    <div class=""
        style="max-width: 1240px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
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
                         {{-- {{dd($sessions)}} --}}
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
                </div>



                    <div id="candidate-details" style="margin:10px 0 18px;">
                        <h4 style="margin:0;color:#183d69;font-size:18px;font-weight:800;"><i class="fas fa-user-edit" style="margin-right:8px;color:#2869ad;"></i>Candidate details</h4>
                        <p style="margin:4px 0 0;color:#6b7e94;font-size:12px;">Enter the candidate's details to continue with registration.</p>
                    </div>
                    <div class="row">
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

<section class="lec-booking" aria-label="Exam selection and basket">
                        <div class="lec-booking-hero">
                            <h2><i class="fas fa-graduation-cap" style="margin-right:10px;"></i>Find and book your exams</h2>
                            <p>Search subjects by qualification, exam board or subject code. Add exams to your basket as you go.</p>
                        </div>
                        <div class="lec-booking-grid">
                            <div class="lec-select-panel">
                                <div class="lec-panel-heading"><h3><i class="fas fa-search lec-icon"></i>Choose your exam</h3></div>
                                <div class="lec-select-body">
                                    <div class="lec-field-grid">
                                        <div><label class="lec-field-label" for="lecBoard">Exam board</label><select id="lecBoard" class="lec-field" disabled><option value="">Select board</option></select></div>
                                        <div><label class="lec-field-label" for="lecQualification">Qualification</label><select id="lecQualification" class="lec-field" disabled><option value="">Select qualification</option></select></div>
                                        <div><label class="lec-field-label" for="lecSubject">Subject</label><select id="lecSubject" class="lec-field" disabled><option value="">Select subject</option></select></div>
                                        <div><label class="lec-field-label" for="lecSubjectCode">Subject Code</label><select id="lecSubjectCode" class="lec-field" disabled><option value="">Select subject code</option></select></div>
                                        <div><label class="lec-field-label" for="lecOptionCode">Option Code</label><select id="lecOptionCode" class="lec-field" disabled><option value="">Select option code</option></select></div>
                                    </div>
                                    <div class="lec-current-subject">
                                        <div class="lec-current-top">
                                            <div><div class="lec-current-title" id="lecSelectedTitle">Select a subject to view details</div><div class="lec-current-meta" id="lecSelectedMeta">Qualification, board and subject code will appear here</div></div>
                                            <div class="lec-current-price" id="lecSelectedPrice">—</div>
                                        </div>
                                        <div class="lec-option-grid">
                                            <div class="lec-option-block">
                                                <p class="lec-option-title"><i class="fas fa-layer-group"></i> Tier</p>
                                                <label class="lec-choice"><input type="radio" name="lecTier" value="N/A" checked> N/A (Not tiered)</label>
                                                <label class="lec-choice"><input type="radio" name="lecTier" value="Higher Tier"> Higher tier</label>
                                                <label class="lec-choice"><input type="radio" name="lecTier" value="Foundation Tier"> Foundation tier</label>
                                            </div>
                                            <div class="lec-option-block">
                                                <p class="lec-option-title"><i class="fas fa-file-alt"></i> NEA (Non-Exam Assessment)</p>
                                                <label class="lec-choice"><input type="radio" name="lecNea" value="N/A" checked> Not applicable</label>
                                                <label class="lec-choice"><input type="radio" name="lecNea" value="Carry Forward"> Carry forward existing NEA</label>
                                                <label class="lec-choice"><input type="radio" name="lecNea" value="Frobel's NEA"> Frobel's NEA support</label>
                                            </div>
                                        </div>
                                        <div class="lec-info" id="lecOptionInfo"><i class="fas fa-info-circle"></i> Carry forward is only available where a candidate has an eligible, previously approved NEA. Frobel's NEA support and any additional fee are subject to centre confirmation.</div>
                                        <div class="lec-add-row"><strong id="lecAddPrice">—</strong><button type="button" class="lec-add-btn" id="lecAddToBasket"><i class="fas fa-shopping-cart" style="margin-right:7px;"></i>Add to basket</button></div>
                                    </div>
                                </div>
                            </div>
                            <aside class="lec-basket-panel">
                                <div class="lec-panel-heading"><h3><i class="fas fa-shopping-cart lec-icon"></i>Your exam basket</h3><span class="lec-basket-count" id="lecBasketCount">0 subjects</span></div>
                                <div class="lec-basket-body">
                                    <div id="lecBasketItems"><div class="lec-basket-empty"><i class="fas fa-shopping-basket"></i>Your basket is empty.<br>Choose an exam and select Add to basket.</div></div>
                                    <div class="lec-basket-total"><span id="lecBasketTotalLabel">Total (0 subjects)</span><strong id="lecBasketTotal">£0.00</strong></div>
                                    <p class="lec-basket-note">Prices shown are taken from the exam-centre fee records. Where a fee or NEA charge has not been configured, it will be marked as to be confirmed before payment.</p>
                                    <button type="button" class="lec-continue-btn" id="lecContinueRegistration">Continue to registration <i class="fas fa-arrow-right" style="margin-left:8px;"></i></button>
                                </div>
                            </aside>
                        </div>
                        <div class="lec-feature-strip">
                            <span><i class="fas fa-check-circle"></i>Multiple qualifications</span><span><i class="fas fa-layer-group"></i>Optional tier selection</span><span><i class="fas fa-file-alt"></i>NEA options</span><span><i class="fas fa-pound-sign"></i>Transparent pricing</span><span><i class="fas fa-shield-alt"></i>Secure online payment</span>
                        </div>
                        <div id="lecBasketInputs" style="display:none;"></div>
                    </section>

                <!-- Exam Table -->
                {{-- <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-table sessionFAS"
                            style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Subject Code</label>
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
                                        @foreach ($boards as $board)
                                            @if ($board !== 'CIE')
                                                <option value="{{ $board }}">{{ $board }}</option>
                                            @endif
                                        @endforeach
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
                        style="background-color: #57afbfd6; border: none; padding: 12px 15px; font-size: 12px; margin-top: 10px; color: white;">
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
                                // Trigger custom event for validation
                                const event = new Event('signatureUpdated', { bubbles: true });
                                signatureInput.dispatchEvent(event);
                            }

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


        // Qualification, board and subject data supplied by the controller.
        const subjectsBySession = @json($subjectsBySession);
                let sessionData = {};
        const sessionSelect = document.getElementById('session');
        const qualificationSelect = document.getElementById('lecQualification');
        const boardSelect = document.getElementById('lecBoard');
        const subjectSelect = document.getElementById('lecSubject');
        const subjectCodeSelect = document.getElementById('lecSubjectCode');
        const basket = [];
        const basketItems = document.getElementById('lecBasketItems');
        const basketInputs = document.getElementById('lecBasketInputs');

        function resetSelect(select, placeholder) {
            select.innerHTML = '';
            const option = new Option(placeholder, '');
            option.disabled = true;
            option.selected = true;
            select.add(option);
        }

        function loadSessionOptions() {
            sessionData = subjectsBySession[sessionSelect.value] || {};
            [boardSelect, qualificationSelect, subjectSelect, subjectCodeSelect].forEach((select, index) => {
                resetSelect(select, ['Select board', 'Select qualification', 'Select subject', 'Select subject code'][index]);
            });
            boardSelect.disabled = !sessionSelect.value;
            qualificationSelect.disabled = true;
            subjectSelect.disabled = true;
            subjectCodeSelect.disabled = true;
            const boards = [...new Set(Object.values(sessionData.boardsByQualification || {}).flat())].sort();
            boards.forEach(board => boardSelect.add(new Option(board, board)));
            updateSelectedSubject();
        }

        sessionSelect.addEventListener('change', loadSessionOptions);
        loadSessionOptions();

        boardSelect.addEventListener('change', function () {
            resetSelect(qualificationSelect, 'Select qualification');
            resetSelect(subjectSelect, 'Select subject');
            resetSelect(subjectCodeSelect, 'Select subject code');
            const qualifications = Object.keys(sessionData.boardsByQualification || {})
                .filter(qualification => (sessionData.boardsByQualification[qualification] || []).includes(this.value));
            qualifications.forEach(qualification => qualificationSelect.add(new Option(qualification, qualification)));
            qualificationSelect.disabled = !this.value || qualifications.length === 0;
            subjectSelect.disabled = true;
            subjectCodeSelect.disabled = true;
            updateSelectedSubject();
        });

        qualificationSelect.addEventListener('change', function () {
            resetSelect(subjectSelect, 'Select subject');
            resetSelect(subjectCodeSelect, 'Select subject code');
            const subjects = sessionData.subjectsByQualificationAndBoard?.[this.value]?.[boardSelect.value] || {};
            Object.keys(subjects).forEach(subject => {
                subjectSelect.add(new Option(subject, subject));
                const code = subjects[subject]?.code;
                if (code !== null && code !== undefined && String(code).trim() !== '') {
                    subjectCodeSelect.add(new Option(String(code), String(code)));
                }
            });
            subjectSelect.disabled = !this.value;
            subjectCodeSelect.disabled = !this.value || subjectCodeSelect.options.length <= 1;
            updateSelectedSubject();
        });

        subjectSelect.addEventListener('change', function () {
            const details = sessionData.subjectsByQualificationAndBoard?.[qualificationSelect.value]?.[boardSelect.value]?.[this.value];
            subjectCodeSelect.value = details?.code ? String(details.code) : '';
            updateSelectedSubject();
        });

        subjectCodeSelect.addEventListener('change', function () {
            const subjects = sessionData.subjectsByQualificationAndBoard?.[qualificationSelect.value]?.[boardSelect.value] || {};
            const match = Object.keys(subjects).find(subject => String(subjects[subject]?.code ?? '') === this.value);
            if (match) subjectSelect.value = match;
            updateSelectedSubject();
        });document.querySelectorAll('input[name="lecTier"], input[name="lecNea"]').forEach(input => input.addEventListener('change', updateSelectedSubject));

        function getSelectedDetails() {
            const qualification = qualificationSelect.value;
            const board = boardSelect.value;
            const subject = subjectSelect.value;
            const details = sessionData.subjectsByQualificationAndBoard?.[qualification]?.[board]?.[subject];
            return details ? { qualification, board, subject, code: details.code, amount: details.amount } : null;
        }

        function money(value) {
            return '£' + Number(value).toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function updateSelectedSubject() {
            const item = getSelectedDetails();
            const tier = document.querySelector('input[name="lecTier"]:checked')?.value || 'N/A';
            const nea = document.querySelector('input[name="lecNea"]:checked')?.value || 'N/A';
            const title = document.getElementById('lecSelectedTitle');
            const meta = document.getElementById('lecSelectedMeta');
            const price = document.getElementById('lecSelectedPrice');
            const addPrice = document.getElementById('lecAddPrice');
            if (!item) {
                title.textContent = 'Select a subject to view details';
                meta.textContent = 'Qualification, board and subject code will appear here';
                price.textContent = '—';
                addPrice.textContent = '—';
                return;
            }
            title.textContent = item.qualification + ' ' + item.subject;
            meta.textContent = item.board + '  |  ' + item.code + (tier !== 'N/A' ? '  |  ' + tier : '');
            const basePrice = item.amount !== null && item.amount !== '' ? Number(item.amount) : null;
            const displayPrice = basePrice === null || !Number.isFinite(basePrice) ? 'Price TBC' : money(basePrice);
            price.textContent = displayPrice;
            addPrice.textContent = displayPrice + (nea === "Frobel's NEA" ? ' + NEA fee TBC' : '');
            document.getElementById('lecOptionInfo').textContent = nea === "Frobel's NEA"
                ? "Frobel's NEA support selected. The additional NEA fee must be confirmed by the Exams Office before payment."
                : "Carry forward is only available where a candidate has an eligible, previously approved NEA. Frobel's NEA support and any additional fee are subject to centre confirmation.";
        }

        function escapeBasketText(value) {
            return String(value ?? '').replace(/[&<>"']/g, character => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;' }[character]));
        }

        function renderBasket() {
            basketItems.innerHTML = '';
            if (!basket.length) {
                basketItems.innerHTML = '<div class="lec-basket-empty"><i class="fas fa-shopping-basket"></i>Your basket is empty.<br>Choose an exam and select Add to basket.</div>';
            }
            basket.forEach((item, index) => {
                const priceText = item.amount === null ? 'Price TBC' : money(item.amount);
                const extraText = item.nea === "Frobel's NEA" ? ' + NEA fee TBC' : '';
                const card = document.createElement('div');
                card.className = 'lec-basket-item';
                card.innerHTML = '<span class="lec-item-price">' + priceText + extraText + '</span>' +
                    '<h4>' + escapeBasketText(item.qualification) + ' ' + escapeBasketText(item.subject) + '</h4>' +
                    '<div class="lec-item-meta">' + escapeBasketText(item.board) + ' · ' + escapeBasketText(item.code) + '</div>' +
                    '<div class="lec-item-options"><span class="lec-item-pill"><i class="fas fa-layer-group"></i> Tier: ' + escapeBasketText(item.tier) + '</span>' +
                    '<span class="lec-item-pill"><i class="fas fa-file-alt"></i> NEA: ' + escapeBasketText(item.nea) + '</span></div>' +
                    '<button type="button" class="lec-remove-item" data-index="' + index + '" aria-label="Remove item"><i class="fas fa-trash-alt"></i></button>';
                basketItems.appendChild(card);
            });

            const count = basket.length;
            document.getElementById('lecBasketCount').textContent = count + (count === 1 ? ' subject' : ' subjects');
            document.getElementById('lecBasketTotalLabel').textContent = 'Total (' + count + (count === 1 ? ' subject)' : ' subjects)');
            const hasUnknownBase = basket.some(item => item.amount === null);
            const hasUnknownNea = basket.some(item => item.nea === "Frobel's NEA");
            const knownTotal = basket.reduce((sum, item) => sum + (item.amount === null ? 0 : item.amount), 0);
            let totalText = money(knownTotal);
            if (hasUnknownBase) totalText = 'Price TBC';
            else if (hasUnknownNea) totalText += ' + NEA fee TBC';
            document.getElementById('lecBasketTotal').textContent = count ? totalText : '£0.00';

            basketInputs.innerHTML = '';
            basket.forEach(item => {
                [['examBoard[]',item.board],['courseType[]',item.qualification],['subjectName[]',item.subject],['subjectCode[]',item.code,'subject-code'],['tire[]',item.tier],['nea[]',item.nea]].forEach(([name,value,className]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    if (className) input.className = className;
                    basketInputs.appendChild(input);
                });
            });
        }

        document.getElementById('lecAddToBasket').addEventListener('click', function () {
            const selected = getSelectedDetails();
            if (!selected) {
                Swal.fire({ icon:'warning', title:'Choose an exam', text:'Please select a qualification, exam board and subject first.', confirmButtonColor:'#1e60a7' });
                return;
            }
            if (basket.some(item => item.board === selected.board && item.qualification === selected.qualification && item.subject === selected.subject)) {
                Swal.fire({ icon:'info', title:'Already in basket', text:'This subject has already been added to your basket.', confirmButtonColor:'#1e60a7' });
                return;
            }
            const rawAmount = selected.amount;
            const amount = rawAmount === null || rawAmount === '' || !Number.isFinite(Number(rawAmount)) ? null : Number(rawAmount);
            basket.push({ ...selected, amount, tier:document.querySelector('input[name="lecTier"]:checked')?.value || 'N/A', nea:document.querySelector('input[name="lecNea"]:checked')?.value || 'N/A' });
            renderBasket();
            // Reload board options from the currently selected session.
            loadSessionOptions();
            document.querySelector('input[name="lecTier"][value="N/A"]').checked = true;
            document.querySelector('input[name="lecNea"][value="N/A"]').checked = true;
            updateSelectedSubject();

            Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Added to basket', text:'Choose your next exam below.', showConfirmButton:false, timer:1400 });
        });

        basketItems.addEventListener('click', function (event) {
            const button = event.target.closest('.lec-remove-item');
            if (!button) return;
            basket.splice(Number(button.dataset.index), 1);
            renderBasket();
        });

        document.getElementById('lecContinueRegistration').addEventListener('click', function () {
            if (!basket.length) {
                Swal.fire({ icon:'warning', title:'Your basket is empty', text:'Add at least one exam before continuing.', confirmButtonColor:'#1e60a7' });
                return;
            }
            document.getElementById('candidate-details').scrollIntoView({ behavior:'smooth', block:'start' });
        });

        renderBasket();

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

        // Main submit handler
// Main submit handler
$('.submitNow').on('click', function(e) {
    e.preventDefault();
    console.log('Submit button clicked, starting validation process');

    const form = document.getElementById('examForm');
    const uciNumber = $('#uciNumber').val().trim();
    const accessArrangement = $('#accessArrangement').val();
    const supportingDocuments = $('#supportingDocuments')[0].files;
    const emailInput = document.getElementById('email');
    const contactNumberInput = document.getElementById('contactNumber');
    const emailError = document.getElementById('emailError');
    const termsCheckbox = document.getElementById('termsCheckbox');
    const signatureInput = document.getElementById('signature');
    const canvas = document.getElementById('signatureCanvas');

    // Array to collect all missing validations
    const allMissingValidations = [];

    // Reset border colors and error display
    emailInput.style.borderColor = '#b0e0e6';
    contactNumberInput.style.borderColor = '#b0e0e6';
    emailError.style.display = 'none';

    // Session validation
    const sessionInput = document.getElementById('session');
    if (sessionInput) {
        sessionInput.style.borderColor = '#b0e0e6';
        if (!sessionInput.value || sessionInput.value.trim() === '') {
            sessionInput.style.borderColor = 'red';
            if (!allMissingValidations.includes('Session')) {
                allMissingValidations.push('Session');
            }
        }
    }

    // Personal details validation
    const surnameInput = document.getElementById('surname');
    const firstNameInput = document.getElementById('firstName');
    const dobInput = document.getElementById('dob');
    const genderInput = document.getElementById('gender');

    [surnameInput, firstNameInput, dobInput, genderInput].forEach(input => {
        input.style.borderColor = '#b0e0e6';
    });

    if (!surnameInput.value.trim()) {
        surnameInput.style.borderColor = 'red';
        allMissingValidations.push('Surname');
    }
    if (!firstNameInput.value.trim()) {
        firstNameInput.style.borderColor = 'red';
        allMissingValidations.push('First Name');
    }
    if (!dobInput.value) {
        dobInput.style.borderColor = 'red';
        allMissingValidations.push('Date of Birth');
    }
    if (!genderInput.value) {
        genderInput.style.borderColor = 'red';
        allMissingValidations.push('Gender');
    }

    // Email validation (empty, @ symbol, and format)
    const emailValue = emailInput.value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailValue) {
        emailInput.style.borderColor = 'red';
        emailError.textContent = 'Email is required';
        emailError.style.display = 'block';
        if (!allMissingValidations.includes('Email Address')) {
            allMissingValidations.push('Email Address');
        }
    } else if (!emailValue.includes('@')) {
        emailInput.style.borderColor = 'red';
        emailError.textContent = 'Email must contain @ symbol';
        emailError.style.display = 'block';
        if (!allMissingValidations.includes('Email Address')) {
            allMissingValidations.push('Email Address');
        }
    } else if (!emailPattern.test(emailValue)) {
        emailInput.style.borderColor = 'red';
        emailError.textContent = 'Please enter a valid email address';
        emailError.style.display = 'block';
        if (!allMissingValidations.includes('Email Address')) {
            allMissingValidations.push('Email Address');
        }
    }

    // Contact Number validation (only null check)
    const contactNumberValue = contactNumberInput.value.trim();
    if (!contactNumberValue) {
        contactNumberInput.style.borderColor = 'red';
        if (!allMissingValidations.includes('Contact Number')) {
            allMissingValidations.push('Contact Number');
        }
    }

    // At least one exam must be selected in the basket before registration.
    if (basket.length === 0) {
        allMissingValidations.push('At least one exam subject');
    }
    // Subject code validation
    const subjectCodeInputs = document.querySelectorAll('.subject-code');
    let hasEmptySubjectCode = false;
    subjectCodeInputs.forEach(input => {
        input.style.borderColor = '#b0e0e6';
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            hasEmptySubjectCode = true;
        }
    });

    if (hasEmptySubjectCode) {
        if (!allMissingValidations.includes('Subject Code(s)')) {
            allMissingValidations.push('Subject Code(s)');
        }
    }

    // Access arrangement validation
    if (accessArrangement === 'yes' && supportingDocuments.length === 0) {
        if (!allMissingValidations.includes('Supporting Documents (required for Access Arrangement)')) {
            allMissingValidations.push('Supporting Documents (required for Access Arrangement)');
        }
    }

    // File type validation
    if (supportingDocuments.length > 0) {
        const allowedTypes = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
        for (let i = 0; i < supportingDocuments.length; i++) {
            if (!allowedTypes.includes(supportingDocuments[i].type)) {
                if (!allMissingValidations.includes('Valid File Type for Supporting Documents')) {
                    allMissingValidations.push('Valid File Type for Supporting Documents');
                }
                break;
            }
        }
    }

    // Terms and Conditions validation
    if (!termsCheckbox || !termsCheckbox.checked) {
        if (!allMissingValidations.includes('Terms and Conditions Agreement')) {
            allMissingValidations.push('Terms and Conditions Agreement');
        }
    }

    // Signature validation
    const signatureValue = signatureInput ? signatureInput.value : '';
    // Check if signature is empty or invalid
    // Since we track hasDrawn flag, if signature is empty it means no drawing was done
    const isEmptySignature = !signatureValue || 
                            signatureValue.trim() === '' || 
                            !signatureValue.startsWith('data:image');
    
    if (isEmptySignature) {
        if (!allMissingValidations.includes('Signature')) {
            allMissingValidations.push('Signature');
        }
    }

    // If there are any missing validations, show them all at once
    if (allMissingValidations.length > 0) {
        const validationMessage = 'Please complete the following validations:\n\n' + 
                                  allMissingValidations.map((item, index) => `${index + 1}. ${item}`).join('\n') +
                                  '\n\nAll validations must be completed before submitting the form.';
        
        Swal.fire({
            icon: 'error',
            title: 'Validation Pending',
            html: '<div style="text-align: left; padding: 10px;">' +
                  '<strong>Please complete the following validations:</strong><br><br>' +
                  '<ol style="padding-left: 20px;">' +
                  allMissingValidations.map(item => `<li style="margin-bottom: 8px;">${item}</li>`).join('') +
                  '</ol><br>' +
                  '<strong style="color: #e74c3c;">All validations must be completed before submitting the form.</strong>' +
                  '</div>',
            confirmButtonColor: '#57afbfd6',
            confirmButtonText: 'OK',
            width: '600px'
        });

        // Scroll to first error field
        const firstErrorField = document.querySelector('input[style*="border-color: red"], select[style*="border-color: red"]');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        } else if (sessionInput && (!sessionInput.value || sessionInput.value.trim() === '')) {
            // Scroll to session if it's missing
            sessionInput.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        } else if (!termsCheckbox || !termsCheckbox.checked) {
            // Scroll to terms checkbox if it's not checked
            termsCheckbox.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        } else if (isEmptySignature) {
            // Scroll to signature canvas if signature is missing
            if (canvas) {
                canvas.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }
        return;
    }

    // All validations passed - submit form
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




{{-- brenttuitionandexamcentre start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'brenttuitionandexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#57afbfd6'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#57afbfd6'; // Set text color for each element
                });
            }
        }
    });
</script>
{{-- brenttuitionandexamcentre end --}}

{{-- essexexamcentre start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'essexexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#0070b7f0'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#0070b7'; // Set text color for each element
                });
            }
        }
    });
</script>
{{-- brenttuitionandexamcentre end --}}


{{-- frobeleducation start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'frobeleducation.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#F27810'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#F27810'; // Set text color for each element
                });
            }
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'frobeleducation.efrobel.com';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#F27810'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#F27810'; // Set text color for each element
                });
            }
        }
    });
</script>
{{-- frobeleducation end --}}



{{-- essexexamcentre start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'londonexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#2A2A70'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#2A2A70'; // Set text color for each element
                });
            }
        }
    });
</script>
{{-- brenttuitionandexamcentre end --}}


{{-- camdentuitionandexamcentre.co.uk start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'camdentuitionandexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#70A309'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#70A309'; // Set text color for each element
                });
            }
        }
    });
</script>


{{-- camdentuitionandexamcentre.co.uk end --}}



{{-- croydontuitionandexamcentre.co.uk start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'croydontuitionandexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#FD8700'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#FD8700'; // Set text color for each element
                });
            }
        }
    });
</script>


{{-- croydontuitionandexamcentre.co.uk end --}}





{{-- ealingtuitionandexamcentre.co.uk start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'ealingtuitionandexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#535353'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#535353'; // Set text color for each element
                });
            }
        }
    });
</script>


{{-- ealingtuitionandexamcentre.co.uk end --}}




{{-- https://bexleytuitionandexamcentre.co.uk/ start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'bexleytuitionandexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#448375'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#448375'; // Set text color for each element
                });
            }
        }
    });
</script>


{{-- https://bexleytuitionandexamcentre.co.uk/ end --}}




{{-- please wait on page laod  --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create loading overlay
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.style.position = 'fixed';
        loadingOverlay.style.top = '0';
        loadingOverlay.style.left = '0';
        loadingOverlay.style.width = '100%';
        loadingOverlay.style.height = '100%';
        loadingOverlay.style.backgroundColor = 'rgba(255, 255, 255, 0.9)';
        loadingOverlay.style.display = 'flex';
        loadingOverlay.style.justifyContent = 'center';
        loadingOverlay.style.alignItems = 'center';
        loadingOverlay.style.zIndex = '9999';
        loadingOverlay.innerHTML = `
        <div style="text-align: center;">
            <i class="fas fa-spinner fa-spin" style="font-size: 48px; color: #57afbfd6;"></i>
            <p style="font-size: 24px; color: #2c3e50; margin-top: 20px;">Loading, please wait...</p>
        </div>
    `;

        // Append loading overlay to body
        document.body.appendChild(loadingOverlay);

        // Function to hide loading overlay
        function hideLoadingOverlay() {
            loadingOverlay.style.display = 'none';
        }

        // Check if all scripts are loaded
        const scripts = [
            'https://code.jquery.com/jquery-3.6.4.min.js',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
            'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'
        ];

        let loadedScripts = 0;

        function checkAllLoaded() {
            loadedScripts++;
            if (loadedScripts === scripts.length && document.readyState === 'complete') {
                hideLoadingOverlay();
            }
        }

        // Monitor script loading
        scripts.forEach(src => {
            const script = document.createElement('script');
            script.src = src;
            script.async = true;
            script.onload = checkAllLoaded;
            script.onerror = checkAllLoaded; // Handle errors to prevent hanging
            document.head.appendChild(script);
        });

        // Also check for complete page load
        window.addEventListener('load', function() {
            if (loadedScripts === scripts.length) {
                hideLoadingOverlay();
            }
        });
    });
</script>






{{-- barnettuitionandexamcentre.co.uk start --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'barnettuitionandexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#008000'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#008000'; // Set text color for each element
                });
            }
        }
    });
</script>


<!-- greenwichtuitionandexamcentre.co.uk --><script>
    document.addEventListener('DOMContentLoaded', () => {
        const domain = 'greenwichtuitionandexamcentre.co.uk';
        let found = false;

        // Check referrer or current URL for domain
        try {
            const ref = document.referrer || window.location.href;
            const hostname = new URL(ref).hostname;
            if (hostname.includes(domain)) {
                document.getElementById('current_domain').value = hostname;
                found = true;
            }
        } catch (e) {
            console.log('Domain detection failed:', e);
        }

        // Apply style changes if domain is found
        if (found) {
            const headers = document.querySelectorAll(
                '.mainHeadning'); // Select all elements with mainHeadning class
            const sessions = document.querySelectorAll(
                '.sessionFAS'); // Select all elements with sessionFAS class

            if (headers.length > 0) {
                headers.forEach(header => {
                    header.style.backgroundColor = '#86C4DF'; // Set background color for each element
                });
            }

            if (sessions.length > 0) {
                sessions.forEach(session => {
                    session.style.color = '#86C4DF'; // Set text color for each element
                });
            }
        }
    });
</script>
