@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success" style="display: flex; align-items: center;">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" style="display: flex; align-items: center;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow mb-4" style="border-radius: 8px; border: none;">
                <div class="card-header py-3" style="background: #F87A14; border-radius: 8px 8px 0 0; border-bottom: 4px solid #1976D2;">
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-search fa-lg text-white me-2"></i>
                        <h5 class="m-0 font-weight-bold text-white">Generate Exam Timetable</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('generate-timetable') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="student_id" value="{{ @$student_id }}" class="form-control"
                                   placeholder="Enter Student ID" style="border: 1px solid #b0e0e6;"
                                   onfocus="this.style.borderColor='#57afbf'; this.style.boxShadow='0 0 0 0.2rem rgba(87, 175, 191, 0.25)'"
                                   onblur="this.style.borderColor='#b0e0e6'; this.style.boxShadow='none'">
                            <button class="btn btn-primary" type="submit" style="background: #F87A14; border: none;">
                                <i class="fas fa-file-alt me-1"></i> Generate
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if (@$studentDetail)
            <div class="card shadow mb-4" style=>
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-calendar-alt fa-lg text-white me-2"></i>
                        <h5 class="m-0 font-weight-bold text-white">Student Exam Timetable</h5>
                    </div>
                    <div>
                        <button onclick="printLayout()" class="btn btn-light me-2">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                        @if($studentEmail)
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#emailModal">
                            <i class="fas fa-envelope me-1"></i> Email
                        </button>
                        @else
                        <button class="btn btn-light" disabled title="No email address available">
                            <i class="fas fa-envelope me-1"></i> Email
                        </button>
                        @endif
                    </div>
                </div>
                <div class="card-body" id="dynamicLayout">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/LondonExamCentre.png') }}"
                             style="width: 300px; height: auto;" alt="Logo">
                        <h2 class="mt-3" style="color: #2c3e50;">Student Exam Timetable</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <th style="white-space: nowrap; background-color: #f8f9fa;">Session</th>
                                    <td>{{$studentDetail->exam_session}}</td>
                                    <th colspan="2" style="white-space: nowrap; background-color: #f8f9fa;">Centre Name</th>
                                    <td colspan="4">Frobel Learning</td>
                                </tr>
                                <tr>
                                    <th style="white-space: nowrap; background-color: #f8f9fa;">Center No</th>
                                    <td>12144 Frobel Learning</td>
                                    <th colspan="2" style="white-space: nowrap; background-color: #f8f9fa;">Candidate Name</th>
                                    <td colspan="2">{{ $studentDetail->surname . ' ' . $studentDetail->first_name }}</td>
                                    <th style="white-space: nowrap; background-color: #f8f9fa;">Candidate ID</th>
                                    <td>{{ $student_id }}</td>
                                </tr>
                                <tr>
                                    <th style="white-space: nowrap; background-color: #f8f9fa;">DOB</th>
                                    <td>{{ date('d/m/Y', strtotime($studentDetail->dob)) }}</td>
                                    <th colspan="2" style="white-space: nowrap; background-color: #f8f9fa;">Gender</th>
                                    <td colspan="4">{{ ucfirst($studentDetail->gender) }}</td>
                                </tr>
                                <tr>
                                    <th style="white-space: nowrap; background-color: #f8f9fa;">Speaking Exam</th>
                                    <td>{{ $language }}</td>
                                    <th colspan="2" style="white-space: nowrap; background-color: #f8f9fa;">Course Work</th>
                                    <td colspan="4">{{ $courseWork }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mt-4" style="width: 100%;zoom:0.8;">
                            <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th style="white-space: nowrap;">Exam Board</th>
                                    <th style="white-space: nowrap;">Qualification</th>
                                    <th style="white-space: nowrap;">Subject / Title</th>
                                    <th style="white-space: nowrap;">Exam Code</th>
                                    <th style="white-space: nowrap;">Date</th>
                                    <th style="white-space: nowrap;">Day</th>
                                    <th style="white-space: nowrap;">Time</th>
                                    <th style="white-space: nowrap;">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- {{dd($studentDetailz)}} --}}
                                @foreach ($studentDetailz as $examEntry)
                                <tr>
                                    <td style="white-space: nowrap;">{{ $examEntry->exam_board }}</td>
                                    <td style="white-space: nowrap;">
                                        {{ $examEntry->course_type }}
                                    </td>
                                    @php
                                    // dd($examEntry->subject_name);
                                    $words = explode(' ', $examEntry->subject_name);
                                    $chunks = array_chunk($words, 5);
                                @endphp
                                <td style="white-space: normal;">
                                    @foreach ($chunks as $chunk)
                                        {{ implode(' ', $chunk) }}<br>
                                    @endforeach
                                </td>

                                    <td style="white-space: nowrap;">{{ $examEntry->subject_code }}</td>
                                    <td style="white-space: nowrap;">{{ $examEntry->date ? date('d/m/Y', strtotime($examEntry->date)) : '' }}</td>
                                    <td style="white-space: nowrap;">{{ $examEntry->day }}</td>
                                    <td style="white-space: nowrap; text-transform: uppercase;">{{ $examEntry->time }}</td>
                                    <td style="white-space: nowrap;">
                                        @if ($examEntry->duration !== 'Submit by')
                                            {{ $examEntry->duration }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <table class="table table-bordered mt-3" style="width: auto;">
                        <tr>
    <th style="white-space: nowrap; background-color: #f8f9fa;">
        Access Arrangements:
    </th>
    <td>
        @if($studentDetail->access_arrangement == 1)
            @if(!empty($studentDetail->access_arrangement_explanation))
                {{ strtoupper($studentDetail->access_arrangement_explanation) }}
            @else
                Yes
            @endif
        @else
            No
        @endif
    </td>
</tr>

                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if (@$studentEmail)
<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #F87A14; border-bottom: 4px solid #1976D2;">
                <h5 class="modal-title text-white" id="emailModalLabel">
                    <i class="fas fa-envelope me-1"></i> Confirm Email
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="form-group mb-3">
                    <label for="student-email" class="font-weight-bold">
                        <i class="fas fa-envelope me-1"></i> Student Email:
                    </label>
                    <input type="text" readonly value="{{ $studentEmail }}" name="studentEmail" id="student-email"
                           class="form-control text-center mt-2" style="font-size: 1.1rem;">
                </div>
                <div class="form-group mb-3">
                    <label for="cc-email" class="font-weight-bold">
                        <i class="fas fa-envelope-open me-1"></i> CC Email: <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <input type="email" value="{{ $studentDetail->cc_email ?? '' }}" name="ccEmail" id="cc-email"
                           class="form-control text-center mt-2" placeholder="Enter CC email address (optional)" style="font-size: 1rem;">
                </div>
                <p class="mt-3">
                    <i class="fas fa-info-circle me-1"></i> Are you sure you want to send the timetable to this email address?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="confirmYes">
                    <i class="fas fa-paper-plane me-1"></i> Send
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
  function printLayout() {
    var printContents = document.getElementById("dynamicLayout").innerHTML;
    var originalContents = document.body.innerHTML;

    var tempPrintDiv = document.createElement("div");
    tempPrintDiv.innerHTML = `
        <html>
            <head>
                <title>Print Timetable</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 5mm;
                        width: 100%;
                        box-sizing: border-box;
                        font-size: 10px; /* Reduced font size for print */
                    }
                    .container-fluid {
                        width: 100%;
                        padding: 0;
                        margin: 0;
                    }
                    table {
                        width: 100% !important;
                        border-collapse: collapse;
                        margin-bottom: 10px; /* Reduced margin */
                    }
                    th, td {
                        padding: 5px; /* Reduced padding */
                        border: 1px solid #ddd;
                        line-height: 1.2; /* Tighter line height */
                    }
                    th {
                        background-color: #f2f2f2;
                        text-align: left;
                    }
                    .text-center {
                        text-align: center;
                    }
                    img {
                        max-width: 180px; /* Reduced logo size */
                        height: auto;
                    }
                    h2 {
                        font-size: 16px; /* Reduced heading size */
                        margin: 5px 0; /* Reduced margin */
                    }
                    @page {
                        size: A4;
                        margin: 5mm; /* Smaller margins */
                    }
                    @media print {
                        body {
                            font-size: 12px;
                            margin: 0;
                            padding: 5mm;
                            width: 100%;
                        }
                        table {
                            page-break-inside: auto; /* Allow tables to break if necessary */
                            width: 100% !important;
                        }
                        tr {
                            page-break-inside: avoid;
                            page-break-after: auto;
                        }
                        .no-print {
                            display: none !important;
                        }
                        .card, .card-body {
                            border: none;
                            box-shadow: none;
                            padding: 0;
                            margin: 0;
                        }
                        .table-responsive {
                            overflow: visible !important;
                            width: 100%;
                        }
                    }
                </style>
            </head>
            <body>
                ${printContents}
            </body>
        </html>
    `;

    document.body.innerHTML = tempPrintDiv.innerHTML;
    window.print();
    document.body.innerHTML = originalContents;
     window.location.reload(); // Refresh the page
}
</script>

@if (@$studentEmail)
<script>
    document.getElementById('confirmYes').addEventListener('click', function() {
        Swal.fire({
            title: 'Sending Timetable',
            html: '<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x" style="color:#57afbfd6;"></i><p class="mt-3">Please wait...</p></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        var dynamicLayoutContent = document.getElementById('dynamicLayout').innerHTML;
        var studentId = "{{ $student_id }}";
        var ccEmail = document.getElementById('cc-email').value.trim();

        var formData = new FormData();
        formData.append('dynamicLayoutContent', dynamicLayoutContent);
        formData.append('student_id', studentId);
        formData.append('email', "{{ $studentEmail }}");
        if (ccEmail !== '') {
            formData.append('cc_email', ccEmail);
        }

        fetch('{{ url('send-timetable') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: data.success ? 'Success!' : 'Error!',
                text: data.message,
                icon: data.success ? 'success' : 'error',
                confirmButtonColor: '#57afbfd6'
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred. Please try again later.',
                icon: 'error',
                confirmButtonColor: '#57afbfd6'
            });
        });
    });
</script>
@endif

@endsection
