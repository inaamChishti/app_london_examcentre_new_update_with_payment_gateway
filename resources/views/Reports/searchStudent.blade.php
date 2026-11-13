@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Card Container -->
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <div class="d-flex justify-content-between align-items-center py-2 px-4">
                        <h5 class="mb-0 fw-bold" style="color: #fff;">
                            <i class="fas fa-user-graduate me-2"></i> Candidate Details
                        </h5>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4" style="background-color: #f8f9fa;">
                    <!-- Search Box -->
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <div class="input-group" style="max-width: 400px;">
                            <span class="input-group-text" style="background-color: #F87A14; color: white;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Enter Candidate ID...">
                            <button class="btn" id="searchBtn" style="background-color: #F87A14; color: white;">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>

                    <!-- Candidate Details -->
                    <div id="candidateDetails" class="d-none">
                        <div class="row g-4 mb-4">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3" style="color: #F87A14;">
                                            <i class="fas fa-id-card me-2"></i>Personal Information
                                        </h5>
                                        <div class="row <strong>Candidate ID:</strong> <span id="student_id" class="text-primary">-</span></div>
                                        <div class="mb-2"><i class="fas fa-calendar-alt me-2 text-success"></i><strong>Session:</strong> <span id="exam_session" class="text-success">-</span></div>
                                        <div class="mb-2"><i class="fas fa-user me-2 text-warning"></i><strong>Name:</strong> <span id="surname" class="text-warning">-</span> <span id="first_name">-</span></div>
                                        <div class="mb-2"><i class="fas fa-birthday-cake me-2 text-danger"></i><strong>DOB:</strong> <span id="dob" class="text-danger">-</span></div>
                                        <div class="mb-2"><i class="fas fa-venus-mars me-2 text-info"></i><strong>Gender:</strong> <span id="gender" class="text-info">-</span></div>
                                        <div class="mb-2"><i class="fas fa-phone me-2 text-primary"></i><strong>Contact:</strong> <span id="contact_number" class="text-primary">-</span></div>
                                        <div><i class="fas fa-envelope me-2 text-success"></i><strong>Email:</strong> <span id="email" class="text-success">-</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address & Emergency -->
                            <div class="col-md-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3" style="color: #F87A14;">
                                            <i class="fas fa-home me-2"></i>Address & Emergency
                                        </h5>
                                        <div class="mb-2"><i class="fas fa-map-marker-alt me-2 text-warning"></i><strong>Address:</strong> <span id="address_line1" class="text-warning">-</span></div>
                                        <div class="mb-2"><i class="fas fa-map me-2 text-danger"></i><strong>Address Line 2:</strong> <span id="address_line2" class="text-danger">-</span></div>
                                        <div class="mb-2"><i class="fas fa-mail-bulk me-2 text-info"></i><strong>Postcode:</strong> <span id="postcode" class="text-info">-</span></div>
                                        <div class="mb-2"><i class="fas fa-ambulance me-2 text-primary"></i><strong>Emergency Contact:</strong> <span id="emergency_contact" class="text-primary">-</span></div>
                                        <div class="mb-2"><i class="fas fa-id-badge me-2 text-success"></i><strong>ULN:</strong> <span id="uln" class="text-success">-</span></div>
                                        <div class="mb-2"><i class="fas fa-barcode me-2 text-warning"></i><strong>UCI Number:</strong> <span id="uci_number" class="text-warning">-</span></div>
                                        <div><i class="fas fa-notes-medical me-2 text-danger"></i><strong>Medical Condition:</strong> <span id="medical_condition" class="text-danger">-</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Examinations & Documents -->
                        <div class="row g-4">
                            <!-- Examinations & Access -->
                            <div class="col-md-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3" style="color: #F87A14;">
                                            <i class="fas fa-book me-2"></i>Examinations & Access
                                        </h5>
                                        <div class="mb-2"><i class="fas fa-universal-access me-2 text-info"></i><strong>Access Arrangement:</strong> <span id="access_arrangement" class="text-info">-</span></div>
                                        <div class="mb-2"><i class="fas fa-comment me-2 text-primary"></i><strong>Explanation:</strong> <span id="access_arrangement_explanation" class="text-primary">-</span></div>
                                        <div class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i><strong>Practical Endorsement:</strong> <span id="practical_endorsement" class="text-success">-</span></div>
                                        <div class="mb-2"><i class="fas fa-file-alt me-2 text-warning"></i><strong>Coursework UCI:</strong> <span id="course_work_uci" class="text-warning">-</span></div>
                                        <div><i class="fas fa-tasks me-2 text-danger"></i><strong>Coursework:</strong> <span id="course_work" class="text-danger">-</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents -->
                            {{-- <div class="col-md-6">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3" style="color: #F87A14;">
                                            <i class="fas fa-folder-open me-2"></i>Documents
                                        </h5>
                                        <div class="mb-3"><i class="fas fa-camera me-2 text-primary"></i><strong>Photo:</strong>
                                            <a id="photoLink" target="_blank">
                                                <img id="photo" class="img-thumbnail" style="max-width: 50px;">
                                            </a>
                                        </div>
                                        <div class="mb-3"><i class="fas fa-id-card me-2 text-success"></i><strong>ID Document:</strong>
                                            <a id="idDocumentLink" target="_blank">
                                                <img id="id_document" class="img-thumbnail" style="max-width: 50px;">
                                            </a>
                                        </div>
                                        <div class="mb-3"><i class="fas fa-file-pdf me-2 text-warning"></i><strong>Supporting Documents:</strong>
                                            <a id="supportingDocumentsLink" target="_blank">
                                                <img id="supporting_documents" class="img-thumbnail" style="max-width: 50px;">
                                            </a>
                                        </div>
                                        <div><i class="fas fa-files me-2 text-danger"></i><strong>Additional Documents:</strong>
                                            <div id="additionalDocsContainer" class="d-flex flex-wrap gap-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                             <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color: #F87A14;">
                        <i class="fas fa-folder-open me-2"></i>Documents
                    </h5>
                    <div id="documentsContainer">
                        <div class="mb-3">
                            <i class="fas fa-camera me-2 text-primary"></i><strong>Photo:</strong>
                            <a id="photoLink" target="_blank">
                                <img id="photo" class="img-thumbnail" style="max-width: 50px;" alt="Photo">
                            </a>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-id-card me-2 text-success"></i><strong>ID Document:</strong>
                            <a id="idDocumentLink" target="_blank">
                                <img id="id_document" class="img-thumbnail" style="max-width: 50px;" alt="ID Document">
                            </a>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-file-pdf me-2 text-warning"></i><strong>Supporting Documents:</strong>
                            <a id="supportingDocumentsLink" target="_blank">
                                <img id="supporting_documents" class="img-thumbnail" style="max-width: 50px;" alt="Supporting Documents">
                            </a>
                        </div>
                        <div>
                            <i class="fas fa-files me-2 text-danger"></i><strong>Additional Documents:</strong>
                            <div id="additionalDocsContainer" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                    <div id="noDocuments" class="text-center p-3" style="display: none;">
                        <i class="fas fa-folder-open" style="font-size: 1.5rem; color: #6c757d;"></i>
                        <p class="text-muted mt-2" style="font-size: 0.85rem;">No documents uploaded</p>
                    </div>
                </div>
            </div>
        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        background-color: #ffffff;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(23, 162, 184, 0.2) !important;
    }
    .card-body {
        color: #333;
    }
    .input-group-text, .btn {
        transition: background-color 0.3s ease;
    }
    .input-group-text:hover, .btn:hover {
        background-color: #F87A14 !important;
    }
    .img-thumbnail {
        border: 1px solid #F87A14;
        transition: transform 0.2s ease;
    }
    .img-thumbnail:hover {
        transform: scale(1.1);
    }
</style>

<script>
    $(document).ready(function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function formatDate(dateString) {
            if (!dateString) return 'null';
            let date = new Date(dateString);
            if (isNaN(date)) return 'Invalid Date';
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function fetchCandidateData() {
            let candidateID = $("#searchInput").val().trim();
            if (!candidateID) {
                Toast.fire({ icon: 'warning', title: 'Please enter a Candidate ID!' });
                return;
            }

            Toast.fire({ icon: 'info', title: 'Fetching Data... Please wait...', timer: 0 });

            $.ajax({
                url: "{{ url('get-candidate-data') }}/" + candidateID,
                type: "GET",
                success: function (response) {
                    Swal.close();
                    if (!response || Object.keys(response).length === 0) {
                        Toast.fire({ icon: 'error', title: 'No candidate found.' });
                        return;
                    }

                    $("#candidateDetails").removeClass('d-none');

                    $("#student_id").text(response.student_id ?? 'null');
                    $("#exam_session").text(response.exam_session ?? 'null');
                    $("#surname").text(response.surname ?? 'null');
                    $("#first_name").text(response.first_name ?? 'null');
                    $("#dob").text(formatDate(response.dob));
                    $("#gender").text(response.gender ?? 'null');
                    $("#contact_number").text(response.contact_number ?? 'null');
                    $("#email").text(response.email ?? 'null');
                    $("#address_line1").text(response.address_line1 ?? 'null');
                    $("#address_line2").text(response.address_line2 ?? 'null');
                    $("#postcode").text(response.postcode ?? 'null');
                    $("#emergency_contact").text(response.emergency_contact ?? 'null');
                    $("#uln").text(response.uln ?? 'null');
                    $("#uci_number").text(response.uci_number ?? 'null');
                    $("#medical_condition").text(response.medical_condition ?? 'null');
                    $("#access_arrangement").text(response.access_arrangement ?? 'null');
                    $("#access_arrangement_explanation").text(response.access_arrangement_explanation ?? 'null');
                    $("#practical_endorsement").text(response.practical_endorsement ?? 'null');
                    $("#course_work_uci").text(response.course_work_uci ?? 'null');
                    $("#course_work").text(response.course_work ?? 'null');

                    updateImage("#photo", response.photo_path);
                    updateImage("#id_document", response.id_document_path);
                    updateImage("#supporting_documents", response.supporting_documents_path);

                    let additionalDocs = response.additional_docs_paths ? JSON.parse(response.additional_docs_paths) : [];
                    let additionalDocsContainer = $("#additionalDocsContainer").empty();
                    if (additionalDocs.length > 0) {
                        additionalDocs.forEach(doc => {
                            let img = $("<img>")
                                .attr("src", "{{ asset('') }}" + doc)
                                .addClass("img-thumbnail")
                                .css("max-width", "100px")
                                .css("cursor", "pointer")
                                .click(function () { window.open($(this).attr("src"), "_blank"); });
                            additionalDocsContainer.append(img);
                        });
                    } else {
                        additionalDocsContainer.append("<p class='text-muted'><i class='fas fa-info-circle me-2'></i>No additional documents found.</p>");
                    }

                    Toast.fire({ icon: 'success', title: 'Candidate data loaded successfully!' });
                },
                error: function () {
                    Swal.close();
                    Toast.fire({ icon: 'error', title: 'No student found in this session.' });
                }
            });
        }

        function updateImage(selector, imageUrl) {
            let defaultImg = "{{ asset('images/no-image.png') }}";
            let imgTag = $(selector);
            if (imageUrl) {
                let fullPath = "{{ asset('') }}" + imageUrl;
                imgTag.attr("src", fullPath)
                      .css("cursor", "pointer")
                      .off("click")
                      .click(function () { window.open(fullPath, "_blank"); });
            } else {
                imgTag.attr("src", defaultImg);
            }
        }

        $("#searchBtn").click(function () { fetchCandidateData(); });
        $("#searchInput").keypress(function (event) {
            if (event.which === 13) {
                event.preventDefault();
                fetchCandidateData();
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                Toast.fire({ icon: 'error', title: '{{ $error }}' });
            @endforeach
        @endif
    });
</script>
@endsection
