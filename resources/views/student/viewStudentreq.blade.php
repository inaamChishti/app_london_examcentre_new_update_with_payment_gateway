@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="card-header position-relative py-3"
                        style="background: #F87A14; border-bottom: 4px solid #1976D2;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h4 mb-0 text-white">
                                <i class="fas fa-user-graduate me-2" style="color: #ffffff;"></i>
                                {{ isset($request->first_name) ? $request->first_name : 'N/A' }}
                                {{ isset($request->surname) ? $request->surname : '' }}
                            </h2>
                            <div
                                class="position-absolute top-0 end-0 px-3 py-1 bg-{{ isset($request->is_approved) && $request->is_approved ? 'success' : 'warning' }} text-white rounded-bl-lg">
                                {{ isset($request->is_approved) && $request->is_approved ? 'Approved' : 'Pending' }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-4 p-4" style="background-color: #f8fafb;">
                                <div class="text-center mb-4">
                                    @if (isset($request->photo_path) && !empty($request->photo_path))
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset($request->photo_path) }}"
                                                class="rounded-circle shadow border border-4 border-white" alt="Profile"
                                                style="width: 150px; height: 150px; object-fit: cover;">
                                            <span class="position-absolute bottom-0 end-0 bg-info p-2 rounded-circle">
                                                <i class="fas fa-check text-white" style="color: #ffffff;"></i>
                                            </span>
                                        </div>
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                            style="width: 150px; height: 150px; margin: 0 auto;">
                                            <i class="fas fa-user-graduate text-muted fa-3x" style="color: #6c757d;"></i>
                                        </div>
                                    @endif
                                    <h4 class="mt-3 mb-0" style="color: #000000;">
                                        {{ isset($request->first_name) ? $request->first_name : 'N/A' }}
                                        {{ isset($request->surname) ? $request->surname : '' }}
                                    </h4>
                                </div>

                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-white py-2 border-bottom">
                                        <h5 class="mb-0" style="color: #000000;">
                                            <i class="fas fa-id-card me-2" style="color: #17a2b8;"></i>Personal Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-calendar-alt"
                                                        style="color: #007bff;"></i></span>
                                                <span><strong>DOB:</strong>
                                                    {{ isset($request->dob) ? $request->dob : 'N/A' }}</span>
                                            </li>
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-venus-mars"
                                                        style="color: #6f42c1;"></i></span>
                                                <span><strong>Gender:</strong>
                                                    {{ isset($request->gender) ? $request->gender : 'N/A' }}</span>
                                            </li>
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-home"
                                                        style="color: #28a745;"></i></span>
                                                <span><strong>Address:</strong>
                                                    {{ isset($request->address_line1) ? $request->address_line1 : 'N/A' }}
                                                    @if (isset($request->address_line2) && !empty($request->address_line2))
                                                        <br>{{ $request->address_line2 }}
                                                    @endif
                                                </span>
                                            </li>
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-map-marker-alt"
                                                        style="color: #dc3545;"></i></span>
                                                <span><strong>Post Code:</strong>
                                                    {{ isset($request->postcode) ? $request->postcode : 'N/A' }}</span>
                                            </li>
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-id-badge"
                                                        style="color: #ffc107;"></i></span>
                                                <span><strong>ULN:</strong>
                                                    {{ isset($request->uln) ? $request->uln : 'N/A' }}</span>
                                            </li>
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-calendar-check"
                                                        style="color: #17a2b8;"></i></span>
                                                <span><strong>Form Filling Date:</strong>
                                                    {{ isset($request->form_filling_date) ? $request->form_filling_date : 'N/A' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white py-2 border-bottom">
                                        <h5 class="mb-0" style="color: #000000;">
                                            <i class="fas fa-phone-alt me-2" style="color: #17a2b8;"></i>Contact Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-mobile-alt"
                                                        style="color: #007bff;"></i></span>
                                                <span>{{ isset($request->contact_number) ? $request->contact_number : 'N/A' }}</span>
                                            </li>
                                            <li class="mb-2 d-flex">
                                                <span class="me-2"><i class="fas fa-envelope"
                                                        style="color: #28a745;"></i></span>
                                                <span>{{ isset($request->email) ? $request->email : 'N/A' }}</span>
                                            </li>
                                            <li class="d-flex">
                                                <span class="me-2"><i class="fas fa-exclamation-triangle"
                                                        style="color: #dc3545;"></i></span>
                                                <span><strong>Emergency:</strong>
                                                    {{ isset($request->emergency_contact) ? $request->emergency_contact : 'N/A' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                @if (isset($request->signature) && !empty($request->signature))
                                    <div class="card mt-4 border-0 shadow-sm">
                                        <div class="card-header bg-white py-2 border-bottom">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-signature me-2" style="color: #6f42c1;"></i>Signature
                                            </h5>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ $request->signature }}" alt="Signature"
                                                style="max-width: 200px; height: auto;">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-lg-8 p-4">
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-header bg-white py-2">
                                                <h5 class="mb-0" style="color: #000000;">
                                                    <i class="fas fa-heartbeat me-2" style="color: #dc3545;"></i>Medical
                                                    Information
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <p
                                                    class="{{ isset($request->medical_condition) && !empty($request->medical_condition) ? 'text-dark' : 'text-muted' }}">
                                                    {{ isset($request->medical_condition) && !empty($request->medical_condition) ? $request->medical_condition : 'No medical conditions reported' }}
                                                </p>

                                                @if (isset($request->access_arrangement) && $request->access_arrangement)
                                                    <div class="mt-3">
                                                        <h6 class="text-info">
                                                            <i class="fas fa-wheelchair me-2"
                                                                style="color: #6f42c1;"></i>Access Arrangement
                                                        </h6>
                                                        <p>{{ isset($request->access_arrangement_explanation) ? $request->access_arrangement_explanation : 'No details provided' }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-header bg-white py-2">
                                                <h5 class="mb-0" style="color: #000000;">
                                                    <i class="fas fa-id-badge me-2" style="color: #ffc107;"></i>UCI
                                                    Details
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                                        <i class="fas fa-id-card" style="color: #17a2b8;"></i>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0"><strong>UCI Number</strong></p>
                                                        <p class="mb-0 font-monospace">
                                                            {{ isset($request->uci_number) ? $request->uci_number : 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4 border-0 shadow-sm" style="zoom:0.8;">
                                    <div class="card-header bg-white py-2">
                                        <h5 class="mb-0" style="color: #000000;">
                                            <i class="fas fa-clipboard-list me-2" style="color: #007bff;"></i>Exam Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            // Handle fields that might be arrays or JSON strings with null checks
                                            $exam_board = isset($request->exam_board)
                                                ? (is_array($request->exam_board)
                                                    ? $request->exam_board
                                                    : json_decode($request->exam_board ?? '[]', true) ?? ['N/A'])
                                                : ['N/A'];
                                            $course_type = isset($request->course_type)
                                                ? (is_array($request->course_type)
                                                    ? $request->course_type
                                                    : json_decode($request->course_type ?? '[]', true) ?? ['N/A'])
                                                : ['N/A'];
                                            $subject_name = isset($request->subject_name)
                                                ? (is_array($request->subject_name)
                                                    ? $request->subject_name
                                                    : json_decode($request->subject_name ?? '[]', true) ?? ['N/A'])
                                                : ['N/A'];
                                            $subject_code = isset($request->subject_code)
                                                ? (is_array($request->subject_code)
                                                    ? $request->subject_code
                                                    : json_decode($request->subject_code ?? '[]', true) ?? ['N/A'])
                                                : ['N/A'];
                                            $tire = isset($request->tire)
                                                ? (is_array($request->tire)
                                                    ? $request->tire
                                                    : json_decode($request->tire ?? '[]', true) ?? ['N/A'])
                                                : ['N/A'];
                                            $nea = isset($request->nea)
                                                ? (is_array($request->nea)
                                                    ? $request->nea
                                                    : json_decode($request->nea ?? '[]', true) ?? ['N/A'])
                                                : ['N/A'];
                                        @endphp

                                        @if (!empty($exam_board) && isset($exam_board[0]) && $exam_board[0] !== 'N/A')
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead class="table-info">
                                                        <tr>
                                                            <th>Exam Board</th>
                                                            <th>Course Type</th>
                                                            <th>Subject</th>
                                                            <th>Subject Code</th>
                                                            <th>Tier</th>
                                                            <th>NEA</th>
                                                            <th>Session</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ $exam_board[0] }}</td>
                                                            <td>{{ isset($course_type[0]) ? $course_type[0] : 'N/A' }}</td>
                                                            <td>{{ isset($subject_name[0]) ? $subject_name[0] : 'N/A' }}
                                                            </td>
                                                            <td>{{ isset($subject_code[0]) ? $subject_code[0] : 'N/A' }}
                                                            </td>
                                                            <td>{{ isset($tire[0]) ? $tire[0] : 'N/A' }}</td>
                                                            <td>{{ isset($nea[0]) ? $nea[0] : 'N/A' }}</td>
                                                            <td>{{ isset($request->exam_session) ? $request->exam_session : 'N/A' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-clipboard-question fa-3x" style="color: #6c757d;"></i>
                                                <p class="text-muted">No exam details recorded</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white py-2">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-file-alt me-2" style="color: #17a2b8;"></i>Supporting Documents
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @if (isset($request->id_document_path) && !empty($request->id_document_path))
                                                <div class="mb-3">
                                                    <h6><i class="fas fa-id-card me-2" style="color: #ffc107;"></i>ID Document</h6>
                                                    <a href="{{ asset($request->id_document_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye me-1" style="color: #17a2b8;"></i>View
                                                    </a>
                                                </div>
                                            @endif

                                            @if (isset($request->supporting_documents_path) && !empty($request->supporting_documents_path))
                                                <div class="mb-3">
                                                    <h6><i class="fas fa-file-pdf me-2" style="color: #dc3545;"></i>Supporting Documents</h6>
                                                    <a href="{{ asset($request->supporting_documents_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye me-1" style="color: #17a2b8;"></i>View
                                                    </a>
                                                </div>
                                            @endif

                                            @php
                                                $additional_docs = isset($request->additional_docs_paths) ? (is_array($request->additional_docs_paths) ? $request->additional_docs_paths : (json_decode($request->additional_docs_paths ?? '[]', true) ?? [])) : [];
                                            @endphp

                                            @if (!empty($additional_docs))
                                                <div>
                                                    <h6><i class="fas fa-file-archive me-2" style="color: #6f42c1;"></i>Additional Documents</h6>
                                                    @foreach ($additional_docs as $index => $doc)
                                                        @if (!empty($doc))
                                                            <div class="mb-2">
                                                                <a href="{{ asset($doc) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                                    <i class="fas fa-file me-1" style="color: #28a745;"></i>Document {{ $index + 1 }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ((!isset($request->id_document_path) || empty($request->id_document_path)) && (!isset($request->supporting_documents_path) || empty($request->supporting_documents_path)) && empty($additional_docs))
                                                <div class="text-center py-4">
                                                    <i class="fas fa-folder-open fa-3x" style="color: #6c757d;"></i>
                                                    <p class="text-muted">No documents uploaded</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white py-2">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>Coursework & Practical
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h6><i class="fas fa-flask me-2" style="color: #007bff;"></i>Practical Endorsement</h6>
                                                <p>{{ isset($request->practical_endorsement) ? $request->practical_endorsement : 'N/A' }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <h6><i class="fas fa-book me-2" style="color: #6f42c1;"></i>Coursework</h6>
                                                <p>{{ isset($request->course_work) ? $request->course_work : 'N/A' }}</p>
                                            </div>

                                            @if (isset($request->course_work_uci) && !empty($request->course_work_uci))
                                                <div>
                                                    <h6><i class="fas fa-id-card-alt me-2" style="color: #ffc107;"></i>Coursework UCI</h6>
                                                    <p>{{ $request->course_work_uci }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-white py-2">
                                                <h5 class="mb-0" style="color: #000000;">
                                                    <i class="fas fa-file-alt me-2" style="color: #17a2b8;"></i>Supporting
                                                    Documents
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                @if (isset($request->id_document_path) && !empty($request->id_document_path))
                                                    <div class="mb-3">
                                                        <h6><i class="fas fa-id-card me-2" style="color: #ffc107;"></i>ID
                                                            Document</h6>
                                                        @php
                                                            $encoded = base64_encode($request->id_document_path);
                                                        @endphp
                                                        <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye me-1" style="color: #17a2b8;"></i>View
                                                        </a>
                                                    </div>
                                                @endif

                                                @if (isset($request->supporting_documents_path) && !empty($request->supporting_documents_path))
                                                    <div class="mb-3">
                                                        <h6><i class="fas fa-file-pdf me-2"
                                                                style="color: #dc3545;"></i>Supporting Documents</h6>
                                                        @php
                                                            $encoded = base64_encode(
                                                                $request->supporting_documents_path,
                                                            );
                                                        @endphp
                                                        <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye me-1" style="color: #17a2b8;"></i>View
                                                        </a>
                                                    </div>
                                                @endif

                                                @php
                                                    $additional_docs = isset($request->additional_docs_paths)
                                                        ? (is_array($request->additional_docs_paths)
                                                            ? $request->additional_docs_paths
                                                            : json_decode(
                                                                    $request->additional_docs_paths ?? '[]',
                                                                    true,
                                                                ) ?? [])
                                                        : [];
                                                @endphp

                                                @if (!empty($additional_docs))
                                                    <div>
                                                        <h6><i class="fas fa-file-archive me-2"
                                                                style="color: #6f42c1;"></i>Additional Documents</h6>
                                                        @foreach ($additional_docs as $index => $doc)
                                                            @if (!empty($doc))
                                                                <div class="mb-2">
                                                                    @php
                                                                        $encoded = base64_encode($doc);
                                                                    @endphp
                                                                    <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-info">
                                                                        <i class="fas fa-file me-1"
                                                                            style="color: #28a745;"></i>Document
                                                                        {{ $index + 1 }}
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if (
                                                    (!isset($request->id_document_path) || empty($request->id_document_path)) &&
                                                        (!isset($request->supporting_documents_path) || empty($request->supporting_documents_path)) &&
                                                        empty($additional_docs))
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-folder-open fa-3x" style="color: #6c757d;"></i>
                                                        <p class="text-muted">No documents uploaded</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-white py-2">
                                                <h5 class="mb-0" style="color: #000000;">
                                                    <i class="fas fa-check-circle me-2"
                                                        style="color: #28a745;"></i>Coursework & Practical
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <h6><i class="fas fa-flask me-2" style="color: #007bff;"></i>Practical
                                                        Endorsement</h6>
                                                    <p>{{ isset($request->practical_endorsement) ? $request->practical_endorsement : 'N/A' }}
                                                    </p>
                                                </div>

                                                <div class="mb-3">
                                                    <h6><i class="fas fa-book me-2" style="color: #6f42c1;"></i>Coursework
                                                    </h6>
                                                    <p>{{ isset($request->course_work) ? $request->course_work : 'N/A' }}
                                                    </p>
                                                </div>

                                                @if (isset($request->course_work_uci) && !empty($request->course_work_uci))
                                                    <div>
                                                        <h6><i class="fas fa-id-card-alt me-2"
                                                                style="color: #ffc107;"></i>Coursework UCI</h6>
                                                        <p>{{ $request->course_work_uci }}</p>
                                                    </div>
                                                @endif

                                                <div class="mb-3">
                                                    <h6><i class="fas fa-university me-2" style="color: #8e44ad;"></i>Predicted Grades & UCAS Reference</h6>
                                                    <p>{{ isset($request->need_predicted_grades) && !empty($request->need_predicted_grades) ? $request->need_predicted_grades : 'N/A' }}</p>
                                                </div>

                                                <div class="mb-3">
                                                    <h6><i class="fas fa-pencil-alt me-2" style="color: #16a085;"></i>Mock Exams</h6>
                                                    <p>{{ isset($request->need_mock_exams) && !empty($request->need_mock_exams) ? $request->need_mock_exams : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="fas fa-clock me-1" style="color: #6c757d;"></i>
                                Created:
                                {{ isset($request->created_at) && !empty($request->created_at) ? \Carbon\Carbon::parse($request->created_at)->format('M d, Y') : 'N/A' }}
                                |
                                Last Updated:
                                {{ isset($request->updated_at) && !empty($request->updated_at) ? \Carbon\Carbon::parse($request->updated_at)->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8fafb;
        }

        .card {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
        }

        .card:hover {
            box-shadow: 0 10px 25px rgba(87, 175, 191, 0.2);
            transform: translateY(-2px);
        }

        .card-header {
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }

        .table-info {
            --bs-table-bg: rgba(87, 175, 191, 0.05);
            --bs-table-striped-bg: rgba(87, 175, 191, 0.03);
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .font-monospace {
            font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05) !important;
        }

        .text-info {
            color: #57afbf !important;
        }

        .btn-info,
        .bg-info {
            background-color: #57afbf !important;
            border-color: #57afbf !important;
        }

        .btn-outline-info {
            color: #57afbf;
            border-color: #57afbf;
        }

        .btn-outline-info:hover {
            background-color: #57afbf;
            color: white;
        }

        .bg-info.bg-opacity-10 {
            background-color: rgba(87, 175, 191, 0.1) !important;
        }

        .fa-icon-hover:hover {
            transform: scale(1.2);
            color: #57afbf;
            transition: all 0.3s ease;
        }

        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #000000 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fa-icon-hover').forEach(icon => {
                icon.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.2)';
                    this.style.color = '#57afbf';
                });
                icon.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                    this.style.color = '';
                });
            });

            document.querySelectorAll('.table-hover tbody tr').forEach(row => {
                row.addEventListener('click', function() {});
            });
        });
    </script>
@endsection
