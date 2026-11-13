@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 20px;">
        <div style="max-width: 900px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
            <div style="padding: 15px 20px; background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2; display: flex; align-items: center;">
                <i class="fas fa-user-graduate fa-2x" style="color: #fff; margin-right: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);"></i>
                <h3 style="margin: 0; color: white; font-weight: 600; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Edit Exam Registration</h3>
            </div>

            <div style="padding: 25px;">
                @if (session('success'))
                    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <i class="fas fa-check-circle" style="color: #28a745; margin-right: 10px;"></i>
                        <span style="flex-grow: 1;">{{ session('success') }}</span>
                        <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #155724; cursor: pointer;">×</button>
                    </div>
                @endif
                @if (session('error'))
                    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <i class="fas fa-exclamation-circle" style="color: #dc3545; margin-right: 10px;"></i>
                        <span style="flex-grow: 1;">{{ session('error') }}</span>
                        <button onclick="this.parentElement.style.display='none'" style="background: none; border: none; color: #721c24; cursor: pointer;">×</button>
                    </div>
                @endif

                <form method="POST" action="{{ url('update-student/' . $request->id) }}" enctype="multipart/form-data" id="examForm">
                    @csrf
                    @method('PUT')

                    <!-- General Information -->
                    <div class="row">
                        <div class="col-md-4" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-user" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Surname</label>
                            </div>
                            <input type="text" class="form-control" id="surname" name="surname" value="{{ $request->surname }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-user" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">First Name</label>
                            </div>
                            <input type="text" class="form-control" id="firstName" name="first_name" value="{{ $request->first_name }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-birthday-cake" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Date of Birth</label>
                            </div>
                            <input type="date" class="form-control" id="dob" name="dob" value="{{ $request->dob }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Gender -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-venus-mars" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
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
                        <input type="text" class="form-control" id="addressLine1" name="address_line1" value="{{ $request->address_line1 }}"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-home" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Address Line 2</label>
                        </div>
                        <input type="text" class="form-control" id="addressLine2" name="address_line2" value="{{ $request->address_line2 }}"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-map-marker-alt" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Postcode</label>
                            </div>
                            <input type="text" class="form-control" id="postcode" name="postcode" value="{{ $request->postcode }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-badge" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">UCI Number</label>
                            </div>
                            <input type="text" class="form-control" id="uciNumber" name="uci_number" value="{{ $request->uci_number }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-phone" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Contact Number</label>
                            </div>
                            <input type="tel" class="form-control" id="contactNumber" name="contact_number" value="{{ $request->contact_number }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-envelope" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Email Address</label>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $request->email }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Emergency Contact and ULN -->
                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-phone-square" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Emergency Contact Number</label>
                            </div>
                            <input type="tel" class="form-control" id="emergencyContact" name="emergency_contact" value="{{ $request->emergency_contact }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                        <div class="col-md-6" style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-card-alt" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">ULN</label>
                            </div>
                            <input type="text" class="form-control" id="uln" name="uln" value="{{ $request->uln }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
                    </div>

                    <!-- Medical Condition -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-notes-medical" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Medical Condition (if any)</label>
                        </div>
                        <textarea class="form-control" id="medicalCondition" name="medical_condition" style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">{{ $request->medical_condition }}</textarea>
                    </div>

                    <!-- Access Arrangement -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-universal-access" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Access Arrangement</label>
                        </div>
                        <select class="form-select" id="accessArrangement" name="access_arrangement" onchange="toggleExplanation()"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="1" {{ $request->access_arrangement ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$request->access_arrangement ? 'selected' : '' }}>No</option>
                        </select>
                        <div id="explanationArea" style="display: {{ $request->access_arrangement ? 'block' : 'none' }}; margin-top: 15px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-comment" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">Please Specify Access Arrangement</label>
                            </div>
                            <textarea class="form-control" id="explanation" name="access_arrangement_explanation" style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">{{ $request->access_arrangement_explanation }}</textarea>
                        </div>
                    </div>

                    <!-- Practical Endorsement -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-certificate" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Practical Endorsement</label>
                        </div>
                        <select class="form-select" id="practicalEndorsement" name="practical_endorsement"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="LondonExamCentre" {{ $request->practical_endorsement == 'LondonExamCentre' ? 'selected' : '' }}>London Exam Centre’s</option>
                            <option value="carry_forward" {{ $request->practical_endorsement == 'carry_forward' ? 'selected' : '' }}>Carry Forward</option>
                            <option value="not_required" {{ $request->practical_endorsement == 'not_required' ? 'selected' : '' }}>Not Required</option>
                        </select>
                    </div>

                    <!-- Course Work -->
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-book" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Course Work</label>
                        </div>
                        <select class="form-select" id="courseWork" name="course_work" onchange="toggleExplanationthree()"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                            <option value="LondonExamCentre" {{ $request->course_work == 'LondonExamCentre' ? 'selected' : '' }}>London Exam Centre’s</option>
                            <option value="carry_forward" {{ $request->course_work == 'carry_forward' ? 'selected' : '' }}>Carry Forward</option>
                            <option value="not_required" {{ $request->course_work == 'not_required' ? 'selected' : '' }}>Not Required</option>
                        </select>
                        <div id="courseWorkExplanationArea" style="display: {{ $request->course_work == 'carry_forward' ? 'block' : 'none' }}; margin-top: 15px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-id-badge" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                                <label style="font-weight: 600; color: #2c3e50;">UCI Number</label>
                            </div>
                            <input type="text" class="form-control" name="course_work_uci" id="courseWorkExplanation" value="{{ $request->course_work_uci }}"
                                style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        </div>
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
                                    <th style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">Exam Board</th>
                                    <th style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 15%;">Course Type</th>
                                    <th style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 16%;">Subject Name</th>
                                    <th style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">Tier</th>
                                    <th style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 14%;">NEA</th>
                                    <th style="text-align: center; font-size: 13px; border: 1px solid #b0e0e6; padding: 10px; width: 16%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $examBoards = $request->exam_board;
                                    $courseTypes = $request->course_type;
                                    $subjectNames = $request->subject_name;
                                    $subjectCodes = $request->subject_code;
                                    $tiers = $request->tire;
                                    $neas = $request->nea;
                                @endphp

                                @foreach ($examBoards as $index => $examBoard)
                                    <tr class="{{ $index == 0 ? 'mainsec' : '' }}" style="background: #ffffff; border: 1px solid #b0e0e6;">
                                        <td>
                                            <select class="form-select exam-board" name="examBoard[]">
                                                <option value="{{ $examBoard }}" selected>{{ $examBoard }}</option>
                                                @foreach ($boards as $board)
                                                    @if ($board !== $examBoard)
                                                        <option value="{{ $board }}">{{ $board }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select course-type" name="courseType[]">
                                                <option value="{{ $courseTypes[$index] }}" selected>{{ $courseTypes[$index] }}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select subject-name" name="subjectName[]">
                                                <option value="{{ $subjectNames[$index] }}" selected>{{ $subjectNames[$index] }}</option>
                                            </select>
                                            <input type="hidden" class="subject-code" name="subjectCode[]" value="{{ $subjectCodes[$index] }}">
                                        </td>
                                        <td>
                                            <select class="form-select tire" name="tire[]">
                                                <option value="{{ $tiers[$index] }}" selected>{{ $tiers[$index] }}</option>
                                                <option value="N/A">N/A</option>
                                                <option value="Foundation Tier">Foundation Tier</option>
                                                <option value="Higher Tier">Higher Tier</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select nea" name="nea[]">
                                                <option value="{{ $neas[$index] }}" selected>{{ $neas[$index] }}</option>
                                                <option value="N/A">N/A</option>
                                                <option value="course work">Course Work</option>
                                                <option value="language">Speaking Exam</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if ($index == 0)
                                                <button class="btn btn-primary add-row-button" type="button"
                                                    style="width: 100%; font-size: 11px; background-color: #57afbfd6; border: none; padding: 8px; color: white;">
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
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-camera" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Photo</label>
                        </div>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        <small>Current: <a href="{{ asset($request->photo_path) }}" target="_blank">View Photo</a></small>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-passport" style="color: #e67e22; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">ID Document</label>
                        </div>
                        <input type="file" class="form-control" id="idDocument" name="id_document" accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        <small>Current: <a href="{{ asset($request->id_document_path) }}" target="_blank">View ID</a></small>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-file-alt" style="color: #2ecc71; margin-right: 10px; font-size: 1.2rem;"></i>
                            <label style="font-weight: 600; color: #2c3e50;">Supporting Documents</label>
                        </div>
                        <input type="file" class="form-control" id="supportingDocuments" name="supporting_documents" multiple accept=".pdf, .jpg, .jpeg, .png"
                            style="width: 100%; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                        <small>Current: <a href="{{ asset($request->supporting_documents_path) }}" target="_blank">View Supporting Docs</a></small>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                            <i class="fas fa-folder-plus" style="color: #3498db; margin-right: 10px; font-size: 1.2rem;"></i>
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
                                    <small style="margin-left: 10px;">Current: <a href="{{ asset($doc) }}" target="_blank">View Doc {{ $index + 1 }}</a></small>
                                    <button class="btn btn-danger remove-doc" type="button"
                                        style="margin-left: 10px; padding: 12px 15px; font-size: 12px; height: 44px; border: none;">
                                        <i class="fas fa-trash" style="margin-right: 5px;"></i>Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button id="addDoc" class="btn btn-primary" type="button"
                            style="background-color: #57afbfd6; border: none; padding: 12px 15px; font-size: 12px; margin-top: 10px; color: white;">
                            <i class="fas fa-plus" style="margin-right: 5px;"></i>Add Additional Document
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <div style="text-align: right;">
                        <button type="submit" class="btn btn-success"
                            style="background: #F87A14; border: none; color: white; padding: 12px 25px; font-weight: 600; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-save" style="margin-right: 8px;"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function toggleExplanation() {
            var select = document.getElementById('accessArrangement');
            var explanationArea = document.getElementById('explanationArea');
            explanationArea.style.display = select.value == '1' ? 'block' : 'none';
        }

        function toggleExplanationthree() {
            var select = document.getElementById('courseWork');
            var explanationArea = document.getElementById('courseWorkExplanationArea');
            explanationArea.style.display = select.value == 'carry_forward' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Add row functionality
            document.querySelector('.add-row-button').addEventListener('click', function() {
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

                const addButton = newRow.querySelector('.add-row-button');
                addButton.innerHTML = '<i class="fas fa-trash"></i> Delete';
                addButton.className = 'btn btn-danger delete-row-button';
                addButton.style.backgroundColor = '#e74c3c';
                addButton.onclick = function() {
                    rowContainer.remove();
                };

                const selects = newRow.querySelectorAll('select');
                selects.forEach(select => {
                    select.selectedIndex = 0;
                });

                rowContainer.appendChild(newTable);
                appendSection.appendChild(rowContainer);
            });

            // Delete row functionality
            document.querySelectorAll('.delete-row-button').forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('tr').remove();
                });
            });

            // Additional Documents functionality
            const addButton = document.getElementById('addDoc');
            const container = document.getElementById('additionalDocsContainer');

            addButton.addEventListener('click', function() {
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'input-group mb-2';
                newInputGroup.style.display = 'flex';
                newInputGroup.style.alignItems = 'center';
                newInputGroup.innerHTML = `
                    <input type="file" class="form-control" name="additional_docs[]"
                           accept=".pdf, .jpg, .jpeg, .png"
                           style="flex-grow: 1; padding: 12px; border: 1px solid #b0e0e6; border-radius: 4px; background-color: #f0f8ff;">
                    <button class="btn btn-danger remove-doc" type="button"
                            style="margin-left: 10px; padding: 12px 15px; font-size: 12px; height: 44px; border: none;">
                        <i class="fas fa-trash" style="margin-right: 5px;"></i>Remove
                    </button>
                `;
                container.appendChild(newInputGroup);
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-doc') || e.target.closest('.remove-doc')) {
                    const inputGroup = e.target.closest('.input-group');
                    if (inputGroup) {
                        inputGroup.remove();
                    }
                }
            });
        });
    </script>
@endsection
