@extends('layouts.app')

@section('content')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-selection--multiple {
            min-height: 38px !important;
            border: 1px solid #b0e0e6 !important;
            background-color: #f0f8ff !important;
        }

        .select2-selection__clear {
            margin-right: 10px !important;
        }

        .select2-results__option {
            font-size: 10px !important;
            padding: 6px 10px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            /* background-color: #e4e4e4; */
            border: 1px solid #aaa;
            border-radius: 4px;

            white-space: normal;
            font-size: 9px;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: #615d60;
            color: black;
        }
    </style>

    <div class="container-fluid" style="padding: 20px;">

        <div
            style="max-width: 900px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
            <div
                style="padding: 15px 20px; background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2; display: flex; align-items: center;">
                <i class="fas fa-user-graduate fa-2x"
                    style="color: #fff; margin-right: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"></i>
                <h3 style="margin: 0; color: white; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Edit Exam
                    Registration</h3>
            </div>


            <!-- Missing Fields Notification -->
            <div id="missingFieldsAlert" class="alert alert-warning"
                style="display: none; background: #fff3cd; color: #856404; border-left: 4px solid #ffeeba; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-exclamation-triangle"
                        style="font-size: 1.5rem; margin-right: 15px; color: #ffc107;"></i>
                    <div>
                        <h4 style="margin: 0 0 10px 0; font-weight: 600;">Please complete the following fields:</h4>
                        <ul id="missingFieldsList" style="margin: 0; padding-left: 20px; columns: 2; column-gap: 30px;">
                        </ul>
                    </div>
                </div>
                <button onclick="document.getElementById('missingFieldsAlert').style.display='none'"
                    style="position: absolute; top: 10px; right: 10px; background: none; border: none; color: #856404; cursor: pointer;">×</button>
            </div>
            <div style="padding: 25px;">
                @if (session('success'))
                    <div
                        style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
                        <span style="flex-grow: 1;">{{ session('success') }}</span>
                        <button onclick="this.parentElement.style.display='none'"
                            style="background: none; border: none; color: #155724; cursor: pointer;">×</button>
                    </div>
                @endif
                @if (session('error'))
                    <div
                        style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <i class="fas fa-exclamation-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <span style="flex-grow: 1;">{{ session('error') }}</span>
                        <button onclick="this.parentElement.style.display='none'"
                            style="background: none; border: none; color: #721c24; cursor: pointer;">×</button>
                    </div>
                @endif
                <div id="missingFieldsAlert" class="alert alert-warning"
                    style="display: none; background: #fff3cd; color: #856404; border-left: 4px solid #ffeeba; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-exclamation-triangle"
                            style="font-size: 1.5rem; margin-right: 15px; color: #ffc107;"></i>
                        <div>
                            <h4 style="margin: 0 0 10px 0; font-weight: 600;">Please complete the following fields:</h4>
                            <ul id="missingFieldsList" style="margin: 0; padding-left: 20px; columns: 2; column-gap: 30px;">
                            </ul>
                        </div>
                    </div>
                    <button onclick="document.getElementById('missingFieldsAlert').style.display='none'"
                        style="position: absolute; top: 10px; right: 10px; background: none; border: none; color: #856404; cursor: pointer;">×</button>
                </div>
                <form method="POST" action="{{ url('approve-proceed/' . $request->id) }}" enctype="multipart/form-data"
                    id="examForm">
                    {{-- {{dd($request)}} --}}
                    <input type="hidden" name="exam_session" value="{{ @$request->exam_session }}" id="">
                    @csrf

                    <input type="hidden" name="current_domain" id="current_domain" value="{{ @$request->current_domain }}">
                    <!-- General Information -->
                    <div class="row">
                        <div class="col-md-3" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-user" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Student ID</label>
                            </div>
                            <input readonly type="text" class="form-control" id="student_id" name="student_id"
                                value="{{ @$nextId }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-3" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-user" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Surname</label>
                            </div>
                            <input type="text" class="form-control" id="surname" name="surname"
                                value="{{ $request->surname }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-3" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-user" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">First Name</label>
                            </div>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ $request->first_name }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-3" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-birthday-cake"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Date of Birth</label>
                            </div>
                            <input type="date" class="form-control" id="dob" name="dob"
                                value="{{ $request->dob }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Gender -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-venus-mars"
                                style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Gender</label>
                        </div>
                        <select class="form-select" id="gender" name="gender" required
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="male" {{ $request->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $request->gender == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Address Information -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-home" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Address Line 1</label>
                        </div>
                        <input type="text" class="form-control" id="addressLine1" name="address_line1"
                            value="{{ $request->address_line1 }}"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-home" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Address Line 2</label>
                        </div>
                        <input type="text" class="form-control" id="addressLine2" name="address_line2"
                            value="{{ $request->address_line2 }}"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-map-marker-alt"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Postcode</label>
                            </div>
                            <input type="text" class="form-control" id="postcode" name="postcode"
                                value="{{ $request->postcode }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-badge"
                                    style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">UCI Number</label>
                            </div>
                            <input type="text" class="form-control" id="uciNumber" name="uci_number"
                                value="{{ $request->uci_number }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-phone"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Contact Number</label>
                            </div>
                            <input type="tel" class="form-control" id="contactNumber" name="contact_number"
                                value="{{ $request->contact_number }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-envelope"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Email Address</label>
                            </div>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ $request->email }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Emergency Contact and ULN -->
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-phone-square"
                                    style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Emergency Contact Number</label>
                            </div>
                            <input type="tel" class="form-control" id="emergencyContact" name="emergency_contact"
                                value="{{ $request->emergency_contact }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-card-alt"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">ULN</label>
                            </div>
                            <input type="text" class="form-control" id="uln" name="uln"
                                value="{{ $request->uln }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Medical Condition -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-notes-medical"
                                style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Medical Condition (if any)</label>
                        </div>
                        <textarea class="form-control" id="medicalCondition" name="medical_condition"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">{{ $request->medical_condition }}</textarea>
                    </div>

                    <!-- Access Arrangement -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-universal-access"
                                style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Access Arrangement</label>
                        </div>
                        <select class="form-select" id="accessArrangement" name="access_arrangement"
                            onchange="toggleExplanation()"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="1" {{ $request->access_arrangement ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$request->access_arrangement ? 'selected' : '' }}>No</option>
                        </select>
                        <div id="explanationArea"
                            style="display: {{ $request->access_arrangement ? 'block' : 'none' }}; margin-top: 15px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-comment"
                                    style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Please Specify Access Arrangement</label>
                            </div>
                            <textarea class="form-control" id="explanation" name="access_arrangement_explanation"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">{{ $request->access_arrangement_explanation }}</textarea>
                        </div>
                    </div>

                    <!-- Practical Endorsement -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-certificate"
                                style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Practical Endorsement</label>
                        </div>
                        <select class="form-select" id="practicalEndorsement" name="practical_endorsement"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="LondonExamCentre" {{ $request->practical_endorsement == 'LondonExamCentre' ? 'selected' : '' }}>
                                London Exam Centre’s</option>
                            <option value="carry_forward"
                                {{ $request->practical_endorsement == 'carry_forward' ? 'selected' : '' }}>Carry Forward
                            </option>
                            <option value="not_required"
                                {{ $request->practical_endorsement == 'not_required' ? 'selected' : '' }}>Not Required
                            </option>
                        </select>
                    </div>

                    <!-- Course Work -->
                    <div style="margin-bottom: 25px;">
                        {{-- <div style="display: flex; align-items: center; margin-bottom: 8px;">
                        <i class="fas fa-book" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                        <label style="font-weight: 600; color: #2c3e50;">Course Work</label>
                    </div>
                    <select class="form-select" id="courseWork" name="course_work" onchange="toggleExplanationthree()" style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        <option value="LondonExamCentre" {{ $request->course_work == 'LondonExamCentre' ? 'selected' : '' }}>London Exam Centre’s</option>
                        <option value="carry_forward" {{ $request->course_work == 'carry_forward' ? 'selected' : '' }}>Carry Forward</option>
                        <option value="not_required" {{ $request->course_work == 'not_required' ? 'selected' : '' }}>Not Required</option>
                    </select> --}}
                        {{-- <div id="courseWorkExplanationArea" style="display: {{ $request->course_work == 'carry_forward' ? 'block' : 'none' }}; margin-top: 15px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-id-badge" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">UCI Number</label>
                        </div>
                        <input type="text" class="form-control" name="course_work_uci" id="courseWorkExplanation" value="{{ $request->course_work_uci }}" style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                    </div> --}}
                    </div>


                    <!-- Predicted Grades & UCAS Reference -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-university" style="color: #8e44ad; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Do you need predicted grades and a UCAS reference for university applications?</label>
                        </div>
                        <select class="form-select" name="need_predicted_grades"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="" {{ old('need_predicted_grades', $request->need_predicted_grades ?? '') == '' ? 'selected' : '' }} disabled>Select an option</option>
                            <option value="Yes" {{ old('need_predicted_grades', $request->need_predicted_grades ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ old('need_predicted_grades', $request->need_predicted_grades ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Mock Exams -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-pencil-alt" style="color: #16a085; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Do you need to take mock exams?</label>
                        </div>
                        <select class="form-select" name="need_mock_exams"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="" {{ old('need_mock_exams', $request->need_mock_exams ?? '') == '' ? 'selected' : '' }} disabled>Select an option</option>
                            <option value="Yes" {{ old('need_mock_exams', $request->need_mock_exams ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ old('need_mock_exams', $request->need_mock_exams ?? '') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <!-- Exam Details -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-table" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Exam Details</label>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                            <thead>
                                <tr style="background: #f0f8ff;">
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 13%;">
                                        Exam Board</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">
                                        Course Type</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 15%;">
                                        Subject Name</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 15%;">
                                        Exams</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 13%;">
                                        Tier</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 13%;">
                                        NEA</th>
                                    <th
                                        style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 16%;">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $examBoards = is_string($request->exam_board)
                                        ? json_decode($request->exam_board, true)
                                        : $request->exam_board ?? [];
                                    $courseTypes = is_string($request->course_type)
                                        ? json_decode($request->course_type, true)
                                        : $request->course_type ?? [];
                                    $subjectCodes = is_string($request->subject_code)
                                        ? json_decode($request->subject_code, true)
                                        : $request->subject_code ?? [];
                                    $optionCodes = is_string($request->option_code)
                                        ? json_decode($request->option_code, true)
                                        : $request->option_code ?? [];
                                    $neas = is_string($request->nea)
                                        ? json_decode($request->nea, true)
                                        : $request->nea ?? [];
                                    $exams = is_string($request->exams)
                                        ? json_decode($request->exams, true)
                                        : $request->exams ?? array_fill(0, count($examBoards), []);
                                @endphp

                                @foreach ($examBoards as $index => $examBoard)
                                    <tr class="{{ $index == 0 ? 'mainsec' : '' }}"
                                        style="background: #ffffff; border: 1px solid #b0e0e6;">
                                        <td>
                                            <select class="form-select exam-board" name="examBoard[]">
                                                <option value="{{ $examBoard }}" selected>{{ $examBoard }}</option>
                                                @foreach ($data['boards'] as $board)
                                                    @if ($board !== $examBoard)
                                                        <option value="{{ $board }}">{{ $board }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select course-type" name="courseType[]"
                                                data-selected="{{ isset($courseTypes[$index]) ? $courseTypes[$index] : '' }}">
                                                <option value="{{ $courseTypes[$index] ?? '' }}" selected>
                                                    {{ $courseTypes[$index] ?? '' }}
                                                </option>

                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select subject-name" name="subjectName[]"
                                                data-selected="{{ $preSelectedSubjects[$index]['name'] }}">
                                                <option value="{{ $preSelectedSubjects[$index]['name'] }}" selected>
                                                    {{ $preSelectedSubjects[$index]['name'] }}</option>
                                            </select>
                                            <input type="hidden" class="subject-code" name="subjectCode[]"
                                                value="{{ $preSelectedSubjects[$index]['code'] }}" readonly>
                                            <input type="hidden" class="option-code" name="optionCode[]"
                                                value="{{ $optionCodes[$index] ?? '' }}">
                                        </td>
                                        <td>
                                            <select class="form-select exams" name="exams[{{ $index }}][]"
                                                multiple>
                                                <!-- Options will be populated dynamically -->
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select tire" name="tire[]">
                                                <option value="N/A"
                                                    {{ $preSelectedTiers[$index] === 'N/A' ? 'selected' : '' }}>N/A
                                                </option>
                                                <option value="Foundation Tier"
                                                    {{ $preSelectedTiers[$index] === 'Foundation' ? 'selected' : '' }}>
                                                    Foundation Tier</option>
                                                <option value="Higher Tier"
                                                    {{ $preSelectedTiers[$index] === 'Higher' ? 'selected' : '' }}>Higher
                                                    Tier</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select nea" name="nea[]">
                                                <option value="N/A" {{ $neas[$index] === 'N/A' ? 'selected' : '' }}>N/A
                                                </option>
                                                <option value="course work"
                                                    {{ $neas[$index] === 'course work' ? 'selected' : '' }}>Course Work
                                                </option>
                                                <option value="language"
                                                    {{ $neas[$index] === 'language' ? 'selected' : '' }}>Speaking Exam
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            @if ($index == 0)
                                                <button class="btn btn-primary add-row-button" type="button"
                                                    style="width: 100%; font-size: 11px; background-color: #F87A14; border: none; padding: 8px; color: white;">
                                                    <i class="fas fa-plus" style="margin-right: 5px;"></i>Add
                                                </button>
                                            @else
                                                <button class="btn btn-danger delete-row-button" type="button"
                                                    style="width: 100%; font-size: 11px; background-color: #e74c3c; border: none; padding: 8px; color: white;">
                                                    <i class="fas fa-trash" style="margin-right: 5px;"></i>Delete
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="appendSec" style="margin-top: 15px;"></div>
                    </div>

                    <!-- File Uploads -->
                    {{-- <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-camera" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Photo</label>
                        </div>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        <small>Current: <a href="{{ asset($request->photo_path) }}" target="_blank">View
                                Photo</a></small>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-passport" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">ID Document</label>
                        </div>
                        <input type="file" class="form-control" id="idDocument" name="id_document"
                            accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        <small>Current: <a href="{{ asset($request->id_document_path) }}" target="_blank">View
                                ID</a></small>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-file-alt" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Supporting Documents</label>
                        </div>
                        <input type="file" class="form-control" id="supportingDocuments" name="supporting_documents"
                            multiple accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        <small>Current: <a href="{{ asset($request->supporting_documents_path) }}" target="_blank">View
                                Supporting Docs</a></small>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-folder-plus"
                                style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Additional Documents</label>
                        </div>
                        <div id="additionalDocsContainer">
                            @php
                                $additionalDocs = $request->additional_docs_paths;
                            @endphp
                            @foreach ($additionalDocs as $index => $doc)
                                <div class="input-group mb-2" style="display: flex; align-items: center;">
                                    <input type="file" class="form-control" name="additional_docs[]"
                                        accept=".pdf, .jpg, .jpeg, .png"
                                        style="flex-grow: 1; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                                    <small style="margin-left: 10px;">Current: <a href="{{ asset($doc) }}"
                                            target="_blank">View Doc {{ $index + 1 }}</a></small>
                                    <button class="btn btn-danger remove-doc" type="button"
                                        style="margin-left: 10px; padding: 12px 15px; font-size: 12px; height: 44px; border: none;">
                                        <i class="fas fa-trash" style="margin-right: 5px;"></i>Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button id="addDoc" class="btn btn-primary" type="button"
                            style="background-color: #F87A14; border: none; padding: 12px 15px; font-size: 12px; margin-top: 10px; color: white;">
                            <i class="fas fa-plus" style="margin-right: 5px;"></i>Add Additional Document
                        </button>
                    </div> --}}

                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-camera" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Photo</label>
                        </div>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        @if (isset($request->photo_path) && !empty($request->photo_path))
                            @php
                                $encoded = base64_encode($request->photo_path);
                            @endphp
                            <small>Current: <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                    target="_blank">View Photo</a></small>
                        @endif
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-passport" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">ID Document</label>
                        </div>
                        <input type="file" class="form-control" id="idDocument" name="id_document"
                            accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        @if (isset($request->id_document_path) && !empty($request->id_document_path))
                            @php
                                $encoded = base64_encode($request->id_document_path);
                            @endphp
                            <small>Current: <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                    target="_blank">View ID</a></small>
                        @endif
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-file-alt" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Supporting Documents</label>
                        </div>
                        <input type="file" class="form-control" id="supportingDocuments" name="supporting_documents"
                            multiple accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        @if (isset($request->supporting_documents_path) && !empty($request->supporting_documents_path))
                            @php
                                $encoded = base64_encode($request->supporting_documents_path);
                            @endphp
                            <small>Current: <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                    target="_blank">View Supporting Docs</a></small>
                        @endif
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-folder-plus"
                                style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Additional Documents</label>
                        </div>
                        <div id="additionalDocsContainer">
                            @php
                                $additionalDocs = isset($request->additional_docs_paths)
                                    ? (is_array($request->additional_docs_paths)
                                        ? $request->additional_docs_paths
                                        : json_decode($request->additional_docs_paths ?? '[]', true) ?? [])
                                    : [];
                            @endphp
                            @foreach ($additionalDocs as $index => $doc)
                                @if (!empty($doc))
                                    <div class="input-group mb-2" style="display: flex; align-items: center;">
                                        <input type="file" class="form-control" name="additional_docs[]"
                                            accept=".pdf, .jpg, .jpeg, .png"
                                            style="flex-grow: 1; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                                        @php
                                            $encoded = base64_encode($doc);
                                        @endphp
                                        <small style="margin-left: 10px;">Current: <a
                                                href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                                target="_blank">View Doc {{ $index + 1 }}</a></small>
                                        <button class="btn btn-danger remove-doc" type="button"
                                            style="margin-left: 10px; padding: 12px 15px; font-size: 12px; height: 44px; border: none;">
                                            <i class="fas fa-trash" style="margin-right: 5px;"></i>Remove
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <button id="addDoc" class="btn btn-primary" type="button"
                            style="background-color: #F87A14; border: none; padding: 12px 15px; font-size: 12px; margin-top: 10px; color: white;">
                            <i class="fas fa-plus" style="margin-right: 5px;"></i>Add Additional Document
                        </button>
                    </div>
                    <div class="row">
                        <!-- Form Filling Date -->
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-calendar-alt"
                                    style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Form Filling Date</label>
                            </div>
                            <input type="date" class="form-control" id="formFillDate" name="form_fill_date"
                                value="{{ $request->form_filling_date }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>

                        <!-- Signature -->
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <input type="hidden" name="sign" value="{{ $request->signature }}">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-signature"
                                    style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Signature</label>
                            </div>
                            @if ($request->signature && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $request->signature))
                                <img src="{{ $request->signature }}" alt="Signature"
                                    style="width: 100%; max-width: 200px; height: auto; object-fit: contain; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            @else
                                <div
                                    style="width: 150%; max-width: 500px; height: 200px; display: flex; align-items: center; justify-content: center; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff; color: #856404; font-weight: 600;">
                                    No signature available
                                </div>
                            @endif
                        </div>
                    </div>


                    @php
                        $currentSession = session('current_session');
                    @endphp

                    <!-- Add before closing </form> -->
                    @if ($currentSession !== 'Autumn 2025')
                        <div class="row" style="margin-top: 25px;">
                            <div class="col-md-6">
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <i class="fas fa-pound-sign"
                                        style="color: #28a745; margin-right: 10px; font-size: 1.2rem;"></i>
                                    <label style="font-weight: 600; color: #2c3e50;">Amount Received <span
                                            style="color: red;">*</span></label>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    name="amount_received"
                                    value="{{ old('amount_received', $request->amount_received ?? '') }}"
                                    style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            </div>
                            <div class="col-md-6">
                                <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                    <i class="fas fa-file-upload"
                                        style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                    <label style="font-weight: 600; color: #2c3e50;">Upload Receipt <span
                                            style="color: red;">*</span></label>
                                </div>
                                <input type="file" class="form-control" name="receipt_upload"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                                @if (!empty($request->receipt_path))
                                    @php $encoded = base64_encode($request->receipt_path); @endphp
                                    <small>Current: <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                            target="_blank">View Receipt</a></small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Referral -->
                   <div class="col-md-6 mt-3" style="margin-bottom: 25px;">
    <div style="display: flex; align-items: center; margin-bottom: 8px;">
        <i class="fas fa-share-alt" style="color: #9b59b6; margin-right: 10px; font-size: 1.2rem;"></i>
        <label style="font-weight: 600; color: #2c3e50;">Referral</label>
    </div>
    <select class="form-select" id="referral" name="referral"
        style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
        <option value="">-- Select Referral --</option>
        @foreach ($referrals as $referral)
            <option value="{{ $referral->name }}"
                {{ old('referral', $request->referral ?? '') == $referral->name ? 'selected' : '' }}>
                {{ $referral->name }}
            </option>
        @endforeach
    </select>
</div>

                    <!-- Submit Button -->
                    <div style="text-align: right;">
                        <button type="submit" class="btn btn-success mt-5"
                            style="background: #F87A14; border: none; color: white; padding: 12px 25px; font-weight: 600; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-save" style="margin-right: 8px; color: #fff;"></i>Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/edit-request-deduplicate.js') }}"></script>


    <script>
        // Pass PHP data to JavaScript
        const courseTypesByBoard = @json($data['courseTypesByBoard']);
        const subjectsByBoardAndCourse = @json($data['subjectsByBoardAndCourse']);
        const existingExams = @json($exams); // Pre-existing exams data

        function toggleExplanation() {
            const select = document.getElementById('accessArrangement');
            const explanationArea = document.getElementById('explanationArea');
            explanationArea.style.display = select.value == '1' ? 'block' : 'none';
        }

        function toggleExplanationthree() {
            const select = document.getElementById('courseWork');
            const explanationArea = document.getElementById('courseWorkExplanationArea');
            explanationArea.style.display = select.value == 'carry_forward' ? 'block' : 'none';
        }

        function updateCourseTypes(row) {
            const examBoard = row.querySelector('.exam-board').value;
            const courseTypeSelect = row.querySelector('.course-type');
            const subjectNameSelect = row.querySelector('.subject-name');
            const currentCourseType = courseTypeSelect.dataset.selected || '';

            courseTypeSelect.innerHTML = '<option value="">Select Course Type</option>';
            subjectNameSelect.innerHTML = '<option value="">Select Subject</option>';

            if (courseTypesByBoard[examBoard]) {
                courseTypesByBoard[examBoard].forEach(courseType => {
                    const option = document.createElement('option');
                    option.value = courseType;
                    option.textContent = courseType;
                    option.selected = (courseType === currentCourseType);
                    courseTypeSelect.appendChild(option);
                });
            }
            updateSubjects(row);
        }

        function updateSubjects(row) {
            const examBoard = row.querySelector('.exam-board').value;
            const courseType = row.querySelector('.course-type').value;
            const subjectNameSelect = row.querySelector('.subject-name');
            const currentSubjectName = subjectNameSelect.dataset.selected || '';

            subjectNameSelect.innerHTML = '<option value="">Select Subject</option>';

            if (subjectsByBoardAndCourse[examBoard] && subjectsByBoardAndCourse[examBoard][courseType]) {
                Object.entries(subjectsByBoardAndCourse[examBoard][courseType]).forEach(([subjectName, subjectCode]) => {
                    const option = document.createElement('option');
                    option.value = subjectName; // Use subjectName as the value
                    option.textContent = subjectName;
                    option.selected = (subjectName === currentSubjectName);
                    subjectNameSelect.appendChild(option);
                });
            }
            updateSubjectCode(row);
        }

        function updateSubjectCode(row) {
            const examBoard = row.querySelector('.exam-board').value;
            const courseType = row.querySelector('.course-type').value;
            const subjectName = row.querySelector('.subject-name').value;
            const subjectCodeInput = row.querySelector('.subject-code');

            if (subjectsByBoardAndCourse[examBoard] && subjectsByBoardAndCourse[examBoard][courseType] &&
                subjectsByBoardAndCourse[examBoard][courseType][subjectName]) {
                subjectCodeInput.value = subjectsByBoardAndCourse[examBoard][courseType][subjectName];
            } else {
                subjectCodeInput.value = '';
            }
        }

        function fetchExamsData(row, index) {
            const examBoard = row.querySelector('.exam-board').value;
            const courseType = row.querySelector('.course-type').value;
            const subjectName = row.querySelector('.subject-name').value;
            const subjectCode = row.querySelector('.subject-code').value;
            const optionCodeInput = row.querySelector('.option-code');
            const optionCode = optionCodeInput ? optionCodeInput.value : '';
            const examsSelect = row.querySelector('.exams');

            const url = '{{ url('/get-timetable-subjs') }}' +
                `?examBoard=${encodeURIComponent(examBoard)}` +
                `&courseType=${encodeURIComponent(courseType)}` +
                `&subjectName=${encodeURIComponent(subjectName)}` +
                `&subjectCode=${encodeURIComponent(subjectCode)}` +
                `&optionCode=${encodeURIComponent(optionCode)}`;

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        toastr.error(data.error);
                        examsSelect.innerHTML = '<option value="">Error: No active session</option>';
                    } else {
                        if ($(examsSelect).hasClass('select2-hidden-accessible')) {
                            $(examsSelect).select2('destroy');
                        }

                        examsSelect.innerHTML = '';

                        if (data.exams && Array.isArray(data.exams)) {
                            data.exams.forEach(exam => {
                                const option = document.createElement('option');
                                option.value = exam;
                                option.text = exam;
                                option.selected = existingExams[index] && existingExams[index].includes(exam);
                                examsSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.text = 'No exams available';
                            examsSelect.appendChild(option);
                        }

                        $(examsSelect).select2({
                            placeholder: "Select Exams",
                            width: '100%',
                            minimumResultsForSearch: 1,
                            tags: false,
                            allowClear: true,
                            closeOnSelect: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching exams:', error);
                    examsSelect.innerHTML = '<option value="">Error loading exams</option>';
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('tbody tr').forEach((row, index) => {
                const courseTypeSelect = row.querySelector('.course-type');
                const subjectNameSelect = row.querySelector('.subject-name');
                courseTypeSelect.dataset.selected = courseTypeSelect.value;
                subjectNameSelect.dataset.selected = subjectNameSelect.value;

                updateCourseTypes(row);
                fetchExamsData(row, index);

                row.querySelector('.exam-board').addEventListener('change', () => {
                    updateCourseTypes(row);
                    fetchExamsData(row, index);
                });
                row.querySelector('.course-type').addEventListener('change', () => {
                    updateSubjects(row);
                    fetchExamsData(row, index);
                });
                row.querySelector('.subject-name').addEventListener('change', () => {
                    updateSubjectCode(row);
                    fetchExamsData(row, index);
                });
            });

            document.querySelector('.add-row-button').addEventListener('click', function() {
                const tbody = document.querySelector('tbody');
                const rowCount = tbody.querySelectorAll('tr').length;
                const newRow = document.createElement('tr');
                newRow.style.background = '#ffffff';
                newRow.style.border = '1px solid #b0e0e6';
                newRow.innerHTML = `
                <td><select class="form-select exam-board" name="examBoard[]">${document.querySelector('.exam-board').innerHTML}</select></td>
                <td><select class="form-select course-type" name="courseType[]"><option value="">Select Course Type</option></select></td>
                <td>
                    <select class="form-select subject-name" name="subjectName[]"><option value="">Select Subject</option></select>
                    <input type="hidden" class="subject-code" name="subjectCode[]" value="">
                </td>
                <td><select class="form-select exams" name="exams[${rowCount}][]" multiple></select></td>
                <td><select class="form-select tire" name="tire[]"><option value="N/A">N/A</option><option value="Foundation Tier">Foundation Tier</option><option value="Higher Tier">Higher Tier</option></select></td>
                <td><select class="form-select nea" name="nea[]"><option value="N/A">N/A</option><option value="course work">Course Work</option><option value="language">Speaking Exam</option></select></td>
                <td><button class="btn btn-danger delete-row-button" type="button" style="width: 100%; font-size: 11px; background-color: #e74c3c; border: none; padding: 8px; color: white;"><i class="fas fa-trash" style="margin-right: 5px;"></i>Delete</button></td>
            `;
                tbody.appendChild(newRow);

                updateCourseTypes(newRow);
                fetchExamsData(newRow, rowCount);

                newRow.querySelector('.exam-board').addEventListener('change', () => {
                    updateCourseTypes(newRow);
                    fetchExamsData(newRow, rowCount);
                });
                newRow.querySelector('.course-type').addEventListener('change', () => {
                    updateSubjects(newRow);
                    fetchExamsData(newRow, rowCount);
                });
                newRow.querySelector('.subject-name').addEventListener('change', () => {
                    updateSubjectCode(newRow);
                    fetchExamsData(newRow, rowCount);
                });

                newRow.querySelector('.delete-row-button').addEventListener('click', function() {
                    newRow.remove();
                });
            });

            document.querySelectorAll('.delete-row-button').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('tr').remove();
                });
            });

            const addDocButton = document.getElementById('addDoc');
            const container = document.getElementById('additionalDocsContainer');

            if (addDocButton && container) {
                addDocButton.addEventListener('click', function() {
                    const newInputGroup = document.createElement('div');
                    newInputGroup.className = 'input-group mb-2';
                    newInputGroup.style.display = 'flex';
                    newInputGroup.style.alignItems = 'center';
                    newInputGroup.innerHTML = `
                    <input type="file" class="form-control" name="additional_docs[]" accept=".pdf, .jpg, .jpeg, .png" style="flex-grow: 1; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                    <button class="btn btn-danger remove-doc" type="button" style="margin-left: 10px; padding: 12px 15px; font-size: 12px; height: 44px; border: none;">
                        <i class="fas fa-trash" style="margin-right: 5px;"></i>Remove
                    </button>
                `;
                    container.appendChild(newInputGroup);

                    newInputGroup.querySelector('.remove-doc').addEventListener('click', function() {
                        newInputGroup.remove();
                    });
                });

                container.querySelectorAll('.remove-doc').forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('.input-group').remove();
                    });
                });
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('examForm');
            const missingFieldsAlert = document.getElementById('missingFieldsAlert');
            const missingFieldsList = document.getElementById('missingFieldsList');

            // Function to validate select2 fields and input fields, apply red border if empty
            function validateFields() {
                const examRows = document.querySelectorAll('tbody tr');
                let missingFields = [];

                let amountReceived = document.querySelector('input[name="amount_received"]');
                let receiptUpload = document.querySelector('input[name="receipt_upload"]');

                // Reset borders
                if (amountReceived) amountReceived.style.borderColor = '';
                if (receiptUpload) receiptUpload.style.borderColor = '';

                // Validate only if session is not Autumn 2025
                @if ($currentSession !== 'Autumn 2025')
                    if (!amountReceived?.value) {
                        amountReceived.style.borderColor = 'red';
                        missingFields.push('Amount Received');
                    }
                    if (!receiptUpload?.files?.length) {
                        receiptUpload.style.borderColor = 'red';
                        missingFields.push('Upload Receipt');
                    }
                @endif

                examRows.forEach((row, index) => {
                    const examBoard = row.querySelector('.exam-board');
                    const courseType = row.querySelector('.course-type');
                    const subjectName = row.querySelector('.subject-name');
                    const subjectCode = row.querySelector('.subject-code');
                    const exams = row.querySelector('.exams');

                    // Reset borders
                    examBoard.style.borderColor = '';
                    courseType.style.borderColor = '';
                    subjectName.style.borderColor = '';
                    subjectCode.style.borderColor = '';
                    const examsSelect2Container = exams.parentElement.querySelector(
                        '.select2-container .select2-selection');
                    if (examsSelect2Container) {
                        examsSelect2Container.style.borderColor = '';
                    }

                    // Check if fields are empty
                    if (!examBoard.value) {
                        examBoard.style.borderColor = 'red';
                        missingFields.push(`Row ${index + 1}: Exam Board`);
                    }
                    if (!courseType.value) {
                        courseType.style.borderColor = 'red';
                        missingFields.push(`Row ${index + 1}: Course Type`);
                    }
                    if (!subjectName.value) {
                        subjectName.style.borderColor = 'red';
                        missingFields.push(`Row ${index + 1}: Subject Name`);
                    }
                    if (!subjectCode.value) {
                        subjectCode.style.borderColor = 'red';
                        missingFields.push(`Row ${index + 1}: Subject Code`);
                    }
                    if (!$(exams).val() || $(exams).val().length === 0) {
                        if (examsSelect2Container) {
                            examsSelect2Container.style.border = '2px solid red !important';
                        }
                        missingFields.push(`Row ${index + 1}: Exams`);
                    }
                });

                return missingFields;
            }

            // Form submission handler
            form.addEventListener('submit', function(event) {
                // Referral validation — must select before approving
                const referralSelect = document.getElementById('referral');
                if (!referralSelect.value || referralSelect.value === '') {
                    event.preventDefault();
                    toastr.error('Please select a referral before approving the form.', 'Referral Required', {
                        timeOut: 4000,
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                    });
                    referralSelect.style.border = '2px solid #dc3545';
                    referralSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
                referralSelect.style.border = '';

                const missingFields = validateFields();

                if (missingFields.length > 0) {
                    event.preventDefault(); // Prevent form submission

                    // Show the alert and populate missing fields
                    missingFieldsAlert.style.display = 'block';
                    missingFieldsList.innerHTML = '';
                    missingFields.forEach(field => {
                        const li = document.createElement('li');
                        li.textContent = field;
                        missingFieldsList.appendChild(li);
                    });

                    // Scroll to the alert
                    missingFieldsAlert.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
    <style>
        #missingFieldsAlert {
            position: relative;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #missingFieldsList li {
            transition: all 0.3s ease;
        }

        #missingFieldsList li:hover {
            color: #dc3545;
            transform: translateX(5px);
        }
    </style>
@endsection
