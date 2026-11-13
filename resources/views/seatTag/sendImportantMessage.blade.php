@extends('layouts.app')

@section('content')

<div class="container py-4" style="">
        <div class="card shadow-sm" style="border-color: #b2e4ede6; border-width: 2px; margin-left: 2%;">
            <div class="card-header" style="background-color: #F87A14; border-bottom: 4px solid #1976D2;">
                <h2 class="mb-0" style="color: white;">Send Important Message</h2>
            </div>

            <div class="card-body">
    <script>
                // Initialize Toast using SweetAlert2
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                // Success Toast
                @if(session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: @json(session('success'))
                    });
                @endif

                // Error Toasts
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        Toast.fire({
                            icon: 'error',
                            title: @json($error)
                        });
                    @endforeach
                @endif
            </script>


                <form action="{{ url('send-important-message') }}" method="POST" id="messageForm" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="emails" class="form-label fw-bold">Select Recipients</label>
                        <select name="emails[]" id="emails" class="form-control select2" multiple required>
                            @foreach ($students as $email => $display)
                                <option value="{{ $email }}" {{ in_array($email, old('emails', [])) ? 'selected' : '' }}>{{ $display }}</option>
                            @endforeach
                        </select>
                        @error('emails')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">You can select multiple recipients</div>
                    </div>

                    <div class="mb-4">
                        <label for="subject" class="form-label fw-bold">Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Include CKEditor 5 and necessary plugins -->
                    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
                    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/html-support/html-support.js"></script>
                    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/font/font.js"></script>

                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-bold mb-0">Message (HTML Email Template)</label>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small me-2">Mode:</span>
                                <div class="btn-group btn-group-sm" role="group" id="editorModeGroup">
                                    <button type="button" class="btn btn-warning active" id="btnEditorMode" onclick="switchMode('editor')">
                                        ✏️ Visual Editor
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="btnHtmlMode" onclick="switchMode('html')">
                                        &lt;/&gt; Paste HTML
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- VISUAL EDITOR MODE --}}
                        <div id="visualEditorWrapper">
                            <div id="editor" style="min-height: 400px;"></div>
                        </div>

                        {{-- HTML PASTE MODE --}}
                        <div id="htmlPasteWrapper" style="display:none;">
                            <div class="alert alert-info py-2 mb-2" style="font-size:13px;">
                                <strong>📋 Paste your full HTML here.</strong> A live preview will appear below automatically.
                            </div>
                            <textarea id="htmlPasteInput" class="form-control font-monospace"
                                rows="10"
                                placeholder="Paste your full HTML email code here..."
                                style="font-size:12px; border:2px dashed #6b7280; background:#f8f9fa; resize:vertical;"></textarea>
                            <div class="mt-3">
                                <label class="fw-bold small text-muted mb-1">📧 Live Email Preview:</label>
                                <div style="border:2px solid #F87A14; border-radius:8px; overflow:hidden; background:#f3f4f6;">
                                    <iframe id="htmlPreviewFrame"
                                        style="width:100%; min-height:600px; border:none; display:block;"
                                        sandbox="allow-same-origin">
                                    </iframe>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden textarea to store final message content -->
                        <textarea name="message" id="message" class="form-control d-none" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-4">
        <label for="attachments" class="form-label fw-bold">Attachments (PDF, Image, or File)</label>
        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        @error('attachments.*')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        <div class="form-text">You can upload multiple files (PDF, JPG, PNG, DOC, DOCX).</div>
    </div>

                    <!-- Add Please Wait Spinner -->
                    <div id="loadingSpinner" style="display: none;" class="text-center mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Please Wait...</span>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn text-white" id="submitButton" style="background-color: #b2e4edd6; border-color: #8fd8e8;">
                            <i class="fas fa-paper-plane me-2"></i> Send Emails
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

   <script>
    // ── Mode switching ─────────────────────────────────────────────────────
    let currentMode = 'editor'; // 'editor' | 'html'

    function switchMode(mode) {
        currentMode = mode;

        if (mode === 'editor') {
            document.getElementById('visualEditorWrapper').style.display = '';
            document.getElementById('htmlPasteWrapper').style.display = 'none';
            document.getElementById('btnEditorMode').classList.add('active', 'btn-warning');
            document.getElementById('btnEditorMode').classList.remove('btn-outline-secondary');
            document.getElementById('btnHtmlMode').classList.remove('active', 'btn-warning');
            document.getElementById('btnHtmlMode').classList.add('btn-outline-secondary');
        } else {
            document.getElementById('visualEditorWrapper').style.display = 'none';
            document.getElementById('htmlPasteWrapper').style.display = '';
            document.getElementById('btnHtmlMode').classList.add('active', 'btn-warning');
            document.getElementById('btnHtmlMode').classList.remove('btn-outline-secondary');
            document.getElementById('btnEditorMode').classList.remove('active', 'btn-warning');
            document.getElementById('btnEditorMode').classList.add('btn-outline-secondary');

            // Trigger preview update if there's already content
            updateHtmlPreview();
        }
    }

    function updateHtmlPreview() {
        const html = document.getElementById('htmlPasteInput').value;
        const frame = document.getElementById('htmlPreviewFrame');
        const doc = frame.contentDocument || frame.contentWindow.document;
        doc.open();
        doc.write(html);
        doc.close();

        // Auto-resize iframe to content height
        try {
            frame.style.height = (doc.body.scrollHeight + 40) + 'px';
        } catch(e) {}
    }

    // Live preview as user types/pastes
    document.addEventListener('DOMContentLoaded', function() {
        const htmlInput = document.getElementById('htmlPasteInput');
        if (htmlInput) {
            htmlInput.addEventListener('input', updateHtmlPreview);
            htmlInput.addEventListener('paste', function() {
                // Small delay to let paste complete
                setTimeout(updateHtmlPreview, 50);
            });
        }
    });

    $(document).ready(function() {
        // Ensure editor element exists
        if (!document.getElementById('editor')) {
            console.error('CKEditor container (#editor) not found in DOM');
            return;
        }

        // Default editable content
        var defaultEditableContent = `{!! addslashes('
            <h2 style="color: #2e6da4;">Dear Candidate,</h2>
            <p>We hope you’re well and feeling prepared for your upcoming exams. Please take note of the following important instructions and guidelines to ensure a smooth and compliant exam day experience:</p>
            <h3 style="color: #d9534f;">📋 Exam Day Requirements:</h3>
            <ul>
                <li><strong>Clothing:</strong> Please wear <span style="color: #2e6da4;">plain shirts</span> with no writing.</li>
                <li><strong>Water Bottles:</strong> Only <span style="color: #2e6da4;">transparent bottles</span> are allowed, with no labels.</li>
                <li><strong>Stationery:</strong> Must be brought in a <span style="color: #2e6da4;">transparent pencil case</span> or plastic wallet.</li>
                <li><strong>Bags:</strong> <span style="color: #d9534f;">Not permitted</span> inside the exam room.</li>
                <li><strong>Watches:</strong> <span style="color: #d9534f;">Not allowed</span> (digital, smart, or analogue).</li>
            </ul>
            <h3 style="color: #5cb85c;">🕒 Exam Start Times:</h3>
            <p><strong>Cambridge International (CIE) Exams:</strong></p>
            <ul>
                <li>Morning: <strong>09:30 AM</strong></li>
                <li>Afternoon: <strong>01:30 PM</strong></li>
            </ul>
            <p><strong>AQA, Edexcel, WJEC, OCR Exams:</strong></p>
            <ul>
                <li>Morning: <strong>09:00 AM</strong></li>
                <li>Afternoon: <strong>01:00 PM</strong></li>
            </ul>
            <p><em>Please make sure you arrive at least <strong>30 minutes before</strong> your scheduled exam time to allow for check-in and seating.</em></p>
            <p>If you have any questions or require clarification, feel free to get in touch with us.</p>
            <p>Wishing you the very best in your exams!</p>
            <p>Kind regards,<br>
            <strong>Exams Team</strong><br>
            London Exam Centre</p>
        ') !!}`;

        // Initialize CKEditor 5 with HTML Support and custom font colors
        let editorInstance;
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontColor', 'fontBackgroundColor', '|',
                    'link', 'imageUpload', '|',
                    'bulletedList', 'numberedList', '|',
                    'alignment', '|',
                    'blockQuote', 'codeBlock', '|',
                    'undo', 'redo'
                ],
                image: {
                    toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
                },
                fontColor: {
                    colors: [
                        { color: '#2e6da4', label: 'Primary Blue' },
                        { color: '#d9534f', label: 'Red' },
                        { color: '#5cb85c', label: 'Green' },
                        { color: '#337ab7', label: 'Link Blue' },
                        { color: '#777', label: 'Gray' },
                        { color: '#000000', label: 'Black' }
                    ],
                    colorPicker: {
                        allowCustomColors: true
                    }
                },
                fontBackgroundColor: {
                    colors: [
                        { color: 'transparent', label: 'Transparent' },
                        { color: '#f5f7fa', label: 'Light Gray' }
                    ]
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: ['style'],
                            styles: ['color', 'font-weight', 'font-style', 'text-decoration', 'text-align', 'margin-bottom', 'max-width', 'margin-top', 'font-size', 'padding', 'border-radius', 'box-shadow']
                        },
                        {
                            name: /^(div|span|img|a|h1|h2|h3|h4|h5|h6|p)$/,
                            attributes: ['style', 'src', 'alt', 'href']
                        }
                    ],
                    disallow: []
                },
                extraPlugins: [
                    function(editor) {
                        // Extend schema to allow fontColor and style attributes for text and inline elements
                        editor.model.schema.register('span', {
                            inheritAllFrom: '$inline',
                            allowAttributes: ['fontColor', 'style']
                        });
                        editor.model.schema.extend('$text', {
                            allowAttributes: ['fontColor', 'style']
                        });

                        // Upcast: Convert style attributes to model
                        editor.conversion.for('upcast').elementToElement({
                            view: {
                                name: /.*/,
                                attributes: ['style']
                            },
                            model: (viewElement, { writer }) => {
                                const styles = viewElement.getStyle();
                                if (styles && styles.color) {
                                    return writer.createElement('span', {
                                        fontColor: styles.color,
                                        style: `color:${styles.color}`
                                    });
                                }
                                return null;
                            }
                        });

                        // Downcast: Convert model attributes back to view
                        editor.conversion.for('downcast').elementToElement({
                            model: 'span',
                            view: (modelElement, { writer }) => {
                                const attributes = {};
                                if (modelElement.getAttribute('fontColor')) {
                                    attributes.style = `color:${modelElement.getAttribute('fontColor')}`;
                                }
                                if (modelElement.getAttribute('style')) {
                                    attributes.style = modelElement.getAttribute('style');
                                }
                                return writer.createContainerElement('span', attributes);
                            }
                        });

                        // Preserve styles during text edits
                        editor.model.document.on('change', () => {
                            editor.model.change(writer => {
                                const selection = editor.model.document.selection;
                                const range = selection.getFirstRange();
                                if (!range) return;

                                for (const item of range.getItems()) {
                                    if (item.is('$text')) {
                                        const parent = item.parent;
                                        if (parent) {
                                            const parentStyles = parent.getAttribute('style');
                                            const parentColor = parent.getAttribute('fontColor');
                                            if (parentStyles || parentColor) {
                                                writer.setAttributes({
                                                    style: parentStyles || `color:${parentColor}`,
                                                    fontColor: parentColor || parentStyles?.match(/color:([^;]+)/)?.[1]
                                                }, item);
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    }
                ]
            })
            .then(editor => {
                editorInstance = editor;
                // Populate with existing content or default editable content
                const initialContent = `{!! old('message') ? addslashes(old('message')) : '' !!}`;
                if (initialContent && initialContent.trim() !== '') {
                    editor.setData(initialContent);
                    document.getElementById('message').value = initialContent;
                } else {
                    editor.setData(defaultEditableContent);
                    document.getElementById('message').value = defaultEditableContent;
                }

                // Sync CKEditor content with hidden textarea
                editor.model.document.on('change:data', () => {
                    let htmlContent = editor.getData();
                    // Reapply styles to critical elements to prevent loss
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(htmlContent, 'text/html');
                    const h2 = doc.querySelector('h2');
                    if (h2 && !h2.style.color) {
                        h2.style.color = '#2e6da4';
                    }
                    const h3s = doc.querySelectorAll('h3');
                    h3s.forEach(h3 => {
                        if (h3.textContent.includes('Exam Day Requirements') && !h3.style.color) {
                            h3.style.color = '#d9534f';
                        }
                        if (h3.textContent.includes('Exam Start Times') && !h3.style.color) {
                            h3.style.color = '#5cb85c';
                        }
                    });
                    const spans = doc.querySelectorAll('span');
                    spans.forEach(span => {
                        if (['plain shirts', 'transparent bottles', 'transparent pencil case'].includes(span.textContent) && !span.style.color) {
                            span.style.color = '#2e6da4';
                        }
                        if (['Not permitted', 'Not allowed'].includes(span.textContent) && !span.style.color) {
                            span.style.color = '#d9534f';
                        }
                    });
                    htmlContent = doc.body.innerHTML;
                    document.getElementById('message').value = htmlContent;
                    console.log('CKEditor Content:', htmlContent); // Debug content
                });
            })
            .catch(error => {
                console.error('Failed to initialize CKEditor:', error);
            });

        // Wrap the editable content with the static template during form submission
        $('#messageForm').on('submit', function(e) {
            e.preventDefault();

            if (currentMode === 'html') {
                // HTML paste mode: use the raw pasted HTML directly
                const pastedHtml = document.getElementById('htmlPasteInput').value.trim();
                if (!pastedHtml) {
                    Swal.fire({ icon: 'error', title: 'Please paste your HTML content before sending.' });
                    return;
                }
                document.getElementById('message').value = pastedHtml;
            } else {
                // Visual editor mode: wrap with template
                let editableContent = document.getElementById('message').value;
                console.log('Editable Content Before Wrapping:', editableContent);

                const fullTemplate = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <title>Exam Day Instructions</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="{{ asset('images/LondonExamCentre.png') }}" alt="London Exam Centre Logo" style="max-width: 220px;">
                        </div>
                        <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                            ${editableContent}
                        </div>
                        <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
                            <p>
                                140 Paul Street Stratford, London – E15 4FF<br>
                                Phone: <a href="tel:+447538555089" style="color: #337ab7;">+44 7538 555089</a> |
                                Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
                            </p>
                        </div>
                    </body>
                    </html>
                `;
                document.getElementById('message').value = fullTemplate;
            }

            $('#loadingSpinner').show();
            $('#submitButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Sending...');
            this.submit();
        });

        // Initialize Select2
        $('#emails').select2({
            placeholder: "Select email addresses",
            allowClear: true,
            width: '100%',
            dropdownParent: $('.card-body'),
            dropdownAutoWidth: true,
            minimumResultsForSearch: 10
        });

        // Add Select All and Unselect All buttons
        $('#emails').on('select2:open', function() {
            if (!$('.select2-container .select2-buttons').length) {
                const dropdown = $('.select2-container--open .select2-dropdown');
                dropdown.prepend(
                    '<div class="select2-buttons p-2 border-bottom" style="position: sticky; top: 0; background: white; z-index: 10;">' +
                    '<button type="button" class="btn btn-sm me-2 text-white select-all" style="background-color: #b2e4edd6;">Select All</button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary unselect-all">Clear All</button>' +
                    '</div>'
                );

                $('.select-all').on('click', function() {
                    $('#emails option').prop('selected', true);
                    $('#emails').trigger('change');
                    $('#emails').trigger('close');
                });

                $('.unselect-all').on('click', function() {
                    $('#emails option').prop('selected', false);
                    $('#emails').trigger('change');
                    $('#emails').trigger('close');
                });
            }
        });
    });
</script>

  <style>
    /* General Container and Card Styling */
.container.py-4 {
    zoom: 0.9;
}

.card {
    border-color: #F87A14 !important; /* Darker cyan for border */
    border-width: 2px;
    border-radius: 10px;
    overflow: hidden;
    margin-left: 13%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important; /* Enhanced shadow */
}

.card-header {
    background-color: #F87A14 !important; /* Darker cyan for header */
    color: #ffffff !important; /* White text for contrast */
    padding: 1rem;
}

.card-body {
    background-color: #f8fafc; /* Light gray for body */
    padding: 1.5rem;
}

/* Form Elements */
.form-control, .select2-container .select2-selection--multiple {
    border-color: #6b7280 !important; /* Darker gray for borders */
    border-radius: 6px;
    background-color: #ffffff;
    color: #1f2937 !important; /* Dark gray for text */
}

.form-control:focus, .select2-container--focus .select2-selection--multiple {
    border-color: #3b82f6 !important; /* Vibrant blue for focus */
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3) !important;
}

.form-label.fw-bold {
    color: #1f2937 !important; /* Dark gray for labels */
}

/* Select2 Styling */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #F87A14 !important; /* Darker cyan for chips */
    border-color: #3b82f6 !important; /* Vibrant blue border */
    color: #ffffff !important; /* White text */
    padding: 2px 8px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffffff !important; /* White for remove icon */
    margin-right: 4px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    padding-left: 19px;
    padding-right: 5px;
}

.select2-container .select2-selection--multiple {
    min-height: 120px;
    max-height: 150px;
    overflow-y: auto;
    padding: 8px;
}

.select2-results__option--highlighted {
    background-color: #3b82f6 !important; /* Vibrant blue for highlight */
    color: #ffffff !important; /* White text */
}

.select2-container--open .select2-dropdown {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #6b7280 !important; /* Darker gray border */
    border-radius: 4px;
    background-color: #ffffff;
}

.select2-results {
    max-height: 250px;
    overflow-y: auto;
}

.select2-buttons {
    position: sticky;
    top: 0;
    background: #ffffff;
    z-index: 10;
}

/* Buttons */
.btn.text-white {
    background-color: #F87A14 !important; /* Darker cyan for button */
    border-color: #3b82f6 !important; /* Vibrant blue border */
    transition: background-color 0.3s ease;
}

.btn.text-white:hover {
    background-color: #3b82f6 !important; /* Vibrant blue on hover */
    border-color: #2563eb !important; /* Darker blue border */
}

.select-all {
    background-color: #F87A14 !important; /* Darker cyan */
    color: #ffffff !important;
}

.select-all:hover {
    background-color: #3b82f6 !important; /* Vibrant blue */
}

.unselect-all {
    border-color: #6b7280 !important; /* Darker gray */
    color: #1f2937 !important; /* Dark gray text */
}

.unselect-all:hover {
    background-color: #e5e7eb !important; /* Light gray background */
}

/* Alerts */
.alert-success {
    background-color: #10b981 !important; /* Vibrant green */
    color: #ffffff !important;
    border-color: #059669 !important; /* Darker green */
}

.alert-danger {
    background-color: #ef4444 !important; /* Vibrant red */
    color: #ffffff !important;
    border-color: #dc2626 !important; /* Darker red */
}

/* Loading Spinner */
#loadingSpinner {
    color: #4a90a6 !important; /* Darker cyan */
    font-weight: bold;
}

.spinner-border.text-primary {
    border-color: #3b82f6 !important; /* Vibrant blue */
    border-right-color: transparent !important;
}

/* CKEditor Styling */
.ck-editor__editable {
    min-height: 400px;
    background-color: #f1f5f9 !important; /* Light gray */
    border-radius: 8px;
    padding: 10px;
    border: 1px solid #6b7280 !important; /* Darker gray border */
    color: #1f2937 !important; /* Dark gray text */
}

.ck-editor__editable img {
    max-width: 100%;
    height: auto;
}

/* Textarea */
textarea {
    resize: vertical;
}
  </style>

@endsection
