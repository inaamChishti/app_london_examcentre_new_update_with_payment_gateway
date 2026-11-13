@extends('layouts.app')

@section('content')
<style>
    .rr-page { padding: 20px; }

    .rr-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .rr-card-header {
        background: #F87A14;
        padding: 18px 25px;
        border-bottom: 4px solid #1976D2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }    .rr-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.2rem;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .session-badge {
        background: rgba(0,0,0,0.15);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .rr-filter-wrap {
        padding: 20px 25px;
        background: #FFF3E0;
        border-bottom: 1px solid #FFCC80;
        display: flex;
        align-items: flex-end;
        gap: 16px;
        flex-wrap: wrap;
    }
    .rr-filter-wrap label {
        font-weight: 600;
        font-size: 0.88rem;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }
    .rr-filter-wrap select {
        min-width: 260px;
        border: 1px solid #FFCC80;
        border-radius: 8px;
        padding: 9px 13px;
        font-size: 0.9rem;
        background: #fff;
        color: #333;
        cursor: pointer;
    }
    .rr-filter-wrap select:focus {
        outline: none;
        border-color: #F87A14;
        box-shadow: 0 0 0 0.2rem rgba(248,122,20,0.3);
    }
    .btn-filter {
        background: #333;
        color: #F87A14;
        border: none;
        border-radius: 8px;
        padding: 9px 22px;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-filter:hover { background: #111; color: #E66A00; }

    .rr-stats-bar {
        padding: 14px 25px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        border-bottom: 1px solid #f0f0f0;
    }
    .stat-chip {
        background: #FFF3E0;
        border: 1px solid #FFCC80;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.8rem;
        color: #555;
        font-weight: 600;
    }
    .stat-chip span { color: #F87A14; font-weight: 700; }

    .rr-table-wrap { padding: 16px 25px 25px; }

    .rr-table {
        width: auto;
        min-width: 500px;
        border-collapse: collapse;
    }
    .rr-table thead th {
        background: #FFF3E0;
        color: #555;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 14px;
        border-bottom: 2px solid #F87A14;
        white-space: nowrap;
    }
    .rr-table tbody tr { transition: background 0.15s; }
    .rr-table tbody tr:hover { background: #FFF3E0; }
    .rr-table tbody td {
        padding: 8px 14px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.88rem;
        color: #444;
        vertical-align: middle;
        white-space: nowrap;
    }
    .rr-table tbody tr:last-child td { border-bottom: none; }

    .rr-empty {
        text-align: center;
        padding: 50px 20px;
        color: #aaa;
    }
    .rr-empty i { font-size: 3rem; color: #F87A14; margin-bottom: 12px; display: block; }
    .rr-empty p { font-size: 1rem; margin: 0; }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #555;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        padding: 7px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        transition: background 0.2s;
    }
    .back-link:hover { background: #f5f5f5; color: #333; text-decoration: none; }
</style>

<div class="rr-page">

    {{-- Back link --}}
    <div style="margin-bottom: 16px;">
        <a href="{{ route('referrals.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Referrals
        </a>
    </div>

    <div class="rr-card">

        {{-- Header --}}
        <div class="rr-card-header">
            <h4>
                <i class="fas fa-chart-bar"></i>
                Referral Report
                @if($currentSession)
                    <span class="session-badge">{{ $currentSession }}</span>
                @endif
            </h4>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('referrals.report') }}">
            <div class="rr-filter-wrap">
                <div>
                    <label for="referralSelect">Select Referral</label>
                    <select name="referral" id="referralSelect" onchange="this.form.submit()">
                        <option value="">-- Choose a referral --</option>
                        @foreach($referrals as $ref)
                            <option value="{{ $ref->name }}"
                                {{ $selectedReferral == $ref->name ? 'selected' : '' }}>
                                {{ $ref->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($selectedReferral)
                    <a href="{{ route('referrals.report') }}" class="back-link" style="margin-bottom:1px;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>

        @if($selectedReferral)

            {{-- Stats bar --}}
            <div class="rr-stats-bar">
                <div class="stat-chip">Referral: <span>{{ $selectedReferral }}</span></div>
                <div class="stat-chip">Session: <span>{{ $currentSession ?? 'N/A' }}</span></div>
                <div class="stat-chip">Total Students: <span>{{ $totalCount }}</span></div>
            </div>

            {{-- Table --}}
            <div class="rr-table-wrap">
                @if($students->isEmpty())
                    <div class="rr-empty">
                        <i class="fas fa-users"></i>
                        <p>No students found for referral <strong>{{ $selectedReferral }}</strong> in the current session.</p>
                    </div>
                @else
                    <table class="rr-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>UCI Number</th>
                                <th>Email</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $i => $student)
                            <tr>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->first_name }} {{ $student->surname }}</td>
                                <td>{{ $student->uci_number ?? '—' }}</td>
                                <td>{{ $student->email ?? '—' }}</td>
                                <td>{{ $student->contact_number ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        @else
            <div class="rr-empty">
                <i class="fas fa-share-alt"></i>
                <p>Select a referral above to see the students associated with it in the current session.</p>
            </div>
        @endif

    </div>
</div>
@endsection
