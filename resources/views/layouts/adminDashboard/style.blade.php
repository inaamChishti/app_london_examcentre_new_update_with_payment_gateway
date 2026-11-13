<style>
    /* CSS Variables */
:root {
    --primary-color: #2196F3;
    --primary-light: #BBDEFB;
    --primary-dark: #1976D2;
    --secondary-color: #FF9800;
    --secondary-light: #FFE0B2;
    --secondary-dark: #F57C00;
    --sidebar-width: 240px;
    --sidebar-collapsed-width: 70px;
    --transition-speed: 0.3s;
    --sidebar-bg: #ffffff;
    --sidebar-text: #333333;
    --sidebar-hover: #FFF3E0;
    --sidebar-active: #FFE0B2;
    --sidebar-border: #E0E0E0;
}

/* General Body Styles */
body {
    font-family: 'Nunito', sans-serif;
    background-color: #f8fafc;
    overflow-x: hidden;
    font-size: 14px;
    color: #444;
}

/* Dashboard Layout */
.dashboard-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    transition: all var(--transition-speed) ease;
    position: fixed;
    height: 100vh;
    z-index: 1000;
    overflow-y: auto;
    border-right: 1px solid var(--sidebar-border);
}

.sidebar::-webkit-scrollbar {
    width: 8px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.sidebar-collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar-collapsed .menu-text,
.sidebar-collapsed .submenu-text,
.sidebar-collapsed .logo-text,
.sidebar-collapsed .time-display {
    display: none !important;
}

.sidebar-collapsed .menu-item {
    justify-content: center;
}

.sidebar-header {
    padding: 18px 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--sidebar-border);
    background-color: var(--sidebar-bg);
    position: relative;
}

.logo-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: 100%;
}

.logo {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.logo-icon {
    width: 30px;
    height: 30px;
    object-fit: contain;
    margin-right: 10px;
}

.logo-text {
    font-weight: 700;
    color: var(--primary-dark);
    font-size: 16px;
    transition: opacity var(--transition-speed);
}

.time-display {
    font-size: 11px;
    color: #666;
    font-weight: 500;
    margin-left: 40px;
    transition: opacity var(--transition-speed);
}

.toggle-btn {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    color: #666;
    transition: transform var(--transition-speed);
    padding: 5px;
    border-radius: 4px;
}

.toggle-btn:hover {
    background-color: var(--sidebar-hover);
    color: var(--primary-dark);
}

.sidebar-collapsed .toggle-btn {
    transform: rotate(180deg);
}

.menu {
    padding: 15px 0;
    max-height: 80vh;
    overflow-y: auto;
}

.menu-title {
    padding: 10px 15px 8px;
    font-size: 11px;
    font-weight: 600;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: opacity var(--transition-speed);
}

.menu-item {
    padding: 12px 15px;
    display: flex;
    align-items: center;
    color: var(--sidebar-text);
    text-decoration: none;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
    margin: 2px 8px;
    border-radius: 6px;
}

.menu-item:hover {
    color: var(--primary-dark);
    background-color: var(--sidebar-hover);
}

.menu-item.active {
    color: var(--primary-dark);
    background-color: var(--sidebar-active);
    font-weight: 600;
}

.menu-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background-color: var(--primary-dark);
    border-radius: 0 3px 3px 0;
}

.menu-icon {
    font-size: 16px;
    margin-right: 12px;
    width: 20px;
    text-align: center;
    color: inherit;
}

.menu-text {
    font-weight: 500;
    font-size: 13px;
    transition: opacity var(--transition-speed);
}

.menu-arrow {
    margin-left: auto;
    transition: all var(--transition-speed);
    font-size: 12px;
    opacity: 0.7;
}

.menu-item.open .menu-arrow {
    transform: rotate(90deg);
}

.submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height var(--transition-speed) ease-out;
    padding-left: 15px;
}

.menu-item.open + .submenu {
    max-height: 500px;
}

.submenu-item {
    padding: 10px 15px 10px 40px;
    display: flex;
    align-items: center;
    color: #666;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 13px;
    margin: 2px 8px;
    border-radius: 6px;
}

.submenu-item:hover {
    color: var(--primary-dark);
    background-color: var(--sidebar-hover);
}

.submenu-item.active {
    color: var(--primary-dark);
    font-weight: 500;
    background-color: var(--sidebar-active);
}

/* Main Content */
.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    transition: margin-left var(--transition-speed);
}

.sidebar-collapsed + .main-content {
    margin-left: var(--sidebar-collapsed-width);
}

/* Top Navigation */
.top-nav {
    background: white;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 100;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 15px;
}

.notification-icon,
.message-icon {
    position: relative;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
    padding: 6px;
    border-radius: 50%;
    font-size: 14px;
}

.notification-icon:hover,
.message-icon:hover {
    background-color: rgba(178, 228, 237, 0.2);
    color: var(--primary-dark);
}

.notification-badge,
.message-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #ff4757;
    color: white;
    border-radius: 50%;
    width: 15px;
    height: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    font-weight: bold;
}

/* User Dropdown */
.user-dropdown {
    position: relative;
}

.user-profile {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.user-profile:hover {
    background-color: rgba(178, 228, 237, 0.2);
}

.user-profile.active {
    background-color: rgba(178, 228, 237, 0.3);
}

.user-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    margin-right: 8px;
    object-fit: cover;
    border: 1px solid #eee;
    transition: all 0.3s ease;
}

.user-profile:hover .user-avatar {
    border-color: var(--primary-dark);
}

.user-name {
    font-weight: 600;
    color: #333;
    margin-right: 6px;
    font-size: 12px;
}

.user-dropdown-icon {
    transition: transform 0.3s ease;
    font-size: 12px;
}

.user-profile.active .user-dropdown-icon {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 180px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(8px);
    transition: all 0.3s ease;
    z-index: 1000;
}

.user-profile.active + .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.dropdown-user-info {
    display: flex;
    align-items: center;
}

.dropdown-user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 8px;
    object-fit: cover;
}

.dropdown-user-name {
    font-weight: 600;
    font-size: 12px;
    margin-bottom: 2px;
}

.dropdown-user-email {
    font-size: 10px;
    color: #777;
}

.dropdown-body {
    padding: 8px 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    color: #555;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 12px;
}

.dropdown-item:hover {
    background-color: rgba(178, 228, 237, 0.2);
    color: var(--primary-dark);
}

.dropdown-item i {
    width: 16px;
    margin-right: 8px;
    text-align: center;
    font-size: 12px;
}

.dropdown-divider {
    height: 1px;
    background-color: #eee;
    margin: 4px 0;
}

/* Sessions Dropdown */
.sessions-dropdown {
    position: relative;
}

.sessions-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    padding: 6px;
    border-radius: 50%;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.sessions-btn:hover {
    background-color: rgba(178, 228, 237, 0.2);
    color: var(--primary-dark);
}

.sessions-btn.active {
    background-color: rgba(178, 228, 237, 0.3);
    color: var(--primary-dark);
}

.sessions-btn i {
    display: inline-block;
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.sessions-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    width: 220px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(8px);
    transition: all 0.3s ease;
    z-index: 1000;
}

.sessions-btn.active + .sessions-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.sessions-header {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    font-size: 12px;
}

.sessions-body {
    max-height: 250px;
    overflow-y: auto;
    padding: 8px 0;
}

.session-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    transition: all 0.2s;
}

.session-item:hover {
    background-color: rgba(178, 228, 237, 0.2);
}

.session-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background-color: rgba(178, 228, 237, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    color: var(--primary-dark);
    font-size: 12px;
}

.session-info {
    flex: 1;
}

.session-name {
    font-weight: 500;
    font-size: 11px;
    margin-bottom: 2px;
}

.session-meta {
    font-size: 10px;
    color: #777;
}

.session-active {
    color: #2ecc71;
    font-size: 9px;
    font-weight: bold;
}

.session-actions {
    margin-left: 8px;
}

.session-logout {
    color: #e74c3c;
    cursor: pointer;
    font-size: 10px;
    padding: 2px 4px;
    border-radius: 2px;
    transition: all 0.2s;
}

.session-logout:hover {
    background-color: rgba(231, 76, 60, 0.1);
}

.sessions-footer {
    padding: 8px 12px;
    border-top: 1px solid #eee;
    text-align: center;
}

.view-all-sessions {
    color: var(--primary-dark);
    font-size: 10px;
    text-decoration: none;
    font-weight: 500;
}

.view-all-sessions:hover {
    text-decoration: underline;
}

/* Content Area */
.content-wrapper {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.page-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
}

/* Cards */
.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
}

.card-header {
    padding: 12px 16px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-weight: 600;
    color: #333;
    margin: 0;
    font-size: 14px;
}

.card-body {
    padding: 16px;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    padding: 15px;
    border-radius: 8px;
    color: white;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -15px;
    right: -15px;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
}

.stat-card.primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    }

    .stat-card.secondary {
        background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    }

    .stat-card.success {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .stat-card.warning {
        background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    }

    .stat-card.danger {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
    }

.stat-icon {
    font-size: 24px;
    margin-bottom: 10px;
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 4px;
}

.stat-title {
    font-size: 12px;
    opacity: 0.9;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    border: 1px solid #b0e0e6;
}

td, th {
    padding: 8px;
    vertical-align: middle;
    text-align: center;
    border: 1px solid #b0e0e6;
}

/* Button Styles for Exam Details Table */
.add-row-button {
    width: 100% !important;
    max-width: 100px !important;
    font-size: 11px !important;
    padding: 8px !important;
    border: none !important;
    border-radius: 4px !important;
    color: white !important;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
}

.add-row-button.add {
    background-color: #57afbfd6 !important; /* Blue for Add button */
}

.add-row-button.delete {
    background-color: #e74c3c !important; /* Red for Delete button */
}

.add-row-button.add:hover {
    background-color: #4682b4 !important; /* Darker blue on hover */
}

.add-row-button.delete:hover {
    background-color: #c0392b !important; /* Darker red on hover */
}

td[data-label="Action"] {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 6px 5px !important;
}

/* General styles for select and input elements */
select, input[type="text"] {
    width: 100%;
    padding: 6px;
    border: 1px solid #b0e0e6;
    border-radius: 4px;
    font-size: 12px;
    background-color: #f0f8ff;
}

/* Row container styling */
.row-container {
    margin-bottom: 10px;
}

/* UK Time Display */
#uk-time {
    font-size: 12px;
    color: #777777;
    font-family: Arial, sans-serif;
    font-weight: normal;
}

/* General Responsive Adjustments */
@media (max-width: 768px) {
    /* Form Container with Zoom */
    .container-fluid {
        padding: 10px !important;
        zoom: 0.8; /* Scale down form to 80% */
        transform-origin: top left; /* Ensure zoom starts from top-left */
    }

    .container-fluid > div {
        max-width: 100% !important;
        margin: 0 8px; /* Reduced margin for zoomed content */
    }

    /* Form Inputs and Labels */
    .form-control,
    .form-select {
        font-size: 13px !important; /* Adjusted for zoom */
        padding: 5px !important; /* Reduced to prevent overflow */
        max-width: 100% !important; /* Prevent overflow */
        width: 100% !important;
        box-sizing: border-box !important;
        border: 1px solid #b0e0e6 !important;
        border-radius: 4px !important;
        background-color: #f0f8ff !important;
        overflow: hidden; /* Prevent content spillover */
    }

    label {
        font-size: 13px !important; /* Adjusted for zoom */
    }

    .sessionFAS {
        font-size: 0.9rem !important; /* Adjusted for zoom */
    }

    /* Stack Columns in Rows */
    .row {
        flex-direction: column;
    }

    .col-md-4,
    .col-md-6,
    .col-md-12 {
        width: 100% !important;
        margin-bottom: 12px !important; /* Reduced for zoom */
    }

    /* Exam Details Table */
    div[style*="margin-bottom: 25px"] table {
        width: 100% !important;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        min-width: 0; /* Fully fluid to avoid forced scrolling */
        border-collapse: collapse;
    }

    /* Responsive Table Layout for Small Screens */
    @media (max-width: 576px) {
        div[style*="margin-bottom: 25px"] table {
            display: block;
        }

        div[style*="margin-bottom: 25px"] thead {
            display: none; /* Hide headers on small screens */
        }

        div[style*="margin-bottom: 25px"] tbody,
        div[style*="margin-bottom: 25px"] tr {
            display: block;
        }

        div[style*="margin-bottom: 25px"] tr {
            margin-bottom: 12px; /* Reduced for zoom */
            border: 1px solid #b0e0e6;
            border-radius: 4px;
            padding: 6px; /* Reduced padding */
            background: #ffffff;
            overflow: hidden; /* Prevent row overflow */
        }

        div[style*="margin-bottom: 25px"] td {
            display: flex;
            flex-direction: column;
            padding: 6px 5px; /* Reduced to prevent overflow */
            border: none;
            text-align: left !important;
            min-height: 45px; /* Adjusted for zoom and controls */
            max-width: 100% !important; /* Prevent cell overflow */
            box-sizing: border-box;
        }

        /* Add Labels for Each Cell */
        div[style*="margin-bottom: 25px"] td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 3px;
            font-size: 10px; /* Adjusted for zoom */
            text-transform: uppercase;
        }

        div[style*="margin-bottom: 25px"] td select,
        div[style*="margin-bottom: 25px"] td input {
            max-width: 100% !important; /* Prevent overflow */
            width: 100% !important;
            font-size: 12px !important; /* Adjusted for zoom */
            padding: 5px !important; /* Reduced padding */
            border: 1px solid #b0e0e6 !important;
            border-radius: 4px !important;
            background-color: #f0f8ff !important;
            overflow: hidden;
        }

        /* Specific Fixes for Tier, NEA, and Action */
        div[style*="margin-bottom: 25px"] td[data-label="Tier"],
        div[style*="margin-bottom: 25px"] td[data-label="NEA"] {
            align-items: flex-start;
        }

        div[style*="margin-bottom: 25px"] td[data-label="Tier"] select,
        div[style*="margin-bottom: 25px"] td[data-label="NEA"] select {
            height: 36px !important; /* Adjusted for zoom */
        }

        div[style*="margin-bottom: 25px"] td[data-label="Action"] {
            align-items: center;
            justify-content: center;
        }

        /* Button styles in media query */
        div[style*="margin-bottom: 25px"] .add-row-button,
        .row-container .add-row-button {
            width: 100% !important;
            max-width: 100px !important; /* Reduced for zoom */
            font-size: 11px !important; /* Adjusted for zoom */
            padding: 6px !important;
            border: none !important;
            border-radius: 4px !important;
            color: white !important;
            text-align: center;
        }

        /* Maintain Add button color */
        div[style*="margin-bottom: 25px"] .add-row-button.add,
        .row-container .add-row-button.add {
            background-color: #57afbfd6 !important; /* Blue for Add */
        }

        /* Maintain Delete button color */
        div[style*="margin-bottom: 25px"] .add-row-button.delete,
        .row-container .add-row-button.delete {
            background-color: #e74c3c !important; /* Red for Delete */
        }

        /* Ensure Dropdowns Are Not Cut Off */
        div[style*="margin-bottom: 25px"] select {
            overflow: visible !important;
            max-width: 100% !important;
        }

        /* Assign Data Labels to Table Cells */
        div[style*="margin-bottom: 25px"] td:nth-child(1):before {
            content: "Exam Board";
        }
        div[style*="margin-bottom: 25px"] td:nth-child(2):before {
            content: "Course Type";
        }
        div[style*="margin-bottom: 25px"] td:nth-child(3):before {
            content: "Subject Name";
        }
        div[style*="margin-bottom: 25px"] td:nth-child(4):before {
            content: "Subject Code";
        }
        div[style*="margin-bottom: 25px"] td:nth-child(5):before {
            content: "Tier";
        }
        div[style*="margin-bottom: 25px"] td:nth-child(6):before {
            content: "NEA";
        }
        div[style*="margin-bottom: 25px"] td:nth-child(7):before {
            content: "Action";
        }
    }

    /* Signature Canvas */
    #signatureCanvas {
        width: 100% !important;
        height: 100px !important; /* Adjusted for zoom */
    }

    /* Buttons */
    .btn {
        font-size: 11px !important; /* Adjusted for zoom */
        padding: 6px 10px !important;
    }

    #addDoc,
    #clearSignature,
    .submitNow {
        width: 100% !important;
        margin-top: 8px !important; /* Adjusted for zoom */
    }

    /* Terms and Conditions Modal */
    #termsModal {
        padding: 8px !important; /* Adjusted for zoom */
    }

    #termsModal > div {
        max-width: 95vw !important;
        margin: 16px auto !important; /* Adjusted for zoom */
        padding: 16px !important;
    }

    #termsModal h1 {
        font-size: 20px !important; /* Adjusted for zoom */
    }

    #termsModal h2 {
        font-size: 16px !important; /* Adjusted for zoom */
    }

    #termsModal p,
    #termsModal li,
    #termsModal td {
        font-size: 12px !important; /* Adjusted for zoom */
    }

    #termsModal table {
        display: block;
        overflow-x: auto;
    }
}

/* Sidebar Responsive Adjustments */
@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.active {
        transform: translateX(0);
        width: 100% !important;
        z-index: 2000;
    }

    .main-content {
        margin-left: 0 !important;
    }

    .mobile-menu-btn {
        display: block !important;
    }

    .user-name {
        display: none;
    }
}

/* Adjust Table for Larger Mobile/Tablet Screens */
@media (min-width: 577px) and (max-width: 768px) {
    div[style*="margin-bottom: 25px"] th,
    div[style*="margin-bottom: 25px"] td {
        font-size: 11px !important; /* Adjusted for zoom */
        padding: 5px !important;
    }

    div[style*="margin-bottom: 25px"] select,
    div[style*="margin-bottom: 25px"] input {
        font-size: 11px !important; /* Adjusted for zoom */
        padding: 5px !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Button styles for medium screens */
    div[style*="margin-bottom: 25px"] .add-row-button,
    .row-container .add-row-button {
        font-size: 10px !important; /* Adjusted for zoom */
        padding: 5px !important;
        width: 100% !important;
        max-width: 90px !important; /* Reduced for zoom */
    }

    /* Maintain Add button color */
    div[style*="margin-bottom: 25px"] .add-row-button.add,
    .row-container .add-row-button.add {
        background-color: #57afbfd6 !important; /* Blue for Add */
    }

    /* Maintain Delete button color */
    div[style*="margin-bottom: 25px"] .add-row-button.delete,
    .row-container .add-row-button.delete {
        background-color: #e74c3c !important; /* Red for Delete */
    }
}
</style>
