<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Offered Subjects</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .custom-file-label::after { content: "Browse"; }
        .sample-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .sample-table th, .sample-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .sample-table th { background-color: #f2f2f2; }
        .sample-table tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-file-import"></i> Import Offered Subjects</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            @if(session('imported'))
                                <br>Imported: {{ session('imported') }} records
                            @endif
                            @if(session('skipped'))
                                <br>Skipped: {{ session('skipped') }} records
                            @endif
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <strong>Note:</strong> Excel file must have these columns in order:
                        <ol>
                            <li><strong>Qualification</strong> (e.g., CIPQ, GCE AS & A Level)</li>
                            <li><strong>Code</strong> (e.g., 9980/01, 8021/11)</li>
                            <li><strong>Syllabus</strong> (e.g., Cambridge International Project, English General Paper)</li>
                            <li><strong>Component Title</strong> (e.g., Project, Essay 11)</li>
                            <li><strong>Exam Date</strong> (format: DD/MM/YYYY or YYYY-MM-DD)</li>
                            <li><strong>Session</strong> (e.g., AM, PM, -)</li>
                            <li><strong>Duration</strong> (e.g., 1h 15m, 16m, -)</li>
                            <li><strong>Marks Due Deadline</strong> (format: DD/MM/YYYY or YYYY-MM-DD)</li>
                            <li><strong>Board</strong> (e.g., CIE, AQA)</li>
                        </ol>
                    </div>

                    <form action="{{ url('submit-import-datesheet-subjects') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excel_file">Select Excel File</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="excel_file" name="file" required accept=".xlsx,.xls,.csv">
                                <label class="custom-file-label" for="excel_file">Choose file...</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('.custom-file-input').on('change', function () {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
</body>
</html>
