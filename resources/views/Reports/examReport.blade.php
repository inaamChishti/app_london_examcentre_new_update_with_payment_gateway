@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .header {
        background: #F87A14;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        color: white;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 4px solid #1976D2;
    }

    .header i {
        margin-right: 10px;
        font-size: 1.3rem;
        color: #ffffff;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-3px);
    }

    .card-body {
        padding: 15px;
        text-align: center;
    }

    .rank-1 { color: #4CAF50; }
    .rank-2 { color: #2196F3; }
    .rank-3 { color: #FFC107; }

    .date-picker {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px;
        width: 100%;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
        display: none;
    }

    .table th, .table td {
        padding: 10px;
        border: 1px solid #eee;
    }

    .table th {
        background: #F87A14;
        color: white;
    }

    .table tr:nth-child(even) {
        background: #f9f9f9;
    }

    /* Icon Colors */
    .fa-trophy { color: #4CAF50; }
    .fa-medal { color: #2196F3; }
    .fa-award { color: #F87A14; }
    .fa-calendar { color: #F87A14; }
    .fa-clock { color: #6c757d; }
    .fa-book { color: #8e44ad; }
    .fa-users { color: #e67e22; }
</style>

<div class="container">
    <!-- Header -->
    <div class="header">
        <i class="fas fa-chart-bar"></i>
        <h3>Exam Reports</h3>
    </div>

    <!-- Cards -->
    <div class="row">
        @foreach($highestRecords as $index => $paper)
            <div class="col-md-4">
                <a href="{{ route('exam.report.detail', [
                    'day' => \Carbon\Carbon::parse($paper['date'])->format('d'),
                    'month' => \Carbon\Carbon::parse($paper['date'])->format('m'),
                    'year' => \Carbon\Carbon::parse($paper['date'])->format('Y')
                ]) }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="rank-{{ $index + 1 }}">
                                <i class="fas @if($index == 0) fa-trophy @elseif($index == 1) fa-medal @else fa-award @endif"></i>
                                {{ $index == 0 ? 'Highest' : ($index == 1 ? '2nd' : '3rd') }}
                            </h4>
                            <p>
                                <i class="fas fa-calendar"></i>
                                Date: {{ isset($paper['date']) ? \Carbon\Carbon::parse($paper['date'])->format('d/m/Y') : 'N/A' }}
                            </p>

                            <p>
                                AM: {{ $paper['am_count'] ?? 0 }} |
                                PM: {{ $paper['pm_count'] ?? 0 }} |
                                Total: {{ $paper['total_count'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Date Picker -->
    <div class="date-picker">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <label><i class="fas fa-calendar"></i> Date:</label>
                <input type="date" id="datetimepicker" class="form-control">
            </div>
            <div class="col-md-6">
                <label><i class="fas fa-clock"></i> Time:</label>
                <select id="timepicker" class="form-control">
                    <option disabled selected>Select</option>
                    <option value="am">AM</option>
                    <option value="pm">PM</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <table class="table">
        <thead>
            <tr>
                <th><i class="fas fa-book"></i> Subject</th>
                <th><i class="fas fa-university"></i> Exam Board</th>
                <th><i class="fas fa-users"></i> Students</th>
            </tr>
        </thead>
        <tbody id="resultTableBody"></tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <td></td>
                <td id="grandTotal"></td>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const fetchData = () => {
        const date = $('#datetimepicker').val();
        const time = $('#timepicker').val();

        if (!date || !time) return;

        $.get('{{ url('get-details') }}', { date, time })
            .done(data => {
                const $tbody = $('#resultTableBody').empty();
                let total = 0;

                // Convert to array and sort alphabetically by exam_board
                const rows = Object.entries(data).map(([subject, info]) => ({
                    subject,
                    count:     typeof info === 'object' ? info.count      : info,
                    examBoard: typeof info === 'object' ? (info.exam_board || '') : '',
                }));

                rows.sort((a, b) => a.examBoard.localeCompare(b.examBoard));

                rows.forEach(({ subject, count, examBoard }) => {
                    $tbody.append(`
                        <tr>
                            <td>${subject}</td>
                            <td>${examBoard}</td>
                            <td>${count}</td>
                        </tr>
                    `);
                    total += parseInt(count);
                });

                $('#grandTotal').text(total);
                $('.table').show();
            })
            .fail(() => $('.table').hide());
    };

    $('#datetimepicker, #timepicker').on('change', fetchData);
});
</script>
@endsection
