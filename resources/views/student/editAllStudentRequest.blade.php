@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div style="max-width: 960px; margin: 0 auto;">

        {{-- Header --}}
        <div style="background: #F87A14; border-bottom: 4px solid #1976D2; border-radius: 10px 10px 0 0;
                    padding: 15px 22px; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; color: #fff; font-weight: 700; font-size: 1.15rem;">
                <i class="fas fa-edit" style="margin-right: 8px;"></i>
                Edit Student Request — {{ $student->first_name }} {{ $student->surname }}
            </h5>
            <a href="{{ route('allStudentRequests') }}"
               style="padding: 6px 14px; background: rgba(255,255,255,0.2); color: #fff;
                      border-radius: 5px; font-size: 0.82rem; text-decoration: none; border: 1px solid rgba(255,255,255,0.5);">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px 18px; border-left: 4px solid #28a745; margin-top: 12px; border-radius: 4px;">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 12px 18px; border-left: 4px solid #dc3545; margin-top: 12px; border-radius: 4px;">
            <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
            Please fix the following errors:
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('allRequests.update', $student->id) }}"
              style="background: #fff; border-radius: 0 0 10px 10px; padding: 28px;
                     box-shadow: 0 0.2rem 2rem rgba(58,59,69,0.15);">
            @csrf
            @method('PUT')

            {{-- ── Section: Personal Info ── --}}
            <div style="margin-bottom: 22px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">
                <h6 style="color: #F87A14; font-weight: 700; margin-bottom: 18px;">
                    <i class="fas fa-user" style="margin-right: 7px;"></i> Personal Information
                </h6>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px;">

                    <div>
                        <label style="font-weight: 600; color: #2c3e50; font-size: 0.85rem; display: block; margin-bottom: 5px;">
                            First Name <span style="color:red;">*</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}"
                               required style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #2c3e50; font-size: 0.85rem; display: block; margin-bottom: 5px;">
                            Surname <span style="color:red;">*</span>
                        </label>
                        <input type="text" name="surname" value="{{ old('surname', $student->surname) }}"
                               required style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #2c3e50; font-size: 0.85rem; display: block; margin-bottom: 5px;">
                            Date of Birth
                        </label>
                        <input type="date" name="dob" value="{{ old('dob', $student->dob) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #2c3e50; font-size: 0.85rem; display: block; margin-bottom: 5px;">
                            Gender
                        </label>
                        <select name="gender" style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                                border-radius:5px; background:#f0f8ff; font-size:0.88rem;">
                            <option value="">-- Select --</option>
                            <option value="male"   {{ old('gender', $student->gender) == 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- ── Section: Address ── --}}
            <div style="margin-bottom: 22px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">
                <h6 style="color: #F87A14; font-weight: 700; margin-bottom: 18px;">
                    <i class="fas fa-home" style="margin-right: 7px;"></i> Address
                </h6>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px;">

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Address Line 1</label>
                        <input type="text" name="address_line1" value="{{ old('address_line1', $student->address_line1) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Address Line 2</label>
                        <input type="text" name="address_line2" value="{{ old('address_line2', $student->address_line2) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Postcode</label>
                        <input type="text" name="postcode" value="{{ old('postcode', $student->postcode) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                </div>
            </div>

            {{-- ── Section: Contact ── --}}
            <div style="margin-bottom: 22px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">
                <h6 style="color: #F87A14; font-weight: 700; margin-bottom: 18px;">
                    <i class="fas fa-phone" style="margin-right: 7px;"></i> Contact Information
                </h6>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px;">

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Contact Number</label>
                        <input type="tel" name="contact_number" value="{{ old('contact_number', $student->contact_number) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Emergency Contact</label>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $student->emergency_contact) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">ULN</label>
                        <input type="text" name="uln" value="{{ old('uln', $student->uln) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">UCI Number</label>
                        <input type="text" name="uci_number" value="{{ old('uci_number', $student->uci_number) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Exam Session</label>
                        <input type="text" name="exam_session" value="{{ old('exam_session', $student->exam_session) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                               border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box;">
                    </div>

                </div>
            </div>

            {{-- ── Section: Medical & Arrangements ── --}}
            <div style="margin-bottom: 22px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">
                <h6 style="color: #F87A14; font-weight: 700; margin-bottom: 18px;">
                    <i class="fas fa-notes-medical" style="margin-right: 7px;"></i> Medical & Arrangements
                </h6>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Medical Condition</label>
                    <textarea name="medical_condition" rows="3"
                              style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                              border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box; resize:vertical;">{{ old('medical_condition', $student->medical_condition) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px;">

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Access Arrangement</label>
                        <select name="access_arrangement" id="accessArrangement" onchange="toggleAccessExplanation()"
                                style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                                border-radius:5px; background:#f0f8ff; font-size:0.88rem;">
                            <option value="1" {{ old('access_arrangement', $student->access_arrangement) ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !old('access_arrangement', $student->access_arrangement) ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Practical Endorsement</label>
                        <select name="practical_endorsement"
                                style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                                border-radius:5px; background:#f0f8ff; font-size:0.88rem;">
                            <option value="">-- Select --</option>
                            <option value="LondonExamCentre" {{ old('practical_endorsement', $student->practical_endorsement) == 'LondonExamCentre' ? 'selected' : '' }}>London Exam Centre's</option>
                            <option value="carry_forward"    {{ old('practical_endorsement', $student->practical_endorsement) == 'carry_forward'    ? 'selected' : '' }}>Carry Forward</option>
                            <option value="not_required"     {{ old('practical_endorsement', $student->practical_endorsement) == 'not_required'     ? 'selected' : '' }}>Not Required</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Need Predicted Grades?</label>
                        <select name="need_predicted_grades"
                                style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                                border-radius:5px; background:#f0f8ff; font-size:0.88rem;">
                            <option value="">-- Select --</option>
                            <option value="Yes" {{ old('need_predicted_grades', $student->need_predicted_grades) == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No"  {{ old('need_predicted_grades', $student->need_predicted_grades) == 'No'  ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">Need Mock Exams?</label>
                        <select name="need_mock_exams"
                                style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                                border-radius:5px; background:#f0f8ff; font-size:0.88rem;">
                            <option value="">-- Select --</option>
                            <option value="Yes" {{ old('need_mock_exams', $student->need_mock_exams) == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No"  {{ old('need_mock_exams', $student->need_mock_exams) == 'No'  ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                </div>

                <div id="accessExplanationWrap"
                     style="margin-top: 15px; display: {{ $student->access_arrangement ? 'block' : 'none' }};">
                    <label style="font-weight:600; color:#2c3e50; font-size:0.85rem; display:block; margin-bottom:5px;">
                        Access Arrangement Explanation
                    </label>
                    <textarea name="access_arrangement_explanation" rows="3"
                              style="width:100%; padding:10px 12px; border:1px solid #b0e0e6;
                              border-radius:5px; background:#f0f8ff; font-size:0.88rem; box-sizing:border-box; resize:vertical;">{{ old('access_arrangement_explanation', $student->access_arrangement_explanation) }}</textarea>
                </div>
            </div>

            {{-- ── Section: Exam Subjects ── --}}
            <div style="margin-bottom: 22px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;">
                <h6 style="color: #F87A14; font-weight: 700; margin-bottom: 18px;">
                    <i class="fas fa-book" style="margin-right: 7px;"></i> Exam Subjects
                </h6>

                @php
                    $examBoards  = is_array($student->exam_board)  ? $student->exam_board  : (json_decode($student->exam_board  ?? '[]', true) ?? []);
                    $courseTypes = is_array($student->course_type) ? $student->course_type : (json_decode($student->course_type ?? '[]', true) ?? []);
                    $subjectNames= is_array($student->subject_name)? $student->subject_name: (json_decode($student->subject_name?? '[]', true) ?? []);
                    $subjectCodes= is_array($student->subject_code)? $student->subject_code: (json_decode($student->subject_code?? '[]', true) ?? []);
                    $tires       = is_array($student->tire)        ? $student->tire        : (json_decode($student->tire        ?? '[]', true) ?? []);
                    $neas        = is_array($student->nea)         ? $student->nea         : (json_decode($student->nea         ?? '[]', true) ?? []);
                    $rowCount    = max(count($examBoards), 1);
                @endphp

                <div id="subjectRows">
                @for ($i = 0; $i < $rowCount; $i++)
                <div class="subject-row" style="background:#f8f9fa; border:1px solid #dee2e6; border-radius:7px;
                                                 padding:15px; margin-bottom:12px; position:relative;">
                    <span style="position:absolute; top:8px; right:12px; font-size:0.75rem; color:#aaa; font-weight:600;">
                        Subject #{{ $i + 1 }}
                    </span>
                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:14px; margin-top: 6px;">

                        <div>
                            <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Exam Board</label>
                            <input type="text" name="examBoard[]" value="{{ $examBoards[$i] ?? '' }}"
                                   style="width:100%; padding:8px 10px; border:1px solid #b0e0e6;
                                   border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
                        </div>

                        <div>
                            <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Course Type</label>
                            <input type="text" name="courseType[]" value="{{ $courseTypes[$i] ?? '' }}"
                                   style="width:100%; padding:8px 10px; border:1px solid #b0e0e6;
                                   border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
                        </div>

                        <div>
                            <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Subject Name</label>
                            <input type="text" name="subjectName[]" value="{{ $subjectNames[$i] ?? '' }}"
                                   style="width:100%; padding:8px 10px; border:1px solid #b0e0e6;
                                   border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
                        </div>

                        <div>
                            <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Subject Code</label>
                            <input type="text" name="subjectCode[]" value="{{ $subjectCodes[$i] ?? '' }}"
                                   style="width:100%; padding:8px 10px; border:1px solid #b0e0e6;
                                   border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
                        </div>

                        <div>
                            <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Tier</label>
                            <select name="tire[]"
                                    style="width:100%; padding:8px 10px; border:1px solid #b0e0e6;
                                    border-radius:4px; background:#fff; font-size:0.85rem;">
                                <option value="">-- Select --</option>
                                <option value="Higher"     {{ ($tires[$i] ?? '') == 'Higher'     ? 'selected' : '' }}>Higher</option>
                                <option value="Foundation" {{ ($tires[$i] ?? '') == 'Foundation' ? 'selected' : '' }}>Foundation</option>
                                <option value="N/A"        {{ ($tires[$i] ?? '') == 'N/A'        ? 'selected' : '' }}>N/A</option>
                            </select>
                        </div>

                        <div>
                            <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">NEA</label>
                            <select name="nea[]"
                                    style="width:100%; padding:8px 10px; border:1px solid #b0e0e6;
                                    border-radius:4px; background:#fff; font-size:0.85rem;">
                                <option value="">-- Select --</option>
                                <option value="LondonExamCentre" {{ ($neas[$i] ?? '') == 'LondonExamCentre' ? 'selected' : '' }}>London Exam Centre's</option>
                                <option value="carry_forward"    {{ ($neas[$i] ?? '') == 'carry_forward'    ? 'selected' : '' }}>Carry Forward</option>
                                <option value="not_required"     {{ ($neas[$i] ?? '') == 'not_required'     ? 'selected' : '' }}>Not Required</option>
                            </select>
                        </div>

                    </div>

                    @if ($i > 0)
                    <button type="button" onclick="removeRow(this)"
                            style="margin-top:10px; padding:5px 12px; background:#dc3545; color:#fff;
                                   border:none; border-radius:4px; font-size:0.8rem; cursor:pointer;">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                    @endif
                </div>
                @endfor
                </div>

                <button type="button" onclick="addSubjectRow()"
                        style="margin-top: 8px; padding: 8px 18px; background: #28a745; color: #fff;
                               border: none; border-radius: 5px; font-size: 0.85rem; cursor: pointer;">
                    <i class="fas fa-plus"></i> Add Subject
                </button>
            </div>

            {{-- ── Submit ── --}}
            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 10px;">
                <a href="{{ route('allStudentRequests') }}"
                   style="padding: 10px 24px; background: #6c757d; color: #fff; border-radius: 6px;
                          text-decoration: none; font-size: 0.88rem;">
                    Cancel
                </a>
                <button type="submit"
                        style="padding: 10px 28px; background: #F87A14; color: #fff; border: none;
                               border-radius: 6px; font-size: 0.88rem; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-save" style="margin-right: 6px;"></i> Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function toggleAccessExplanation() {
    var val  = document.getElementById('accessArrangement').value;
    var wrap = document.getElementById('accessExplanationWrap');
    wrap.style.display = (val === '1') ? 'block' : 'none';
}

function addSubjectRow() {
    var container = document.getElementById('subjectRows');
    var rows      = container.querySelectorAll('.subject-row');
    var index     = rows.length + 1;

    var html = `
    <div class="subject-row" style="background:#f8f9fa; border:1px solid #dee2e6; border-radius:7px;
                                     padding:15px; margin-bottom:12px; position:relative;">
        <span style="position:absolute; top:8px; right:12px; font-size:0.75rem; color:#aaa; font-weight:600;">
            Subject #${index}
        </span>
        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:14px; margin-top:6px;">
            <div>
                <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Exam Board</label>
                <input type="text" name="examBoard[]"
                       style="width:100%; padding:8px 10px; border:1px solid #b0e0e6; border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
            </div>
            <div>
                <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Course Type</label>
                <input type="text" name="courseType[]"
                       style="width:100%; padding:8px 10px; border:1px solid #b0e0e6; border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
            </div>
            <div>
                <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Subject Name</label>
                <input type="text" name="subjectName[]"
                       style="width:100%; padding:8px 10px; border:1px solid #b0e0e6; border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
            </div>
            <div>
                <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Subject Code</label>
                <input type="text" name="subjectCode[]"
                       style="width:100%; padding:8px 10px; border:1px solid #b0e0e6; border-radius:4px; background:#fff; font-size:0.85rem; box-sizing:border-box;">
            </div>
            <div>
                <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">Tier</label>
                <select name="tire[]"
                        style="width:100%; padding:8px 10px; border:1px solid #b0e0e6; border-radius:4px; background:#fff; font-size:0.85rem;">
                    <option value="">-- Select --</option>
                    <option value="Higher">Higher</option>
                    <option value="Foundation">Foundation</option>
                    <option value="N/A">N/A</option>
                </select>
            </div>
            <div>
                <label style="font-weight:600; color:#555; font-size:0.82rem; display:block; margin-bottom:4px;">NEA</label>
                <select name="nea[]"
                        style="width:100%; padding:8px 10px; border:1px solid #b0e0e6; border-radius:4px; background:#fff; font-size:0.85rem;">
                    <option value="">-- Select --</option>
                    <option value="LondonExamCentre">London Exam Centre's</option>
                    <option value="carry_forward">Carry Forward</option>
                    <option value="not_required">Not Required</option>
                </select>
            </div>
        </div>
        <button type="button" onclick="removeRow(this)"
                style="margin-top:10px; padding:5px 12px; background:#dc3545; color:#fff;
                       border:none; border-radius:4px; font-size:0.8rem; cursor:pointer;">
            <i class="fas fa-trash-alt"></i> Remove
        </button>
    </div>`;

    container.insertAdjacentHTML('beforeend', html);
    renumberRows();
}

function removeRow(btn) {
    btn.closest('.subject-row').remove();
    renumberRows();
}

function renumberRows() {
    document.querySelectorAll('#subjectRows .subject-row').forEach(function(row, i) {
        var label = row.querySelector('span');
        if (label) label.textContent = 'Subject #' + (i + 1);
    });
}
</script>
@endsection
