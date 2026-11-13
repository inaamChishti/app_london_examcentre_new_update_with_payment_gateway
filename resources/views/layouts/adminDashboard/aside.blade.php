<style>
    .submenu-item {
        display: flex;
        align-items: center;
        padding: 8px 15px;
    }

    .submenu-icon {
        margin-right: 10px; /* Adjust this value for desired spacing */
        display: flex;
        align-items: center;
    }

    .submenu-item i {
        font-style: normal; /* Remove italic styling if not needed */
    }
</style>
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-text">London Exam Centre</div>
            <div style="color:#333333;" class="logo-text" id="uk-time"></div>
        </div>
        <button class="toggle-btn"><i class="fas fa-angle-left" style="color: #333;"></i></button>
    </div>

    <div class="menu">
        @php
            $pagePermissions = isset($pagePermissions) ? $pagePermissions : [];
            if (empty($pagePermissions) && auth()->user()) {
                $userPermissions = auth()->user()->permissions;
                $pagePermissions = $userPermissions && $userPermissions->isNotEmpty()
                    ? json_decode($userPermissions->first()->page_name, true) ?? []
                    : [];
            }
        @endphp

        <!-- Main Section -->
        <div class="menu-title">Main</div>
        @if(isset($pagePermissions['dashboard']) && $pagePermissions['dashboard'] === 'on')
            <a href="{{ url('/') }}" class="menu-item {{ request()->is('/') ? 'active' : '' }}">
                <div class="menu-icon"><i class="fas fa-tachometer-alt" style="color: #44c3c3;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Dashboard</i></div>
            </a>
        @endif

        <!-- Management Section -->
        <div class="menu-title">Management</div>
        @if(in_array(true, array_map(fn($key) => isset($pagePermissions[$key]) && $pagePermissions[$key] === 'on',
            ['add_new_student', 'student_requests', 'approved_requests', 'view_datesheet', 'generate_timetable', 'seat_tags', 'view_sheet_data'])))
            <!-- Students Menu -->
            <a href="#" class="menu-item">
                <div class="menu-icon"><i class="fas fa-user-graduate" style="color: #1E90FF;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Students</i></div>
                <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
            </a>
            <div class="submenu">
                @if(isset($pagePermissions['add_new_student']) && $pagePermissions['add_new_student'] === 'on')
                    <a href="{{ url('add-student') }}" class="submenu-item {{ request()->is('add-student') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-user-plus" style="color: #FFD700;"></i></div>
                        <i style="font-size: 12px;">Add New Student</i>
                    </a>
                @endif
                @if(isset($pagePermissions['student_requests']) && $pagePermissions['student_requests'] === 'on')
                    <a href="{{ url('student-request') }}" class="submenu-item {{ request()->is('student-request') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-user-graduate" style="color: #5350bb;"></i></div>
                        <i style="font-size: 12px;">Student Requests</i>
                    </a>
                @endif
                @if(isset($pagePermissions['approved_requests']) && $pagePermissions['approved_requests'] === 'on')
                    <a href="{{ url('approved-request') }}" class="submenu-item {{ request()->is('approved-request') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-user-check" style="color: #cb400cc4;"></i></div>
                        <i style="font-size: 12px;">Approved Requests</i>
                    </a>
                @endif
                @if(isset($pagePermissions['view_datesheet']) && $pagePermissions['view_datesheet'] === 'on')
                    <a href="{{ url('view-datesheet') }}" class="submenu-item {{ request()->is('view-datesheet') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-calendar-day" style="color: #FF8C00;"></i></div>
                        <i style="font-size: 12px;">View Datesheet</i>
                    </a>
                @endif
                @if(isset($pagePermissions['generate_timetable']) && $pagePermissions['generate_timetable'] === 'on')
                    <a href="{{ url('generate-timetable') }}" class="submenu-item {{ request()->is('generate-timetable') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-table" style="color: #9370DB;"></i></div>
                        <i style="font-size: 12px;">Generate Time Table</i>
                    </a>
                @endif
            </div>

            <!-- Exams Menu -->
            @if((isset($pagePermissions['seat_tags']) && $pagePermissions['seat_tags'] === 'on') || (isset($pagePermissions['send_important_message']) && $pagePermissions['send_important_message'] === 'on') || (isset($pagePermissions['email_report']) && $pagePermissions['email_report'] === 'on'))
                <a href="#" class="menu-item">
                    <div class="menu-icon"><i class="fas fa-chalkboard-teacher" style="color: #FF4500;"></i></div>
                    <div class="menu-text"><i style="font-size: 12px;">Exams</i></div>
                    <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
                </a>
                <div class="submenu">
                    @if(isset($pagePermissions['seat_tags']) && $pagePermissions['seat_tags'] === 'on')
                    <a href="{{ url('seat-tags') }}" class="submenu-item {{ request()->is('seat-tags') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-chair" style="color: #228B22;"></i></div>
                        <i style="font-size: 12px;">Seat Tags</i>
                    </a>
                    @endif
                    @if(isset($pagePermissions['send_important_message']) && $pagePermissions['send_important_message'] === 'on')
                    <a href="{{ url('send-important-message') }}" class="submenu-item {{ request()->is('send-important-message') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-envelope" style="color: #1E90FF;"></i></div>
                        <i style="font-size: 12px;">Send Important Msg</i>
                    </a>
                    @endif
                    @if(isset($pagePermissions['email_report']) && $pagePermissions['email_report'] === 'on')
                    <a href="{{ url('important-message-report') }}" class="submenu-item {{ request()->is('important-message-report') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-chart-line" style="color: #28a745;"></i></div>
                        <i style="font-size: 12px;">Email Tracking Report</i>
                    </a>
                    @endif
                </div>
            @endif
        @endif

        <!-- Payments Menu -->
        @if(isset($pagePermissions['create_payment']) || isset($pagePermissions['view_payments']))
            <a href="#" class="menu-item">
                <div class="menu-icon"><i class="fas fa-pound-sign" style="color: #4CAF50;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Payments</i></div>
                <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
            </a>
            <div class="submenu">
                @if(isset($pagePermissions['create_payment']) && $pagePermissions['create_payment'] === 'on')
                    <a href="{{ url('create-payment') }}" class="submenu-item {{ request()->is('create-payment') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-money-bill-transfer" style="color: #FF9800;"></i></div>
                        <i style="font-size: 12px;">Create Payment</i>
                    </a>
                @endif
                @if(isset($pagePermissions['view_payments']) && $pagePermissions['view_payments'] === 'on')
                    <a href="{{ url('view-payments') }}" class="submenu-item {{ request()->is('view-payments') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-receipt" style="color: #2196F3;"></i></div>
                        <i style="font-size: 12px;">View Payments</i>
                    </a>
                @endif
            </div>
        @endif

        <!-- Session Menu -->
        @if(isset($pagePermissions['add_session']) || isset($pagePermissions['view_sessions']))
            <a href="#" class="menu-item">
                <div class="menu-icon"><i class="fas fa-calendar-alt" style="color: #FF6347;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Session</i></div>
                <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
            </a>
            <div class="submenu">
                @if(isset($pagePermissions['add_session']) && $pagePermissions['add_session'] === 'on')
                    <a href="{{ url('add-session') }}" class="submenu-item {{ request()->is('add-session') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-plus-circle" style="color: #20B2AA;"></i></div>
                        <i style="font-size: 12px;">Add Session</i>
                    </a>
                @endif
                @if(isset($pagePermissions['view_sessions']) && $pagePermissions['view_sessions'] === 'on')
                    <a href="{{ url('view-sessions') }}" class="submenu-item {{ request()->is('view-sessions') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-list-ol" style="color: #DA70D6;"></i></div>
                        <i style="font-size: 12px;">View Sessions</i>
                    </a>
                @endif
            </div>
        @endif

        <!-- Referrals Menu -->
        @if(isset($pagePermissions['referrals']) && $pagePermissions['referrals'] === 'on')
            <a href="{{ url('referrals') }}" class="menu-item {{ request()->is('referrals') ? 'active' : '' }}">
                <div class="menu-icon"><i class="fas fa-share-alt" style="color: #E91E63;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Referrals</i></div>
            </a>
        @endif

        <!-- Results Menu -->
        <div class="menu-title">Exams</div>
        @if(in_array(true, array_map(fn($key) => isset($pagePermissions[$key]) && $pagePermissions[$key] === 'on',
            ['view_sheet_data', 'upload_result_pdf', 'send_results', 'modify_records', 'modify_email', 'ignored_emails'])))
            <a href="#" class="menu-item">
                <div class="menu-icon"><i class="fas fa-poll" style="color: #9ACD32;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Results</i></div>
                <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
            </a>
            <div class="submenu">
                @if(isset($pagePermissions['view_sheet_data']) && $pagePermissions['view_sheet_data'] === 'on')
                    <a href="{{ url('view-sheet-data') }}" class="submenu-item {{ request()->is('view-sheet-data') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-file-alt" style="color: #4169E1;"></i></div>
                        <i style="font-size: 12px;">View Sheet Data</i>
                    </a>
                @endif
                @if(isset($pagePermissions['upload_result_pdf']) && $pagePermissions['upload_result_pdf'] === 'on')
                    <a href="{{ url('upload-result-pdf') }}" class="submenu-item {{ request()->is('upload-result-pdf') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-file-pdf" style="color: #FF4444;"></i></div>
                        <i style="font-size: 12px;">Upload Result PDF</i>
                    </a>
                @endif
                @if(isset($pagePermissions['send_results']) && $pagePermissions['send_results'] === 'on')
                    <a href="{{ url('send-results') }}" class="submenu-item {{ request()->is('send-results') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-paper-plane" style="color: #32CD32;"></i></div>
                        <i style="font-size: 12px;">Send Results</i>
                    </a>
                @endif
                @if(isset($pagePermissions['modify_records']) && $pagePermissions['modify_records'] === 'on')
                    <a href="{{ url('modify-records') }}" class="submenu-item {{ request()->is('modify-records') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-edit" style="color: #FFA500;"></i></div>
                        <i style="font-size: 12px;">Modify Records</i>
                    </a>
                @endif
                {{-- @if(isset($pagePermissions['modify_email']) && $pagePermissions['modify_email'] === 'on')
                    <a href="{{ url('modify-email') }}" class="submenu-item {{ request()->is('modify-email') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-envelope" style="color: #20B2AA;"></i></div>
                        <i style="font-size: 12px;">Modify Email</i>
                    </a>
                @endif --}}
                @if(isset($pagePermissions['ignored_emails']) && $pagePermissions['ignored_emails'] === 'on')
                    <a href="{{ url('ignored-emails') }}" class="submenu-item {{ request()->is('ignored-emails') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-ban" style="color: #DC143C;"></i></div>
                        <i style="font-size: 12px;">Ignored Emails</i>
                    </a>
                @endif
            </div>
        @endif

        <!-- Reports Section -->
        <div class="menu-title">Reports</div>
        @if(in_array(true, array_map(fn($key) => isset($pagePermissions[$key]) && $pagePermissions[$key] === 'on',
            ['exam_report', 'exam_report_by_subject', 'internal_timetable', 'spoken_coursework', 'search_candidate', 'access_arrangement_report', 'candidate_approval_report', 'email_logs', 'referral_report'])))
            <a href="#" class="menu-item">
                <div class="menu-icon"><i class="fas fa-file-alt" style="color: #6A5ACD;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Reports</i></div>
                <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
            </a>
            <div class="submenu">
                @if(isset($pagePermissions['exam_report']) && $pagePermissions['exam_report'] === 'on')
                    <a href="{{ url('exam-report') }}" class="submenu-item {{ request()->is('exam-report') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-file-contract" style="color: #4682B4;"></i></div>
                        <i style="font-size: 12px;">Exam Report</i>
                    </a>
                @endif
                @if(isset($pagePermissions['exam_report_by_subject']) && $pagePermissions['exam_report_by_subject'] === 'on')
                    <a href="{{ url('exam-report-by-subject') }}" class="submenu-item {{ request()->is('exam-report-by-subject') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-clipboard-list" style="color: #8B4513;"></i></div>
                        <i style="font-size: 12px;">Exam Report by Subject</i>
                    </a>
                @endif
                @if(isset($pagePermissions['internal_timetable']) && $pagePermissions['internal_timetable'] === 'on')
                    <a href="{{ url('internal-timetable') }}" class="submenu-item {{ request()->is('internal-timetable') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-calendar-week" style="color: #32CD32;"></i></div>
                        <i style="font-size: 12px;">Internal Timetable</i>
                    </a>
                @endif
                @if(isset($pagePermissions['spoken_coursework']) && $pagePermissions['spoken_coursework'] === 'on')
                    <a href="{{ url('spoken-coursework') }}" class="submenu-item {{ request()->is('spoken-coursework') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-microphone-alt" style="color: #FF6347;"></i></div>
                        <i style="font-size: 12px;">Spoken and Course Work</i>
                    </a>
                @endif
                @if(isset($pagePermissions['search_candidate']) && $pagePermissions['search_candidate'] === 'on')
                    <a href="{{ url('search-candidate') }}" class="submenu-item {{ request()->is('search-candidate') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-search" style="color: #20B2AA;"></i></div>
                        <i style="font-size: 12px;">Search Candidate</i>
                    </a>
                @endif
                @if(isset($pagePermissions['access_arrangement_report']) && $pagePermissions['access_arrangement_report'] === 'on')
                    <a href="{{ url('access-arrangement-report') }}" class="submenu-item {{ request()->is('access-arrangement-report') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-universal-access" style="color: #9370DB;"></i></div>
                        <i style="font-size: 12px; white-space: nowrap;">Access Arrangement</i>
                    </a>
                @endif
                @if(isset($pagePermissions['candidate_approval_report']) && $pagePermissions['candidate_approval_report'] === 'on')
                    <a href="{{ url('candidate-approval-report') }}" class="submenu-item {{ request()->is('candidate-approval-report') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-check-circle" style="color: #28a745;"></i></div>
                        <i style="font-size: 12px; white-space: nowrap;">Candidate Approval Report</i>
                    </a>
                @endif
                @if(isset($pagePermissions['referral_report']) && $pagePermissions['referral_report'] === 'on')
                    <a href="{{ route('referrals.report') }}" class="submenu-item {{ request()->is('referral-report') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-chart-bar" style="color: #E91E63;"></i></div>
                        <i style="font-size: 12px; white-space: nowrap;">Referral Report</i>
                    </a>
                @endif
                @if(isset($pagePermissions['email_logs']) && $pagePermissions['email_logs'] === 'on')
                    <a href="{{ url('email-logs') }}" class="submenu-item {{ request()->is('email-logs') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-envelope-open-text" style="color: #6A5ACD;"></i></div>
                        <i style="font-size: 12px;">Email Logs</i>
                    </a>
                @endif
            </div>
        @endif

        <!-- Settings Section -->
        <div class="menu-title">Settings</div>
        @if(isset($pagePermissions['create_user']) || isset($pagePermissions['view_users']))
            <a href="#" class="menu-item">
                <div class="menu-icon"><i class="fas fa-user-cog" style="color: #4CAF50;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Users</i></div>
                <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
            </a>
            <div class="submenu">
                @if(isset($pagePermissions['create_user']) && $pagePermissions['create_user'] === 'on')
                    <a href="{{ url('create-user') }}" class="submenu-item {{ request()->is('create-user') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-user-plus" style="color: #00BCD4;"></i></div>
                        <i style="font-size: 12px;">Create User</i>
                    </a>
                @endif
                @if(isset($pagePermissions['view_users']) && $pagePermissions['view_users'] === 'on')
                    <a href="{{ url('view-users') }}" class="submenu-item {{ request()->is('view-users') ? 'active' : '' }}">
                        <div class="submenu-icon"><i class="fas fa-users" style="color: #FF5722;"></i></div>
                        <i style="font-size: 12px;">View Users</i>
                    </a>
                @endif
            </div>
        @endif

        {{-- @if(isset($pagePermissions['help']) && $pagePermissions['help'] === 'on')
            <a href="{{ url('help') }}" class="menu-item {{ request()->is('help') ? 'active' : '' }}">
                <div class="menu-icon"><i class="fas fa-info-circle" style="color: #8A2BE2;"></i></div>
                <div class="menu-text"><i style="font-size: 12px;">Help</i></div>
            </a>
        @endif --}}

        <!-- Admin Operations Section -->
        <div class="menu-title">Admin Operations</div>
        <a href="#" class="menu-item {{ request()->is('admin/*') ? 'active' : '' }}">
            <div class="menu-icon"><i class="fas fa-cogs" style="color: #F87A14;"></i></div>
            <div class="menu-text"><i style="font-size: 12px;">Admin Operations</i></div>
            <div class="menu-arrow"><i class="fas fa-angle-right" style="color: #FF69B4;"></i></div>
        </a>
        <div class="submenu">
            <a href="{{ url('admin/offered-subjects') }}" class="submenu-item {{ request()->is('admin/offered-subjects') ? 'active' : '' }}">
                <div class="submenu-icon"><i class="fas fa-book-open" style="color: #1976D2;"></i></div>
                <i style="font-size: 12px;">Offered Subjects</i>
            </a>
        </div>

    </div>
</aside>
