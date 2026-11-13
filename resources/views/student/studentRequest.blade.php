@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 20px;">
        <div style="display: flex; flex-wrap: wrap; margin-right: -10px; margin-left: -10px;">
            <div style="flex: 0 0 100%; max-width: 100%; padding-right: 10px; padding-left: 10px;">
                <div
                    style="position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: none; border-radius: 10px; box-shadow: 0 0.2rem 2rem 0 rgba(58, 59, 69, 0.2); margin-bottom: 20px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div
                        style="padding: 15px 20px; background-color: #F87A14; border-bottom: 4px solid #1976D2; display: flex; justify-content: space-between; align-items: center; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h6 style="margin: 0; font-weight: 700; font-size: 1.2rem; color: #fff;">
                            <i class="fas fa-user-graduate" style="margin-right: 8px; color: #fff;"></i> Student Requests
                        </h6>
                        <button id="exportBtn" class="btn btn-success"
                            style="background-color: #28a745; border: none; padding: 0.5rem 1rem; border-radius: 0.25rem; color: white;">
                            <i class="fas fa-file-export" style="margin-right: 5px;"></i> Export to Excel
                        </button>
                    </div>

                    <div style="flex: 1 1 auto; padding: 15px;">
                        <div style="overflow-x: auto;">
                            <table id="studentRequestTable"
                                style="width: 100%; font-size: 0.9rem; border-collapse: collapse;">
                                <thead style="background-color:#F87A14; color: #fff;">
                                    <tr>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Name</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Date of Birth</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Session</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Status</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Initiated At</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach ($students as $request)
    @if ($request->is_approved == 0)
        @php
            // Process exam_board
            $examBoards = is_string($request->exam_board) ? (json_decode($request->exam_board, true) ?: [$request->exam_board]) : (is_array($request->exam_board) ? $request->exam_board : [$request->exam_board]);
            $examBoards = array_filter($examBoards);

            // Process course_type
            $courseTypes = is_string($request->course_type) ? (json_decode($request->course_type, true) ?: [$request->course_type]) : (is_array($request->course_type) ? $request->course_type : [$request->course_type]);
            $courseTypes = array_filter($courseTypes);

            // Process subject_name
            $subjectNames = is_string($request->subject_name) ? (json_decode($request->subject_name, true) ?: [$request->subject_name]) : (is_array($request->subject_name) ? $request->subject_name : [$request->subject_name]);
            $subjectNames = array_filter($subjectNames);

            // Combine exam_board, course_type, and subject_name into formatted subjects
            $formattedSubjects = [];
            $maxLength = max(count($examBoards), count($courseTypes), count($subjectNames));
            for ($i = 0; $i < $maxLength; $i++) {
                $board = $examBoards[$i] ?? 'Unknown Board';
                $courseType = $courseTypes[$i] ?? 'Unknown Course Type';
                $subject = $subjectNames[$i] ?? 'Unknown Subject';
                $formattedSubjects[] = "$board-$courseType-$subject";
            }

            // Check if the request has an invoice
            $hasInvoice = in_array($request->id, $invoicedRequestIds);
        @endphp
        <tr id="request-row-{{ $request->id }}"
            style="transition: background-color 0.3s ease; {{ $hasInvoice ? 'background-color: #d4edda !important;' : '' }}">
            <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                {{ $request->first_name }} {{ $request->surname }}
            </td>
            <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                {{ \Carbon\Carbon::parse($request->dob)->format('d M Y') }}
            </td>
            <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                {{ ucfirst($request->exam_session) }}
            </td>
            <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; background-color: #fff3cd; color: #856404;">
                    Pending
                </span>
            </td>
            <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                {{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}
                <small class="text-muted">({{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }})</small>
            </td>
            <td style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                <div style="display: flex; gap: 6px;">
                    <a href="{{ url('view-request-studentReq/' . $request->id) }}"
                        title="View"
                        style="background-color: #24b572; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                        <i class="fas fa-eye" style="font-size: 0.8rem;"></i>
                    </a>
                    <a href="{{ url('edit-request/' . $request->id) }}" title="Edit"
                        style="background-color: #F87A14; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                        <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                    </a>
                    <button onclick="confirmDelete('{{ $request->id }}')"
                        title="Delete"
                        style="background-color: #dc3545; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                        <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                    </button>
                    <button
                        onclick='openEmailModal({{ $request->id }}, @json($request->email))'
                        title="Send Custom Email"
                        style="background-color: #007bff; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                        <i class="fas fa-envelope" style="font-size: 0.8rem;"></i>
                    </button>
                    <button
                        onclick='openRegistrationEmailModal({{ $request->id }}, @json($request->email), @json($request->first_name . " " . $request->surname), @json($formattedSubjects))'
                        title="Send Registration Email"
                        style="background-color: #17a2b8; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                        <i class="fas fa-paper-plane" style="font-size: 0.8rem;"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endif
@endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Email Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Send Custom Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="emailForm" enctype="multipart/form-data">
                            <input type="hidden" id="requestId" name="request_id">
                            <div class="mb-3">
                                <label for="email" class="form-label">Recipient Email</label>
                                <input type="email" class="form-control" id="email" name="email" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Attach PDF (Optional)</label>
                                <input type="file" class="form-control" id="attachment" name="attachments[]"
                                    accept=".pdf" multiple>
                                <small class="form-text text-muted">Optional: You can select multiple PDF files. Select files multiple times to add more.</small>
                                <div id="fileList" class="mt-2" style="display: none;">
                                    <small class="text-muted">Selected files:</small>
                                    <ul id="fileListItems" class="list-unstyled mt-1"></ul>
                                </div>
                            </div>
                        </form>
                        <div id="emailLoader" style="display: none; text-align: center; margin-top: 15px;">
                            <div class="spinner-border text-primary" style="color: #4fc3f7 !important;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span style="margin-left: 10px; color: #01579b; font-weight: 500;">Please wait, email is
                                sending...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="sendEmailBtn">Send</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Email Modal -->
        <div class="modal fade" id="registrationEmailModal" tabindex="-1" aria-labelledby="registrationEmailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationEmailModalLabel">Send Registration Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="registrationEmailForm" enctype="multipart/form-data">
                            <input type="hidden" id="regRequestId" name="request_id">
                            <div class="mb-3">
                                <label for="regEmail" class="form-label">Recipient Email</label>
                                <input type="email" class="form-control" id="regEmail" name="email" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="regCc" class="form-label">CC Email <span class="text-muted">(Optional)</span></label>
                                <input type="email" class="form-control" id="regCc" name="cc_email" placeholder="Enter CC email address">
                                <small class="form-text text-muted">Email will also be sent to this address.</small>
                            </div>
                            <div class="mb-3">
                                <label for="candidateName" class="form-label">Candidate Name</label>
                                <input type="text" class="form-control" id="candidateName" name="candidate_name"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="subjects" class="form-label">Subjects (one per line)</label>
                                <textarea class="form-control" id="subjects" name="subjects[]" rows="4" required
                                    placeholder="Enter each subject in the format: EXAM BOARD - Subject name"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="totalFee" class="form-label">Total Fee (£)</label>
                                <input type="number" class="form-control" id="totalFee" name="total_fee"
                                    step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="paymentDeadline" class="form-label">Payment Deadline</label>
                                <input type="date" class="form-control" id="paymentDeadline" name="payment_deadline"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="regAttachment" class="form-label">Attach Files (Optional)</label>
                                <input type="file" class="form-control" id="regAttachment" name="attachments[]"
                                    multiple>
                                <small class="form-text text-muted">Optional: You can select multiple files. Select files multiple times to add more.</small>
                                <div id="regFileList" class="mt-2" style="display: none;">
                                    <small class="text-muted">Selected files:</small>
                                    <ul id="regFileListItems" class="list-unstyled mt-1"></ul>
                                </div>
                            </div>
                        </form>
                        <div id="regEmailLoader" style="display: none; text-align: center; margin-top: 15px;">
                            <div class="spinner-border text-primary" style="color: #4fc3f7 !important;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span style="margin-left: 10px; color: #01579b; font-weight: 500;">Please wait, email is
                                sending...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="sendRegistrationEmailBtn">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // Define Toast with sky-themed styling
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#e3f2fd',
            iconColor: '#0288d1',
            customClass: {
                popup: 'sky-toast',
                title: 'sky-toast-title'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Export to Excel
        document.getElementById('exportBtn').addEventListener('click', function() {
            // Prepare data for export
            const studentsData = [
                @foreach ($students as $request)
                    @if ($request->is_approved == 0)
                        {
                            "Full Name": "{{ $request->first_name }} {{ $request->surname }}",
                            "Contact Number": "{{ $request->contact_number }}",
                            "Emergency Contact": "{{ $request->emergency_contact }}",
                            "Exam Board": "{{ is_string($request->exam_board) ? (json_decode($request->exam_board, true) ? implode(', ', json_decode($request->exam_board, true)) : $request->exam_board) : (is_array($request->exam_board) ? implode(', ', $request->exam_board) : $request->exam_board) }}",
                            "Subject Name": "{{ is_string($request->subject_name) ? (json_decode($request->subject_name, true) ? implode(', ', json_decode($request->subject_name, true)) : $request->subject_name) : (is_array($request->subject_name) ? implode(', ', $request->subject_name) : $request->subject_name) }}",
                            "Form Filling Date": "{{ $request->form_filling_date ? \Carbon\Carbon::parse($request->form_filling_date)->format('d/m/Y') : '' }}",
                            "Access Arrangement": "{{ $request->access_arrangement ? 'Yes' : 'No' }}"
                        }
                        @if (!$loop->last)
                            ,
                        @endif
                    @endif
                @endforeach
            ];

            // Define headers separately
            const headers = {
                "Full Name": "Full Name",
                "Contact Number": "Contact Number",
                "Emergency Contact": "Emergency Contact",
                "Exam Board": "Exam Board",
                "Subject Name": "Subject Name",
                "Form Filling Date": "Form Filling Date",
                "Access Arrangement": "Access Arrangement"
            };

            // Convert to worksheet with headers
            const ws = XLSX.utils.json_to_sheet(studentsData, {
                header: Object.keys(headers),
                skipHeader: false
            });
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Student Requests");

            // Generate Excel file and trigger download
            XLSX.writeFile(wb, "student_requests.xlsx");
        });

        function confirmDelete(requestId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteUrl = "{{ route('student-requests.delete', ':id') }}".replace(':id', requestId);

                    fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message || 'Network response was not ok');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            Toast.fire({
                                icon: 'success',
                                title: data.message
                            });
                            const row = document.getElementById('request-row-' + requestId);
                            if (row) {
                                row.remove();
                            }
                        })
                        .catch(error => {
                            Toast.fire({
                                icon: 'error',
                                title: error.message || 'Error deleting student request'
                            });
                        });
                }
            });
        }

        // Global array to store selected files for custom email
        let selectedFiles = [];

        function openEmailModal(requestId, email) {
            document.getElementById('requestId').value = requestId;
            document.getElementById('email').value = email;
            document.getElementById('subject').value = '';
            document.getElementById('message').value = '';
            document.getElementById('attachment').value = '';
            selectedFiles = [];
            document.getElementById('fileList').style.display = 'none';
            document.getElementById('fileListItems').innerHTML = '';
            document.getElementById('emailLoader').style.display = 'none';
            const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
            emailModal.show();
        }

        // Function to display selected files for custom email
        function displayFiles() {
            const fileList = document.getElementById('fileList');
            const fileListItems = document.getElementById('fileListItems');

            if (selectedFiles.length > 0) {
                fileList.style.display = 'block';
                fileListItems.innerHTML = '';
                selectedFiles.forEach((file, index) => {
                    const li = document.createElement('li');
                    li.className = 'mb-1 d-flex justify-content-between align-items-center';
                    li.innerHTML = `
                        <small><i class="fas fa-file-pdf text-danger"></i> ${file.name}</small>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFile(${index})" style="padding: 0.1rem 0.4rem; font-size: 0.7rem;">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    fileListItems.appendChild(li);
                });
            } else {
                fileList.style.display = 'none';
                fileListItems.innerHTML = '';
            }
        }

        // Function to remove a file from custom email
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            displayFiles();
        }

        // Handle file selection for custom email
        document.getElementById('attachment').addEventListener('change', function() {
            const newFiles = Array.from(this.files);
            newFiles.forEach(file => {
                const exists = selectedFiles.some(existingFile =>
                    existingFile.name === file.name && existingFile.size === file.size
                );
                if (!exists) {
                    selectedFiles.push(file);
                }
            });
            this.value = '';
            displayFiles();
        });

        // Global array to store all selected files for registration email
        let regSelectedFiles = [];

        function openRegistrationEmailModal(requestId, email, candidateName, subjects) {
            document.getElementById('regRequestId').value = requestId;
            document.getElementById('regEmail').value = email;
            document.getElementById('candidateName').value = candidateName;
            // Display subjects in the format exam_board-course_type-subject_name, one per line
            document.getElementById('subjects').value = subjects.length ? subjects.join('\n') : 'No subjects available';
            document.getElementById('totalFee').value = '';
            document.getElementById('paymentDeadline').value = '';
            document.getElementById('regCc').value = ''; // Clear CC field
            document.getElementById('regAttachment').value = ''; // Clear file input
            regSelectedFiles = []; // Clear selected files array
            document.getElementById('regFileList').style.display = 'none'; // Hide file list
            document.getElementById('regFileListItems').innerHTML = ''; // Clear file list items
            document.getElementById('regEmailLoader').style.display = 'none';
            const registrationEmailModal = new bootstrap.Modal(document.getElementById('registrationEmailModal'));
            registrationEmailModal.show();
        }

        // Function to display all selected files for registration email
        function displayRegFiles() {
            const fileList = document.getElementById('regFileList');
            const fileListItems = document.getElementById('regFileListItems');

            if (regSelectedFiles.length > 0) {
                fileList.style.display = 'block';
                fileListItems.innerHTML = '';
                regSelectedFiles.forEach((file, index) => {
                    const li = document.createElement('li');
                    li.className = 'mb-1 d-flex justify-content-between align-items-center';
                    // Determine icon based on file type
                    let iconClass = 'fas fa-file';
                    if (file.type.includes('pdf')) {
                        iconClass = 'fas fa-file-pdf text-danger';
                    } else if (file.type.includes('image')) {
                        iconClass = 'fas fa-file-image text-primary';
                    } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                        iconClass = 'fas fa-file-word text-info';
                    } else if (file.type.includes('excel') || file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
                        iconClass = 'fas fa-file-excel text-success';
                    }
                    li.innerHTML = `
                        <small><i class="${iconClass}"></i> ${file.name}</small>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRegFile(${index})" style="padding: 0.1rem 0.4rem; font-size: 0.7rem;">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    fileListItems.appendChild(li);
                });
            } else {
                fileList.style.display = 'none';
                fileListItems.innerHTML = '';
            }
        }

        // Function to remove a file from registration email
        function removeRegFile(index) {
            regSelectedFiles.splice(index, 1);
            displayRegFiles();
        }

        // Display selected files for registration email - add to existing array instead of replacing
        document.getElementById('regAttachment').addEventListener('change', function() {
            const newFiles = Array.from(this.files);
            
            // Add new files to the existing array
            newFiles.forEach(file => {
                // Check if file already exists (by name and size to avoid duplicates)
                const exists = regSelectedFiles.some(existingFile => 
                    existingFile.name === file.name && existingFile.size === file.size
                );
                if (!exists) {
                    regSelectedFiles.push(file);
                }
            });

            // Clear the input so user can select more files
            this.value = '';
            
            // Display all files
            displayRegFiles();
        });

        document.getElementById('sendEmailBtn').addEventListener('click', function() {
            const formData = new FormData();
            const sendButton = document.getElementById('sendEmailBtn');
            const loader = document.getElementById('emailLoader');

            // Add form fields manually
            formData.append('request_id', document.getElementById('requestId').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('subject', document.getElementById('subject').value);
            formData.append('message', document.getElementById('message').value);

            // Validate and append files
            const maxSize = 5 * 1024 * 1024;
            for (let i = 0; i < selectedFiles.length; i++) {
                const file = selectedFiles[i];
                if (file.type !== 'application/pdf') {
                    Toast.fire({
                        icon: 'error',
                        title: 'Please upload only valid PDF files. Invalid file: ' + file.name
                    });
                    return;
                }
                if (file.size > maxSize) {
                    Toast.fire({
                        icon: 'error',
                        title: 'File size exceeds 5MB limit: ' + file.name
                    });
                    return;
                }
            }

            // Append files
            selectedFiles.forEach(file => {
                formData.append('attachments[]', file);
            });

            loader.style.display = 'flex';
            sendButton.disabled = true;
            sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            fetch("{{ route('student-requests.send-email') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Failed to send email');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    loader.style.display = 'none';
                    sendButton.disabled = false;
                    sendButton.innerHTML = 'Send';
                    selectedFiles = [];
                    displayFiles();
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Email sent successfully'
                    });
                    bootstrap.Modal.getInstance(document.getElementById('emailModal')).hide();
                })
                .catch(error => {
                    loader.style.display = 'none';
                    sendButton.disabled = false;
                    sendButton.innerHTML = 'Send';
                    Toast.fire({
                        icon: 'error',
                        title: error.message || 'Error sending email'
                    });
                });
        });

        document.getElementById('sendRegistrationEmailBtn').addEventListener('click', function() {
            const form = document.getElementById('registrationEmailForm');
            const formData = new FormData();
            const sendButton = document.getElementById('sendRegistrationEmailBtn');
            const loader = document.getElementById('regEmailLoader');

            // Add form fields manually
            formData.append('request_id', document.getElementById('regRequestId').value);
            formData.append('email', document.getElementById('regEmail').value);
            formData.append('candidate_name', document.getElementById('candidateName').value);
            formData.append('total_fee', document.getElementById('totalFee').value);
            formData.append('payment_deadline', document.getElementById('paymentDeadline').value);

            const ccEmail = document.getElementById('regCc').value.trim();
            if (ccEmail) {
                formData.append('cc_email', ccEmail);
            }

            const subjects = document.getElementById('subjects').value.split('\n').filter(subject => subject.trim() !== '');
            subjects.forEach(subject => formData.append('subjects[]', subject));

            if (subjects.length === 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please enter at least one subject.'
                });
                return;
            }

            // Append all files to FormData if any selected
            if (regSelectedFiles.length > 0) {
                regSelectedFiles.forEach((file, index) => {
                    formData.append('attachments[]', file);
                });
            }

            loader.style.display = 'flex';
            sendButton.disabled = true;
            sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            fetch("{{ route('student-requests.send-exam-registration-email') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Failed to send exam registration email');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    loader.style.display = 'none';
                    sendButton.disabled = false;
                    sendButton.innerHTML = 'Send';
                    regSelectedFiles = []; // Clear selected files after successful send
                    displayRegFiles(); // Update display
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Exam registration email sent successfully'
                    });
                    bootstrap.Modal.getInstance(document.getElementById('registrationEmailModal')).hide();
                    setTimeout(() => {
            window.location.reload();
        }, 1500);
                })
                .catch(error => {
                    loader.style.display = 'none';
                    sendButton.disabled = false;
                    sendButton.innerHTML = 'Send';
                    Toast.fire({
                        icon: 'error',
                        title: error.message || 'Error sending exam registration email'
                    });
                });
        });

        document.getElementById('message').addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                return;
            } else if (event.key === 'Enter' && event.shiftKey) {
                event.preventDefault();
                document.getElementById('sendEmailBtn').click();
            }
        });

        document.getElementById('subjects').addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                return;
            } else if (event.key === 'Enter' && event.shiftKey) {
                event.preventDefault();
                document.getElementById('sendRegistrationEmailBtn').click();
            }
        });
    </script>

    <style>
        .sky-toast {
            border: 1px solid #4fc3f7;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        }

        .sky-toast-title {
            color: #01579b;
            font-weight: 500;
        }

        #emailLoader,
        #regEmailLoader {
            display: none;
            align-items: center;
            justify-content: center;
        }

        #emailLoader .spinner-border,
        #regEmailLoader .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
            border-width: 0.2em;
        }
    </style>
@endsection
