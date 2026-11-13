@extends('layouts.app')

@section('content')
<style>
    .card-header {
        background: #F87A14 !important;
        color: #fff;
        transition: all 0.3s ease-in-out;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 25px;
        border-bottom: 4px solid #1976D2;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .card {
        background-color: #FFFDE7 !important;
        border: none;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    .card-header h3 {
        font-weight: 700;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.25);
        margin: 0;
        font-size: 2rem;
        color: #fff;
        letter-spacing: 0.5px;
    }
    .logo-container {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .frobel-logo {
        width: 332px;
        height: 205px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .frobel-logo:hover {
        transform: scale(1.05);
    }
    .exam-centre-logo {
        max-width: 300px;
        object-fit: contain;
        transition: transform 0.3s ease;
        margin-top: -59px;
    }
    .exam-centre-logo:hover {
        transform: scale(1.05);
    }
    .branding-text {
        font-size: 1.2rem;
        color: #F87A14;
        font-weight: 600;
        margin-top: 12px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .card-body {
        padding: 30px;
        background: #FFFFFF;
        border-radius: 0 0 15px 15px;
    }
    .welcome-text {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.6;
        text-align: center;
        max-width: 700px;
        margin: 0 auto;
    }
    .dashboard-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    .btn-action {
        background-color: #F87A14;
        color: #333;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-decoration: none;
    }
    .btn-action:hover {
        background-color: #E86A00;
        transform: translateY(-2px);
    }
    .widget-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .widget {
        background-color: #FFF;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .widget:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .widget-title {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 15px;
        font-weight: 600;
        border-bottom: 2px solid #F87A14;
        padding-bottom: 8px;
    }
    .widget-value {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        text-align: center;
        margin: 15px 0;
    }
    .exam-date-widget {
        grid-column: span 2;
        background-color: #FFF8E1;
    }
    .access-arrangement-wide {
        grid-column: span 2;
    }
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
        margin-top: 15px;
    }
    .progress-container {
        width: 100%;
        background-color: #f1f1f1;
        border-radius: 10px;
        margin: 15px 0;
    }
    .progress-bar {
        height: 20px;
        border-radius: 10px;
        background-color: #F87A14;
        text-align: center;
        line-height: 20px;
        color: #333;
        font-weight: bold;
        transition: width 0.5s ease;
    }
    .countdown-timer {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        text-align: center;
        margin: 15px 0;
        padding: 10px;
        background-color: #FFF3E0;
        border-radius: 8px;
    }
    @media (max-width: 768px) {
        .frobel-logo {
            width: 200px;
            height: 123px;
        }
        .exam-centre-logo {
            max-width: 200px;
            margin-top: -8px;
        }
        .card-header {
            padding: 15px;
            gap: 10px;
        }
        .card-header h3 {
            font-size: 1.6rem;
        }
        .branding-text {
            font-size: 1rem;
        }
        .card-body {
            padding: 20px;
        }
        .welcome-text {
            font-size: 1rem;
        }
        .logo-container {
            gap: 10px;
        }
        .widget-container {
            grid-template-columns: 1fr;
        }
        .exam-date-widget {
            grid-column: span 1;
        }
        .access-arrangement-wide {
            grid-column: span 1;
        }
    }
    @media (max-width: 576px) {
        .frobel-logo {
            width: 150px;
            height: 92px;
        }
        .exam-centre-logo {
            max-width: 150px;
            margin-top: -6px;
        }
        .card-header h3 {
            font-size: 1.4rem;
        }
        .branding-text {
            font-size: 0.9rem;
        }
    }
</style>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm mt-5">
                <div class="card-header">
                    <h3>Examination Dashboard</h3>
                </div>
                <div class="card-body">
                    <!-- Widgets Section -->
                    <div class="widget-container">
                        <!-- Student Registration Widget with Chart -->
                        <div class="widget">
                            <div class="widget-title">Student Registrations</div>
                            <div class="widget-value">{{ isset($totalStudents['total_students']) ? $totalStudents['total_students'] : '0' }}</div>
                            <div class="progress-container">
                                {{-- <div class="progress-bar" style="width: 100%">100% of target</div> --}}
                            </div>
                            <div class="chart-container">
                                <canvas id="registrationsChart"></canvas>
                            </div>
                        </div>

                        <!-- Withdrawal Widget -->
                        <div class="widget">
                            <div class="widget-title">Student Withdrawals</div>
                            <div class="widget-value">{{ isset($withdrawnStudents['withdrawn_students']) ? $withdrawnStudents['withdrawn_students'] : '0' }}</div>
                            <div class="chart-container">
                                <canvas id="withdrawalsChart"></canvas>
                            </div>
                        </div>

                        <!-- Access Arrangement Widget -->
                        <div class="widget access-arrangement-wide">
                            <div class="widget-title">Access Arrangement</div>
                            <div class="widget-value">{{ isset($accessArrangementStudents) ? $accessArrangementStudents : '0' }}</div>
                            <div class="chart-container">
                                <canvas id="accessArrangementChartSecondary"></canvas>
                            </div>
                        </div>

                        <!-- Exam Date Widget with Countdown -->
                        <div class="widget exam-date-widget">
                            <div class="widget-title">Next Exam Date</div>
                            <div class="widget-value">{{ $nextExamDate ? $nextExamDate->format('d/m/Y') : 'No upcoming exams' }}</div>
                            <div class="countdown-timer" id="countdown"></div>
                            <div style="display: flex; justify-content: space-around; margin-top: 20px;">
                                <div>
                                    <div style="font-weight: 600; color: #555;">Morning Session</div>
                                    <div style="font-size: 1.5rem; font-weight: 600; text-align: center;">{{ isset($amStudentsCount) ? $amStudentsCount : '0' }}</div>
                                    <div class="chart-container" style="height: 100px;">
                                        <canvas id="morningChart"></canvas>
                                    </div>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #555;">Afternoon Session</div>
                                    <div style="font-size: 1.5rem; font-weight: 600; text-align: center;">{{ isset($pmStudentsCount) ? $pmStudentsCount : '0' }}</div>
                                    <div class="chart-container" style="height: 100px;">
                                        <canvas id="afternoonChart"></canvas>
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

<script>
    // Pass PHP data to JavaScript for dynamic widgets with null checks
    const totalStudents = {{ isset($totalStudents['total_students']) ? $totalStudents['total_students'] : 0 }};
    const withdrawnStudents = {{ isset($withdrawnStudents['withdrawn_students']) ? $withdrawnStudents['withdrawn_students'] : 0 }};
    const amStudentsCount = {{ isset($amStudentsCount) ? $amStudentsCount : 0 }};
    const pmStudentsCount = {{ isset($pmStudentsCount) ? $pmStudentsCount : 0 }};
    const accessArrangementStudents = {{ isset($accessArrangementStudents) ? $accessArrangementStudents : 0 }};
    const nextExamDate = "{{ $nextExamDate ? $nextExamDate->format('Y-m-d') : '' }}";

    // Registration Trend Chart (Dynamic)
    const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsChart = new Chart(registrationsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Monthly Registrations',
                data: totalStudents ? [
                    totalStudents * 0.1,
                    totalStudents * 0.15,
                    totalStudents * 0.2,
                    totalStudents * 0.25,
                    totalStudents * 0.3,
                    totalStudents
                ] : [0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(248, 122, 20, 0.2)',
                borderColor: 'rgba(248, 122, 20, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Withdrawals Pie Chart (Dynamic)
    const withdrawalsCtx = document.getElementById('withdrawalsChart').getContext('2d');
    const withdrawalsChart = new Chart(withdrawalsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Withdrawn', 'Active'],
            datasets: [{
                data: [withdrawnStudents || 0, (totalStudents || 0) - (withdrawnStudents || 0)],
                backgroundColor: [
                    'rgba(248, 122, 20, 0.8)',
                    'rgba(232, 106, 0, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });

    // Access Arrangement Chart (Secondary)
    const accessArrangementSecondaryCtx = document.getElementById('accessArrangementChartSecondary').getContext('2d');
    const accessArrangementSecondaryChart = new Chart(accessArrangementSecondaryCtx, {
        type: 'bar',
        data: {
            labels: ['Students'],
            datasets: [{
                label: 'Access Arrangement',
                data: [accessArrangementStudents || 0],
                backgroundColor: 'rgba(248, 122, 20, 0.85)',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Morning Session Chart (Dynamic)
    const morningCtx = document.getElementById('morningChart').getContext('2d');
    const morningChart = new Chart(morningCtx, {
        type: 'bar',
        data: {
            labels: ['Morning Students'],
            datasets: [{
                data: [amStudentsCount || 0],
                backgroundColor: 'rgba(248, 122, 20, 0.8)',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    display: false
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Afternoon Session Chart (Dynamic)
    const afternoonCtx = document.getElementById('afternoonChart').getContext('2d');
    const afternoonChart = new Chart(afternoonCtx, {
        type: 'bar',
        data: {
            labels: ['Afternoon Students'],
            datasets: [{
                data: [pmStudentsCount || 0],
                backgroundColor: 'rgba(230, 106, 0, 0.8)',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    display: false
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Countdown Timer (Dynamic)
    function updateCountdown() {
        if (!nextExamDate || nextExamDate === '') {
            document.getElementById('countdown').innerHTML = "No upcoming exams";
            return;
        }

        const examDate = new Date(nextExamDate + 'T09:00:00').getTime();
        const now = new Date().getTime();
        const distance = examDate - now;

        if (distance < 0) {
            document.getElementById('countdown').innerHTML = "EXAM DAY!";
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('countdown').innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateCountdown();
    const countdownTimer = setInterval(updateCountdown, 1000);
</script>
@endsection
