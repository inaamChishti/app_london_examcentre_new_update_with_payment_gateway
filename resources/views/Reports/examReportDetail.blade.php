@extends('layouts.app')

@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-3px);
    }

    .card-header {
        background: #F87A14;
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        text-align: center;
    }

    .card-header i {
        margin-right: 10px;
        font-size: 1.3rem;
    }

    .card-body {
        padding: 15px;
    }

    .detail-card {
        background: #f9f9f9;
        border: 1px solid #eee;
        height: 100%;
    }

    .detail-card .card-body {
        padding: 10px;
        position: relative; /* Ensures tooltips are positioned relative to card body */
    }

    .detail-card h5 {
        color: #2c3e50;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: normal;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        max-height: 3em;
        position: relative;
    }

    /* Tooltip styling */
    .detail-card h5:hover:after {
        content: attr(data-fullname);
        position: absolute;
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.9rem;
        white-space: nowrap;
        z-index: 10;
        top: calc(100% + 5px); /* Position below the h5 instead of above */
        left: 50%;
        transform: translateX(-50%);
        display: block;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .detail-card .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 5px 0;
        font-size: 0.9rem;
        overflow: hidden;
        white-space: nowrap;
    }

    .detail-card .label {
        color: #57afbf;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
    }

    .detail-card .value {
        overflow: hidden;
        text-overflow: ellipsis;
        flex-grow: 1;
    }

    /* Icon Colors */
    .fa-file-alt { color: #fff; }
    .fa-calendar { color: #fff; }
    .fa-clock { color: #6c757d; }
    .fa-hourglass { color: #e67e22; }
    .fa-graduation-cap { color: #8e44ad; }
    .fa-book { color: #4CAF50; }
    .fa-id-code { color: #2196F3; }
    .detail-card h5 {
    color: #2c3e50;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: normal; /* Allows text to wrap naturally */
    word-wrap: break-word;
    overflow: visible; /* Prevents cropping */
    max-height: none; /* Removes the height restriction */
    position: relative;
}
</style>

<div class="container">
    <div class="card">
        <!-- Header -->
        <div class="card-header">
            <h2><i class="fas fa-file-alt"></i> Exam Report Detail</h2>
            <p>
                <i class="fas fa-calendar"></i> Date: {{ $records->first()->date ?? 'N/A' }} |
                Day: {{ $records->first()->day ?? 'N/A' }}
            </p>
        </div>


        <!-- Body -->
        <div class="card-body">
            <div class="row">
                @foreach($records as $record)
                    <div class="col-md-3 mb-4">
                        <div class="card detail-card h-100">
                            <div class="card-body mt-3">
                               <div></div>
                                <h5 class="card-title " data-fullname="{{ $record->subject_name }}">
                                    <i class="fas fa-book"></i> {{ $record->subject_name }}
                                </h5>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-clock"></i> Session:</span>
                                    <span class="value">{{ $record->exam_session ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-clock"></i> Time:</span>
                                    <span class="value">{{ $record->time }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-hourglass"></i> Duration:</span>
                                    <span class="value">{{ $record->duration }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-graduation-cap"></i> Board:</span>
                                    <span class="value">{{ $record->exam_board }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-book"></i> Course:</span>
                                    <span class="value">{{ $record->course_type }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label"><i class="fas fa-id-code"></i> Code:</span>
                                    <span class="value">{{ $record->subject_code }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($loop->iteration % 4 == 0)
                        </div><div class="row">
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
