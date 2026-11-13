@extends('layouts.app')

@section('content')

    <div class="container-fluid" style="padding: 20px;">
        @if (session('success'))
            <script>
                console.log('Session success message: {{ session('success') }}');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                });
            </script>
        @endif
        <div style="display: flex; flex-wrap: wrap; margin-right: -10px; margin-left: -10px;">
            <div style="flex: 0 0 100%; max-width: 100%; padding-right: 10px; padding-left: 10px;">
                <div
                    style="position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: none; border-radius: 10px; box-shadow: 0 0.2rem 2rem 0 rgba(58, 59, 69, 0.2); margin-bottom: 20px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div
                        style="padding: 15px 20px; background-color: #F87A14; border-bottom: 4px solid #1976D2; display: flex; justify-content: space-between; align-items: center; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h6 style="margin: 0; font-weight: 700; font-size: 1.2rem; color: #fff;">
                            <i class="fas fa-user-graduate" style="margin-right: 8px; color: #ffff;"></i> Approved Students
                        </h6>
                        <div class="mb-3">
                            <a href="{{ url('export-all-timetables') }}" class="btn btn-danger">Export All Students</a>
                        </div>
                    </div>

                    <!-- Search inputs and Dropdown for filtering -->
                    <div style="padding: 15px 20px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <div style="display: flex; flex-wrap: wrap; margin-right: -10px; margin-left: -10px;">
                            <div style="flex: 0 0 50%; max-width: 50%; padding-right: 10px; padding-left: 10px;">
                                <input type="text" id="searchByName" class="form-control" placeholder="Search by Name"
                                    style="width: 100%;">
                            </div>
                            <div style="flex: 0 0 50%; max-width: 50%; padding-right: 10px; padding-left: 10px;">
                                <input type="text" id="searchById" class="form-control"
                                    placeholder="Search by Student ID" style="width: 100%;">
                            </div>
                        </div>
                    </div>

                    <div style="flex: 1 1 auto; padding: 15px;">
                        <div style="overflow-x: auto;">
                            <table id="studentRequestTable"
                                style="width: 100%; font-size: 0.9rem; border-collapse: collapse;">
                                <thead style="background-color: #F87A14; color: #fff;">
                                    <tr>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Student ID</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Name</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Withdraw Candidate</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Date of Birth</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Gender</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Session</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Documents</th>
                                        <th
                                            style="padding: 10px 15px; font-size: 0.9rem; font-weight: 600; text-align: left;">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr id="request-row-{{ $student->student_id }}"
                                            style="transition: background-color 0.3s ease;">
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                                {{ $student->student_id ?? 'N/A' }}
                                            </td>
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                                {{ $student->first_name }} {{ $student->surname }}
                                            </td>
                                            {{-- {{dd($student->first_name)}} --}}
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                                <select name="exam_withdrawal"
                                                    onchange="handleWithdrawal(this, '{{ $student->student_id ?? '' }}')"
                                                    class="form-control form-control-sm">
                                                    <option disabled selected>Decision</option>
                                                    <option value="withdraw"
                                                        {{ str_ends_with($student->first_name, ' - withdrawn') ? 'selected' : '' }}>
                                                        Withdraw
                                                    </option>
                                                    <option value="no_withdrawal">Undo</option>
                                                    <option value="migrate">Migrate</option>
                                                    <option value="unapprove">Unapprove</option>
                                                </select>
                                            </td>
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                                {{ \Carbon\Carbon::parse($student->dob)->format('d M Y') }}
                                            </td>
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                                {{ ucfirst($student->gender) }}
                                            </td>
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                                {{ $student->exam_session }}
                                            </td>
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6; zoom: 0.7;">
                                                @php
                                                    $documents = [
                                                        'Photo' => $student->photo_path ?? null,
                                                        'ID Document' => $student->id_document_path ?? null,
                                                        'Supporting Documents' =>
                                                            $student->supporting_documents_path ?? null,
                                                        'Additional Docs' => $student->additional_docs_paths ?? [],
                                                    ];
                                                    $allEmpty = collect($documents)->every(function ($doc) {
                                                        return is_null($doc) || (is_array($doc) && empty($doc));
                                                    });
                                                @endphp
                                                @if ($allEmpty)
                                                    <div style="text-align: center; padding: 10px;">
                                                        <i class="fas fa-folder-open"
                                                            style="font-size: 1.5rem; color: #6c757d;"></i>
                                                        <p style="color: #6c757d; font-size: 0.75rem; margin: 5px 0 0;">No
                                                            documents uploaded</p>
                                                    </div>
                                                @else
                                                    <div class="border p-2 rounded"
                                                        style="background-color: #f8f9fa; max-width: 200px;">
                                                        @foreach ($documents as $label => $doc)
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-1">
                                                                <div style="flex: 1; min-width: 100px;">
                                                                    <strong
                                                                        style="font-size: 0.75rem;">{{ $label }}</strong>
                                                                </div>
                                                                <div>
                                                                    @if (!is_null($doc))
                                                                        @if (is_array($doc))
                                                                            @if (count($doc) > 0)
                                                                                @foreach ($doc as $index => $file)
                                                                                    @php
                                                                                        $encoded = base64_encode($file);
                                                                                    @endphp
                                                                                    <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                                                                        target="_blank"
                                                                                        class="btn btn-link p-0"
                                                                                        style="font-size: 0.75rem;">View
                                                                                        {{ $index + 1 }}</a><br>
                                                                                @endforeach
                                                                            @else
                                                                                <span class="text-muted"
                                                                                    style="font-size: 0.75rem;">None</span>
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $encoded = base64_encode($doc);
                                                                            @endphp
                                                                            <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                                                                target="_blank" class="btn btn-link p-0"
                                                                                style="font-size: 0.75rem;">View</a>
                                                                        @endif
                                                                    @else
                                                                        <span class="text-muted"
                                                                            style="font-size: 0.75rem;">None</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if (!$loop->last)
                                                                <hr class="my-1" style="border-top: 1px solid #dee2e6;">
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td
                                                style="padding: 10px 15px; vertical-align: middle; font-size: 0.85rem; border-bottom: 1px solid #dee2e6;">
                                                <div style="display: flex; gap: 6px;">
                                                    <a href="{{ url('view-request/' . $student->student_id) }}"
                                                        title="View"
                                                        style="background-color: #17a2b8; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                                                        <i class="fas fa-eye" style="font-size: 0.8rem;"></i>
                                                    </a>
                                                    <a href="{{ url('modify-approved-student/' . $student->student_id) }}"
                                                        title="Edit"
                                                        style="background-color: #ffc107; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                                                        <i class="fas fa-edit" style="font-size: 0.8rem;"></i>
                                                    </a>
                                                    <button
                                                        onclick="openEmailModal('{{ $student->student_id }}', '{{ $student->email }}')"
                                                        title="Send Email"
                                                        style="background-color: #007bff; border: none; padding: 0.3rem 0.6rem; border-radius: 0.25rem; color: white; transition: transform 0.2s ease, background-color 0.2s ease;">
                                                        <i class="fas fa-envelope" style="font-size: 0.8rem;"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Modal -->
        <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailModalLabel">Send Email</h5>
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
                        <!-- Loader -->
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Define Toast with sky-themed styling
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#e3f2fd', // Light blue "sky" background
            iconColor: '#0288d1', // Blue icon
            customClass: {
                popup: 'sky-toast',
                title: 'sky-toast-title'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        $(document).ready(function() {
            // Search by Student ID
            $('#searchById').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $("#studentRequestTable tbody tr").filter(function() {
                    $(this).toggle($(this).find('td:eq(0)').text().toLowerCase().indexOf(value) > -
                        1);
                });
            });

            // Search by Student Name
            $('#searchByName').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $("#studentRequestTable tbody tr").filter(function() {
                    $(this).toggle($(this).find('td:eq(1)').text().toLowerCase().indexOf(value) > -
                        1);
                });
            });
        });

        function handleWithdrawal(selectElement, studentId) {
            const selectedValue = selectElement.value;

            if (selectedValue === 'withdraw') {
                const userConfirmed = confirm("Are you sure you want to withdraw this candidate?");
                if (userConfirmed) {
                    window.location.href = "{{ url('widthraw-student/') }}" + '/' + studentId;
                } else {
                    selectElement.selectedIndex = 0;
                }
            } else if (selectedValue === 'no_withdrawal') {
                window.location.href = "{{ url('undo-student/') }}" + '/' + studentId;
            } else if (selectedValue === 'migrate') {
                handleMigration(studentId, selectElement);
            } else if (selectedValue === 'unapprove') {
                handleUnapprove(studentId, selectElement);
            }
        }

        // New Migration Handler
        function handleMigration(studentId, selectElement) {
            const userConfirmed = confirm("Are you sure you want to migrate this candidate?");
            if (userConfirmed) {
                window.location.href = "{{ url('migrate-student/') }}" + '/' + studentId;
            } else {
                selectElement.selectedIndex = 0;
            }
        }

        // Unapprove Handler
        function handleUnapprove(studentId, selectElement) {
            const userConfirmed = confirm("Are you sure you want to unapprove this candidate? This will delete all student details for the current session and set the request status to 0.");
            if (userConfirmed) {
                window.location.href = "{{ url('unapprove-student/') }}" + '/' + studentId;
            } else {
                selectElement.selectedIndex = 0;
            }
        }


        // Global array to store all selected files
        let selectedFiles = [];

        // Open Email Modal
        function openEmailModal(requestId, email) {
            document.getElementById('requestId').value = requestId;
            document.getElementById('email').value = email;
            document.getElementById('subject').value = '';
            document.getElementById('message').value = '';
            document.getElementById('attachment').value = ''; // Clear file input
            selectedFiles = []; // Clear selected files array
            document.getElementById('fileList').style.display = 'none'; // Hide file list
            document.getElementById('fileListItems').innerHTML = ''; // Clear file list items
            document.getElementById('emailLoader').style.display = 'none'; // Hide loader when modal opens
            const emailModal = new bootstrap.Modal(document.getElementById('emailModal'));
            emailModal.show();
        }

        // Function to display all selected files
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

        // Function to remove a file
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            displayFiles();
        }

        // Display selected files - add to existing array instead of replacing
        document.getElementById('attachment').addEventListener('change', function() {
            const newFiles = Array.from(this.files);
            
            // Add new files to the existing array
            newFiles.forEach(file => {
                // Check if file already exists (by name and size to avoid duplicates)
                const exists = selectedFiles.some(existingFile => 
                    existingFile.name === file.name && existingFile.size === file.size
                );
                if (!exists) {
                    selectedFiles.push(file);
                }
            });

            // Clear the input so user can select more files
            this.value = '';
            
            // Display all files
            displayFiles();
        });

        // Handle Email Form Submission
        document.getElementById('sendEmailBtn').addEventListener('click', function() {
            const form = document.getElementById('emailForm');
            const formData = new FormData();
            const sendButton = document.getElementById('sendEmailBtn');
            const loader = document.getElementById('emailLoader');

            // Add form fields manually (excluding file input)
            formData.append('request_id', document.getElementById('requestId').value);
            formData.append('email', document.getElementById('email').value);
            formData.append('subject', document.getElementById('subject').value);
            formData.append('message', document.getElementById('message').value);

            // Validate PDF files (if attached) - use selectedFiles array
            if (selectedFiles.length > 0) {
                for (let i = 0; i < selectedFiles.length; i++) {
                    const file = selectedFiles[i];
                    if (file.type !== 'application/pdf') {
                        Toast.fire({
                            icon: 'error',
                            title: 'Please upload only valid PDF files. Invalid file: ' + file.name
                        });
                        return;
                    }
                }

                // Append all files to FormData
                selectedFiles.forEach((file, index) => {
                    formData.append('attachments[]', file);
                });
            }

            // Show loader and disable button
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
                    selectedFiles = []; // Clear selected files after successful send
                    displayFiles(); // Update display
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

        // Handle Enter key in textarea
        document.getElementById('message').addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                // Allow default behavior (new line) in textarea
                return;
            } else if (event.key === 'Enter' && event.shiftKey) {
                // Shift+Enter triggers form submission
                event.preventDefault();
                document.getElementById('sendEmailBtn').click();
            }
        });
    </script>



    <style>
        .sky-toast {
            border: 1px solid #4fc3f7;
            /* Sky blue border */
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
            /* Subtle blue shadow */
        }

        .sky-toast-title {
            color: #01579b;
            /* Darker blue for text */
            font-weight: 500;
        }

        #emailLoader {
            display: none;
            align-items: center;
            justify-content: center;
        }

        #emailLoader .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
            border-width: 0.2em;
        }
    </style>
@endsection
