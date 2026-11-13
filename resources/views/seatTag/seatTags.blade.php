@extends('layouts.app')

@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <style>
        .gap-3 {
            gap: 0 !important;
        }
        #recordsGrid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
        }
        .grid-cell,
        .blank-cell {
            padding: 10px;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .blank-cell {
            background-color: #fff;
        }
        .select2-selection__choice,
        #studentSelectss,
        .printtt,
        .recordsGridsss {
            display: none;
        }
        #recordsGrid .grid-cell {
            margin-right: 4px;
            margin-bottom: 4px;
        }
    </style>

    <div class="container">
        <input type="hidden" id="oneTimeJob" value="">
        <div class="row offset-md-4">
            <div class="col-md-3">
                <label for="date" class="form-label" style="font-weight: bold;">Select Date:</label>
                <input type="date" id="date" name="date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="ampm" class="form-label" style="font-weight: bold;">Select AM/PM:</label>
                <select id="ampm" name="ampm" class="form-select">
                    <option selected disabled>Choose time</option>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            </div>
            <div class="col-md-3 mt-4 printtt">
                <button id="generatePdf" class="btn " style="zoom:0.8;margin-top: 10px; background:#F87A14;">Generate File</button>
            </div>
        </div>
        <div class="row offset-md-1">
            <div class="col-md-2 mt-5 printtt" style="margin-top: 0.9rem !important;">
                <label for="text" class="form-label" style="font-weight: bold;">Type Room Name:</label>
                <input type="text" id="text" name="text" class="form-control" placeholder="Type Room Name">
            </div>
            <div class="col-md-4 mt-3" id="studentSelectss" style="margin-top: 0.9rem !important;">
                <label for="studentSelect" class="form-label" style="font-weight: bold;">Select Students:</label>
                <select id="studentSelect" class="form-select" multiple></select>
            </div>
            <div class="col-md-2 recordsGridsss" style="margin-top: 0.9rem !important;">
                <label for="columnInput" class="form-label" style="font-weight: bold;">Grid Columns:</label>
                <input type="number" id="columnInput" class="form-control" min="1" max="6" value="6" style="width: 70%;">
            </div>
            <div class="col-md-2 recordsGridsss" style="margin-top: 3.15rem !important; margin-left: -63px; zoom:0.7;">
                <button class="btn  mt-4" type="button" id="saveLayoutBtn" style="font-weight: bold; padding: 5px 10px; width: auto; height: auto; background:#F87A14;">Save Layout</button>
            </div>
            <div class="col-md-2 recordsGridsss" style="margin-top: 3.15rem !important; margin-left: -9%; zoom:0.7;">
                <button class="btn  mt-4" type="button" id="importLayout" style="font-weight: bold; padding: 5px 10px; width: auto; height: auto;background:#F87A14;">Import Layout</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-3 recordsGridsss mt-3">
                <strong>Date: <span id="selected-date">-</span></strong>
            </div>
            <div class="col-md-2 recordsGridsss mt-3">
                <strong>Day: <span id="selected-day">-</span></strong>
            </div>
            <div class="col-md-2 recordsGridsss sic mt-3">
                <strong>Time: <span id="selected-time">-</span></strong>
            </div>
            <div class="col-md-2 recordsGridsss mt-3">
                <strong>Room: <span id="Room"></span></strong>
            </div>
            <div class="col-md-2 recordsGridsss mt-3">
                <strong>Invigilator:</strong>
            </div>
            <div class="col-md-12">
                <div id="recordsGrid" class="d-grid gap-3" style="grid-template-columns: repeat(6, 1fr);"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Global Variables
            let selectedLayoutId = null;
            let studentEntries = [];
            let selectedStudents = new Set();

            // Helper Functions
            const formatDate = d => new Date(d).toLocaleDateString('en-GB', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const toggleGridVisibility = () => {
                const selectedDate = $('#date').val(),
                      selectedAMPM = $('#ampm').val();
                (selectedDate && selectedAMPM && selectedStudents.size > 0) ?
                    $('#recordsGrid, .recordsGridsss').show() :
                    $('#recordsGrid, .recordsGridsss').hide();
            };

            // Grid Population Functions
            const populateGrid = (cal = null) => {
                $('#studentSelect').prop('disabled', false);
                const grid = $('#recordsGrid');

                if ($('#oneTimeJob').val() === 'yes') {
                    grid.find('.grid-cell').each(function() {
                        $(this).removeClass('filled').addClass('blank-cell')
                            .css('background-color', '#fff')
                            .html('')
                            .attr('data-id', '');
                    });
                    $('#oneTimeJob').val('no');
                }

                if (grid.children().length === 0) {
                    grid.css({ display: "grid", gridTemplateColumns: "repeat(6, 1fr)", gap: "10px" });
                    for (let i = 0; i < 72; i++) {
                        grid.append('<div class="grid-cell blank-cell"></div>');
                    }
                }

                grid.find('.grid-cell').each(function() {
                    let studentId = $(this).data('id');
                    if (studentId && !selectedStudents.has(studentId)) {
                        $(this).html('').attr('data-id', '').addClass('blank-cell').css('background-color', '#fff');
                    }
                });

                let blankCells = grid.find('.blank-cell');
                studentEntries.forEach(entry => {
                    if (!selectedStudents.has(entry.student_id)) return;

                    let studentCells = grid.find(`.grid-cell[data-id="${entry.student_id}"]`);
                    if (studentCells.length === 0) {
                        let records = studentEntries.filter(e => e.student_id === entry.student_id);
                        records.forEach((record, index) => {
                            let nextBlankCell = blankCells.filter(function() { return !$(this).hasClass('filled'); }).eq(index);
                            if (nextBlankCell.length && !nextBlankCell.hasClass('filled')) {
                                let boardColor = record.exam_board.toLowerCase().includes('aqa') ? '#F2D6F0' :
                                    record.exam_board.toLowerCase().includes('edexcel') ? '#D3E7EB' :
                                    record.exam_board.toLowerCase().includes('ocr') ? '#D1E1F3' :
                                    record.exam_board.toLowerCase().includes('pearson') ? '#D3E7EB' :
                                    record.exam_board.toLowerCase().includes('cie') ? '#F8D3D3' : '#fff';

                                let newStudentCell = $(`
                                    <div class="grid-cell" data-id="${record.student_id}"
                                        style="position: relative; background-color: ${boardColor}; padding: 10px;
                                        display: flex; flex-direction: column; justify-content: space-between; height: 200px;">
                                        <div style="font-size: 12px; word-wrap: break-word; text-align: left; flex-grow: 1;">
                                            <b>Candidate No:</b> ${record.student_id}<br>
                                            <b>Name:</b> ${record.surname}: ${record.firstName}<br>
                                            <b>Exam Board:</b> ${record.exam_board}<br>
                                            <b>Exam Code:</b> ${record.exam_code}<br>
                                            <b>Paper Title:</b> ${record.paper_title}<br>
                                            <b>Duration:</b> ${record.duration}<br>
                                            ${record.medical_condition ? '<b>Medical Condition:</b> ' + record.medical_condition + '<br>' : ''}
                                        </div>
                                        <button class="delete-icon"
                                            style="position: absolute; top: 5px; right: 5px; background: red; color: white;
                                            border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;
                                            display: flex; justify-content: center; align-items: center; font-size: 18px;">×</button>
                                    </div>
                                `);

                                nextBlankCell.replaceWith(newStudentCell);
                                nextBlankCell.addClass('filled');
                                blankCells = grid.find('.blank-cell');

                                newStudentCell.find('.delete-icon').on('click', function() {
                                    let parentCell = $(this).closest('.grid-cell');
                                    let studentId = parentCell.attr('data-id');
                                    parentCell.html('').attr('data-id', '').addClass('blank-cell')
                                        .css('background-color', '#fff').removeClass('filled');
                                    selectedStudents.delete(studentId);
                                    let remainingCells = grid.find(`.grid-cell[data-id="${studentId}"]`);
                                    if (remainingCells.length === 0) {
                                        let studentSelect = $('#studentSelect');
                                        let currentSelection = studentSelect.val();
                                        currentSelection = currentSelection.filter(id => id !== studentId);
                                        studentSelect.val(currentSelection).trigger('change');
                                    }
                                    toggleGridVisibility();
                                });
                            }
                        });
                    }
                });

                new Sortable(grid[0], { animation: 150, swap: true, onEnd(evt) {
                    const draggedCell = evt.item, targetCell = grid[0].children[evt.newIndex];
                    if (!targetCell || draggedCell === targetCell) return;
                    [draggedCell.innerHTML, targetCell.innerHTML] = [targetCell.innerHTML, draggedCell.innerHTML];
                }});

                $('#recordsGrid, .recordsGridsss').show();
                if (cal == 1) grid.find('.grid-cell').html('').attr('data-id', '').addClass('blank-cell').css('background-color', '#fff');
            };

            const populateGridForSpecialCase = (layout = null) => {
                console.log("populateGridForSpecialCase called with layout:", layout);
                $('#studentSelect').prop('disabled', true);
                const grid = $('#recordsGrid');

                $('#oneTimeJob').val('yes');

                grid.find('.grid-cell').each(function() {
                    $(this).removeClass('filled').addClass('blank-cell')
                        .css('background-color', '#fff')
                        .html('')
                        .attr('data-id', '');
                });

                if (grid.children().length !== 72) {
                    grid.empty();
                    grid.css({ display: "grid", gridTemplateColumns: "repeat(6, 1fr)", gap: "10px" });
                    for (let i = 0; i < 72; i++) {
                        grid.append('<div class="grid-cell blank-cell"></div>');
                    }
                }

                if (layout && layout.position) {
                    console.log("Raw position string:", layout.position);
                    let cleanedPosition = layout.position.replace(/^"|"$/g, '').replace(/\\"/g, '"');
                    console.log("Cleaned position string:", cleanedPosition);

                    let positionData;
                    try {
                        positionData = JSON.parse(cleanedPosition);
                        console.log("Parsed positionData:", positionData);
                    } catch (e) {
                        console.error("Error parsing position data:", e);
                        return;
                    }

                    const gridCells = grid.find('.grid-cell');
                    let studentIndexMap = {};

                    positionData.forEach(({ id, position }) => {
                        console.log(`Processing id: ${id}, position: ${position}`);
                        if (id && position > 0 && position <= 72) {
                            if (!studentIndexMap[id]) studentIndexMap[id] = 0;
                            const studentMatches = studentEntries.filter(entry => entry.student_id === id);
                            console.log(`Student matches for ${id}:`, studentMatches);

                            const studentEntry = studentMatches[studentIndexMap[id]];
                            if (studentEntry) {
                                console.log(`Using entry for ${id} at index ${studentIndexMap[id]}:`, studentEntry);
                                const targetCell = gridCells.eq(position - 1);
                                let boardColor = studentEntry.exam_board.toLowerCase().includes('aqa') ? '#F2D6F0' :
                                    studentEntry.exam_board.toLowerCase().includes('edexcel') ? '#D3E7EB' :
                                    studentEntry.exam_board.toLowerCase().includes('ocr') ? '#D1E1F3' :
                                    record.exam_board.toLowerCase().includes('pearson') ? '#D3E7EB' :
                                    studentEntry.exam_board.toLowerCase().includes('cie') ? '#F8D3D3' : '#fff';

                                let newStudentCell = $(`
                                    <div class="grid-cell filled" data-id="${studentEntry.student_id}"
                                        style="background-color: ${boardColor}; padding: 10px; position: relative;
                                        height: 200px; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div style="font-size: 12px; word-wrap: break-word; text-align: left; flex-grow: 1;">
                                            <b>Candidate No:</b> ${studentEntry.student_id}<br>
                                            <b>Name:</b> ${studentEntry.surname}: ${studentEntry.firstName}<br>
                                            <b>Exam Board:</b> ${studentEntry.exam_board}<br>
                                            <b>Exam Code:</b> ${studentEntry.exam_code}<br>
                                            <b>Paper Title:</b> ${studentEntry.paper_title}<br>
                                            <b>Duration:</b> ${studentEntry.duration}<br>
                                            ${studentEntry.medical_condition ? '<b>Medical Condition:</b> ' + studentEntry.medical_condition + '<br>' : ''}
                                        </div>
                                        <button class="delete-icon"
                                            style="position: absolute; top: 5px; right: 5px; background: red; color: white;
                                            border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;
                                            display: flex; justify-content: center; align-items: center; font-size: 18px;">
                                            ×
                                        </button>
                                    </div>
                                `);

                                targetCell.replaceWith(newStudentCell);
                                newStudentCell.find('.delete-icon').on('click', function() {
                                    let parentCell = $(this).closest('.grid-cell');
                                    let studentId = parentCell.attr('data-id');
                                    parentCell.html('').attr('data-id', '').addClass('blank-cell')
                                        .css('background-color', '#fff')
                                        .removeClass('filled');
                                    selectedStudents.delete(studentId);
                                });
                                studentIndexMap[id]++;
                            } else {
                                console.log(`No more entries for student ${id}`);
                            }
                        }
                    });
                } else {
                    console.log("No layout or position data provided");
                }

                new Sortable(grid[0], { animation: 150, swap: true, onEnd(evt) {
                    const draggedCell = evt.item, targetCell = grid[0].children[evt.newIndex];
                    if (!targetCell || draggedCell === targetCell) return;
                    [draggedCell.innerHTML, targetCell.innerHTML] = [targetCell.innerHTML, draggedCell.innerHTML];
                }});

                $('#recordsGrid, .recordsGridsss').show();
            };

            const populateStudentDropdown = (entries) => {
                const studentSelect = $('#studentSelect').empty();
                if (entries.length === 0) return;

                $('#studentSelectss, .printtt').show();
                [...new Set(entries.map(entry => entry.student_id))].forEach(student_id => {
                    const student = entries.find(entry => entry.student_id === student_id);
                    studentSelect.append(new Option(
                        `${student.firstName} ${student.surname} (ID: ${student.student_id})`,
                        student.student_id));
                });

                $('#studentSelect').select2({
                    placeholder: "Select Students",
                    allowClear: false,
                    closeOnSelect: false
                }).on('change', function() {
                    selectedStudents = new Set($(this).val());
                    if (selectedStudents.size == 0) {
                        populateGrid(1);
                    } else {
                        populateGrid();
                    }
                    toggleGridVisibility();
                }).on('select2:clear', function() {
                    selectedStudents.clear();
                    populateGrid();
                    toggleGridVisibility();
                });
            };

            // Data Fetching
            const fetchData = () => {
                const selectedDate = $('#date').val(),
                      selectedAMPM = $('#ampm').val();

                if (!selectedDate || !selectedAMPM) return;

                let requestData = {
                    date: selectedDate,
                    ampm: selectedAMPM
                };

                if (selectedLayoutId !== null && selectedLayoutId !== "null" && selectedLayoutId !== undefined && selectedLayoutId !== "") {
                    requestData.layout_id = selectedLayoutId;
                }

                $.ajax({
                    url: "{{ url('getClassData') }}",
                    method: "GET",
                    data: requestData,
                    success: (res) => {
                        console.log("getClassData Response:", res);
                        if (res.special === 'yes') {
                            studentEntries = res.entries;
                            console.log("studentEntries:", studentEntries);
                            console.log("layout:", res.layout);
                            populateGridForSpecialCase(res.layout);
                        } else {
                            studentEntries = res.entries;
                            populateStudentDropdown(res.entries);
                            populateGrid(1);
                        }
                    },
                    error: (xhr, status, error) => console.error("Error fetching data:", error)
                });
            };

            // Event Listeners
            $("#text").on("input", function() {
                $("#Room").text($(this).val());
            });

            document.getElementById('date').addEventListener('change', e => {
                let d = new Date(e.target.value);
                document.getElementById('selected-date').innerText = formatDate(d);
                document.getElementById('selected-day').innerText = d.toLocaleString('en-GB', { weekday: 'long' });
            });

            document.getElementById('ampm').addEventListener('change', e => {
                document.getElementById('selected-time').innerText = e.target.value;
            });

            $("#columnInput").on("input", function() {
                let col = parseInt($(this).val()) || 6,
                    widths = ["19%", "38%", "49.3333%", "80%", "87%", "100%"];
                $("#recordsGrid").css({
                    "grid-template-columns": `repeat(${col}, 1fr)`,
                    "width": widths[col - 1] || "100%"
                });
            });

            $('#date, #ampm').on('change', function() {
                selectedLayoutId = null;
                fetchData();
                toggleGridVisibility();
            });

            document.getElementById('generatePdf').addEventListener('click', () => {
                Swal.fire({
                    title: 'Please wait',
                    text: 'DOCX is generating...',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const element = document.querySelector('#recordsGrid'),
                      deleteButtons = element.querySelectorAll('.delete-icon'),
                      cells = [...element.querySelectorAll('.grid-cell')],
                      baseColumns = parseInt(document.getElementById('columnInput').value) || 6,
                      roomValue = document.getElementById("Room").textContent.trim().toLowerCase(),
                      isHall = ["hall"].includes(roomValue),
                      columnCount = isHall ? baseColumns + 1 : baseColumns,
                      logoUrl = "{{ asset('timetable/datesheetLogo.png') }}";

                deleteButtons.forEach(btn => btn.style.display = 'none');

                let docContent = `
                    <div style="text-align:center; margin-bottom:10px;">
                        <img src="${logoUrl}" width="180" height="140" alt="Logo">
                    </div>
                    <div style="padding:10px; font-size:14px; font-weight:bold;">
                        <strong>Date:</strong> ${document.getElementById("selected-date").textContent}   |
                        <strong>Day:</strong> ${document.getElementById("selected-day").textContent}   |
                        <strong>Time:</strong> ${document.getElementById("selected-time").textContent}   |
                        <strong>Room:</strong> ${document.getElementById("Room").textContent}   |
                        <strong>Invigilator:</strong> ___________________
                    </div>
                    <table border="1" style="width:100%; border-collapse:collapse; text-align:center; font-size:14px;">
                `;

                for (let i = 0; i < cells.length; i += baseColumns) {
                    let rowContent = "";
                    let hasContent = false;

                    for (let j = 0; j < columnCount; j++) {
                        let cellIndex = i + j;
                        let cellContent = "";
                        let bgColor = "#ffffff";
                        let textColor = "#000000";

                        if (j < baseColumns && cellIndex < cells.length) {
                            let cellClone = cells[cellIndex].cloneNode(true);
                            let deleteButton = cellClone.querySelector('.delete-icon');
                            if (deleteButton) deleteButton.remove();
                            cellContent = cellClone.innerHTML.trim();
                            bgColor = cells[cellIndex].style.backgroundColor || "#ffffff";
                            textColor = cells[cellIndex].style.color || "#000000";
                            if (cellContent) hasContent = true;
                        }

                        let columnLetter = String.fromCharCode(65 + j);
                        let rowNumber = Math.floor(i / baseColumns) + 1;
                        let indexing = `${columnLetter}${rowNumber}`;

                        if (cellContent) {
                            cellContent += `<div style="position:absolute; bottom:2px; right:2px; font-size:8px; color:#888; text-align:right;">${indexing}</div>`;
                        }

                        let cellStyle = `background-color:${bgColor}; color:${textColor}; padding:5px; font-size:8px; font-weight:bold; width:${100 / columnCount}%; position:relative;`;
                        if (isHall && j === columnCount - 1) {
                            cellStyle += ' background:yellow; font-weight:bold;';
                            cellContent = ' ';
                        }

                        rowContent += `<td style="${cellStyle}">${cellContent}</td>`;
                    }

                    if (hasContent) {
                        docContent += `<tr>${rowContent}</tr>`;
                    }
                }

                docContent += `</table>`;

                let htmlContent = `<!DOCTYPE html><html><head><meta charset="utf-8"><title>Records</title></head><body>${docContent}</body></html>`;
                let blob = new Blob(['\ufeff', htmlContent], { type: 'application/msword' });
                let url = URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'records.doc';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                setTimeout(() => {
                    deleteButtons.forEach(btn => btn.style.display = 'flex');
                    Swal.close();
                }, 500);
            });

            $('#saveLayoutBtn').click(function() {
                Swal.fire({
                    title: 'Enter Layout Name',
                    input: 'text',
                    inputPlaceholder: 'Layout name',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    cancelButtonText: 'Cancel',
                    inputValidator: value => !value && 'Layout name is required!'
                }).then(result => {
                    if (result.isConfirmed) {
                        var cellData = $('#recordsGrid .grid-cell').map(function(index) {
                            return { id: $(this).data('id'), position: index + 1 };
                        }).get();

                        $.ajax({
                            url: '{{ url('store-layout') }}',
                            method: 'GET',
                            data: {
                                cells: JSON.stringify(cellData),
                                date: $('#date').val(),
                                ampm: $('#ampm').val(),
                                layout_name: result.value
                            },
                            success: () => Swal.fire({ icon: 'success', title: 'Saved!', showConfirmButton: false, timer: 1500 }),
                            error: () => Swal.fire({ icon: 'error', title: 'Error!', showConfirmButton: false, timer: 1500 })
                        });
                    } else {
                        Swal.fire({ icon: 'info', title: 'Not saved', showConfirmButton: false, timer: 1500 });
                    }
                });
            });

            $('#importLayout').click(function() {
                $.ajax({
                    url: '{{ url('get-layouts') }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.message === 'Layout not found') {
                            Swal.fire('No Layout Found', 'No layouts found!', 'info');
                        } else {
                            let layoutList = '<ul style="list-style-type: none; padding: 0;">';
                            response.forEach(function(layout) {
                                layoutList += `<li style="margin: 10px 0; font-size: 16px;">
                                    <a href="#" class="select-layout" data-id="${layout.id}"
                                    style="text-decoration: none; color: #007bff; font-weight: bold; transition: color 0.3s;">
                                    ${layout.layout_name}</a></li>`;
                            });
                            layoutList += '</ul>';

                            Swal.fire({
                                icon: 'info',
                                title: 'List of Layouts',
                                html: layoutList,
                                showCancelButton: true,
                                confirmButtonText: 'Cancel',
                                cancelButtonText: 'Close',
                                width: '400px'
                            });

                            $('.select-layout').click(function(e) {
                                e.preventDefault();
                                selectedLayoutId = $(this).data('id');
                                Swal.fire('Layout Selected!', 'You picked layout ID: ' + selectedLayoutId, 'success');
                                fetchData();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Something went wrong!', 'error');
                    }
                });
            });

            // Initial Setup
            toggleGridVisibility();
        });
    </script>

@endsection
