@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="card-header position-relative py-3" style="background: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0 text-white">
                            <i class="fas fa-user-graduate me-2" style="color: #ffffff;"></i>
                            {{ $request->first_name ?? 'N/A' }} {{ $request->surname ?? '' }}
                        </h2>
                        <div class="position-absolute top-0 end-0 px-3 py-1 bg-success text-white rounded-bl-lg">
                            Approved
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- LEFT SIDEBAR -->
                        <div class="col-lg-4 p-4" style="background-color: #f8fafb;">
                            <div class="text-center mb-4">
                                @if(!empty($request->photo_path))
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset($request->photo_path) }}"
                                         class="rounded-circle shadow border border-4 border-white"
                                         alt="Profile"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                    <span class="position-absolute bottom-0 end-0 bg-info p-2 rounded-circle">
                                        <i class="fas fa-check text-white"></i>
                                    </span>
                                </div>
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 150px; margin: 0 auto;">
                                    <i class="fas fa-user-graduate text-muted fa-3x"></i>
                                </div>
                                @endif
                                <h4 class="mt-3 mb-0" style="color: #000000;">{{ $request->first_name ?? 'N/A' }} {{ $request->surname ?? '' }}</h4>
                                <p class="text-muted">Student ID: {{ $request->student_id ?? 'N/A' }}</p>
                            </div>

                            <!-- Personal Details -->
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white py-2 border-bottom">
                                    <h5 class="mb-0" style="color: #000000;">
                                        <i class="fas fa-id-card me-2" style="color: #17a2b8;"></i>Personal Details
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2 d-flex"><span class="me-2"><i class="fas fa-calendar-alt" style="color: #007bff;"></i></span> <strong>DOB:</strong> {{ $request->dob ?? 'N/A' }}</li>
                                        <li class="mb-2 d-flex"><span class="me-2"><i class="fas fa-venus-mars" style="color: #6f42c1;"></i></span> <strong>Gender:</strong> {{ $request->gender ?? 'N/A' }}</li>
                                        <li class="mb-2 d-flex"><span class="me-2"><i class="fas fa-home" style="color: #28a745;"></i></span> <strong>Address:</strong> {{ $request->address_line1 ?? 'N/A' }} @if(!empty($request->address_line2))<br>{{ $request->address_line2 }}@endif</li>
                                        <li class="mb-2 d-flex"><span class="me-2"><i class="fas fa-map-marker-alt" style="color: #dc3545;"></i></span> <strong>Post Code:</strong> {{ $request->postcode ?? 'N/A' }}</li>
                                        <li class="mb-2 d-flex"><span class="me-2"><i class="fas fa-id-badge" style="color: #ffc107;"></i></span> <strong>ULN:</strong> {{ $request->uln ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white py-2 border-bottom">
                                    <h5 class="mb-0" style="color: #000000;">
                                        <i class="fas fa-phone-alt me-2" style="color: #17a2b8;"></i>Contact Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2 d-flex"><span class="me-2"><i class="fas fa-mobile-alt" style="color: #007bff;"></i></span> {{ $request->contact_number ?? 'N/A' }}</li>
                                        <li class="mb-2 d-flex"><span class="me-2"><i class="fas fa-envelope" style="color: #28a745;"></i></span> {{ $request->email ?? 'N/A' }}</li>
                                        <li class="d-flex"><span class="me-2"><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i></span> <strong>Emergency:</strong> {{ $request->emergency_contact ?? 'N/A' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT CONTENT -->
                        <div class="col-lg-8 p-4">

                            <!-- Medical & UCI -->
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white py-2">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-heartbeat me-2" style="color: #dc3545;"></i>Medical Information
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="{{ !empty($request->medical_condition) ? 'text-dark' : 'text-muted' }}">
                                                {{ $request->medical_condition ?? 'No medical conditions reported' }}
                                            </p>
                                            @if($request->access_arrangement)
                                            <div class="mt-3">
                                                <h6 class="text-info"><i class="fas fa-wheelchair me-2" style="color: #6f42c1;"></i>Access Arrangement</h6>
                                                <p>{{ $request->access_arrangement_explanation ?? 'No details provided' }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-header bg-white py-2">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-id-badge me-2" style="color: #ffc107;"></i>UCI Details
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-id-card" style="color: #17a2b8;"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0"><strong>UCI Number</strong></p>
                                                    <p class="mb-0 font-monospace">{{ $request->uci_number ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Exam Details -->
                            <div class="card mb-4 border-0 shadow-sm" style="zoom:0.8;">
                                <div class="card-header bg-white py-2">
                                    <h5 class="mb-0" style="color: #000000;">
                                        <i class="fas fa-clipboard-list me-2" style="color: #007bff;"></i>Exam Details
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($examDetails->isNotEmpty())
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
                                                        <th>Date</th>
                                                        <th>Day</th>
                                                        <th>Time</th>
                                                        <th>Duration</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($examDetails as $exam)
                                                        <tr>
                                                            <td>{{ $exam->exam_board ?? 'N/A' }}</td>
                                                            <td>{{ $exam->course_type ?? 'N/A' }}</td>
                                                            <td>{{ $exam->subject_name ?? 'N/A' }}</td>
                                                            <td>{{ $exam->subject_code ?? 'N/A' }}</td>
                                                            <td>{{ $exam->tire ?? 'N/A' }}</td>
                                                            <td>{{ $exam->nea ?? 'N/A' }}</td>
                                                            <td>{{ $exam->date ? \Carbon\Carbon::make($exam->date)->format('d/m/Y') : 'N/A' }}</td>
                                                            <td>{{ $exam->day ?? 'N/A' }}</td>
                                                            <td>{{ $exam->time ?? 'N/A' }}</td>
                                                            <td>{{ $exam->duration ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
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

                            <!-- SUPPORTING DOCUMENTS + RECEIPTS -->
                            <div class="row">

                                <!-- SUPPORTING DOCUMENTS -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white py-2">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-file-alt me-2" style="color: #17a2b8;"></i>Supporting Documents
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @if(!empty($request->id_document_path))
                                            <div class="mb-3">
                                                <h6><i class="fas fa-id-card me-2" style="color: #ffc107;"></i>ID Document</h6>
                                                @php $encoded = base64_encode($request->id_document_path); @endphp
                                                <a href="{{ route('file.view', ['encoded' => $encoded]) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                            </div>
                                            @endif

                                            @if(!empty($request->supporting_documents_path))
                                            <div class="mb-3">
                                                <h6><i class="fas fa-file-pdf me-2" style="color: #dc3545;"></i>Supporting Documents</h6>
                                                @php $encoded = base64_encode($request->supporting_documents_path); @endphp
                                                <a href="{{ route('file.view', ['encoded' => $encoded]) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                            </div>
                                            @endif

                                            @php
                                                $additional_docs = is_array($request->additional_docs_paths)
                                                    ? $request->additional_docs_paths
                                                    : json_decode($request->additional_docs_paths ?? '[]', true) ?? [];
                                            @endphp

                                            @if(!empty($additional_docs))
                                            <div>
                                                <h6><i class="fas fa-file-archive me-2" style="color: #6f42c1;"></i>Additional Documents</h6>
                                                @foreach($additional_docs as $index => $doc)
                                                @if (!empty($doc))
                                                <div class="mb-2">
                                                    @php $encoded = base64_encode($doc); @endphp
                                                    <a href="{{ route('file.view', ['encoded' => $encoded]) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-file me-1"></i>Document {{ $index + 1 }}
                                                    </a>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                            @endif

                                            @if(empty($request->id_document_path) && empty($request->supporting_documents_path) && empty($additional_docs))
                                            <div class="text-center py-4">
                                                <i class="fas fa-folder-open fa-3x" style="color: #6c757d;"></i>
                                                <p class="text-muted">No documents uploaded</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- RECEIPTS - SIRF VIEW ICON -->
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white py-2">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-receipt me-2" style="color: #28a745;"></i> Receipts
                                            </h5>
                                        </div>
                                        <div class="card-body">

                                            @if($payments->isNotEmpty())
                                                <div class="list-group list-group-flush">
                                                    @foreach($payments as $pay)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                            <span class="small text-muted">
                                                                Payment:
                                                                @if($pay->amount_received)
                                                                    <strong>£{{ number_format($pay->amount_received, 2) }}</strong>
                                                                @endif
                                                            </span>

                                                            @if(!empty($pay->receipt_path))
                                                                @php $encoded = base64_encode($pay->receipt_path); @endphp
                                                                <a href="{{ route('file.view', ['student_id' => $pay->student_id, 'encoded' => $encoded]) }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-outline-success"
                                                                   title="View Receipt">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted small">—</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <i class="fas fa-receipt fa-3x" style="color: #6c757d;"></i>
                                                    <p class="text-muted mt-2">No receipts</p>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- COURSEWORK & PRACTICAL -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white py-2">
                                            <h5 class="mb-0" style="color: #000000;">
                                                <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>Coursework & Practical
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <h6><i class="fas fa-flask me-2" style="color: #007bff;"></i> Practical Endorsement</h6>
                                                <p>{{ $request->practical_endorsement ?? 'N/A' }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <h6><i class="fas fa-book me-2" style="color: #6f42c1;"></i> Coursework</h6>
                                                <p>{{ $request->course_work ?? 'N/A' }}</p>
                                            </div>
                                            @if(!empty($request->course_work_uci))
                                            <div>
                                                <h6><i class="fas fa-id-card-alt me-2" style="color: #ffc107;"></i> Coursework UCI</h6>
                                                <p>{{ $request->course_work_uci }}</p>
                                            </div>
                                            @endif

                                            <div class="mb-3">
                                                <h6><i class="fas fa-university me-2" style="color: #8e44ad;"></i> Predicted Grades & UCAS Reference</h6>
                                                <p>{{ !empty($request->need_predicted_grades) ? $request->need_predicted_grades : 'N/A' }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <h6><i class="fas fa-pencil-alt me-2" style="color: #16a085;"></i> Mock Exams</h6>
                                                <p>{{ !empty($request->need_mock_exams) ? $request->need_mock_exams : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="fas fa-clock me-1" style="color: #6c757d;"></i>
                            Created: {{ isset($request->created_at) ? $request->created_at->format('M d, Y') : 'N/A' }} |
                            Last Updated: {{ isset($request->updated_at) ? $request->updated_at->format('M d, Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafb; }
    .card { transition: all 0.3s ease; border-radius: 0.75rem; }
    .card:hover { box-shadow: 0 10px 25px rgba(87, 175, 191, 0.2); transform: translateY(-2px); }
    .card-header { border-radius: 0.75rem 0.75rem 0 0 !important; }
    .btn-outline-success { color: #28a745; border-color: #28a745; }
    .btn-outline-success:hover { background-color: #28a745; color: white; }
    h2, h3, h4, h5, h6 { color: #000000 !important; }
</style>
@endsection
