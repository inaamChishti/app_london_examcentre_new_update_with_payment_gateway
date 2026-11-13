<nav class="top-nav">
    <button class="mobile-menu-btn"><i class="fas fa-bars"></i></button>
    <div class="search-bar"></div>
    <div class="user-menu">
        <div class="sessions-dropdown">
            <button class="sessions-btn" id="sessionsBtn">
                <i class="fa fa-arrows-spin"></i>
                <span id="currentSessionDisplay">{{ session('current_session', 'Summer 2025') }}</span>
            </button>

            @php
                $sessions = \DB::table('sessions')->get();
            @endphp
            <div class="sessions-menu" id="sessionsMenu">
                <div class="sessions-header">List Sessions</div>
                <div class="sessions-body">
                    @foreach ($sessions as $session)
                        <div class="session-item {{ session('current_session') == $session->session_name ? 'active' : '' }}"
                            data-session="{{ $session->session_name }}">
                            <div class="session-icon">
                                <!-- You can add specific icons for each session, or use a default one -->
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="session-info">
                                <div class="session-name">{{ $session->session_name }}</div>
                                <div class="session-meta">
                                    {{ $session->status == 'active' ? 'Active Session' : 'Inactive Session' }}</div>
                            </div>
                            <div
                                class="session-status {{ session('current_session') == $session->session_name ? 'active' : 'inactive' }}">
                                {{ session('current_session') == $session->session_name ? 'Active' : 'Inactive' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="logout-button">
            <a href="/logout" class="user-profile" style="cursor: pointer; text-decoration: none;">
                <img src="https://st2.depositphotos.com/3262111/6517/v/450/depositphotos_65173517-stock-illustration-male-avatar-profile-picture-vector.jpg"
                    alt="User" class="user-avatar">
                <span class="user-name">Logout</span>
                <i class="fas fa-sign-out-alt" style="color: red;"></i>
            </a>
        </div>
    </div>
</nav>

<style>
    .sessions-menu {
        display: none;
        position: absolute;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 300px;
    }

    .sessions-dropdown {
        position: relative;
        display: inline-block;
    }

    .sessions-dropdown:hover .sessions-menu,
    .sessions-menu.show {
        display: block;
    }

    .session-item {
        display: flex;
        padding: 10px;
        cursor: pointer;
        align-items: center;
    }

    .session-item:hover {
        background-color: #f5f5f5;
    }

    .session-item.active {
        background-color: #e8f4ff;
    }

    .session-icon {
        margin-right: 10px;
    }

    .session-info {
        flex-grow: 1;
    }

    .session-name {
        font-weight: bold;
    }

    .session-meta {
        font-size: 0.8em;
        color: #666;
    }

    .session-status {
        font-weight: bold;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .session-status.active {
        color: #28a745;
        background-color: #e8f8f0;
    }

    .session-status.inactive {
        color: #dc3545;
        background-color: #f8e8e8;
    }

    #currentSessionDisplay {
        margin-left: 5px;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sessionsBtn = document.getElementById('sessionsBtn'),
            sessionsMenu = document.getElementById('sessionsMenu'),
            currentSessionDisplay = document.getElementById('currentSessionDisplay'),
            sessionItems = document.querySelectorAll('.session-item');

        sessionsBtn.addEventListener('click', e => {
            e.stopPropagation();
            sessionsMenu.classList.toggle('show');
        });

        document.addEventListener('click', () => sessionsMenu.classList.remove('show'));

        sessionItems.forEach(item => {
            item.addEventListener('click', async function() {
                const sessionName = this.getAttribute('data-session');

                Swal.fire({
                    title: 'Please wait',
                    html: 'Session is activating...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch("{{ url('update-session') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            session: sessionName
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        sessionItems.forEach(i => {
                            i.classList.remove('active');
                            const statusEl = i.querySelector('.session-status');
                            statusEl.classList.remove('active');
                            statusEl.classList.add('inactive');
                            statusEl.textContent = 'Inactive';
                        });

                        this.classList.add('active');
                        const statusEl = this.querySelector('.session-status');
                        statusEl.classList.remove('inactive');
                        statusEl.classList.add('active');
                        statusEl.textContent = 'Active';
                        currentSessionDisplay.textContent = sessionName;

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `${sessionName} activated successfully`,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                           window.location.assign('/home'); // same kaam karega
                        });
                    }
                } catch (error) {
                    Swal.fire('Error', 'Failed to activate session', 'error');
                }
            });
        });
    });
</script>
