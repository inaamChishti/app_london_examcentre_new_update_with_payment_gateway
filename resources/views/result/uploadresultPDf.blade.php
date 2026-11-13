@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                        <div class="d-flex justify-content-between align-items-center py-2 px-4">
                            <h5 class="mb-0 fw-bold" style="color: #fff;">
                                <i class="fa fa-file-pdf me-2"></i> Upload Results
                            </h5>
                        </div>
                    </div>

                    <div class="card-body p-4" style="background-color: #f8f9fa;">
                        <form action="{{ url('upload-result-pdf') }}" method="POST" enctype="multipart/form-data"
                            class="mb-4">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="candidate_id" class="form-label fw-bold" style="color: #00000;">
                                        <i class="fa fa-user me-2"></i> Candidate ID
                                    </label>
                                    <select id="candidate_id" name="candidate_id" class="form-select" required>
                                        <option value="">Select Candidate ID</option>
                                        @foreach ($data as $item)
                                            <option value="{{ $item->candidate_id }}" data-uci="{{ $item->uci_number }}"
                                                data-name="{{ $item->candidate_name }}">
                                                {{ $item->candidate_id }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('candidate_id')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="uci_number" class="form-label fw-bold" style="color: #00000;">
                                        <i class="fa fa-id-card me-2"></i> UCI Number
                                    </label>
                                    <input type="text" id="uci_number" name="uci_number" class="form-control" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label for="candidate_name" class="form-label fw-bold" style="color: #00000;">
                                        <i class="fa fa-user-circle me-2"></i> Candidate Name
                                    </label>
                                    <input type="text" id="candidate_name" name="candidate_name" class="form-control"
                                        readonly>
                                </div>

                                <div class="col-md-3">
                                    <label for="result_file" class="form-label fw-bold" style="color: #00000;">
                                        <i class="fa fa-upload me-2"></i> Result Files
                                    </label>
                                    <input type="file" id="result_file" name="result_file[]" class="form-control"
                                        accept="application/pdf" multiple required>
                                    @error('result_file')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fa fa-cloud-upload-alt me-2"></i> Upload
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="resultsTable" class="table table-hover align-middle">
                                <thead style="background-color: #57afbfe6; color: white;">
                                    <tr>
                                        <th class="py-3 px-4"><i class="fa fa-user me-2"></i> Candidate ID</th>
                                        <th class="py-3 px-4"><i class="fa fa-id-card me-2"></i> UCI Number</th>
                                        <th class="py-3 px-4"><i class="fa fa-user-circle me-2"></i> Candidate Name</th>
                                        <th class="py-3 px-4"><i class="fa fa-file-pdf me-2"></i> Result Files</th>
                                        <th class="py-3 px-4"><i class="fa fa-cogs me-2"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $candidate_id => $resultGroup)
                                        <tr>
                                            <td class="py-3 px-4">{{ $candidate_id }}</td>
                                            <td class="py-3 px-4">
                                                {{ $resultGroup->first() ? $resultGroup->first()->uci_number : 'N/A' }}</td>
                                            <td class="py-3 px-4">
                                                {{ $resultGroup->first() ? $resultGroup->first()->candidate_name : 'N/A' }}
                                            </td>
                                            {{-- <td class="py-3 px-4">
                                            @foreach ($resultGroup as $result)
                                                @if ($result)
                                                    @php
                                                        $filePath = public_path($result->file);
                                                    @endphp
                                                    @if (file_exists($filePath))
                                                        <a href="#" class="text-primary me-2"
                                                           onclick="openPDF('{{ base64_encode(file_get_contents($filePath)) }}'); return false;">
                                                            <i class="fa fa-eye me-1"></i> View
                                                        </a>
                                                    @else
                                                        <span class="text-danger"><i class="fa fa-exclamation-circle me-1"></i> File not found</span>
                                                    @endif
                                                    <br>
                                                @endif
                                            @endforeach
                                        </td> --}}
                                            <td class="py-3 px-4">
                                                @foreach ($resultGroup as $result)
                                                    @if ($result && !empty($result->file))
                                                        @php
                                                            $filePath = public_path($result->file);
                                                            $encoded = base64_encode($result->file);
                                                        @endphp
                                                        @if (file_exists($filePath))
                                                            <a href="{{ route('file.view', ['encoded' => $encoded]) }}"
                                                                target="_blank" class="text-primary me-2">
                                                                <i class="fa fa-eye me-1"></i> View
                                                            </a>
                                                        @else
                                                            <span class="text-danger"><i
                                                                    class="fa fa-exclamation-circle me-1"></i> File not
                                                                found</span>
                                                        @endif
                                                        <br>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="py-3 px-4">
                                                <a href="{{ url('delete-pdf-data/' . $candidate_id) }}"
                                                    class="btn btn-sm btn-danger delete-result"
                                                    data-id="{{ $candidate_id }}">
                                                    <i class="fa fa-trash me-1"></i> Delete All
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-3 px-4 text-center" style="color: #00000;">
                                                <i class="fa fa-info-circle me-2"></i> No results uploaded yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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

        .table {
            margin: 0 auto;
        }

        .table th {
            font-weight: 600;
            border-bottom: 2px solid #00000;
            white-space: nowrap;
            font-size: 0.9rem;
        }

        .table td {
            border-bottom: 1px solid #b2e4ede6;
            color: #00000;
            font-size: 0.9rem;
        }

        .table-hover tbody tr:hover {
            background-color: #b2e4ede6;
        }

        .btn-primary {
            background-color: #00000;
            border-color: #00000;
        }

        .btn-primary:hover {
            background-color: #138496;
            border-color: #138496;
        }

        .form-select,
        .form-control {
            border-color: #b2e4ede6;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #00000;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        $(document).ready(function() {
            $('#candidate_id').on('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                $('#uci_number').val(selectedOption.getAttribute('data-uci'));
                $('#candidate_name').val(selectedOption.getAttribute('data-name'));
            });

            $('#resultsTable').DataTable({
                paging: true,
                lengthChange: false,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                pageLength: 10
            });

            $('.delete-result').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'All results for this candidate will be deleted permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });


        });

        function openPDF(base64Data) {
            const byteCharacters = atob(base64Data);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], {
                type: 'application/pdf'
            });
            const url = URL.createObjectURL(blob);
            window.open(url, '_blank');
        }
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Toast.fire({
                    icon: 'error',
                    title: '{{ $error }}'
                });
            @endforeach
        @endif
    </script>
@endsection
