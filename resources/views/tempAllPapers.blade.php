<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Exam Papers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .page-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .page-header .badge {
            background: rgba(255,255,255,0.3);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 1rem;
        }
        .board-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .board-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .board-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .board-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .course-type-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 25px 0 15px 0;
        }
        .course-type-header h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .table {
            margin-bottom: 0;
        }
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .no-data i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        .loading-spinner {
            text-align: center;
            padding: 50px;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stats-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }
        .stats-card .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1><i class="fas fa-file-alt me-3"></i>All Exam Papers</h1>
                <span class="badge">
                    <i class="fas fa-book me-2"></i>
                    <span id="totalSubjects">{{ $subjects->count() }}</span> Subjects
                </span>
            </div>
        </div>

        @if($subjects->isNotEmpty())
            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stat-value" id="totalBoards">0</div>
                        <div class="stat-label">Exam Boards</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stat-value" id="totalCourseTypes">0</div>
                        <div class="stat-label">Course Types</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stat-value" id="totalGroups">0</div>
                        <div class="stat-label">Groups</div>
                    </div>
                </div>
            </div>

            {{-- Hidden data container for JavaScript --}}
            <script type="application/json" id="subjectsData">
            @php
                $subjectsArray = [];
                foreach($subjects as $subject) {
                    $subjectsArray[] = [
                        'id' => $subject->id,
                        'examDate' => $subject->examDate,
                        'examDay' => $subject->examDay ?? 'N/A',
                        'examTime' => $subject->examTime ?? 'N/A',
                        'examBoard' => $subject->examBoard ?? 'N/A',
                        'courseType' => $subject->courseType ?? 'N/A',
                        'subjectName' => $subject->subjectName ?? 'N/A',
                        'subjectCode' => $subject->subjectCode ?? 'N/A',
                        'examSession' => $subject->examSession ?? 'N/A',
                        'duration' => $subject->duration ?? 'N/A',
                        'tier' => $subject->tier ?? 'N/A',
                        'slot' => $subject->slot ?? 'N/A',
                        'nea' => $subject->nea ?? 'N/A',
                    ];
                }
            @endphp
            {!! json_encode($subjectsArray) !!}
            </script>

            {{-- Container for grouped tables --}}
            <div id="groupedTablesContainer">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Organizing papers by board and course type...</p>
                </div>
            </div>
        @else
            <div class="no-data">
                <i class="fas fa-inbox"></i>
                <h3>No Subjects Found</h3>
                <p>There are no exam papers available at the moment.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectsDataElement = document.getElementById('subjectsData');
            const subjectsData = subjectsDataElement ? JSON.parse(subjectsDataElement.textContent) : [];
            const container = document.getElementById('groupedTablesContainer');
            
            if (subjectsData.length === 0) {
                container.innerHTML = '<div class="no-data"><i class="fas fa-inbox"></i><h3>No Subjects Found</h3></div>';
                return;
            }

            // Group subjects by board and course type
            const grouped = {};
            subjectsData.forEach(subject => {
                const board = subject.examBoard || 'Unknown Board';
                const courseType = subject.courseType || 'Unknown Course Type';
                
                if (!grouped[board]) {
                    grouped[board] = {};
                }
                if (!grouped[board][courseType]) {
                    grouped[board][courseType] = [];
                }
                grouped[board][courseType].push(subject);
            });

            // Sort subjects within each group by exam date
            Object.keys(grouped).forEach(board => {
                Object.keys(grouped[board]).forEach(courseType => {
                    grouped[board][courseType].sort((a, b) => {
                        if (!a.examDate && !b.examDate) return 0;
                        if (!a.examDate) return 1;
                        if (!b.examDate) return -1;
                        return a.examDate.localeCompare(b.examDate);
                    });
                });
            });

            // Update statistics
            const boards = Object.keys(grouped);
            let totalCourseTypes = 0;
            let totalGroups = 0;
            boards.forEach(board => {
                const courseTypes = Object.keys(grouped[board]);
                totalCourseTypes += courseTypes.length;
                totalGroups += courseTypes.length;
            });

            document.getElementById('totalBoards').textContent = boards.length;
            document.getElementById('totalCourseTypes').textContent = totalCourseTypes;
            document.getElementById('totalGroups').textContent = totalGroups;

            // Generate HTML for grouped tables
            let html = '';
            
            // Sort boards alphabetically
            const sortedBoards = boards.sort();
            
            sortedBoards.forEach(board => {
                html += `
                    <div class="board-section">
                        <div class="board-header">
                            <h2><i class="fas fa-building"></i> ${escapeHtml(board)}</h2>
                        </div>
                `;

                // Sort course types alphabetically
                const courseTypes = Object.keys(grouped[board]).sort();
                
                courseTypes.forEach(courseType => {
                    const subjects = grouped[board][courseType];
                    html += `
                        <div class="course-type-header">
                            <h3><i class="fas fa-graduation-cap"></i> ${escapeHtml(courseType)} <span class="badge bg-light text-dark ms-2">${subjects.length} Papers</span></h3>
                        </div>
                        <div class="table-container">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Exam Date</th>
                                        <th>Exam Day</th>
                                        <th>Exam Time</th>
                                        <th>Subject Name</th>
                                        <th>Exam Code</th>
                                        <th>Exam Session</th>
                                        <th>Duration</th>
                                        <th>Tier</th>
                                        <th>Slot</th>
                                        <th>NEA</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    subjects.forEach(subject => {
                        const examDate = subject.examDate ? formatDate(subject.examDate) : 'N/A';
                        html += `
                            <tr>
                                <td>${escapeHtml(examDate)}</td>
                                <td>${escapeHtml(subject.examDay)}</td>
                                <td>${escapeHtml(subject.examTime)}</td>
                                <td><strong>${escapeHtml(subject.subjectName)}</strong></td>
                                <td><code>${escapeHtml(subject.subjectCode)}</code></td>
                                <td>${escapeHtml(subject.examSession)}</td>
                                <td>${escapeHtml(subject.duration)}</td>
                                <td>${escapeHtml(subject.tier)}</td>
                                <td>${escapeHtml(subject.slot)}</td>
                                <td>${escapeHtml(subject.nea)}</td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                });

                html += `</div>`;
            });

            container.innerHTML = html;
        });

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                // Try to parse Y/m/d format
                const parts = dateString.split('/');
                if (parts.length === 3) {
                    const year = parts[0];
                    const month = parts[1];
                    const day = parts[2];
                    const date = new Date(year, month - 1, day);
                    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                                  'July', 'August', 'September', 'October', 'November', 'December'];
                    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    return `${day} ${months[date.getMonth()]} ${year}`;
                }
                return dateString;
            } catch (e) {
                return dateString;
            }
        }
    </script>
</body>
</html>
