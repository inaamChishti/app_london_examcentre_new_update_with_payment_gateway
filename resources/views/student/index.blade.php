@extends('layouts.app')

@section('content')
<div class="container-fluid" style="padding-left: 16px; padding-right: 16px;">
    <!-- Student List Table -->
    <div style="display: flex; flex-wrap: wrap; margin-right: -8px; margin-left: -8px;">
        <div style="flex: 0 0 100%; max-width: 100%; padding-right: 8px; padding-left: 8px;">
            <div style="position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: none; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); margin-bottom: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                <div style="padding: 12px 16px; background-color: #F87A14; border-bottom: 4px solid #1976D2; display: flex; justify-content: space-between; align-items: center;">
                    <h6 style="margin: 0; font-weight: 600; font-size: 1.1rem; color: #fff;">Student Management</h6>
                    <a href="{{ url('export-all-timetables') }}" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #17a2b8; border: 1px solid transparent; padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; border-radius: 0.2rem; color: white; text-decoration: none;">
                        <i class="fas fa-download" style="margin-right: 4px;"></i> Export All
                    </a>
                </div>
                <div style="flex: 1 1 auto; padding: 12px;">
                    @if (session('success'))
                        <div style="position: relative; padding: 8px 16px; margin-bottom: 16px; border: 1px solid transparent; border-radius: 0.25rem; color: #155724; background-color: #d4edda; border-color: #c3e6cb; display: flex; justify-content: space-between; align-items: center;">
                            {{ session('success') }}
                            <button type="button" style="padding: 0; background-color: transparent; border: 0; font-size: 1.5rem; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff; opacity: 0.5;" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" style="font-size: 1rem;">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Search and Filter Row -->
                    <div style="display: flex; flex-wrap: wrap; margin-right: -8px; margin-left: -8px; margin-bottom: 12px;">
                        <div style="flex: 0 0 33.333333%; max-width: 33.333333%; padding-right: 8px; padding-left: 8px;">
                            <div style="display: flex; position: relative; flex-wrap: wrap; align-items: stretch; width: 100%;">
                                <div style="display: flex; margin-right: -1px;">
                                    <span style="display: flex; align-items: center; padding: 0.25rem 0.5rem; margin-bottom: 0; font-size: 0.875rem; font-weight: 400; line-height: 1.5; color: #495057; text-align: center; white-space: nowrap; background-color: #fff; border: 1px solid #ced4da; border-radius: 0.25rem 0 0 0.25rem;">
                                        <i class="fas fa-id-card" style="color: #6c757d;"></i>
                                    </span>
                                </div>
                                <input type="text" id="searchById" style="flex: 1 1 auto; width: 1%; min-width: 0; padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0 0.25rem 0.25rem 0; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" placeholder="Student ID">
                            </div>
                        </div>
                        <div style="flex: 0 0 33.333333%; max-width: 33.333333%; padding-right: 8px; padding-left: 8px;">
                            <div style="display: flex; position: relative; flex-wrap: wrap; align-items: stretch; width: 100%;">
                                <div style="display: flex; margin-right: -1px;">
                                    <span style="display: flex; align-items: center; padding: 0.25rem 0.5rem; margin-bottom: 0; font-size: 0.875rem; font-weight: 400; line-height: 1.5; color: #495057; text-align: center; white-space: nowrap; background-color: #fff; border: 1px solid #ced4da; border-radius: 0.25rem 0 0 0.25rem;">
                                        <i class="fas fa-user" style="color: #6c757d;"></i>
                                    </span>
                                </div>
                                <input type="text" id="searchByName" style="flex: 1 1 auto; width: 1%; min-width: 0; padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0 0.25rem 0.25rem 0; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" placeholder="Student Name">
                            </div>
                        </div>
                        <div style="flex: 0 0 33.333333%; max-width: 33.333333%; padding-right: 8px; padding-left: 8px;">
                            <div style="display: flex; position: relative; flex-wrap: wrap; align-items: stretch; width: 100%;">
                                <div style="display: flex; margin-right: -1px;">
                                    <span style="display: flex; align-items: center; padding: 0.25rem 0.5rem; margin-bottom: 0; font-size: 0.875rem; font-weight: 400; line-height: 1.5; color: #495057; text-align: center; white-space: nowrap; background-color: #fff; border: 1px solid #ced4da; border-radius: 0.25rem 0 0 0.25rem;">
                                        <i class="fas fa-university" style="color: #6c757d;"></i>
                                    </span>
                                </div>
                                <select id="filterByExamBoard" style="flex: 1 1 auto; width: 1%; min-width: 0; padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0 0.25rem 0.25rem 0; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
                                    <option value="">All Exam Boards</option>
                                    <option value="AQA">AQA</option>
                                    <option value="Edexcel">Edexcel</option>
                                    <option value="OCR">OCR</option>
                                    <option value="WJEC">WJEC</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="overflow-x: auto;">
                        <table id="studentTable" style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                            <thead style="background-color: #F87A14; color: #fff;">
                                <tr>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">CANDIDATE NUMBER</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">Name</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">Withdraw</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">Gender</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">DOB</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">Exam Board</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">QUALIFICATION</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">Documents</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem; font-weight: 600; text-align: left;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="transition: background-color 0.2s;">
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">S1001</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">John Smith</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">
                                        <select onchange="handleWithdrawal(this, 'S1001')" style="display: block; width: 100%; padding: 0.25rem 0.5rem; font-size: 0.8rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
                                            <option disabled selected>Decision</option>
                                            <option value="withdraw">Withdraw</option>
                                            <option value="no_withdrawal">Undo</option>
                                        </select>
                                    </td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">Male</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">15/05/2004</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">AQA</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">GCSE</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">
                                        <div style="border: 1px solid #dee2e6; padding: 8px; border-radius: 4px; background-color: #f8f9fa;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">Photo</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                            <hr style="margin-top: 4px; margin-bottom: 4px; border: 0; border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">ID Document</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                            <hr style="margin-top: 4px; margin-bottom: 4px; border: 0; border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">Supporting Documents</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                            <hr style="margin-top: 4px; margin-bottom: 4px; border: 0; border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">Additional Docs</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">
                                        <div style="display: flex; position: relative;">
                                            <button onclick="submitForm('S1001')" title="View" style="margin-right: 4px; display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #007bff; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button title="Edit" style="margin-right: 4px; display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-edit" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button title="Duplicate" style="margin-right: 4px; display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #03a9f4; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-copy" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button title="Delete" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #dc3545; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="transition: background-color 0.2s;">
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">S1002</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">Sarah Johnson</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">
                                        <select onchange="handleWithdrawal(this, 'S1002')" style="display: block; width: 100%; padding: 0.25rem 0.5rem; font-size: 0.8rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
                                            <option disabled selected>Decision</option>
                                            <option value="withdraw">Withdraw</option>
                                            <option value="no_withdrawal">Undo</option>
                                        </select>
                                    </td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">Female</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">22/08/2005</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">Edexcel</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">A-Level</td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">
                                        <div style="border: 1px solid #dee2e6; padding: 8px; border-radius: 4px; background-color: #f8f9fa;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">Photo</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                            <hr style="margin-top: 4px; margin-bottom: 4px; border: 0; border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">ID Document</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                            <hr style="margin-top: 4px; margin-bottom: 4px; border: 0; border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">Supporting Documents</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                            <hr style="margin-top: 4px; margin-bottom: 4px; border: 0; border-top: 1px solid rgba(0, 0, 0, 0.1);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                                <div><small style="font-weight: 600;">Additional Docs</small></div>
                                                <div><a href="#" style="padding: 0; color: #007bff; text-decoration: none; background-color: transparent;"><small>View</small></a></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 8px 12px; vertical-align: middle; font-size: 0.825rem; border-bottom: 1px solid #dee2e6;">
                                        <div style="display: flex; position: relative;">
                                            <button onclick="submitForm('S1002')" title="View" style="margin-right: 4px; display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #007bff; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button title="Edit" style="margin-right: 4px; display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-edit" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button title="Duplicate" style="margin-right: 4px; display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #03a9f4; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-copy" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button title="Delete" style="display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; background-color: #dc3545; border: 1px solid transparent; padding: 0.2rem 0.4rem; font-size: 0.875rem; line-height: 1; border-radius: 0.2rem; color: white;">
                                                <i class="fas fa-trash" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        var table = $('#studentTable').DataTable({
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>'
                },
                lengthMenu: 'Show <select class="form-control form-control-sm">'+
                    '<option value="10">10</option>'+
                    '<option value="25">25</option>'+
                    '<option value="50">50</option>'+
                    '<option value="100">100</option>'+
                    '<option value="-1">All</option>'+
                    '</select> records'
            },
            dom: '<"top"lf>rt<"bottom"ip>',
            pageLength: 10
        });

        // Search by Student ID
        $('#searchById').on('keyup', function() {
            table.column(0).search(this.value).draw();
        });

        // Search by Student Name
        $('#searchByName').on('keyup', function() {
            table.column(1).search(this.value).draw();
        });

        // Filter by Exam Board
        $('#filterByExamBoard').on('change', function() {
            table.column(5).search(this.value).draw();
        });
    });

    function submitForm(studentId) {
        // Simulate form submission
        alert('Viewing student: ' + studentId);
    }

    function handleWithdrawal(selectElement, studentId) {
        const selectedValue = selectElement.value;

        if (selectedValue === 'withdraw') {
            if (confirm("Are you sure you want to withdraw student " + studentId + "?")) {
                alert('Student ' + studentId + ' withdrawn successfully');
            } else {
                selectElement.selectedIndex = 0;
            }
        } else if (selectedValue === 'no_withdrawal') {
            alert('Withdrawal undone for student ' + studentId);
        }
    }
</script>

@endsection
