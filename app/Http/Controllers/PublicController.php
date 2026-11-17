<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{OfferedSubject, Result, Layout, Session, User, Subjects, StudentRequest, StudentDetail, Payment, AccessPermission, CandidateAndUciNumber, SeatingArrangement};
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Carbon\Carbon;
use App\Helpers\EmailLogHelper;


class PublicController extends Controller
{
    // public function showPublicPage()
    // {


    //     $current_session = Session::where('status', 'active')->first()->session_name;
    //     // dd($current_session);
    //     $subjectsData = OfferedSubject::where('session',$current_session)->select('examboard as examBoard', 'qualification as courseType', 'description as subjectName', 'code as subjectCode')
    //         ->orderBy('examboard')
    //         ->orderBy('qualification')
    //         ->orderBy('description')
    //         ->get();

    //     $data = [
    //         'boards' => $subjectsData->pluck('examBoard')->unique()->values(),
    //         'courseTypesByBoard' => $subjectsData->groupBy('examBoard')->map(function ($items) {
    //             return $items->pluck('courseType')->unique()->values();
    //         }),
    //         'subjectsByBoardAndCourse' => $subjectsData->groupBy(['examBoard', 'courseType'])->map(function ($courseTypes) {
    //             return $courseTypes->map(function ($subjects) {
    //                 return $subjects->mapWithKeys(function ($item) {
    //                     return [$item->subjectName => $item->subjectCode];
    //                 });
    //             });
    //         }),
    //         'sessions' => Session::all(), // Fetch all sessions
    //         'activeSession' => Session::where('status', 'active')->first()
    //     ];

    //     return view('student.addStudentPublic', $data);
    // }
    public function showPublicPage()
    {
        // Show only Summer 2027 in the dropdown (Autumn 2026 removed)
        $sessions = Session::whereIn('session_name', ['Summer 2027'])->get();
        $activeSession = Session::where('session_name', 'Summer 2027')->first();

        // Fetch subjects data for Summer 2027
        $subjectsData = OfferedSubject::select('examboard as examBoard', 'qualification as courseType', 'description as subjectName', 'code as subjectCode', 'session', 'amount')
            ->whereIn('session', ['Summer 2027'])
            ->orderBy('examboard')
            ->orderBy('qualification')
            ->orderBy('description')
            ->get()
            ->groupBy('session');

        // Build specification_code → entry_codes[] map from option_codes table
        $optionCodesMap = \DB::table('option_codes')
            ->select('specification_code', 'entry_code')
            ->whereNotNull('specification_code')
            ->whereNotNull('entry_code')
            ->distinct()
            ->get()
            ->groupBy('specification_code')
            ->map(fn($rows) => $rows->pluck('entry_code')->unique()->values());

        $data = [
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'optionCodesMap' => $optionCodesMap,
            'subjectsBySession' => $subjectsData->map(function ($sessionSubjects) {
                return [
                    'boards' => $sessionSubjects->pluck('examBoard')->unique()->values(),
                    'qualifications' => $sessionSubjects->pluck('courseType')->unique()->values(),
                    'boardsByQualification' => $sessionSubjects->groupBy('courseType')->map(function ($items) {
                        return $items->pluck('examBoard')->unique()->values();
                    }),
                    'subjectsByQualificationAndBoard' => $sessionSubjects->groupBy(['courseType', 'examBoard'])->map(function ($boards) {
                        return $boards->map(function ($subjects) {
                            return $subjects->mapWithKeys(function ($item) {
                                return [$item->subjectName => [
                                    'code' => $item->subjectCode,
                                    'amount' => $item->amount,
                                ]];
                            });
                        });
                    }),
                    'courseTypesByBoard' => $sessionSubjects->groupBy('examBoard')->map(function ($items) {
                        return $items->pluck('courseType')->unique()->values();
                    }),
                    'subjectsByBoardAndCourse' => $sessionSubjects->groupBy(['examBoard', 'courseType'])->map(function ($courseTypes) {
                        return $courseTypes->map(function ($subjects) {
                            return $subjects->mapWithKeys(function ($item) {
                                return [$item->subjectName => $item->subjectCode];
                            });
                        });
                    }),
                ];
            }),
        ];

        return view('student.addStudentPublic', $data);
    }

  public function submitStudent(Request $request)
    {
        // Validate the submitted basket against the server-side exam catalogue.
        // Never trust prices or exam combinations sent by the browser.
        $validator = Validator::make($request->all(), [
            'session' => 'required|string|exists:sessions,session_name',
            'examBoard' => 'required|array|min:1',
            'examBoard.*' => 'required|string|max:255',
            'courseType' => 'required|array|min:1',
            'courseType.*' => 'required|string|max:255',
            'subjectName' => 'required|array|min:1',
            'subjectName.*' => 'required|string|max:255',
            'subjectCode' => 'required|array|min:1',
            'subjectCode.*' => 'required|string|max:100',
            'tire' => 'required|array|min:1',
            'tire.*' => 'required|in:N/A,Foundation Tier,Higher Tier',
            'nea' => 'required|array|min:1',
            'nea.*' => "required|in:N/A,Carry Forward,Frobel's NEA",
        ]);

        $validator->after(function ($validator) use ($request) {
            $boards = $request->input('examBoard', []);
            $qualifications = $request->input('courseType', []);
            $subjects = $request->input('subjectName', []);
            $codes = $request->input('subjectCode', []);
            $tiers = $request->input('tire', []);
            $neas = $request->input('nea', []);

            if (!is_string($request->input('session'))) {
                $validator->errors()->add('session', 'Please select a valid exam session.');
                return;
            }

            foreach ([$boards, $qualifications, $subjects, $codes, $tiers, $neas] as $basketField) {
                if (!is_array($basketField)) {
                    $validator->errors()->add('basket', 'Your exam basket data is invalid. Please select your exams again.');
                    return;
                }
            }

            $count = count($boards);

            if (
                $count < 1 ||
                count($qualifications) !== $count ||
                count($subjects) !== $count ||
                count($codes) !== $count ||
                count($tiers) !== $count ||
                count($neas) !== $count
            ) {
                $validator->errors()->add('basket', 'Your exam basket is incomplete. Please remove the selections and add them again.');
                return;
            }

            $seen = [];
            foreach ($boards as $index => $board) {
                if (
                    !isset($qualifications[$index], $subjects[$index], $codes[$index], $tiers[$index], $neas[$index]) ||
                    !is_string($board) ||
                    !is_string($qualifications[$index] ?? null) ||
                    !is_string($subjects[$index] ?? null) ||
                    !is_string($codes[$index] ?? null)
                ) {
                    $validator->errors()->add('basket', 'Your exam basket rows do not match. Please select your exams again.');
                    continue;
                }

                $optionCodes = $request->input('optionCode', []);
                $key = implode('|', [
                    $request->input('session'),
                    $board,
                    $qualifications[$index],
                    $subjects[$index],
                    $codes[$index],
                    $optionCodes[$index] ?? '',
                ]);

                if (isset($seen[$key])) {
                    $validator->errors()->add('basket', 'The same exam cannot be added to the basket more than once.');
                    continue;
                }
                $seen[$key] = true;

                $offering = OfferedSubject::query()
                    ->where('session', $request->input('session'))
                    ->where('examboard', $board)
                    ->where('qualification', $qualifications[$index])
                    ->where('description', $subjects[$index])
                    ->where('code', $codes[$index])
                    ->first();

                if (!$offering || !is_numeric($offering->amount) || (float) $offering->amount <= 0) {
                    $validator->errors()->add(
                        "subjectCode.{$index}",
                        'One of your selected exams is not available for this session or does not have an approved price.'
                    );
                }
            }
        });

        $validator->validate();
        // dd($request->all());
        $activeSession = Session::where('status', 'active')->first();
        $basePath = public_path('studentDocs');

        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = 'photo_' . time() . '.' . $photo->getClientOriginalExtension();
            $photo->move($basePath, $photoName);
            $photoPath = 'studentDocs/' . $photoName;
        }

        $idDocumentPath = null;
        if ($request->hasFile('idDocument')) {
            $idDocument = $request->file('idDocument');
            $idDocumentName = 'id_' . time() . '.' . $idDocument->getClientOriginalExtension();
            $idDocument->move($basePath, $idDocumentName);
            $idDocumentPath = 'studentDocs/' . $idDocumentName;
        }

        $supportingDocumentsPath = null;
        if ($request->hasFile('supportingDocuments')) {
            $supportingDoc = $request->file('supportingDocuments');
            $supportingDocName = 'support_' . time() . '.' . $supportingDoc->getClientOriginalExtension();
            $supportingDoc->move($basePath, $supportingDocName);
            $supportingDocumentsPath = 'studentDocs/' . $supportingDocName;
        }

        $additionalDocsPaths = [];
        if ($request->hasFile('additional_docs')) {
            foreach ($request->file('additional_docs') as $key => $file) {
                $additionalDocName = 'additional_' . time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $file->move($basePath, $additionalDocName);
                $additionalDocsPaths[] = 'studentDocs/' . $additionalDocName;
            }
        }

        $studentRequest = StudentRequest::create([
            'current_domain' => $request->current_domain ?? null,
            'surname' => $request->surname,
            'first_name' => $request->firstName,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'address_line1' => $request->addressLine1,
            'address_line2' => $request->addressLine2,
            'postcode' => $request->postcode,
            'uci_number' => $request->uciNumber ?? null,
            'contact_number' => $request->contactNumber,
            'email' => $request->email,
            'emergency_contact' => $request->emergencyContact,
            'uln' => $request->uln,
            'medical_condition' => $request->medicalCondition,
            'access_arrangement' => $request->accessArrangement === 'yes',
            'access_arrangement_explanation' => $request->access_arrangement_explanation,
            'practical_endorsement' => $request->practicalEndorsement,
            'need_predicted_grades' => $request->need_predicted_grades,
            'need_mock_exams' => $request->need_mock_exams,
            'exam_board' => $request->examBoard,
            'course_type' => $request->courseType,
            'subject_name' => $request->subjectName,
            'subject_code' => $request->subjectCode,
            'option_code' => $request->optionCode ?? null,
            'tire' => $request->tire,
            'nea' => $request->nea,
            'photo_path' => $photoPath,
            'id_document_path' => $idDocumentPath,
            'supporting_documents_path' => $supportingDocumentsPath,
            'additional_docs_paths' => $additionalDocsPaths,
            'is_approved' => 0,
            'exam_session' => $request->session,
            'signature' => $request->signature,
            'access_arrangement_explanation' => $request->access_arrangement_explanation,
            'form_filling_date' => $request->formFillDate,
        ]);

        // New online booking flow: create a server-priced Stripe Checkout session.
        return app(\App\Http\Controllers\StripeCheckoutController::class)->start($studentRequest->id, $request);

        try {
            $candidateName = $request->firstName . ' ' . $request->surname; // Combine first name and surname
            $emailBody = '
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            <title>Exam Registration Confirmation</title>
            </head>
            <body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
            <!-- Logo -->
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre Logo" style="max-width: 180px;">
            </div>
            <!-- Main Content -->
            <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                <h2 style="color: #2e6da4;">Dear ' . htmlspecialchars($candidateName) . ',</h2>
                <p>Thank you for submitting your exam registration form. We’re pleased to confirm that we have received your exam registration form.</p>
                <p>One of our team members will be in touch with you very soon to follow up and assist you with the next steps.</p>
                <p>Best Regards,<br>
                <strong>Exams Team</strong><br>
                London Exam Centre</p>
            </div>
            <!-- Footer -->
            <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
                <p>
                140 Paul Street Stratford, London – E15 4FF<br>
                Phone: <a href="tel:+447538555089" style="color: #337ab7;">+44 7538 555089</a> |
                Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
                </p>
            </div>
            </body>
            </html>';

            Mail::html($emailBody, function ($message) use ($request) {
                // $message->to([$request->email, 'exams@frobel.co.uk', 'ghafoor777@hotmail.com'])
                $message->to([$request->email])
                    ->subject('Exam Registration Confirmation')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        ////////////////////send all student details to admin////////////////////////////


        $studentData = [
            'surname' => $request->surname,
            'first_name' => $request->firstName,
            'dob' => Carbon::parse($request->dob)->format('d/m/Y'),
            'gender' => $request->gender,
            'address_line1' => $request->addressLine1,
            'address_line2' => $request->addressLine2,
            'postcode' => $request->postcode,
            'uci_number' => $request->uciNumber ?? null,
            'contact_number' => $request->contactNumber,
            'email' => $request->email,
            'emergency_contact' => $request->emergencyContact,
            'uln' => $request->uln,
            'medical_condition' => $request->medicalCondition,
            'access_arrangement' => $request->accessArrangement,
            'access_arrangement_explanation' => $request->access_arrangement_explanation,
            'practical_endorsement' => $request->practicalEndorsement,
            'need_predicted_grades' => $request->need_predicted_grades,
            'need_mock_exams' => $request->need_mock_exams,
            'exam_board' => $request->examBoard,
            'course_type' => $request->courseType,
            'subject_name' => $request->subjectName,
            'subject_code' => $request->subjectCode,
            'tire' => $request->tire,
            'nea' => $request->nea,
            'photo_path' => $photoPath,
            'id_document_path' => $idDocumentPath,
            'supporting_documents_path' => $supportingDocumentsPath,
            'additional_docs_paths' => $additionalDocsPaths,
            'exam_session' => $request->session,
            'signature' => $request->signature,
            'form_filling_date' => Carbon::parse($request->formFillDate)->format('d/m/Y'),
        ];

        // Generate PDF
        $pdfContent = '
            <style>
                table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .two-column-table td { width: 50%; }
                .session-table { margin-top: 10px; margin-bottom: 30px; }
                .session-table th, .session-table td { text-align: center; font-size: 16px; }
                .logo-container { text-align: center; margin-bottom: 10px; }
                .main-heading { text-align: center; margin-bottom: 20px; }
            </style>';

        // Logo and Heading at the top center
        $logoPath = public_path('images/LondonExamCentre.png');
        if (file_exists($logoPath)) {
            $pdfContent .= '
                <div class="logo-container">
                    <img src="' . $logoPath . '" style="max-width: 200px; max-height: 100px;" />
                </div>';
        } else {
            Log::error('Logo file not found at: ' . $logoPath);
            $pdfContent .= '
                <div class="logo-container">
                    <p style="color: red;">Logo not found</p>
                </div>';
        }
        $pdfContent .= '
            <h2 class="main-heading">Student Registration Details</h2>';

        // Exam Session in a single-row table
        $pdfContent .= '
            <h3>Exam Session</h3>
            <table class="session-table">
                <tr>
                    <th>Exam Session</th>
                </tr>
                <tr>
                    <td>' . htmlspecialchars($studentData['exam_session'] ?? '') . '</td>
                </tr>
            </table>';

        // Simple fields in a two-column table (excluding exam_session)
        $simpleFields = [
            'surname' => 'Surname',
            'first_name' => 'First Name',
            'dob' => 'Date of Birth',
            'gender' => 'Gender',
            'address_line1' => 'Address Line 1',
            'address_line2' => 'Address Line 2',
            'postcode' => 'Postcode',
            'uci_number' => 'UCI Number',
            'contact_number' => 'Contact Number',
            'email' => 'Email',
            'emergency_contact' => 'Emergency Contact',
            'uln' => 'ULN',
            'medical_condition' => 'Medical Condition',
            'access_arrangement' => 'Access Arrangement',
            'access_arrangement_explanation' => 'Access Arrangement Explanation',
            'practical_endorsement' => 'Practical Endorsement',
            'need_predicted_grades' => 'Predicted Grades & UCAS Reference',
            'need_mock_exams' => 'Mock Exams Required',
        ];

        $pdfContent .= '
            <h3>Student Information</h3>
            <table class="two-column-table">
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                    <th>Field</th>
                    <th>Value</th>
                </tr>';

        $keys = array_keys($simpleFields);
        for ($i = 0; $i < count($simpleFields); $i += 2) {
            $key1 = $keys[$i] ?? null;
            $key2 = $keys[$i + 1] ?? null;

            $label1 = $key1 ? $simpleFields[$key1] : '';
            $value1 = $key1 ? (is_array($studentData[$key1]) ? implode(', ', $studentData[$key1]) : $studentData[$key1]) : '';
            $label2 = $key2 ? $simpleFields[$key2] : '';
            $value2 = $key2 ? (is_array($studentData[$key2]) ? implode(', ', $studentData[$key2]) : $studentData[$key2]) : '';

            $pdfContent .= '
                <tr>
                    <td>' . htmlspecialchars($label1) . '</td>
                    <td>' . htmlspecialchars($value1) . '</td>
                    <td>' . htmlspecialchars($label2) . '</td>
                    <td>' . htmlspecialchars($value2) . '</td>
                </tr>';
        }

        $pdfContent .= '</table>';

        // Table for document paths (two columns per row)
        $documentFields = [
            'photo_path' => 'Photo Path',
            'id_document_path' => 'ID Document Path',
            'supporting_documents_path' => 'Supporting Documents Path',
            'additional_docs_paths' => 'Additional Documents Paths',
        ];

        
        // Table for exam details
        $examBoards = (array) $studentData['exam_board'];
        $courseTypes = (array) $studentData['course_type'];
        $subjectNames = (array) $studentData['subject_name'];
        $subjectCodes = (array) $studentData['subject_code'];
        $tiers = (array) $studentData['tire'];
        $neas = (array) $studentData['nea'];

        $pdfContent .= '
            <h3>Exam Details</h3>
            <table>
                <tr>
                    <th>Exam Board</th>
                    <th>Course Type</th>
                    <th>Subject Name</th>
                    <th>Subject Code</th>
                    <th>Tier</th>
                    <th>NEA</th>
                </tr>';

        for ($i = 0; $i < max(count($examBoards), count($courseTypes), count($subjectNames), count($subjectCodes), count($tiers), count($neas)); $i++) {
            $pdfContent .= '
                <tr>
                    <td>' . htmlspecialchars($examBoards[$i] ?? '') . '</td>
                    <td>' . htmlspecialchars($courseTypes[$i] ?? '') . '</td>
                    <td>' . htmlspecialchars($subjectNames[$i] ?? '') . '</td>
                    <td>' . htmlspecialchars($subjectCodes[$i] ?? '') . '</td>
                    <td>' . htmlspecialchars($tiers[$i] ?? '') . '</td>
                    <td>' . htmlspecialchars($neas[$i] ?? '') . '</td>
                </tr>';
        }

        $pdfContent .= '</table>';

        // Add Signature and Form Filling Date
        $pdfContent .= '
            <h3>Signature and Form Submission Details</h3>
            <table class="two-column-table">
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>Form Filling Date</td>
                    <td>' . htmlspecialchars($studentData['form_filling_date'] ?? '') . '</td>
                </tr>
                <tr>
                    <td>Signature</td>
                    <td>';

        if (!empty($studentData['signature'])) {
            // Check if the signature is a base64 string
            if (preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $studentData['signature'])) {
                // If it's a base64 string, embed it directly
                $pdfContent .= '<img src="' . $studentData['signature'] . '" style="max-width: 200px; max-height: 100px;" />';
            } elseif (file_exists(public_path($studentData['signature']))) {
                // If it's a file path and exists, embed it as an image
                $pdfContent .= '<img src="' . public_path($studentData['signature']) . '" style="max-width: 200px; max-height: 100px;" />';
            } else {
                // If neither a valid base64 string nor a file, display as text
                $pdfContent .= htmlspecialchars($studentData['signature'] ?? 'No signature provided');
            }
        } else {
            // No signature provided
            $pdfContent .= htmlspecialchars($studentData['signature'] ?? 'No signature provided');
        }

        $pdfContent .= '
                        </td>
                    </tr>
                </table>';

        // Create and save PDF
        if (!file_exists(public_path('students'))) {
            mkdir(public_path('students'), 0777, true);
        }
        // Generate PDF filename based on student name
        $studentName = trim($studentData['first_name'] . '_' . $studentData['surname']);
        $studentName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $studentName); // Sanitize filename
        $pdfPath = public_path('students/' . $studentName . '_' . time() . '.pdf');
        $pdf = Pdf::loadHtml($pdfContent);
        $pdf->save($pdfPath);

        // Send PDF email to exams@frobel.co.uk
        try {
            $adminEmailBody = '
                <html>
                <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                    <p>Dear Admin,</p>
                    <p>A new student registration PDF is attached for your review.</p>
                    <p>Student Email: ' . htmlspecialchars($request->email) . '</p>
                    <p>Best regards,<br>London Exam Centre</p>
                </body>
                </html>';
          
            $examSession = $request->session ?? '';
            Mail::html($adminEmailBody, function ($message) use ($pdfPath, $examSession) {
                    // $message->to('inaam.chishti2@gmail.com')
                    // $message->to('ghafoor777@hotmail.com')
                    $message->to('exams@londonexamcentre.co.uk')
                    ->subject('New Student Registration ' . $examSession)
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->attach($pdfPath);
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        ////////////////////send all student details to admin////////////////////////////

        return redirect()->back()->with('success', 'Your form has been successfully submitted. Our team will contact you very soon.');
    }

    public function showImportForm()
    {
        $offered = OfferedSubject::all();
        return view('import.importPublicPage', compact('offered'));
    }

    public function showImportFormDatesheet()
    {
        return view('import.datesheet');
    }
    public function destroy($id)
    {
        $subject = OfferedSubject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }

    public function submitOfferedDatesheet(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row if exists
            $header = array_shift($rows);

            $importedCount = 0;
            $skippedCount = 0;

            foreach ($rows as $row) {
                // Map Excel columns to database fields
                $qualification = $row[0] ?? null; // Qualification
                $subjectCode = $row[1] ?? null; // Code
                $syllabus = $row[2] ?? null; // Syllabus
                $componentTitle = $row[3] ?? null; // Component Title
                $examDate = $row[4] ?? '-'; // Exam Date
                $examTime = $row[5] ?? '-'; // Session
                $duration = $row[6] ?? '-'; // Duration
                $marksDueDeadline = $row[7] ?? '-'; // Marks Due Deadline
                $examBoard = $row[8] ?? null; // Board

                // Convert dates to YYYY/MM/DD format, handle DD/MM/YYYY and DD/MM/YY
                $examDateFormatted = $examDate;
                if (!empty($examDate) && $examDate !== '-') {
                    try {
                        // Try DD/MM/YYYY format first
                        $date = Carbon::createFromFormat('d/m/Y', $examDate);
                        $examDateFormatted = $date->format('Y/m/d');
                        Log::info("Parsed DD/MM/YYYY: $examDate -> $examDateFormatted");
                    } catch (\Exception $e) {
                        try {
                            // Try DD/MM/YY format
                            $date = Carbon::createFromFormat('d/m/y', $examDate);
                            // Adjust two-digit year to 2025 (since exams are for Autumn 2025)
                            if ($date->year < 2000) {
                                $date->year = 2026; // Explicitly set to 2025
                            }
                            $examDateFormatted = $date->format('Y/m/d');
                            Log::info("Parsed DD/MM/YY: $examDate -> $examDateFormatted");
                        } catch (\Exception $e) {
                            try {
                                // Try Y-m-d format as fallback
                                $date = Carbon::createFromFormat('Y-m-d', $examDate);
                                $examDateFormatted = $date->format('Y/m/d');
                                Log::info("Parsed Y-m-d: $examDate -> $examDateFormatted");
                            } catch (\Exception $e) {
                                Log::warning("Invalid date format: $examDate for subjectCode: $subjectCode");
                                $examDateFormatted = '-'; // Keep as '-' if date is invalid
                            }
                        }
                    }
                } else {
                    Log::info("No date provided or date is '-': $examDate for subjectCode: $subjectCode");
                }

                // Determine exam day from exam date
                $examDay = $examDate === '-' ? '-' : null;
                if ($examDateFormatted !== '-') {
                    try {
                        $examDay = Carbon::createFromFormat('Y/m/d', $examDateFormatted)->format('l');
                        Log::info("Calculated exam day: $examDateFormatted -> $examDay");
                    } catch (\Exception $e) {
                        $examDay = '-';
                        Log::warning("Failed to calculate exam day for: $examDateFormatted");
                    }
                }

                // Map examTime (session) to specific times based on exam board
                $mappedExamTime = $examTime;
                if ($examTime !== '-' && !is_null($examTime)) {
                    if (strtoupper($examBoard) === 'CIE') {
                        if (strtoupper($examTime) === 'AM') {
                            $mappedExamTime = '09:30';
                        } elseif (strtoupper($examTime) === 'PM') {
                            $mappedExamTime = '01:30';
                        }
                    } else {
                        if (strtoupper($examTime) === 'AM') {
                            $mappedExamTime = '09:00';
                        } elseif (strtoupper($examTime) === 'PM') {
                            $mappedExamTime = '01:00';
                        }
                    }
                } else {
                    $mappedExamTime = null;
                }

                // Combine Syllabus and Component Title for subjectName
                $subjectName = trim(($syllabus ?? '') . ' ' . ($componentTitle ?? ''));

                // Subjects::create(
                //     [
                //         'subjectCode' => $subjectCode,
                //         'examBoard' => $examBoard,
                //         'courseType' => $qualification,
                //         'subjectName' => $subjectName,
                //         'tier' => null,
                //         'examSession' => 'Summer 2026',
                //         'examTime' => $examTime,
                //         'slot' => $mappedExamTime,
                //         'examDate' => $examDateFormatted === '-' ? null : $examDateFormatted,
                //         'examDay' => $examDay === '-' ? null : $examDay,
                //         'duration' => $duration === '-' ? null : $duration,
                //     ]
                // );
                Subjects::create(
                    [
                        'subjectCode' => ltrim($subjectCode),   // left side space remove
                        'examBoard'   => ltrim($examBoard),
                        'courseType'  => ltrim($qualification),
                        'subjectName' => ltrim($subjectName),
                        'tier'        => null,
                        'examSession' => 'Summer 2027',
                        'examTime'    => ltrim($examTime),
                        'slot'        => ltrim($mappedExamTime),
                        'examDate'    => $examDateFormatted === '-' ? null : ltrim($examDateFormatted),
                        'examDay'     => $examDay === '-' ? null : ltrim($examDay),
                        'duration'    => $duration === '-' ? null : ltrim($duration),
                    ]
                );


                $importedCount++;
            }

            return back()->with([
                'success' => "Import completed!",
                'imported' => $importedCount,
                'skipped' => $skippedCount
            ]);
        } catch (\Exception $e) {
            Log::error("Error importing file: " . $e->getMessage());
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    // public function submitOffered(Request $request)
    // {

    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv'
    //     ]);

    //     try {
    //         $file = $request->file('file');
    //         $spreadsheet = IOFactory::load($file->getPathname());
    //         $worksheet = $spreadsheet->getActiveSheet();
    //         $rows = $worksheet->toArray();

    //         // Remove header row if exists
    //         $header = array_shift($rows);

    //         $importedCount = 0;
    //         $skippedCount = 0;
    //         // dd($rows);
    //         foreach ($rows as $row) {
    //             // Skip empty rows
    //             if (empty(array_filter($row))) {
    //                 $skippedCount++;
    //                 continue;
    //             }

    //             // Map columns:
    //             // 0 = Entry Code
    //             // 1 = Qualification
    //             // 2 = Description
    //             // 3 = Exam Board (column 4 in Excel, key 3 in array)
    //             $code = $row[0] ?? null;
    //             $qualification = $row[1] ?? null;
    //             $description = $row[2] ?? null;
    //             $examboard = $row[3] ?? null; // Exam board from column 4 (key 3)

    //             // Skip if required fields are missing
    //             if (empty($code) || empty($examboard)) {
    //                 $skippedCount++;
    //                 continue;
    //             }

    //             OfferedSubject::updateOrCreate(
    //                 ['code' => $code],
    //                 [
    //                     'examboard' => $examboard,
    //                     'qualification' => $qualification,
    //                     'description' => $description,
    //                 ]
    //             );

    //             $importedCount++;
    //         }

    //         return back()->with([
    //             'success' => "Import completed!",
    //             'imported' => $importedCount,
    //             'skipped' => $skippedCount
    //         ]);
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Error importing file: ' . $e->getMessage());
    //     }
    // }
    public function submitOffered(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row if exists
            $header = array_shift($rows);

            $importedCount = 0;
            $skippedCount = 0;
            // dd($rows);
            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $skippedCount++;
                    continue;
                }
                // Map columns:
                // 0 = Entry Code
                // 1 = Qualification
                // 2 & 3 = Concatenated Description
                // 4 = Exam Board
                $code = $row[0] ?? null;
                $qualification = $row[1] ?? null;
                // $description = ($row[2] ?? '') . ' ' . ($row[3] ?? ''); // Concatenate keys 2 and 3
                $description = ($row[2]);
                $examboard = $row[3] ?? null; // Exam board from key 4
                // dd($code, $examboard, $description, $row);

                // Skip if required fields are missing
                if (empty($code) || empty($examboard)) {
                    $skippedCount++;
                    continue;
                }


                OfferedSubject::updateOrCreate(
                    // ['code' => $code],
                    [
                        'code' => $code,
                        'examboard' => $examboard,
                        'qualification' => $qualification,
                        'description' => trim($description), // Trim to remove extra spaces
                        'session' => 'Summer 2027',
                    ]
                );

                $importedCount++;
            }

            return back()->with([
                'success' => "Import completed!",
                'imported' => $importedCount,
                'skipped' => $skippedCount
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }


    public function sendImprtantMessage(Request $request)
    {
        $session = Session::where('status', 'active')->first();

        $students = StudentDetail::select('student_id', 'email')
            ->where('exam_session', session('current_session'))
            ->where('first_name', 'NOT LIKE', '%widthraw%')
            ->where('first_name', 'NOT LIKE', '%withdrawn%')
            ->distinct('student_id') // Ensures unique student_id
            ->get();
        // dd($students);

        // Transform the collection to an array with unique keys
        $studentArray = $students->mapWithKeys(function ($student, $index) {
            // Use a combination of student_id and email to ensure unique keys
            // Alternatively, use $index to guarantee uniqueness
            return [
                $student->email . '_' . $student->student_id => $student->student_id . ' - ' . $student->email
            ];
        })->toArray();

        // Add the external email
        // $studentArray['inaam.chishti2@gmail.com'] = 'External - inaam.chishti2@gmail.com';

        // $studentArray['ghafoor777@hotmail.com'] = 'External - ghafoor777@hotmail.com';

        // Ensure uniqueness (optional, since keys are already unique)
        $students = array_unique($studentArray);
        // dd($students);
        return view('seatTag.sendImportantMessage', compact('students'));
    }

    public function sendImportantMessagePost(Request $request)
    {
        $validated = $request->validate([
            'emails' => 'required',
            'emails.*' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'attachments.*' => 'nullable',

        ]);

        $emails = $validated['emails'];
        $subject = $validated['subject'];
        $messageContent = $validated['message'];
        $attachments = $request->file('attachments') ?? [];



        if (empty(trim($messageContent))) {

            return redirect()->back()->with('error', 'Message content cannot be empty');
        }

        $cleanedEmails = array_map(function ($email) {
            return preg_replace('/_\d{3,4}$/', '', $email);
        }, $emails);



        if (empty($cleanedEmails)) {
            return redirect()->back()->with('error', 'No valid recipient emails provided');
        }
        // dd($cleanedEmails);

        try {
            foreach ($emails as $emailKey) {
                $cleanEmail = preg_replace('/_\d{3,4}$/', '', $emailKey);

                // Resolve student info
                $parsedStudentId = null;
                if (str_contains($emailKey, '_')) {
                    $parts = explode('_', $emailKey);
                    $parsedStudentId = end($parts) ?: null;
                }
                $student = null;
                if ($parsedStudentId) {
                    $student = StudentDetail::where('student_id', $parsedStudentId)
                        ->where('exam_session', session('current_session'))
                        ->first();
                }
                if (!$student) {
                    $student = StudentDetail::where('email', $cleanEmail)
                        ->where('exam_session', session('current_session'))
                        ->first();
                }
                $candidateName = $student
                    ? trim(($student->first_name ?? '') . ' ' . ($student->surname ?? ''))
                    : null;

                // ── Step 1: Generate tracking token ──────────────────────
                $trackingToken = \App\Helpers\EmailLogHelper::generateTrackingToken();

                // ── Step 2: Save log first to get the DB id ──────────────
                $logRecord = \App\Helpers\EmailLogHelper::log([
                    'candidate_name'  => \App\Helpers\EmailLogHelper::nameFromStudentRequest(null, $candidateName),
                    'email_address'   => $cleanEmail,
                    'student_id'      => $student?->student_id ?? ($parsedStudentId ?: null),
                    'uci_number'      => $student?->uci_number,
                    'subject_line'    => $subject,
                    'message'         => $messageContent,
                    'message_date'    => now(),
                    'session'         => session('current_session'),
                    'sent_status'     => 'sent',
                    'tracking_token'  => $trackingToken,
                ]);

                // ── Step 3: Build pixel URL using real DB id ──────────────
                // Hardcode live domain — url() returns http://localhost locally
                $liveBase  = 'https://examcentre.londonexamcentre.co.uk';
                $logId     = $logRecord ? $logRecord->id : 0;
                $cacheBust = now()->timestamp . '-' . md5($cleanEmail . microtime(true));
                $pixelUrl  = $liveBase . '/email-track/' . $logId . '.png?t=' . $cacheBust;

                // ── Step 4: Pixel tag — display:block (NOT display:none) ──
                $pixelTag = '<div style="font-size:1px;line-height:1px;">'
                    . '<img src="' . $pixelUrl . '" width="1" height="1" alt="" border="0"'
                    . ' style="display:block;width:1px;height:1px;border:0;outline:none;text-decoration:none;" />'
                    . '</div>';

                // ── Step 5: Inject pixel into message ────────────────────
                $trackedMessage = $messageContent;
                if (preg_match('/<\/body>/i', $trackedMessage)) {
                    $trackedMessage = preg_replace('/<\/body>/i', $pixelTag . '</body>', $trackedMessage, 1);
                } elseif (preg_match('/<\/html>/i', $trackedMessage)) {
                    $trackedMessage = preg_replace('/<\/html>/i', $pixelTag . '</html>', $trackedMessage, 1);
                } else {
                    $trackedMessage .= $pixelTag;
                }

                // ── Step 6: Update log with tracked message ───────────────
                if ($logRecord) {
                    $logRecord->message = $trackedMessage;
                    $logRecord->save();
                }

                // ── Step 7: Send email ────────────────────────────────────
                $sentStatus = 'sent';
                try {
                    Mail::send([], [], function ($message) use ($cleanEmail, $subject, $trackedMessage, $attachments) {
                        $message->to($cleanEmail)
                            ->subject($subject)
                            ->html($trackedMessage)
                            ->text(strip_tags($trackedMessage));

                        foreach ($attachments as $attachment) {
                            $message->attach($attachment->getRealPath(), [
                                'as' => $attachment->getClientOriginalName(),
                                'mime' => $attachment->getClientMimeType(),
                            ]);
                        }
                    });
                } catch (\Exception $mailEx) {
                    $sentStatus = 'failed';
                    \Illuminate\Support\Facades\Log::error('Send important message failed for ' . $cleanEmail . ': ' . $mailEx->getMessage());
                }

                // Update sent_status if failed
                if ($logRecord && $sentStatus === 'failed') {
                    $logRecord->sent_status = 'failed';
                    $logRecord->save();
                }
            }

            return redirect()->back()->with('success', 'Emails sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send emails: ' . $e->getMessage());
        }
    }

   public function validateUciNumber(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'uciNumber' => 'required|string|max:255',
        'session' => 'required|string|exists:sessions,session_name'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Check if UCI number exists for the given session
    $uciRecord = StudentDetail::where('uci_number', $request->uciNumber)
                        ->where('exam_session', $request->session)
                        ->first();

    if ($uciRecord) {
        // Already exists → return error
        return response()->json([
            'success' => false,
            'message' => 'This UCI number already exists in the selected session.'
        ], 409); // 409 Conflict
    }

    // Not exists → success
    return response()->json([
        'success' => true,
        'message' => 'UCI number is available for this session.'
    ], 200);
}

public function allpapers()
{
    // Fetch only student details where exam_session is Summer 2026
    $studentDetails = [];//StudentDetail::where('exam_session', 'Summer 2026')->get();

    // Fetch only subjects where examSession is Summer 2026
    $subjects = Subjects::where('examSession', 'Summer 2026')->get();

    return view('tempAllPapers', compact('studentDetails', 'subjects'));
}

// ── GCSE Crash Course Flyer ─────────────────────────────────────────────────

public function showCrashCourseFlyer()
{
    return view('marketing.crash-course-flyer');
}

public function sendCrashCourseFlyerEmail()
{
    $recipientEmail = 'inaam.chishti2@gmail.com';

    $emailHtml = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GCSE Crash Course - London Exam Centre</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body style="margin:0;padding:0;background:#f3f4f6;font-family:Poppins,Arial,sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f3f4f6;padding:24px 0;">
        <tr><td align="center">
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.1);">

            <!-- Header -->
            <tr>
                <td style="background:#002147;padding:28px 32px;text-align:center;">
                    <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre" style="max-width:180px;margin-bottom:16px;display:block;margin-left:auto;margin-right:auto;">
                    <h2 style="color:#E5A93C;font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:3px;margin:0 0 6px;">GCSE Maths &amp; English</h2>
                    <h1 style="color:#ffffff;font-size:42px;font-weight:900;text-transform:uppercase;letter-spacing:-1px;margin:0;line-height:1;">Resit</h1>
                    <h2 style="color:#E5A93C;font-size:26px;font-weight:800;text-transform:uppercase;margin:4px 0 12px;">Crash Course</h2>
                    <div style="background:#E5A93C;color:#002147;font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;padding:8px 20px;border-radius:5px;display:inline-block;">November 2026 Exam Series</div>
                </td>
            </tr>

            <!-- Tagline -->
            <tr>
                <td style="padding:20px 32px 10px;text-align:center;">
                    <p style="font-size:15px;font-style:italic;font-weight:600;color:#374151;margin:0;">
                        <span style="color:#0056B3;font-weight:900;">Boost</span> Your Grades.
                        <span style="color:#002147;font-weight:900;">Build</span> Confidence.
                        <span style="color:#002147;font-weight:900;">Pass</span> Your Resits.
                    </p>
                </td>
            </tr>

            <!-- Price box -->
            <tr>
                <td style="padding:10px 32px;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="40%" style="background:#002147;border-radius:10px;padding:20px;text-align:center;vertical-align:middle;">
                                <p style="color:#d1d5db;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin:0;">Only</p>
                                <p style="color:#E5A93C;font-size:52px;font-weight:900;margin:4px 0;line-height:1;">£40</p>
                                <p style="color:#ffffff;font-size:13px;font-weight:700;text-transform:uppercase;margin:0;">Per Week</p>
                            </td>
                            <td width="5%"></td>
                            <td width="55%" style="vertical-align:top;">
                                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px;">
                                    <div style="background:#E5A93C;color:#002147;font-weight:900;font-size:10px;text-transform:uppercase;letter-spacing:2px;padding:4px 10px;border-radius:4px;text-align:center;margin-bottom:10px;">What\'s Included?</div>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        ' . implode('', array_map(fn($i) => "<tr><td style=\"font-size:11px;font-weight:700;color:#002147;padding:3px 0;\"><span style=\"color:#E5A93C;margin-right:6px;\">✔</span>{$i}</td></tr>", [
                                            'Weekly GCSE Maths &amp; English Tuition',
                                            'Free Worksheets',
                                            'Free Past Papers',
                                            'Free Full Mock Exam Under Real Conditions',
                                            'Free November 2026 GCSE Resit Exam Entry',
                                            'Experienced Tutors',
                                            'Structured Revision Programme',
                                            'Exam Techniques &amp; Time Management Support',
                                        ])) . '
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- iGCSE Science -->
            <tr>
                <td style="padding:10px 32px;">
                    <div style="background:#002147;color:#fff;border-radius:10px;padding:16px 20px;display:flex;align-items:center;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="color:#E5A93C;font-size:22px;width:44px;vertical-align:middle;">🔬</td>
                                <td style="vertical-align:middle;padding-left:10px;">
                                    <p style="color:#E5A93C;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin:0;">Additional Programme</p>
                                    <p style="color:#ffffff;font-size:18px;font-weight:900;text-transform:uppercase;margin:2px 0;">iGCSE Science</p>
                                    <p style="color:#d1d5db;font-size:11px;font-weight:500;text-transform:uppercase;margin:0;">Tuition Available</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Register Today -->
            <tr>
                <td style="padding:10px 32px;">
                    <div style="background:#002147;border-radius:10px;padding:20px 24px;text-align:center;">
                        <h3 style="color:#ffffff;font-size:18px;font-weight:900;text-transform:uppercase;letter-spacing:2px;margin:0 0 4px;">Register</h3>
                        <h1 style="color:#E5A93C;font-size:48px;font-weight:900;text-transform:uppercase;margin:0 0 12px;line-height:1;">Today</h1>
                        <div style="background:#E5A93C;color:#002147;font-weight:900;font-size:11px;text-transform:uppercase;letter-spacing:3px;padding:8px 20px;border-radius:5px;display:inline-block;">Limited Spaces Available</div>
                    </div>
                </td>
            </tr>

            <!-- Important Notice -->
            <tr>
                <td style="padding:10px 32px;">
                    <div style="background:#F2F4F7;border-radius:8px;padding:14px 16px;display:flex;align-items:center;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="color:#E5A93C;font-size:22px;width:40px;vertical-align:middle;">⚠️</td>
                                <td style="vertical-align:middle;padding-left:10px;">
                                    <p style="font-size:10px;font-weight:900;color:#002147;text-transform:uppercase;letter-spacing:2px;margin:0 0 4px;">Important Notice</p>
                                    <p style="font-size:11px;color:#374151;font-weight:700;margin:0;">Candidate must remain enrolled on the course until the November 2026 examination series to qualify for the free exam entry.</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Footer / Contact -->
            <tr>
                <td style="background:#002147;padding:20px 32px;border-top:1px solid rgba(255,255,255,.1);">
                    <p style="color:#E5A93C;font-weight:800;font-size:13px;text-transform:uppercase;letter-spacing:3px;margin:0 0 12px;text-align:center;">CONTACT US TODAY</p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td style="color:rgba(255,255,255,.85);font-size:11px;font-weight:600;vertical-align:top;width:50%;">
                                <p style="margin:0 0 6px;">📞 +44 7538 555089</p>
                                <p style="margin:0 0 6px;">✉️ exams@londonexamcentre.co.uk</p>
                                <p style="margin:0;color:#d1d5db;font-size:10.5px;">📍 140 Paul Street Stratford, London – E15 4FF</p>
                            </td>
                            <td style="text-align:right;vertical-align:middle;width:50%;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        </td></tr>
        </table>
    </body>
    </html>';

    try {
        Mail::html($emailHtml, function ($message) use ($recipientEmail) {
            $message->to($recipientEmail)
                    ->subject('GCSE Maths & English Resit Crash Course – November 2026')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return response()->json([
            'success' => true,
            'message' => 'Flyer email sent successfully to ' . $recipientEmail,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email: ' . $e->getMessage(),
        ], 500);
    }
}
}
