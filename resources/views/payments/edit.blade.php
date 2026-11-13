@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                        <h5 class="mb-0 fw-bold" style="color: #fff;">
                            <i class="fas fa-edit me-2"></i> Edit Payment
                        </h5>
                    </div>
                    <div class="card-body p-4" style="background-color: #f8f9fa;">
                        <form action="{{ route('payments.update', $payment->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="session" class="form-label" style="color: #0a0a09;">Session</label>
                                <input type="text" readonly class="form-control" id="session" name="session"
                                    value="{{ $payment->session }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="student_id" class="form-label" style="color: #0a0a09;">Student ID</label>
                                <input type="text" readonly class="form-control" id="student_id" name="student_id"
                                    value="{{ $payment->student_id }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label" style="color: #0a0a09;">Name</label>
                                <input type="text" readonly class="form-control" id="name" name="name"
                                    value="{{ $payment->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_received" class="form-label" style="color: #0a0a09;">Payment
                                    Received</label>
                                <select class="form-control" id="payment_received" name="payment_received" required>
                                    <option value="Yes" {{ $payment->payment_received === 'Yes' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="No" {{ $payment->payment_received === 'No' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount_received" class="form-label" style="color: #0a0a09;">Amount (£)</label>
                                <input type="number" class="form-control" id="amount_received" name="amount_received"
                                    value="{{ $payment->amount_received }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmed_by" class="form-label" style="color: #0a0a09;">Confirmed By</label>
                                <input type="text" class="form-control" id="confirmed_by" name="confirmed_by"
                                    value="{{ $payment->confirmed_by }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label" style="color: #0a0a09;">Payment
                                    Method</label>
                                <input type="text" class="form-control" id="payment_method" name="payment_method"
                                    value="{{ $payment->payment_method }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label" style="color: #0a0a09;">Note</label>
                                <textarea class="form-control" id="note" name="note" rows="3">{{ $payment->note }}</textarea>
                            </div>
                            <div class="mb-3">
                                {{-- <label for="receipt_path" class="form-label" style="color: #F87A14;">Receipt (Current: {{ $payment->receipt_path ? basename($payment->receipt_path) : 'None' }})</label> --}}
                                <input type="file" class="form-control" id="receipt_path" name="receipt_path">
                                {{-- @if ($payment->receipt_path)
                                <a href="{{ asset($payment->receipt_path) }}" target="_blank" class="mt-2 d-block" style="color: #0a0a09;">View Current Receipt</a>
                            @endif --}}
                                @if ($payment->receipt_path)
                                    @php
                                        $encoded = base64_encode($payment->receipt_path);
                                    @endphp
                                    <a href="{{ route('file.view', ['encoded' => $encoded]) }}" target="_blank"
                                        class="mt-2 d-block" style="color: #0a0a09;">View Current Receipt</a>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #F87A14; border-color: #F87A14;">Update Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
