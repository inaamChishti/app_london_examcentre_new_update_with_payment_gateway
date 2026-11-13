@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding: 20px;">
    <div style="display: flex; flex-wrap: wrap; margin-right: -10px; margin-left: -10px;">
        <div style="flex: 0 0 100%; max-width: 100%; padding-right: 10px; padding-left: 10px;">
            <div style="position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word;
                        background-color: #fff; background-clip: border-box; border: none; border-radius: 10px;
                        box-shadow: 0 0.2rem 2rem 0 rgba(58,59,69,0.2); margin-bottom: 20px;">

                {{-- Card Header --}}
                <div style="padding: 15px 20px; background-color: #F87A14; border-bottom: 4px solid #1976D2;
                            display: flex; justify-content: space-between; align-items: center;
                            border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h6 style="margin: 0; font-weight: 700; font-size: 1.2rem; color: #fff;">
                        <i class="fas fa-list" style="margin-right: 8px;"></i> All Student Requests
                        <span style="font-size: 0.85rem; font-weight: 400; margin-left: 10px;">
                            ({{ $students->count() }} total records)
                        </span>
                    </h6>
                </div>

                {{-- Card Body --}}
                <div style="flex: 1 1 auto; padding: 15px;">

                    {{-- Search / Filter Bar --}}
                    <div style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                        <input type="text" id="searchInput" placeholder="Search by name, email, session…"
                            style="padding: 7px 12px; border: 1px solid #ccc; border-radius: 6px;
                                   width: 300px; font-size: 0.85rem;" />

                        <select id="statusFilter"
                            style="padding: 7px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.85rem;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>

                        <select id="sessionFilter"
                            style="padding: 7px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 0.85rem;">
                            <option value="">All Sessions</option>
                            @foreach ($students->pluck('exam_session')->unique()->filter() as $sess)
                                <option value="{{ $sess }}">{{ $sess }}</option>
                            @endforeach
                        </select>

                        <button onclick="clearFilters()"
                            style="padding: 7px 14px; background: #6c757d; color: #fff;
                                   border: none; border-radius: 6px; font-size: 0.85rem; cursor: pointer;">
                            Clear
                        </button>
                    </div>

                    <div style="overflow-x: auto;">
                        <table id="allRequestsTable"
                            style="width: 100%; font-size: 0.85rem; border-collapse: collapse;">
                            <thead style="background-color: #F87A14; color: #fff;">
                                <tr>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">#</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Name</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Date of Birth</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Email</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Contact</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Session</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Subjects</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Invoice Sent</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Status</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Submitted At</th>
                                    <th style="padding: 10px 12px; text-align: left; white-space: nowrap;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse ($students as $index => $student)
                                    @php
                                        $subjectNames = is_array($student->subject_name)
                                            ? $student->subject_name
                                            : (json_decode($student->subject_name, true) ?? [$student->subject_name]);
                                        $subjectNames = array_filter((array) $subjectNames);

                                        $hasInvoice = in_array($student->id, $invoicedRequestIds);
                                        $isApproved = $student->is_approved == 1;

                                        $rowBg = $isApproved
                                            ? '#d4edda'
                                            : ($hasInvoice ? '#fff3cd' : '#fff');
                                    @endphp
                                    <tr class="data-row"
                                        data-name="{{ strtolower($student->first_name . ' ' . $student->surname) }}"
                                        data-email="{{ strtolower($student->email) }}"
                                        data-session="{{ $student->exam_session }}"
                                        data-status="{{ $isApproved ? 'approved' : 'pending' }}"
                                        style="background-color: {{ $rowBg }}; transition: background-color 0.2s ease;">
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle;">
                                            {{ $index + 1 }}
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; font-weight: 500;">
                                            {{ $student->first_name }} {{ $student->surname }}
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; white-space: nowrap;">
                                            {{ $student->dob ?? '—' }}
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle;">
                                            {{ $student->email ?? '—' }}
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; white-space: nowrap;">
                                            {{ $student->contact_number ?? '—' }}
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; white-space: nowrap;">
                                            <span style="background: #e9ecef; padding: 2px 8px; border-radius: 12px; font-size: 0.78rem;">
                                                {{ $student->exam_session ?? '—' }}
                                            </span>
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle;">
                                            @if (count($subjectNames) > 0)
                                                @foreach ($subjectNames as $subj)
                                                    <span style="display: inline-block; background: #e3f2fd; color: #1565c0;
                                                                 padding: 2px 7px; border-radius: 10px; font-size: 0.75rem; margin: 2px 2px 2px 0;">
                                                        {{ $subj }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span style="color: #aaa;">—</span>
                                            @endif
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; text-align: center;">
                                            @if ($hasInvoice)
                                                <span style="background: #28a745; color: #fff; padding: 3px 10px; border-radius: 12px; font-size: 0.78rem;">
                                                    <i class="fas fa-check"></i> Yes
                                                </span>
                                            @else
                                                <span style="background: #dc3545; color: #fff; padding: 3px 10px; border-radius: 12px; font-size: 0.78rem;">
                                                    <i class="fas fa-times"></i> No
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; text-align: center;">
                                            @if ($isApproved)
                                                <span style="background: #28a745; color: #fff; padding: 3px 10px; border-radius: 12px; font-size: 0.78rem;">
                                                    Approved
                                                </span>
                                            @else
                                                <span style="background: #ffc107; color: #333; padding: 3px 10px; border-radius: 12px; font-size: 0.78rem;">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; white-space: nowrap; font-size: 0.78rem; color: #666;">
                                            {{ $student->created_at ? $student->created_at->format('d M Y, H:i') : '—' }}
                                        </td>
                                        <td style="padding: 9px 12px; border-bottom: 1px solid #dee2e6; vertical-align: middle; white-space: nowrap;">
                                            <a href="{{ route('viewRequest', $student->id) }}"
                                               style="display: inline-block; padding: 4px 10px; background: #17a2b8; color: #fff;
                                                      border-radius: 5px; font-size: 0.78rem; text-decoration: none; margin-right: 4px;">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('allRequests.edit', $student->id) }}"
                                               style="display: inline-block; padding: 4px 10px; background: #F87A14; color: #fff;
                                                      border-radius: 5px; font-size: 0.78rem; text-decoration: none;">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" style="text-align: center; padding: 30px; color: #888; font-size: 0.9rem;">
                                            <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 8px; color: #ccc;"></i>
                                            No student requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Legend --}}
                    <div style="margin-top: 15px; display: flex; gap: 15px; flex-wrap: wrap; font-size: 0.8rem; color: #555;">
                        <span><span style="display:inline-block; width:14px; height:14px; background:#d4edda; border:1px solid #ccc; border-radius:3px; margin-right:5px; vertical-align:middle;"></span> Approved</span>
                        <span><span style="display:inline-block; width:14px; height:14px; background:#fff3cd; border:1px solid #ccc; border-radius:3px; margin-right:5px; vertical-align:middle;"></span> Invoice Sent (Pending)</span>
                        <span><span style="display:inline-block; width:14px; height:14px; background:#fff; border:1px solid #ccc; border-radius:3px; margin-right:5px; vertical-align:middle;"></span> Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput   = document.getElementById('searchInput');
    const statusFilter  = document.getElementById('statusFilter');
    const sessionFilter = document.getElementById('sessionFilter');

    function filterTable() {
        const search  = searchInput.value.toLowerCase().trim();
        const status  = statusFilter.value;
        const session = sessionFilter.value;

        document.querySelectorAll('#tableBody .data-row').forEach(row => {
            const name    = row.dataset.name    || '';
            const email   = row.dataset.email   || '';
            const rowSess = row.dataset.session  || '';
            const rowStat = row.dataset.status   || '';

            const matchSearch  = !search  || name.includes(search) || email.includes(search) || rowSess.toLowerCase().includes(search);
            const matchStatus  = !status  || rowStat === status;
            const matchSession = !session || rowSess === session;

            row.style.display = (matchSearch && matchStatus && matchSession) ? '' : 'none';
        });
    }

    function clearFilters() {
        searchInput.value   = '';
        statusFilter.value  = '';
        sessionFilter.value = '';
        filterTable();
    }

    searchInput.addEventListener('input',  filterTable);
    statusFilter.addEventListener('change', filterTable);
    sessionFilter.addEventListener('change', filterTable);
</script>
@endsection
