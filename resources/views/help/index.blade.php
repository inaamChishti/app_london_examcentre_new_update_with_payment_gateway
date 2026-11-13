@extends('layouts.app')

@section('content')
<div class="container">
    <div class="interactive-card" style="background-color: #57afbfd6; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h2 class="text-center" style="color: #fff; font-weight: bold;">
            <i class="fas fa-exclamation-circle" style="color: #309ba3;"></i> Important Instructions for Subject Sheets Import
        </h2>
        <p class="text-center" style="color: #fff; font-size: 18px;">
            While importing subject sheets, the following tasks are <strong>extremely urgent</strong> for the module:
        </p>

        <ul class="list-group mt-3">
            <li class="list-group-item d-flex align-items-center" style="background-color: #ffffffd6; margin-bottom: 10px; border-radius: 5px;">
                <i class="fas fa-calendar-alt" style="color: #28a745; font-size: 20px; margin-right: 10px;"></i>
                <span><strong>Date Format:</strong> Must be in <span style="color: #dc3545;">YYYY/MM/DD</span>, e.g., 2025/11/04</span>
            </li>
            <li class="list-group-item d-flex align-items-center" style="background-color: #ffffffd6; margin-bottom: 10px; border-radius: 5px;">
                <i class="fas fa-layer-group" style="color: #ff5733; font-size: 20px; margin-right: 10px;"></i>
                <span><strong>Tiered Papers:</strong> For tiered papers, the tier column must contain <strong>"Higher Tier"</strong> or <strong>"Foundation Tier"</strong>.</span>
            </li>
            <li class="list-group-item d-flex align-items-center" style="background-color: #ffffffd6; margin-bottom: 10px; border-radius: 5px;">
                <i class="fas fa-file-alt" style="color: #309ba3; font-size: 20px; margin-right: 10px;"></i>
                <span><strong>Paper Names:</strong> Tiered papers must include <strong>"Higher Tier"</strong> or <strong>"Foundation Tier"</strong> in their names.</span>
            </li>
        </ul>

        {{-- <div class="text-center mt-4">
            <a href="http://127.0.0.1:8000/approved-request" class="btn" style="background-color: #309ba3; color: #fff; font-weight: bold; border-radius: 20px; padding: 10px 20px;">
                <i class="fas fa-link"></i> Go to Approved Requests
            </a>
        </div> --}}
    </div>
</div>

<style>
    .list-group-item:hover {
        background-color: #f8f9fa !important;
        transition: background-color 0.3s ease;
    }
    .btn:hover {
        background-color: #787059 !important;
        transition: background-color 0.3s ease;
    }
</style>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
