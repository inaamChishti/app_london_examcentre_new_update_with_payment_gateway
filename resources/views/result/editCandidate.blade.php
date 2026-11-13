@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                    <h5 class="mb-0 fw-bold py-2 px-4" style="color: #fff;">
                        <i class="fas fa-user-edit me-2"></i> Edit Candidate
                    </h5>
                </div>

                <div class="card-body p-4" style="background-color: #f8f9fa;">
                    <form method="POST" action="{{ route('candidates.update', $candidate->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" style="color: #00000;">
                                <i class="fas fa-id-card me-2"></i> Candidate ID
                            </label>
                            <input readonly type="text" name="candidate_id" class="form-control @error('candidate_id') is-invalid @enderror"
                                   value="{{ old('candidate_id', $candidate->candidate_id) }}" required>
                            @error('candidate_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: #00000;">
                                <i class="fas fa-user me-2"></i> Candidate Name
                            </label>
                            <input type="text" name="candidate_name" class="form-control @error('candidate_name') is-invalid @enderror"
                            readonly  value="{{ old('candidate_name', $candidate->candidate_name) }}" required>
                            @error('candidate_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: #00000;">
                                <i class="fas fa-envelope me-2"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $candidate->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: #00000;">
                                <i class="fas fa-hashtag me-2"></i> UCI Number
                            </label>
                            <input type="text" name="uci_number" class="form-control @error('uci_number') is-invalid @enderror"
                                   value="{{ old('uci_number', $candidate->uci_number) }}" required>
                            @error('uci_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="color: #00000;">
                                <i class="fas fa-calendar me-2"></i> Session
                            </label>
                            <input type="text" name="session" class="form-control @error('session') is-invalid @enderror"
                            readonly    value="{{ old('session', $candidate->session) }}" required>
                            @error('session')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('view.sheet.data') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn " style="background-color: #F87A14; border-color: #F87A14s;">
                                <i class="fas fa-save"></i> Update Candidate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(23, 162, 184, 0.2) !important;
    }
    .form-control:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        color: #dc3545;
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

        // Display session success message
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        // Display validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
</script>
@endsection
