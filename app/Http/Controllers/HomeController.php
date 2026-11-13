<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CandidateInvoice, EmailLog, OfferedSubject, Result, Layout, Session, User, Subjects, StudentRequest, StudentDetail, Payment, AccessPermission, CandidateAndUciNumber, SeatingArrangement, MigratedStudentRecord, Referral};
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Dompdf\Options;
use Dompdf\Dompdf;
use Illuminate\Mail\Message;
use Carbon\Carbon;
use DB;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\EmailLogHelper;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['trackEmailOpen']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentSession = session('current_session');

        $totalStudents = (array) DB::table('student_details')
            ->select(DB::raw('COUNT(DISTINCT student_id) as total_students'))
            ->where('exam_session', $currentSession)
            ->first();

        $withdrawnStudents = (array) DB::table('student_details')
            ->select(DB::raw('COUNT(DISTINCT student_id) as withdrawn_students'))
            ->where('first_name', 'like', '%withdrawn%')
            ->where('exam_session', $currentSession)
            ->first();

        $accessArrangementStudents = DB::table('student_details')
            ->select(DB::raw('COUNT(DISTINCT student_id) as access_arrangement_students'))
            ->where('exam_session', $currentSession)
            ->where('access_arrangement', 1)
            ->where('first_name', 'not like', '%withdraw%')
            ->value('access_arrangement_students');

        $courseTypeCounts = DB::table('student_details')
            ->select('course_type', DB::raw('COUNT(DISTINCT student_id) as student_count'))
            ->where('exam_session', $currentSession)
            ->groupBy('course_type')
            ->pluck('student_count', 'course_type')
            ->toArray();

        $totalSubjects = Subjects::where('examSession', $currentSession)->count();

        $nextExamDate = DB::table('student_details')
            ->select('date')
            ->where('exam_session', $currentSession)
            ->whereDate('date', '>=', Carbon::today()->toDateString())
            ->where('first_name', 'not like', '%withdraw%')
            ->orderBy('date', 'asc')
            ->first();

        $examCounts = DB::table('student_details')
            ->where('exam_session', 'Autumn 2025')
            ->when($nextExamDate && isset($nextExamDate->date), function ($query) use ($nextExamDate) {
                return $query->whereDate('date', $nextExamDate->date);
            })
            ->where('first_name', 'not like', '%withdraw%')
            ->groupBy('time')
            ->select('time', DB::raw('COUNT(DISTINCT student_id) as total_students'))
            ->pluck('total_students', 'time');

        $amStudentsCount = $examCounts['AM'] ?? 0;
        $pmStudentsCount = $examCounts['PM'] ?? 0;

        $nextExamDate = $nextExamDate ? Carbon::parse($nextExamDate->date) : null;

        return view('home', compact('totalStudents', 'withdrawnStudents', 'courseTypeCounts', 'amStudentsCount', 'pmStudentsCount', 'totalSubjects', 'nextExamDate', 'accessArrangementStudents'));
    }



    public function viewStudent()
    {
        return view('student.index');
    }


    public function addSession(Request $request)
    {
        return view('session.addSession');
    }

    // public function storeSession(Request $request)
    // {
    //     $request->validate([
    //         'session_name' => 'required',
    //         'status'       => 'required',
    //     ]);

    //     Session::create([
    //         'session_name'   => $request->session_name,
    //         'status'         => $request->status,
    //     ]);

    //     return response()->json(['message' => 'Session stored successfully!']);
    // }
    public function storeSession(Request $request)
    {
        $request->validate([
            'session_name' => 'required',
            'status'       => 'required',
        ]);

        if ($request->status === 'active') {
            Session::where('status', 'active')->update(['status' => 'inactive']);
        }

        // Create the new session
        Session::create([
            'session_name' => $request->session_name,
            'status'       => $request->status,
        ]);

        return response()->json(['message' => 'Session stored successfully!']);
    }

    public function viewSession()
    {
        $sessions = Session::all();
        return view('session.EditSession', compact('sessions'));
    }

    public function deleteSession($id)
    {
        try {
            $session = Session::find($id);

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            $session->delete();

            return response()->json([
                'success' => true,
                'message' => 'Session deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting session: ' . $e->getMessage()
            ], 500);
        }
    }


    public function editSession($id)
    {
        $session = Session::where('id', $id)->first();

        return view('session.updateSession', compact('session'));
    }


    // public function updateSession(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'id' => 'required',
    //         'session_name' => 'required',
    //         'status' => 'required',
    //     ]);

    //     $session = Session::find($request->id);

    //     if (!$session) {
    //         return response()->json(['message' => 'Session not found'], 404);
    //     }

    //     $session->update([
    //         'session_name' => $request->session_name,
    //         'status' => $request->status,
    //     ]);

    //     return response()->json(['message' => 'Session updated successfully!']);
    // }
    public function updateSession(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sessions,id',
            'session_name' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);
        // dd($request->all());





        $session = Session::find($request->id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        // If the session is being set to active, make all other sessions inactive
        if ($request->status === 'active') {
            session(['current_session' => $request['session_name']]);
            Session::where('id', '!=', $request->id)->update(['status' => 'inactive']);
        }

        $session->update([
            'session_name' => $request->session_name,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Session updated successfully!']);
    }


    public function addStudent()
    {
        // $subjectsData = Subjects::select('examBoard', 'courseType', 'subjectName', 'subjectCode')
        //     ->orderBy('examBoard')
        //     ->orderBy('courseType')
        //     ->orderBy('subjectName')
        //     ->get();

        // $data = [
        //     'boards' => $subjectsData->pluck('examBoard')->unique()->values(),
        //     'courseTypesByBoard' => $subjectsData->groupBy('examBoard')->map(function ($items) {
        //         return $items->pluck('courseType')->unique()->values();
        //     }),
        //     'subjectsByBoardAndCourse' => $subjectsData->groupBy(['examBoard', 'courseType'])->map(function ($courseTypes) {
        //         return $courseTypes->map(function ($subjects) {
        //             return $subjects->mapWithKeys(function ($item) {
        //                 return [$item->subjectName => $item->subjectCode]; // Name as key, code as value
        //             });
        //         });
        //     })
        // ];
        // $subjectsData = OfferedSubject::select('examboard as examBoard', 'qualification as courseType', 'description as subjectName', 'code as subjectCode')
        //     ->orderBy('examboard')
        //     ->orderBy('qualification')
        //     ->orderBy('description')
        //     ->get();

        // $data = [
        //     'boards' => $subjectsData->pluck('examBoard')->unique()->values(),
        //     'courseTypesByBoard' => $subjectsData->groupBy('examBoard')->map(function ($items) {
        //         return $items->pluck('courseType')->unique()->values();
        //     }),
        //     'subjectsByBoardAndCourse' => $subjectsData->groupBy(['examBoard', 'courseType'])->map(function ($courseTypes) {
        //         return $courseTypes->map(function ($subjects) {
        //             return $subjects->mapWithKeys(function ($item) {
        //                 return [$item->subjectName => $item->subjectCode];
        //             });
        //         });
        //     }),
        //     'sessions' => Session::all(), // Fetch all sessions
        //     'activeSession' => Session::where('status', 'active')->first()
        // ];

        // // dd($session);
        // return view('student.addStudent', $data);

        // Show only Summer 2027 in the dropdown (Autumn 2026 removed)
        $sessions = Session::whereIn('session_name', ['Summer 2027'])->get();
        $activeSession = Session::where('session_name', 'Summer 2027')->first();

        // Fetch subjects data for Summer 2027
        $subjectsData = OfferedSubject::select('examboard as examBoard', 'qualification as courseType', 'description as subjectName', 'code as subjectCode', 'session')
            ->whereIn('session', ['Summer 2027'])
            ->orderBy('examboard')
            ->orderBy('qualification')
            ->orderBy('description')
            ->get()
            ->groupBy('session');

        $data = [
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'subjectsBySession' => $subjectsData->map(function ($sessionSubjects) {
                return [
                    'boards' => $sessionSubjects->pluck('examBoard')->unique()->values(),
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

        return view('student.addStudent', $data);
    }


    public function publicPage()
    {
        $subjectsData = Subjects::select('examBoard', 'courseType', 'subjectName', 'subjectCode')
            ->orderBy('examBoard')
            ->orderBy('courseType')
            ->orderBy('subjectName')
            ->get();

        $data = [
            'boards' => $subjectsData->pluck('examBoard')->unique()->values(),
            'courseTypesByBoard' => $subjectsData->groupBy('examBoard')->map(function ($items) {
                return $items->pluck('courseType')->unique()->values();
            }),
            'subjectsByBoardAndCourse' => $subjectsData->groupBy(['examBoard', 'courseType'])->map(function ($courseTypes) {
                return $courseTypes->map(function ($subjects) {
                    return $subjects->mapWithKeys(function ($item) {
                        return [$item->subjectName => $item->subjectCode]; // Name as key, code as value
                    });
                });
            })
        ];

        return view('student.addStudentPublic', $data);
    }


    public function submitStudent(Request $request)
    {

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

        // Create student request record
        $studentRequest = StudentRequest::create([
            'surname' => $request->surname,
            'first_name' => $request->firstName,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'address_line1' => $request->addressLine1,
            'address_line2' => $request->addressLine2,
            'postcode' => $request->postcode,
            'uci_number' => $request->uciNumber,
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
            'course_work' => $request->courseWork,
            'course_work_uci' => $request->courseWork_UCI,
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
            'is_approved' => 0,
            'exam_session' => $activeSession->session_name
        ]);




        return redirect()->back()->with('success', 'Student request submitted successfully!');
    }


    public function studentRequest()
    {
        //  dd(session('current_session'));
        $students = StudentRequest::where('is_approved', 0)->where('exam_session', session('current_session'))->latest()->get();
        // dd($students);
        $invoicedRequestIds = CandidateInvoice::pluck('request_id')->toArray();
        return view('student.studentRequest', compact('students', 'invoicedRequestIds'));
    }

    public function allStudentRequests()
    {
        $students = StudentRequest::where('exam_session', 'Summer 2027')
            ->latest()
            ->get();

        $invoicedRequestIds = CandidateInvoice::pluck('request_id')->toArray();
        return view('student.allStudentRequests', compact('students', 'invoicedRequestIds'));
    }

    public function editAllRequest($id)
    {
        $student = StudentRequest::findOrFail($id);
        return view('student.editAllStudentRequest', compact('student'));
    }

    public function updateAllRequest(Request $request, $id)
    {
        $student = StudentRequest::findOrFail($id);

        $student->update([
            'surname'                       => $request->surname,
            'first_name'                    => $request->first_name,
            'dob'                           => $request->dob,
            'gender'                        => $request->gender,
            'address_line1'                 => $request->address_line1,
            'address_line2'                 => $request->address_line2,
            'postcode'                      => $request->postcode,
            'uci_number'                    => $request->uci_number,
            'contact_number'                => $request->contact_number,
            'email'                         => $request->email,
            'emergency_contact'             => $request->emergency_contact,
            'uln'                           => $request->uln,
            'medical_condition'             => $request->medical_condition,
            'access_arrangement'            => $request->access_arrangement,
            'access_arrangement_explanation'=> $request->access_arrangement_explanation,
            'practical_endorsement'         => $request->practical_endorsement,
            'need_predicted_grades'         => $request->need_predicted_grades,
            'need_mock_exams'               => $request->need_mock_exams,
            'exam_session'                  => $request->exam_session,
            'exam_board'                    => $request->examBoard ?? [],
            'course_type'                   => $request->courseType ?? [],
            'subject_name'                  => $request->subjectName ?? [],
            'subject_code'                  => $request->subjectCode ?? [],
            'tire'                          => $request->tire ?? [],
            'nea'                           => $request->nea ?? [],
        ]);

        return redirect()->route('allStudentRequests')
            ->with('success', 'Student request updated successfully!');
    }
    public function sendExamRegistrationEmail(Request $request)
    {
        // dd('');
        // Validate the request
        $validated = $request->validate([
            'request_id' => 'required|exists:student_requests,id',
            'email' => 'required|email',
            'candidate_name' => 'required|string|max:255',
            'subjects' => 'required|array',
            'subjects.*' => 'string',
            'total_fee' => 'required|numeric|min:0',
            'payment_deadline' => 'required|date_format:Y-m-d',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file',
        ]);

        // Filter out any empty subjects and reindex
        $validated['subjects'] = array_values(array_filter($validated['subjects'], function($subject) {
            return !empty(trim($subject));
        }));

        // Extract validated data
        $req_id = $validated['request_id'];
        $email = $validated['email'];
        $candidateName = $validated['candidate_name'];
        $subjects = $validated['subjects'];
        $totalFee = $validated['total_fee'];
        $paymentDeadline = (new \DateTime($validated['payment_deadline']))->format('d/m/Y'); //$validated['payment_deadline'];


        CandidateInvoice::create([
            'request_id' => $validated['request_id'],
            'email' => $validated['email'],
            'candidate_name' => $validated['candidate_name'],
            'subjects' => $validated['subjects'],
            'total_fee' => $validated['total_fee'],
            'payment_deadline' => $validated['payment_deadline'],
        ]);

        // Format subjects as a bolded bulleted list
        $subjectsList = '<ul style="margin: 0; padding-left: 20px;">';
        foreach ($subjects as $subject) {
            $subjectsList .= '<li style="font-weight: bold;">' . htmlspecialchars($subject) . '</li>';
        }
        $subjectsList .= '</ul>';

        // Define the email template
        $fullTemplate = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Exam Registration Confirmation</title>
        </head>
        <body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre Logo" style="max-width: 180px;">
            </div>
            <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                <p>Hi ' . htmlspecialchars($candidateName) . ',</p>
                <p>Thank you for completing the exam registration form.</p>
                <p>The subjects you have entered for are:</p>
                ' . $subjectsList . '
                <p>The total fee for these entries is <strong>£' . number_format($totalFee, 2) . '</strong>. Please deposit the fee into the bank account below and send us the confirmation receipt so we can process your exam registration.</p>
                <p><strong>Bank Details</strong><br>
                Barclays Bank<br>
                Account Name: SAEIN LIMITED<br>
                Account Number: 53047849<br>
                Sort Code: 20-37-21</p>
                <p><strong>Payment deadline:</strong> ' . htmlspecialchars($paymentDeadline) . '</p>
                <p><strong>Please note:</strong> Payments received after this date will be subject to a <strong>late fee</strong>.</p>
                <p>Kind regards,<br>Exams Team<br>London Exam Centre</p>
                <p><strong style="color: #d9534f;">After making exam payment, please send the payment confirmation receipt. Without the receipt, we may not be able to locate your payment, and you could miss the exam.</strong></p>
            </div>
            <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
                <p>
                    140 Paul Street Stratford, London – E15 4FF<br>
                    Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
                    Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
                </p>

            </div>
        </body>
        </html>';

        try {
            // ── Step 1: Log first to get the DB id for the tracking pixel ──
            $studentRequest = StudentRequest::find($req_id);
            $logRecord = EmailLogHelper::log([
                'candidate_name'  => EmailLogHelper::nameFromStudentRequest($studentRequest, $candidateName),
                'email_address'   => $email,
                'uci_number'      => $studentRequest->uci_number ?? null,
                'subject_line'    => 'Exam Registration Confirmation',
                'message'         => $fullTemplate,
                'message_date'    => now(),
                'session'         => session('current_session'),
                'sent_status'     => 'sent',
                'tracking_token'  => EmailLogHelper::generateTrackingToken(),
            ]);

            // ── Step 2: Inject tracking pixel using the real DB id ──────────
            if ($logRecord) {
                $fullTemplate = $this->buildAndInjectPixel($fullTemplate, $logRecord->id);
                $logRecord->message = $fullTemplate;
                $logRecord->save();
            }

            // ── Step 3: Send the email ────────────────────────────────────
            Mail::html($fullTemplate, function ($mail) use ($email, $request) {
                $mail->to([$email, 'exams@londonexamcentre.co.uk'])
                //  $mail->to($email)
                ->subject('Exam Registration Confirmation')
                    ->from('exams@londonexamcentre.co.uk', 'London Exam Centre');

                // Attach multiple files if provided
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $attachment) {
                        if ($attachment->isValid()) {
                            $mail->attach($attachment->getRealPath(), [
                                'as' => $attachment->getClientOriginalName(),
                                'mime' => $attachment->getClientMimeType(),
                            ]);
                        }
                    }
                }
            });

            // Return success response
            return response()->json([
                'message' => 'Exam registration email sent successfully!'
            ], 200);
        } catch (\Exception $e) {
            // Mark log as failed if send threw
            if (!empty($logRecord)) {
                $logRecord->sent_status = 'failed';
                $logRecord->save();
            }
            // Log the error
            Log::error('Exam registration email sending failed: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'message' => 'Failed to send exam registration email: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deleteRequest($id)
    {
        try {
            $studentRequest = StudentRequest::findOrFail($id);

            // Get all file paths from the record
            $filePaths = [
                $studentRequest->photo_path,
                $studentRequest->id_document_path,
                $studentRequest->supporting_documents_path
            ];

            // Handle additional docs paths (stored as JSON string or array)
            $additionalDocsRaw = $studentRequest->additional_docs_paths ?? '[]';
            $additionalDocs = is_array($additionalDocsRaw)
                ? $additionalDocsRaw
                : json_decode($additionalDocsRaw, true);

            // Merge additional docs into file paths
            $filePaths = array_merge($filePaths, $additionalDocs);

            // Delete the files from storage
            foreach ($filePaths as $filePath) {
                if ($filePath && file_exists(public_path($filePath))) {
                    unlink(public_path($filePath));
                }
            }

            // Delete the database record
            $studentRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student request and all associated files deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student request: ' . $e->getMessage()
            ], 500);
        }
    }


    public function viewRequest($id)
    {
        // dd(session('current_session'));
        $request = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)->first();
        $examDetails = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)->get();
        $payments = Payment::where('student_id', $id)
           ->where('session', session('current_session'))
            ->get();
        // dd($request,$examDetails);
        return view('student.viewStudent', compact('request', 'examDetails','payments'));
    }

    public function viewRequestStu($id)
    {
        // dd($id);
        $request = StudentRequest::where('id', $id)->first();
        // dd($request);
        return view('student.viewStudentreq', compact('request'));
    }




     public function editRequest($id)
    {

        $request = StudentRequest::where('exam_session', session('current_session'))->where('id', $id)->first();
        if (!$request) {
            return redirect()->back()->with('error', 'Request not found');
        }

        $subjectsData = Subjects::where('examSession', session('current_session'))->select('examBoard', 'courseType', 'subjectName', 'subjectCode', 'tier')
            ->orderBy('examBoard')
            ->orderBy('courseType')
            ->orderBy('subjectName')
            ->get();

        // Initialize $data
        $data = [
            'boards' => $subjectsData->pluck('examBoard')->unique()->values(),
            'courseTypesByBoard' => $subjectsData->groupBy('examBoard')->map(function ($items) {
                return $items->pluck('courseType')->unique()->values();
            }),
            'subjectsByBoardAndCourse' => $subjectsData->groupBy(['examBoard', 'courseType'])->map(function ($courseTypes) {
                return $courseTypes->map(function ($subjects) {
                    $subjectsByCode = [];

                    foreach ($subjects as $subject) {
                        $subjectName = $subject->subjectName;
                        $subjectCode = $subject->subjectCode;

                        // Extract main code (before / or space)
                        $delimiter = strpos($subjectCode, '/') !== false ? '/' : ' ';
                        $mainCode = strtok($subjectCode, $delimiter);

                        // Determine tier
                        $isHigher = stripos($subjectName, 'Higher') !== false || stripos($subjectCode, 'H') !== false;
                        $isFoundation = stripos($subjectName, 'Foundation') !== false || stripos($subjectCode, 'F') !== false;
                        $tier = $isHigher ? 'Higher' : ($isFoundation ? 'Foundation' : null);

                        // Group by main code and tier
                        if ($tier) {
                            $subjectsByCode[$mainCode][$tier][$subjectName] = $subjectCode;
                        } else {
                            $subjectsByCode[$mainCode]['NoTier'][$subjectName] = $subjectCode;
                        }
                    }

                    // Build new subjects array
                    $newSubjects = [];
                    foreach ($subjectsByCode as $mainCode => $tiers) {
                        if (isset($tiers['Higher']) || isset($tiers['Foundation'])) {
                            if (isset($tiers['Higher'])) {
                                $subjectName = array_key_first($tiers['Higher']);
                                $newSubjects[$subjectName] = $tiers['Higher'][$subjectName];
                            }
                            if (isset($tiers['Foundation'])) {
                                $subjectName = array_key_first($tiers['Foundation']);
                                $newSubjects[$subjectName] = $tiers['Foundation'][$subjectName];
                            }
                        } else {
                            $subjectName = array_key_first($tiers['NoTier']);
                            $newSubjects[$subjectName] = $tiers['NoTier'][$subjectName];
                        }
                    }

                    return $newSubjects;
                });
            })->toArray()
        ];

        // Prepare pre-selected data
        $examBoards = is_string($request->exam_board) ? json_decode($request->exam_board, true) : ($request->exam_board ?? []);
        $courseTypes = is_string($request->course_type) ? json_decode($request->course_type, true) : ($request->course_type ?? []);
        $subjectCodes = is_string($request->subject_code) ? json_decode($request->subject_code, true) : ($request->subject_code ?? []);
        $subjectNames = is_string($request->subject_name) ? json_decode($request->subject_name, true) : ($request->subject_name ?? []); // Assume subject_name is stored
        $tiers = is_string($request->tire) ? json_decode($request->tire, true) : ($request->tire ?? []);

        // Merge subjects from StudentRequest into $data['subjectsByBoardAndCourse']
        foreach ($subjectCodes as $index => $subjectCode) {
            $subjectCode = trim($subjectCode, '[]');
            $examBoard = $examBoards[$index] ?? 'Unknown';
            $courseType = $courseTypes[$index] ?? 'Unknown';
            $subjectName = $subjectNames[$index] ?? 'Unknown Subject';

            // Initialize exam board and course type if not present
            if (!isset($data['boards']) || !$data['boards']->contains($examBoard)) {
                $data['boards'][] = $examBoard;
            }
            if (!isset($data['courseTypesByBoard'][$examBoard])) {
                $data['courseTypesByBoard'][$examBoard] = collect([]);
            }
            if (!$data['courseTypesByBoard'][$examBoard]->contains($courseType)) {
                $data['courseTypesByBoard'][$examBoard][] = $courseType;
            }
            if (!isset($data['subjectsByBoardAndCourse'][$examBoard])) {
                $data['subjectsByBoardAndCourse'][$examBoard] = [];
            }
            if (!isset($data['subjectsByBoardAndCourse'][$examBoard][$courseType])) {
                $data['subjectsByBoardAndCourse'][$examBoard][$courseType] = [];
            }

            // Add subject if not already present
            if (!isset($data['subjectsByBoardAndCourse'][$examBoard][$courseType][$subjectName]) && $subjectCode) {
                $data['subjectsByBoardAndCourse'][$examBoard][$courseType][$subjectName] = $subjectCode;
            }
        }

        // Process Chemistry and Physics for Pearson GCSE (MUST run before building preSelectedSubjects)
        $data = $this->processPearsonChemistryPhysics($data, $subjectsData);

        // Map subject codes to subject names and codes
        $preSelectedSubjects = [];
        $preSelectedTiers = [];

        foreach ($subjectCodes as $index => $subjectCode) {
            $subjectCode = trim($subjectCode, '[]');
            $examBoard = $examBoards[$index] ?? null;
            $courseType = $courseTypes[$index] ?? null;
            $tier = $tiers[$index] ?? 'N/A';
            $subjectName = $subjectNames[$index] ?? 'Unknown Subject'; // Use stored subject name if available

            // Normalize tier value
            $tier = in_array($tier, ['N/A', 'Foundation Tier', 'Higher Tier']) ? str_replace(' Tier', '', $tier) : 'N/A';
            if ($tier === 'N/A' && $subjectName !== 'Unknown Subject') {
                if (stripos($subjectName, 'Higher') !== false || strpos($subjectCode, 'H') !== false) {
                    $tier = 'Higher';
                } elseif (stripos($subjectName, 'Foundation') !== false || strpos($subjectCode, 'F') !== false) {
                    $tier = 'Foundation';
                }
            }
            $preSelectedTiers[$index] = $tier;

            // Initialize default values
            $matchingSubject = [
                'name' => $subjectName,
                'code' => $subjectCode
            ];
            $matched = false;

            // Check if examBoard and courseType exist in $data
            if ($examBoard && $courseType && isset($data['subjectsByBoardAndCourse'][$examBoard][$courseType])) {
                $subjects = $data['subjectsByBoardAndCourse'][$examBoard][$courseType];

                // Exact match by subject code
                foreach ($subjects as $subjName => $code) {
                    if ($code === $subjectCode) {
                        $matchingSubject = [
                            'name' => $subjName,
                            'code' => $code
                        ];
                        $matched = true;
                        break;
                    }
                }

                // If no exact match, try broader matching for Pearson (both International GCSE and GCSE)
                if (!$matched && $examBoard === 'Pearson' && in_array($courseType, ['International GCSE', 'GCSE'])) {
                    $baseCode = substr($subjectCode, 0, 4); // Use first 4 chars for most codes
                    $hasF = strpos($subjectCode, 'F') !== false;
                    $hasH = strpos($subjectCode, 'H') !== false;

                    $candidates = [];
                    foreach ($subjects as $subjName => $code) {
                        if (strpos($code, $baseCode) === 0) {
                            $candidates[] = ['name' => $subjName, 'code' => $code];
                        }
                    }

                    if (!empty($candidates)) {
                        $pick = null;
                        if ($hasF) {
                            foreach ($candidates as $c) {
                                if (strpos($c['code'], 'F') !== false) { $pick = $c; break; }
                            }
                        } elseif ($hasH) {
                            foreach ($candidates as $c) {
                                if (strpos($c['code'], 'H') !== false) { $pick = $c; break; }
                            }
                        } else {
                            // Stored code has no tier letter (e.g. "1CH0") - use normalized tier
                            if ($tier === 'Foundation') {
                                foreach ($candidates as $c) {
                                    if (strpos($c['code'], 'F') !== false || stripos($c['name'], 'Foundation') !== false) { $pick = $c; break; }
                                }
                            } elseif ($tier === 'Higher') {
                                foreach ($candidates as $c) {
                                    if (strpos($c['code'], 'H') !== false || stripos($c['name'], 'Higher') !== false) { $pick = $c; break; }
                                }
                            }
                            // If still no pick, prefer Higher, else take first
                            if (!$pick) {
                                foreach ($candidates as $c) {
                                    if (strpos($c['code'], 'H') !== false || stripos($c['name'], 'Higher') !== false) { $pick = $c; break; }
                                }
                            }
                            if (!$pick) {
                                $pick = $candidates[0];
                            }
                        }

                        if ($pick) {
                            $matchingSubject = $pick;
                            $matched = true;
                        }
                    }
                }

                // Last-resort fallback: stored subject name (e.g. "Chemistry") is contained in a dropdown name
                if (!$matched && $subjectName !== 'Unknown Subject') {
                    $nameCandidates = [];
                    foreach ($subjects as $subjName => $code) {
                        if (stripos($subjName, $subjectName) !== false) {
                            $nameCandidates[] = ['name' => $subjName, 'code' => $code];
                        }
                    }

                    if (!empty($nameCandidates)) {
                        // Dynamic preference: separate candidates by how the subject name appears
                        // Group A (highest): subject name appears at START of dropdown name (e.g. "Mathematics Paper 1...")
                        // Group B: subject name is a standalone WORD (word boundary) but not at start
                        // Group C (lowest): substring only (e.g. "Further Mathematics" contains "Mathematics")
                        $groupA = []; // starts with subject + next is NOT a compounding connector (pure subject)
                        $groupB = []; // word boundary match OR starts with but has a connector (&, and, of) after → compound subject
                        $groupC = []; // plain substring inside another word only (e.g. "History" within "Ancient History" where Ancient precedes)
                        foreach ($nameCandidates as $c) {
                            $nameLower = mb_strtolower($c['name']);
                            $snLower = mb_strtolower($subjectName);
                            $snLen = strlen($snLower);
                            $pos = stripos($c['name'], $subjectName);
                            $isStart = ($pos === 0);
                            // Check word boundaries: chars immediately before and after must not be letters
                            $beforeOk = true;
                            $afterOk = true;
                            if ($pos > 0) {
                                $chBefore = $nameLower[$pos - 1];
                                if (ctype_alpha($chBefore)) $beforeOk = false;
                            }
                            $afterCharPos = $pos + $snLen;
                            if ($afterCharPos < strlen($nameLower)) {
                                $chAfter = $nameLower[$afterCharPos];
                                if (ctype_alpha($chAfter)) $afterOk = false;
                            }
                            $isWordBoundary = $beforeOk && $afterOk;
                            // If starts with, check for compounding connectors IMMEDIATELY after the subject (+ optional spaces):
                            // e.g. "English Language & Literature", "English Language and Literature", "History Of Art"
                            $hasCompoundConnector = false;
                            if ($isStart && $afterCharPos < strlen($nameLower)) {
                                $rest = trim(substr($nameLower, $afterCharPos));
                                // Connector at start of rest: "& ", "and ", "of " (+ variants with comma)
                                if (preg_match('#^(\&|and|of)\b#i', $rest)) {
                                    $hasCompoundConnector = true;
                                }
                            }
                            if ($isStart && !$hasCompoundConnector) {
                                $groupA[] = $c;
                            } elseif ($isWordBoundary) {
                                $groupB[] = $c;
                            } else {
                                $groupC[] = $c;
                            }
                        }
                        // Prefer group A, then B, then C. Only drop to next group if current is empty.
                        $pool = !empty($groupA) ? $groupA : (!empty($groupB) ? $groupB : $groupC);

                        $pick = null;
                        if ($tier === 'Foundation') {
                            foreach ($pool as $c) {
                                if (strpos($c['code'], 'F') !== false || stripos($c['name'], 'Foundation') !== false) { $pick = $c; break; }
                            }
                        } elseif ($tier === 'Higher') {
                            foreach ($pool as $c) {
                                if (strpos($c['code'], 'H') !== false || stripos($c['name'], 'Higher') !== false) { $pick = $c; break; }
                            }
                        }
                        if (!$pick && count($pool) === 1) {
                            $pick = $pool[0];
                        }
                        if (!$pick) {
                            foreach ($pool as $c) {
                                if (strpos($c['code'], 'H') !== false || stripos($c['name'], 'Higher') !== false) { $pick = $c; break; }
                            }
                        }
                        if (!$pick) {
                            $pick = $pool[0];
                        }

                        $matchingSubject = $pick;
                        $matched = true;
                    }
                }
            }

            $preSelectedSubjects[$index] = $matchingSubject;
        }

        $latestStudent = StudentDetail::where('exam_session', session('current_session'))->where('exam_session', session('current_session'))->max('student_id');
        // dd($latestStudent);
        $nextId = $latestStudent ? sprintf('%04d', intval($latestStudent) + 1) : '1001';

        // For debugging
        // dd($preSelectedSubjects, $data);
        // dd($request->current_domain);
        // dd('');
        $referrals = Referral::orderBy('name')->get();
        return view('student.edit', compact('request', 'data', 'nextId', 'preSelectedSubjects', 'preSelectedTiers', 'referrals'));
    }
    // public function approveProceed(Request $request, $id)
    // {
    //     // Find the student request
    //     $studentRequest = StudentRequest::findOrFail($id);
    //     $basePath = public_path('studentDocs');

    //     // Handle file uploads (unchanged)
    //     if (!file_exists($basePath)) {
    //         mkdir($basePath, 0777, true);
    //     }

    //     if ($request->hasFile('photo')) {
    //         if ($studentRequest->photo_path && file_exists(public_path($studentRequest->photo_path))) {
    //             unlink(public_path($studentRequest->photo_path));
    //         }
    //         $photo = $request->file('photo');
    //         $photoName = 'photo_' . time() . '.' . $photo->getClientOriginalExtension();
    //         $photo->move($basePath, $photoName);
    //         $photoPath = 'studentDocs/' . $photoName;
    //     } else {
    //         $photoPath = $studentRequest->photo_path;
    //     }

    //     if ($request->hasFile('id_document')) {
    //         if ($studentRequest->id_document_path && file_exists(public_path($studentRequest->id_document_path))) {
    //             unlink(public_path($studentRequest->id_document_path));
    //         }
    //         $idDocument = $request->file('id_document');
    //         $idDocumentName = 'id_' . time() . '.' . $idDocument->getClientOriginalExtension();
    //         $idDocument->move($basePath, $idDocumentName);
    //         $idDocumentPath = 'studentDocs/' . $idDocumentName;
    //     } else {
    //         $idDocumentPath = $studentRequest->id_document_path;
    //     }

    //     if ($request->hasFile('supporting_documents')) {
    //         if ($studentRequest->supporting_documents_path && file_exists(public_path($studentRequest->supporting_documents_path))) {
    //             unlink(public_path($studentRequest->supporting_documents_path));
    //         }
    //         $supportingDoc = $request->file('supporting_documents');
    //         $supportingDocName = 'support_' . time() . '.' . $supportingDoc->getClientOriginalExtension();
    //         $supportingDoc->move($basePath, $supportingDocName);
    //         $supportingDocumentsPath = 'studentDocs/' . $supportingDocName;
    //     } else {
    //         $supportingDocumentsPath = $studentRequest->supporting_documents_path;
    //     }

    //     $additionalDocsPaths = $studentRequest->additional_docs_paths ?? [];
    //     if ($request->hasFile('additional_docs')) {
    //         if (!empty($studentRequest->additional_docs_paths)) {
    //             foreach ($studentRequest->additional_docs_paths as $oldDoc) {
    //                 if (file_exists(public_path($oldDoc))) {
    //                     unlink(public_path($oldDoc));
    //                 }
    //             }
    //         }
    //         $additionalDocsPaths = [];
    //         foreach ($request->file('additional_docs') as $key => $file) {
    //             $additionalDocName = 'additional_' . time() . '_' . $key . '.' . $file->getClientOriginalExtension();
    //             $file->move($basePath, $additionalDocName);
    //             $additionalDocsPaths[] = 'studentDocs/' . $additionalDocName;
    //         }
    //     }

    //     // Update StudentRequest (unchanged)
    //     $studentRequest->update([
    //         'surname' => $request->surname,
    //         'first_name' => $request->first_name,
    //         'dob' => $request->dob,
    //         'gender' => $request->gender,
    //         'address_line1' => $request->address_line1,
    //         'address_line2' => $request->address_line2,
    //         'postcode' => $request->postcode,
    //         'uci_number' => $request->uci_number,
    //         'contact_number' => $request->contact_number,
    //         'email' => $request->email,
    //         'emergency_contact' => $request->emergency_contact,
    //         'uln' => $request->uln,
    //         'medical_condition' => $request->medical_condition,
    //         'access_arrangement' => $request->access_arrangement === '1',
    //         'access_arrangement_explanation' => $request->access_arrangement_explanation,
    //         'practical_endorsement' => $request->practical_endorsement,
    //         'course_work' => $request->course_work,
    //         'course_work_uci' => $request->course_work_uci,
    //         'exam_board' => json_encode($request->examBoard),
    //         'course_type' => json_encode($request->courseType),
    //         'subject_name' => json_encode($request->subjectName),
    //         'subject_code' => json_encode($request->subjectCode),
    //         'exams' => json_encode($request->exams),
    //         'tire' => json_encode($request->tire),
    //         'nea' => json_encode($request->nea),
    //         'photo_path' => $photoPath,
    //         'id_document_path' => $idDocumentPath,
    //         'supporting_documents_path' => $supportingDocumentsPath,
    //         'additional_docs_paths' => $additionalDocsPaths,
    //         'is_approved' => 1,
    //     ]);

    //     // Delete existing StudentDetail records
    //     StudentDetail::where('student_request_id', $studentRequest->id)->delete();

    //     // Process each row and create StudentDetail records
    //     foreach ($request->examBoard as $index => $examBoard) {
    //         $examBoard = is_array($examBoard) ? $examBoard[0] : $examBoard;
    //         $courseType = $request->courseType[$index] ?? null;
    //         $tire = $request->tire[$index] ?? null;
    //         $nea = $request->nea[$index] ?? null;
    //         $selectedExams = $request->exams[$index] ?? [];

    //         // Fetch subjectName and subjectCode directly from the request for this index
    //         $subjectName = $request->subjectName[$index] ?? null;
    //         $subjectCode = $request->subjectCode[$index] ?? null;

    //         // Query the Subject model to get exam details based on subjectCode
    //         $subjectDetails = Subjects::where('subjectCode', $subjectCode)
    //             ->select('examDay', 'examTime', 'examDate', 'duration')
    //             ->first();

    //         $examDay = $subjectDetails->examDay ?? null;
    //         $examTime = $subjectDetails->examTime ?? null;
    //         $examDate = $subjectDetails->examDate ?? null;
    //         $duration = $subjectDetails->duration ?? null;

    //         // Process each selected exam in the group

    //         foreach ($selectedExams as $exam) {

    //             $examParts = explode(' - ', $exam, 2);

    //             $examName = isset($examParts[0]) ? trim($examParts[0]) : null; // e.g., "Chinese Paper 1: Listening"
    //             $examCode = isset($examParts[1]) ? trim($examParts[1]) : null; // e transparently.g., "4CN1 01"
    //             // dd($exam,$examParts,$examParts[0],$examCode,$examName);
    //             // exit;
    //             // Query Subject model for specific exam details
    //             $examDetails = Subjects::where('subjectCode', $examCode)
    //                 ->select('examDay', 'examTime', 'examDate', 'duration')
    //                 ->first();

    //             $examDay = $examDetails->examDay ?? $subjectDetails->examDay ?? null;
    //             $examTime = $examDetails->examTime ?? $subjectDetails->examTime ?? null;
    //             $examDate = $examDetails->examDate ?? $subjectDetails->examDate ?? null;
    //             $duration = $examDetails->duration ?? $subjectDetails->duration ?? null;

    //             StudentDetail::create([
    //                 'student_id' => $request->student_id,
    //                 'student_request_id' => $studentRequest->id,
    //                 'surname' => $request->surname,
    //                 'first_name' => $request->first_name,
    //                 'dob' => $request->dob,
    //                 'gender' => $request->gender,
    //                 'address_line1' => $request->address_line1,
    //                 'address_line2' => $request->address_line2,
    //                 'postcode' => $request->postcode,
    //                 'uci_number' => $request->uci_number,
    //                 'contact_number' => $request->contact_number,
    //                 'email' => $request->email,
    //                 'emergency_contact' => $request->emergency_contact,
    //                 'uln' => $request->uln,
    //                 'medical_condition' => $request->medical_condition,
    //                 'access_arrangement' => $request->access_arrangement === '1',
    //                 'access_arrangement_explanation' => $request->access_arrangement_explanation,
    //                 'practical_endorsement' => $request->practical_endorsement,
    //                 'course_work' => $request->course_work,
    //                 'course_work_uci' => $request->course_work_uci,
    //                 'exam_board' => $examBoard,
    //                 'course_type' => $courseType,
    //                 'subject_name' => $examName, // From request->subjectName[$index]
    //                 'subject_code' => $examCode, // From request->subjectCode[$index]
    //                 // 'exam_name' => $examName, // Specific exam name from exams array
    //                 // 'exam_code' => $examCode, // Specific exam code from exams array
    //                 'exams' =>json_encode($request->exams),
    //                 'day' => $examDay,
    //                 'time' => $examTime,
    //                 'date' => $examDate,
    //                 'duration' => $duration,
    //                 'tire' => $tire,
    //                 'nea' => $nea,
    //                 'photo_path' => $photoPath,
    //                 'id_document_path' => $idDocumentPath,
    //                 'supporting_documents_path' => $supportingDocumentsPath,
    //                 'additional_docs_paths' => $additionalDocsPaths,
    //             ]);
    //         }

    //         // If no exams are selected, create a record with subject details only
    //         // if (empty($selectedExams)) {
    //         //     StudentDetail::create([
    //         //         'student_id' => $request->student_id,
    //         //         'student_request_id' => $studentRequest->id,
    //         //         'surname' => $request->surname,
    //         //         'first_name' => $request->first_name,
    //         //         'dob' => $request->dob,
    //         //         'gender' => $request->gender,
    //         //         'address_line1' => $request->address_line1,
    //         //         'address_line2' => $request->address_line2,
    //         //         'postcode' => $request->postcode,
    //         //         'uci_number' => $request->uci_number,
    //         //         'contact_number' => $request->contact_number,
    //         //         'email' => $request->email,
    //         //         'emergency_contact' => $request->emergency_contact,
    //         //         'uln' => $request->uln,
    //         //         'medical_condition' => $request->medical_condition,
    //         //         'access_arrangement' => $request->access_arrangement === '1',
    //         //         'access_arrangement_explanation' => $request->access_arrangement_explanation,
    //         //         'practical_endorsement' => $request->practical_endorsement,
    //         //         'course_work' => $request->course_work,
    //         //         'course_work_uci' => $request->course_work_uci,
    //         //         'exam_board' => $examBoard,
    //         //         'course_type' => $courseType,
    //         //         'subject_name' => $subjectName,
    //         //         'subject_code' => $subjectCode,
    //         //         // 'exam_name' => null,
    //         //         // 'exam_code' => null,
    //         //         'day' => $examDay,
    //         //         'time' => $examTime,
    //         //         'date' => $examDate,
    //         //         'duration' => $duration,
    //         //         'tire' => $tire,
    //         //         'nea' => $nea,
    //         //         'photo_path' => $photoPath,
    //         //         'id_document_path' => $idDocumentPath,
    //         //         'supporting_documents_path' => $supportingDocumentsPath,
    //         //         'additional_docs_paths' => $additionalDocsPaths,
    //         //     ]);
    //         // }
    //     }

    //     return redirect()->back()->with('success', 'Student request approved and details updated successfully!');
    // }
    public function approveProceed(Request $request, $id)
    {
        // dd($request->all());
        // dd($id);
        // Find the student request
        $studentRequest = StudentRequest::where('exam_session', session('current_session'))->findOrFail($id);
        $basePath = public_path('studentDocs');

        // Handle file uploads (unchanged)
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
        }

        if ($request->hasFile('photo')) {
            if ($studentRequest->photo_path && file_exists(public_path($studentRequest->photo_path))) {
                unlink(public_path($studentRequest->photo_path));
            }
            $photo = $request->file('photo');
            $photoName = 'photo_' . time() . '.' . $photo->getClientOriginalExtension();
            $photo->move($basePath, $photoName);
            $photoPath = 'studentDocs/' . $photoName;
        } else {
            $photoPath = $studentRequest->photo_path;
        }

        if ($request->hasFile('id_document')) {
            if ($studentRequest->id_document_path && file_exists(public_path($studentRequest->id_document_path))) {
                unlink(public_path($studentRequest->id_document_path));
            }
            $idDocument = $request->file('id_document');
            $idDocumentName = 'id_' . time() . '.' . $idDocument->getClientOriginalExtension();
            $idDocument->move($basePath, $idDocumentName);
            $idDocumentPath = 'studentDocs/' . $idDocumentName;
        } else {
            $idDocumentPath = $studentRequest->id_document_path;
        }

        if ($request->hasFile('supporting_documents')) {
            if ($studentRequest->supporting_documents_path && file_exists(public_path($studentRequest->supporting_documents_path))) {
                unlink(public_path($studentRequest->supporting_documents_path));
            }
            $supportingDoc = $request->file('supporting_documents');
            $supportingDocName = 'support_' . time() . '.' . $supportingDoc->getClientOriginalExtension();
            $supportingDoc->move($basePath, $supportingDocName);
            $supportingDocumentsPath = 'studentDocs/' . $supportingDocName;
        } else {
            $supportingDocumentsPath = $studentRequest->supporting_documents_path;
        }

        $additionalDocsPaths = $studentRequest->additional_docs_paths ?? [];
        if ($request->hasFile('additional_docs')) {
            if (!empty($studentRequest->additional_docs_paths)) {
                foreach ($studentRequest->additional_docs_paths as $oldDoc) {
                    if (file_exists(public_path($oldDoc))) {
                        unlink(public_path($oldDoc));
                    }
                }
            }
            $additionalDocsPaths = [];
            foreach ($request->file('additional_docs') as $key => $file) {
                $additionalDocName = 'additional_' . time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $file->move($basePath, $additionalDocName);
                $additionalDocsPaths[] = 'studentDocs/' . $additionalDocName;
            }
        }

        // Update StudentRequest (unchanged)
        $studentRequest->update([
            'current_domain' => $request->current_domain ?? null,
            'surname' => $request->surname,
            'first_name' => $request->first_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'postcode' => $request->postcode,
            'uci_number' => $request->uci_number,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'emergency_contact' => $request->emergency_contact,
            'uln' => $request->uln,
            'medical_condition' => $request->medical_condition,
            'access_arrangement' => $request->access_arrangement === '1',
            'access_arrangement_explanation' => $request->access_arrangement_explanation,
            'practical_endorsement' => $request->practical_endorsement,
            'need_predicted_grades' => $request->need_predicted_grades,
            'need_mock_exams' => $request->need_mock_exams,
            'course_work' => $request->course_work,
            'course_work_uci' => $request->course_work_uci,
            'exam_board' => json_encode($request->examBoard),
            'course_type' => json_encode($request->courseType),
            'subject_name' => json_encode($request->subjectName),
            'subject_code' => json_encode($request->subjectCode),
            'exams' => json_encode($request->exams),
            'tire' => json_encode($request->tire),
            'nea' => json_encode($request->nea),
            'photo_path' => $photoPath,
            'id_document_path' => $idDocumentPath,
            'supporting_documents_path' => $supportingDocumentsPath,
            'additional_docs_paths' => $additionalDocsPaths,
            'is_approved' => 1,

        ]);

        // Delete existing StudentDetail records
        StudentDetail::where('exam_session', session('current_session'))->where('student_request_id', $studentRequest->id)->delete();

        $activeSession = Session::where('status', 'active')->first();


        $receiptPath = $studentRequest->receipt_path ?? null;
        if ($request->hasFile('receipt_upload')) {
            if ($receiptPath && file_exists(public_path($receiptPath))) {
                unlink(public_path($receiptPath));
            }
            $receipt = $request->file('receipt_upload');
            $receiptName = 'receipt_' . time() . '.' . $receipt->getClientOriginalExtension();
            $receipt->move($basePath, $receiptName);
            $receiptPathCan = 'studentDocs/' . $receiptName;
        }

        $amountReceived = $request->amount_received;

        // Process each row and create StudentDetail records
        foreach ($request->examBoard as $index => $examBoard) {



            $examBoard = is_array($examBoard) ? $examBoard[0] : $examBoard;
            $courseType = $request->courseType[$index] ?? null;
            $tire = $request->tire[$index] ?? null;
            $nea = $request->nea[$index] ?? null;
            $selectedExams = $request->exams[$index] ?? [];

            // Fetch subjectName and subjectCode directly from the request for this index
            $subjectName = $request->subjectName[$index] ?? null;
            $subjectCode = $request->subjectCode[$index] ?? null;

            // Query the Subject model to get exam details based on subjectCode

            $subjectDetails = Subjects::where('examSession', session('current_session'))->where('subjectCode', $subjectCode)
                ->select('examDay', 'examTime', 'examDate', 'duration')
                ->first();

            $examDay = $subjectDetails->examDay ?? null;
            $examTime = $subjectDetails->examTime ?? null;
            $examDate = $subjectDetails->examDate ?? null;
            $duration = $subjectDetails->duration ?? null;
            // dd('',$selectedExams);
            // Process each selected exam in the group
            Log::info('reached');
            foreach ($selectedExams as $exam) {
                Log::info('start');
                // Change separator from ' - ' to ' --- '
                $examParts = explode(' ./. ', $exam, 2);
                $examName = isset($examParts[0]) ? trim($examParts[0]) : null; // e.g., "Chinese Paper 1: Listening"
                $examCode = isset($examParts[1]) ? trim($examParts[1]) : null; // e.g., "4CN1 01"

                // Query Subject model for specific exam details
                $examDetails = Subjects::where('examSession', session('current_session'))->where('subjectCode', $examCode)
                    ->select('examDay', 'examTime', 'examDate', 'duration')
                    ->first();

                $examDay = $examDetails->examDay ?? $subjectDetails->examDay ?? null;
                $examTime = $examDetails->examTime ?? $subjectDetails->examTime ?? null;
                $examDate = $examDetails->examDate ?? $subjectDetails->examDate ?? null;
                $duration = $examDetails->duration ?? $subjectDetails->duration ?? null;

                // All exams in this row ($index) will have the same unit number
                StudentDetail::create([

                    'student_id' => $request->student_id,
                    'student_request_id' => $studentRequest->id,
                    'surname' => $request->surname,
                    'first_name' => $request->first_name,
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2,
                    'postcode' => $request->postcode,
                    'uci_number' => $request->uci_number,
                    'contact_number' => $request->contact_number,
                    'email' => $request->email,
                    'emergency_contact' => $request->emergency_contact,
                    'uln' => $request->uln,
                    'medical_condition' => $request->medical_condition,
                    'access_arrangement' => $request->access_arrangement === '1',
                    'access_arrangement_explanation' => $request->access_arrangement_explanation,
                    'practical_endorsement' => $request->practical_endorsement,
                    'need_predicted_grades' => $request->need_predicted_grades,
                    'need_mock_exams' => $request->need_mock_exams,
                    'course_work' => $request->course_work,
                    'course_work_uci' => $request->course_work_uci,
                    'exam_board' => $examBoard,
                    'course_type' => $courseType,
                    'subject_name' => $examName, // Specific exam name
                    'subject_code' => $examCode, // Specific exam code
                    'exams' => json_encode($request->exams),
                    'day' => $examDay,
                    'time' => $examTime,
                    'date' => $examDate,
                    'duration' => $duration,
                    'tire' => $tire,
                    'nea' => $nea,
                    'photo_path' => $photoPath,
                    'id_document_path' => $idDocumentPath,
                    'supporting_documents_path' => $supportingDocumentsPath,
                    'additional_docs_paths' => $additionalDocsPaths,
                    'unit' => "unit " . ($index + 1), // All exams in this row get the same unit
                    'exam_session' => $request->exam_session, //$activeSession->session_name,
                    'form_fill_date' => $request->form_fill_date,
                    'sign' => $request->sign,
                    'current_domain' => $request->current_domain ?? null,
                    'amount_received' => $amountReceived ?? null,
                    'receipt_path'    => $receiptPathCan ?? null,
                    'referral'        => $request->referral ?? null,

                ]);
                Log::info('making data');
            }
        }


        //////////////////////////////for result and send email module ////////////////////////////////////
        $activeSession = Session::where('status', 'active')->first();

        // Filter out "widthraw" at the database level (case-insensitive)
        $studentDetails = StudentDetail::where('exam_session', session('current_session'))

            ->where(function ($query) {
                $query->where('first_name', 'NOT LIKE', '%widthraw%')
                    ->where('surname', 'NOT LIKE', '%widthraw%');
            })
            ->get();

        $candidateAndUciNumbers = CandidateAndUciNumber::where('session', session('current_session'))->get();

        $selectedAttributes = $studentDetails->map(function ($student) {
            return [
                'student_id' => $student->student_id,
                'candidate_name' => $student->first_name . ' ' . $student->surname,
                'uci_number' => $student->uci_number,
                'email' => $student->email,
            ];
        })->unique('student_id');

        $selectedAttributesArray = $selectedAttributes->toArray();
        $candidateAndUciNumbersArray = $candidateAndUciNumbers->pluck('candidate_id')->toArray();

        $missingRecords = array_filter($selectedAttributesArray, function ($item) use ($candidateAndUciNumbersArray) {
            return !in_array($item['student_id'], $candidateAndUciNumbersArray);
        });

        foreach ($missingRecords as $record) {
            CandidateAndUciNumber::updateOrCreate(
                [
                    'candidate_id' => $record['student_id'],
                    'session' => session('current_session')
                ], // Search condition
                [
                    'candidate_name' => $record['candidate_name'],
                    'uci_number' => $record['uci_number'],
                    'email' => $record['email'],
                    'is_sent' => null,
                ]
            );
        }
        ///////////////////////////////////////////////////////////////////////////////////////////
        $fullName = trim($request->surname . ' ' . $request->first_name);

        // Receipt upload handle karo (agar naya upload kiya ho)
        $finalReceiptPath = $studentRequest->receipt_path ?? null;

        // Amount received
        $amountReceived = $request->amount_received ?? 0;

        // Payment entry create karo (payments table mein)
        \App\Models\Payment::create([
            'session'           => session('current_session'), // ya $request->exam_session
            'student_id'        => $request->student_id,
            'name'              => $fullName,
            'payment_received'  => 'Yes',                    // since approved, payment confirmed
            'amount_received'   => $amountReceived,
            'confirmed_by'      => 'Receipt', // admin who approved
            'payment_method'    => 'N/A',
            'note'              => 'N/A',
            'receipt_path'      => $receiptPathCan ?? null,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);


         $activeSession =  session('current_session');

        // get unique student records for active session
        $students = StudentDetail::where('exam_session', $activeSession)
            ->select('student_id', 'first_name', 'surname', 'uci_number', 'email')
            ->groupBy('student_id', 'first_name', 'surname', 'uci_number', 'email')
            ->get();

        // update mismatched records only
        foreach ($students as $student) {

            CandidateAndUciNumber::where('candidate_id', $student->student_id)
                ->where('session', $activeSession)
                ->where(function ($q) use ($student) {
                    $q->where('candidate_name', '!=', $student->first_name.' '.$student->surname)
                    ->orWhere('email', '!=', $student->email)
                    ->orWhere('uci_number', '!=', $student->uci_number);
                })
                ->update([
                    'candidate_name' => $student->first_name.' '.$student->surname,
                    'email'          => $student->email,
                    'uci_number'     => $student->uci_number,
                    'updated_at'     => now(),
                ]);
        }


        return redirect()->route('studentRequest')->with('success', 'Student request approved successfully!');
    }

//     public function getTimetableSubjs(Request $request)
//     {
//         // dd($request->all());
//         $activeSession = Session::where('status', 'active')->first();

//         if (!$activeSession) {
//             return response()->json(['error' => 'Please activate a session from the sessions page to proceed.'], 400);
//         }

//         $examBoard = $request->input('examBoard');
//         $courseType = $request->input('courseType');
//         $subjectName = $request->input('subjectName');
//         $subjectCode = $request->input('subjectCode');

//         if (!empty($subjectCode)) {
//             // Extract the base code (part before any /, space, or -, and remove F or H)
//             $baseCode = preg_replace('/[\/\s-].*$|[FH]$/', '', $subjectCode);


//             // Determine if subjectCode contains F or H for tier filtering
//             $tier = null;
//             // dd($examBoard, $courseType, $subjectCode);
//             if ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4WHI') {
//             } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 3) === '4WM') {
//             } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4WPH') {
//             } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4WCH') {
//             } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4MA1') {
//             } elseif ($examBoard === 'OCR' && $courseType === 'A Level') {
//             } elseif ($examBoard === 'OCR' && $courseType === 'AS') {
//             }
//             // elseif ($examBoard === 'WJEC' && $courseType === 'GCSE' && substr($subjectCode, 0, 4) === '4MA1') {
//             // }
//             else {

//                 if ($examBoard == 'Pearson' && $courseType == 'A Level' && in_array($subjectCode, ['9HI0 30-39', '9HI0 2A-2H', '9HI0 1A-1H', '9HI0', '9CH0 01', '9CH0 02', '9CH0 03', '9CH0', '9FM0', '9PH0', '9FR0', '9HT0', '9FR0 01', '9FR0 02', '9PH0 01', '9PH0 02', '9PH0 03', '9FM0 01', '9FM0 02', '9FM0 3A', '9FM0 3B', '9FM0 3C', '9FM0 3D', '9FM0 4A', '9FM0 4B', '9FM0 4C', '9FM0 4D', '9HT0 01', '9HT0 02'])) {
//                 } elseif ($examBoard == 'Pearson' && $courseType == 'AS Level' && in_array($subjectCode, ['8CH0', '8FM0', '8PH0', '8CH0 01', '8CH0 02', '8FM0 01', '8FM0 2A-2K', '8PH0 01', '8PH0 02'])) {
//                 } elseif ($examBoard == 'Pearson' && $courseType == 'GCSE' && in_array($subjectCode, ['1BH0', '1BH0 01', '1BH0 02', '1HI0', '1HI0 10-13', '1HI0 2A-2W', '1HI0 30-33', '1FR1H', '1FR1 2H', '1FR1 3H', '1FR1 4H', '1PS0 01',])) {
//                 } else {

//                     if (strpos($subjectCode, 'F') !== false) {
//                         $tier = 'F';
//                     } elseif (strpos($subjectCode, 'H') !== false) {
//                         $tier = 'H';
//                     }
//                 }
//             }

//             // Query subjects table to get matching exams
//             $query = Subjects::select('subjectName', 'subjectCode')
//                 ->where('examBoard', $examBoard)
//                 ->where('courseType', $courseType)
//                 ->where('examSession', session('current_session'));

//             // Apply subject code and tier filtering
//             $query->where(function ($query) use ($baseCode, $tier, $examBoard, $courseType, $subjectCode) {

//                 if ($tier) {
//                     // dd('');
//                     // dd($examBoard,$courseType,$subjectCode,$baseCode);
//                     if ($examBoard == 'Pearson' && $courseType == 'International GCSE') {
//                         // dd('1');
//                         $query->where('subjectCode', 'LIKE', $baseCode . '%')
//                             ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
//                             ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
//                             ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
//                     } elseif ($examBoard == 'WJEC' && $courseType == 'GCSE') {
//                         // dd($baseCode,$tier);
//                         if ($baseCode == 'C300' && $tier == 'F') {
//                             $query->whereIn('subjectCode', ['C300U10-1', 'C300U20-1']);
//                         }
//                         if ($baseCode == 'C300' && $tier == 'H') {
//                             $query->whereIn('subjectCode', ['C300UB0-1', 'C300UA0-1']);
//                         }

//                         if ($baseCode == '3320' && $tier == 'H') {
//                             $query->whereIn('subjectCode', ['3320UA0-1', '3320UB0-1']);
//                         }

//                         if ($baseCode == '3320' && $tier == 'F') {
//                             $query->whereIn('subjectCode', ['3320U10-1', '3320U20-1']);
//                         }
//                     } else {
//                         // dd('2');
//                         // dd($baseCode,$tier, $examBoard, $courseType);


//                              if($examBoard == 'AQA' && $courseType =='GCSE' && $baseCode == '8463')
//                             {

//                                   $query->whereIn('subjectCode', ['8463/1F', '8463/1H','8463/2F','8463/2H']);
//                                 //   dd($query->get());
//                             }
//                             else
//                                 {

//                               if($examBoard == 'AQA' && $courseType =='GCSE' && $baseCode == '8462')
//                             {
//                                     // dd('');
//                                     $query->whereIn('subjectCode', ['8462/1F', '8462/1H','8462/2F','8462/2H']);
//                             }
//                             else{

//                             if($examBoard == 'AQA' && $courseType =='GCSE' && $baseCode == '8461')
//                                 {
//  $query->whereIn('subjectCode', ['8461/1F', '8461/1H','8461/2F','8461/2H']);
//                                 }else{
//  $query->whereRaw("REGEXP_REPLACE(subjectCode, '/.*|-.*| .*', '') = ?", [$baseCode])
//                                     ->where('subjectCode', 'LIKE', '%' . $tier);
//                                 }


//                             }


//                                 }

//                         // dd($query->ge/t());
//                     }
//                 } else {
//                     // dd('');
//                     // dd($baseCode, $tier, $examBoard, $courseType, $subjectCode);


//                     //     if ($examBoard == 'Pearson' && $courseType == 'International A-Level' && $subjectCode == '')
//                     //     {
//                     //         dd($examBoard, $courseType, $subjectCode);
//                     //         $this->process_Pearson_international_A_Level($examBoard, $courseType, $subjectCode);
//                     //     }

//                     // Pearson Papers IGCSE 4WAC logic start select paper based on frst 4 letters/chars
//                     if ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WAC') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WBI') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WRS') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WCM') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WEC') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WES') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WEA') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WGE') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WET') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WHI') {
//                         // dd('');
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 3) === '4WM') {
//                         $query = $this->searchSubjectsByCodeMathsTier($examBoard, $courseType, $baseCode, $query);
//                         // dd($query->first(),$examBoard, $courseType, $baseCode,);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WPH') {
//                         // dd('');
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WSD') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WCH') {
//                         $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4IT1') {
//                         $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4CP0') {
//                         $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4MA1') {
//                         // in_array($lastChar = substr($subjectCode, -1), ['F', 'H']) ? $query->where('subjectCode', 'LIKE', '%' . $lastChar) : $query->where(function ($q) use ($baseCode) {
//                         //     $baseCode = substr($baseCode, 0, 3);
//                         //     $q->where('subjectCode', 'LIKE', $baseCode . '%')->orWhere('subjectCode', 'LIKE', $baseCode . '/%')->orWhere('subjectCode', 'LIKE', $baseCode . ' %')->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
//                         // });
//                          $lastChar = substr(trim($subjectCode), -1); // F or H

//     if ($lastChar === 'F') {
//         // dd('1');
//         // Only Foundation papers
//         $query->whereIn('subjectCode', ['4MA1 1F', '4MA1 2F']);
//     } elseif ($lastChar === 'H') {
//         // Only Higher papers
//         $query->whereIn('subjectCode', ['4MA1 1H', '4MA1 2H']);
//     } else {
//         // If no F/H, return nothing or skip
//         $query->whereRaw('1 = 0'); // return no results
//     }
//                     } elseif ($examBoard === 'WJEC/EDUQAS' && $courseType === 'GCSE' && strlen($subjectCode) > 4) {
                        
                       
//                         if($baseCode === 'C700U10')
//                        {
//                             $query->whereIn('subjectCode', ['C700U10-1', 'C700U20-1']);
//                        }
//                        if($baseCode === 'C300U10')
//                        {
//                             $query->whereIn('subjectCode', ['C300U10-1', 'C300U20-1']);
//                        }
//                        if($baseCode === 'C300UA0')
//                        {
//                             $query->whereIn('subjectCode', ['C300UA0-1', 'C300UB0-1']);
//                        }
//                         // $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'WJEC' && $courseType === 'A Level' && strlen($subjectCode) > 4) {
//                         $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'WJEC' && $courseType === 'AS Level' && strlen($subjectCode) > 4) {
//                         $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
//                     } elseif ($examBoard === 'WJEC' && $courseType === 'A2' && strlen($subjectCode) > 4) {
//                         $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
//                     } else {
//                         // dd('');

//                         if ($examBoard == 'Pearson' && $courseType == 'GCSE' && in_array($subjectCode, ['1FR1H', '1FR1 2H', '1FR1 3H', '1FR1 4H'])) {
//                             $query->whereIn('subjectCode', ['1FR1 2H', '1FR1 3H', '1FR1 4H']);
//                             //    dd('1');
//                         } elseif ($examBoard == 'WJEC' && $courseType == 'GCSE' && $baseCode == '3160U10') {

//                             $query->whereIn('subjectCode', ['3160U10-1', '3160U30-1']);
//                         } else {
//                             // dd($examBoard,$courseType,$baseCode);



//                             $query->where('subjectCode', 'LIKE', $baseCode . '%')
//                                 ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
//                                 ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
//                                 ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');



//                             // dd('2');

//                         }
//                     }
//                 }
//             });




//             // if ($examBoard === 'WJEC/EDUQAS' && $courseType === 'GCSE') {
//             //     // dd($subjectCode);
//             //     // dd($activeSession->session_name);

//             //     $query = Subjects::select('subjectName', 'subjectCode')
//             //         ->where('examBoard', $examBoard)
//             //         ->where('courseType', $courseType)
//             //         ->where('examSession', session('current_session'));

//             //     if ($subjectCode === 'C300H' || $subjectCode === 'C300UB0-1H' || $subjectCode === 'C300UA0-1H') {
//             //         $query->where(function ($q) {
//             //             $q->where('subjectCode', 'C300UA0-1H')
//             //                 ->orWhere('subjectCode', 'C300UB0-1H');
//             //         });
//             //     } elseif ($subjectCode === 'C300F' || $subjectCode === 'C300U10-1F' || $subjectCode === 'C300U20-1F') {
//             //         $query->where(function ($q) {
//             //             $q->where('subjectCode', 'C300U10-1F')
//             //                 ->orWhere('subjectCode', 'C300U20-1F');
//             //         });
//             //     } elseif ($subjectCode === 'C700U20-1') {
//             //         $query->where(function ($q) {
//             //             $q->where('subjectCode', 'C700U20-1');
//             //         });
//             //     } elseif ($subjectCode === 'C700U10-1') {
//             //         $query->where(function ($q) {
//             //             $q->where('subjectCode', 'C700U10-1');
//             //         });
//             //     }
//             // }

//              // for summer 2027 start
//             // if($examBoard === 'AQA' && $courseType === 'A Level' ||  $courseType === 'AS Level' )
//             //  {
//             //       $query->where('subjectCode', 'LIKE', $baseCode . '%')
//             //                      ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
//             //                      ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
//             //                      ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');

//             //  }

//              if ($examBoard === 'WJEC/EDUQAS' && $courseType === 'International GCSE') {
//                 // dd($subjectCode);
//                 // dd($activeSession->session_name);

//                 $query = Subjects::select('subjectName', 'subjectCode')
//                     ->where('examBoard', $examBoard)
//                     ->where('courseType', $courseType)
//                     ->where('examSession', session('current_session'));

//                 if ($subjectCode === 'C700U10-1' || $subjectCode === 'C700U20-1' ) {
//                     // dd('');
//                     $query->where(function ($q) {
//                         $q->where('subjectCode', 'C700U10-1')
//                             ->orWhere('subjectCode', 'C700U20-1');
//                     });
//                     // dd($query->get());
//                 } elseif ($subjectCode === 'C300U10-1' || $subjectCode === 'C300U20-1') {
//                     $query->where(function ($q) {
//                         $q->where('subjectCode', 'C300U10-1')
//                             ->orWhere('subjectCode', 'C300U20-1');
//                     });
//                 } elseif ($subjectCode === 'C300UA0-1') {
//                      $query->where(function ($q) {
//                         $q->where('subjectCode', 'C300UA0-1')
//                             ->orWhere('subjectCode', 'C300UB0-1');
//                     });
//                 } 
//             }


//             $allowedCodesInitial = ['J560/01', 'J560/02', 'J560/03', 'J560F', 'J560/04', 'J560/05', 'J560/06', 'J560H'];
//             if ($examBoard === 'OCR' && $courseType === 'GCSE' && in_array($subjectCode, $allowedCodesInitial)) {
//                 $query = Subjects::select('subjectName', 'subjectCode')
//                     ->where('examBoard', $examBoard)
//                     ->where('courseType', $courseType)
//                     ->where('examSession', session('current_session'));

//                 // Define your grouped codes
//                 $group1 = ['J560/01', 'J560/02', 'J560/03', 'J560F'];
//                 $group2 = ['J560/04', 'J560/05', 'J560/06', 'J560H'];

//                 // Check which group the current subject belongs to and apply the filter
//                 if (in_array($subjectCode, $group1)) {
//                     $query->whereIn('subjectCode', $group1);
//                 } elseif (in_array($subjectCode, $group2)) {
//                     $query->whereIn('subjectCode', $group2);
//                 }
//             }



//             if ($examBoard === 'Pearson' && $courseType === 'Functional Skills') {
    
//     // Base query setup
//     $query = Subjects::select('subjectName', 'subjectCode')
//         ->where('examBoard', $examBoard)
//         ->where('courseType', $courseType)
//         ->where('examSession', session('current_session'));
//             // dd($subjectCode);
//     // Separate check for each of the 10 subject codes
//     if ($subjectCode === 'FSM EL1') {
//         $query->where('subjectCode', 'FSM EL1');
//     } elseif ($subjectCode === 'FSM EL2') {
//         $query->where('subjectCode', 'FSM EL2');
//     } elseif ($subjectCode === 'FSM EL3') {
//         $query->where('subjectCode', 'FSM EL3');
//     } elseif ($subjectCode === 'FSM L1') {
//         $query->where('subjectCode', 'FSM L1');
//     } elseif ($subjectCode === 'FSM L2') {
//         $query->where('subjectCode', 'FSM L2');
//     } elseif ($subjectCode === 'FSE EL1') {
//         $query->where('subjectCode', 'FSE EL1');
//     } elseif ($subjectCode === 'FSE EL2') {
//         $query->where('subjectCode', 'FSE EL2');
//     } elseif ($subjectCode === 'FSE EL3') {
//         $query->where('subjectCode', 'FSE EL3');
//     } elseif ($subjectCode === 'FSE L1') {
//         $query->where('subjectCode', 'FSE L1');
//     } elseif ($subjectCode === 'FSE L2') {
//         $query->where('subjectCode', 'FSE L2');
//     }
// }


//             $exams = $query->get();
//             // dd($exams);

//             // Format the exams data for the dropdown
//             $examsList = $exams->map(function ($exam) {
//                 return sprintf('%s ./. %s', $exam->subjectName, $exam->subjectCode);
//             })->toArray();

//             return response()->json([
//                 'exams' => $examsList
//             ]);
//         } else {
//             return response()->json([
//                 'exams' => null
//             ]);
//         }
//     }

 public function getTimetableSubjs(Request $request)
    {
        // dd($request->all());
        $activeSession = Session::where('status', 'active')->first();

        if (!$activeSession) {
            return response()->json(['error' => 'Please activate a session from the sessions page to proceed.'], 400);
        }

        $examBoard = $request->input('examBoard');
        $courseType = $request->input('courseType');
        $subjectName = $request->input('subjectName');
        $subjectCode = $request->input('subjectCode');

        if (!empty($subjectCode)) {
            // Extract the base code (part before any /, space, or -, and remove F or H)
            $baseCode = preg_replace('/[\/\s-].*$|[FH]$/', '', $subjectCode);


            // Determine if subjectCode contains F or H for tier filtering
            $tier = null;
            // dd($examBoard, $courseType, $subjectCode);
            if ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4WHI') {
            } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 3) === '4WM') {
            } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4WPH') {
            } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4WCH') {
            } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($subjectCode, 0, 4) === '4MA1') {
            } elseif ($examBoard === 'OCR' && $courseType === 'A Level') {
            } elseif ($examBoard === 'OCR' && $courseType === 'AS') {
            }
            // elseif ($examBoard === 'WJEC' && $courseType === 'GCSE' && substr($subjectCode, 0, 4) === '4MA1') {
            // }
            else {

                if ($examBoard == 'Pearson' && $courseType == 'A Level' && in_array($subjectCode, ['9HI0 30-39', '9HI0 2A-2H', '9HI0 1A-1H', '9HI0', '9CH0 01', '9CH0 02', '9CH0 03', '9CH0', '9FM0', '9PH0', '9FR0', '9HT0', '9FR0 01', '9FR0 02', '9PH0 01', '9PH0 02', '9PH0 03', '9FM0 01', '9FM0 02', '9FM0 3A', '9FM0 3B', '9FM0 3C', '9FM0 3D', '9FM0 4A', '9FM0 4B', '9FM0 4C', '9FM0 4D', '9HT0 01', '9HT0 02'])) {
                } elseif ($examBoard == 'Pearson' && $courseType == 'AS Level' && in_array($subjectCode, ['8CH0', '8FM0', '8PH0', '8CH0 01', '8CH0 02', '8FM0 01', '8FM0 2A-2K', '8PH0 01', '8PH0 02'])) {
                } elseif ($examBoard == 'Pearson' && $courseType == 'GCSE' && in_array($subjectCode, ['1BH0', '1BH0 01', '1BH0 02', '1HI0', '1HI0 10-13', '1HI0 2A-2W', '1HI0 30-33', '1FR1H', '1FR1 2H', '1FR1 3H', '1FR1 4H', '1PS0 01',])) {
                } else {

                    if (strpos($subjectCode, 'F') !== false) {
                        $tier = 'F';
                    } elseif (strpos($subjectCode, 'H') !== false) {
                        $tier = 'H';
                    }
                }
            }

            // Query subjects table to get matching exams
            $query = Subjects::select('subjectName', 'subjectCode')
                ->where('examBoard', $examBoard)
                ->where('courseType', $courseType)
                ->where('examSession', session('current_session'));

            // Apply subject code and tier filtering
            $query->where(function ($query) use ($baseCode, $tier, $examBoard, $courseType, $subjectCode) {

                if ($tier) {
                    // dd('');
                    // dd($examBoard,$courseType,$subjectCode,$baseCode);
                    if ($examBoard == 'Pearson' && $courseType == 'International GCSE') {
                        // dd('1');
                        $query->where('subjectCode', 'LIKE', $baseCode . '%')
                            ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                            ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                            ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                    } elseif ($examBoard == 'WJEC' && $courseType == 'GCSE') {
                        // dd($baseCode,$tier);
                        if ($baseCode == 'C300' && $tier == 'F') {
                            $query->whereIn('subjectCode', ['C300U10-1', 'C300U20-1']);
                        }
                        if ($baseCode == 'C300' && $tier == 'H') {
                            $query->whereIn('subjectCode', ['C300UB0-1', 'C300UA0-1']);
                        }

                        if ($baseCode == '3320' && $tier == 'H') {
                            $query->whereIn('subjectCode', ['3320UA0-1', '3320UB0-1']);
                        }

                        if ($baseCode == '3320' && $tier == 'F') {
                            $query->whereIn('subjectCode', ['3320U10-1', '3320U20-1']);
                        }
                    } else {
                        // dd('2');
                        // dd($baseCode,$tier, $examBoard, $courseType);


                             if($examBoard == 'AQA' && $courseType =='GCSE' && $baseCode == '8463')
                            {

                                  $query->whereIn('subjectCode', ['8463/1F', '8463/1H','8463/2F','8463/2H']);
                                //   dd($query->get());
                            }
                            else
                                {

                              if($examBoard == 'AQA' && $courseType =='GCSE' && $baseCode == '8462')
                            {
                                    // dd('');
                                    $query->whereIn('subjectCode', ['8462/1F', '8462/1H','8462/2F','8462/2H']);
                            }
                            else{

                            if($examBoard == 'AQA' && $courseType =='GCSE' && $baseCode == '8461')
                                {
 $query->whereIn('subjectCode', ['8461/1F', '8461/1H','8461/2F','8461/2H']);
                                }else{
                                    if($baseCode == '8638')
                                    {
                                        $query->whereIn('subjectCode', ["8638/LF", "8638/LH", "8638/RF", "8638/RH", "8638/SF", "8638/SH", "8638/WF", "8638/WH"]);
                                    }else{
                                        if($baseCode == '8673')
                                    {
                                        $query->whereIn('subjectCode', ["8673/LF", "8673/LF", "8673/RF", "8673/RH", "8673/SF", "8673/SH", "8673/WF", "8673/WH"]);
                                    }
                                    else
                                    {
                                         if($baseCode == '8465')
                                    {
                                        $query->whereIn('subjectCode', ["8465/1F", "8465/1H", "8465/2F", "8465/2H", "8465/3F", "8465/3H", "8465/4F", "8465/4H"]);
                                    }
                                    else
                                    {
                                         if($baseCode == '8652')
                                    {
                                        $query->whereIn('subjectCode', ["8652/LF", "8652/LH", "8652/RF", "8652/RH", "8652/SF", "8652/SH", "8652/WF", "8652/WH"]);
                                    }
                                    else
                                    {
                                         if($baseCode == '8662')
                                    {
                                        $query->whereIn('subjectCode', ["8662/SF", "8662/SH", "8662/LF", "8662/LH", "8662/RF", "8662/RH", "8662/WF", "8662/WH"]);
                                    }
                                    else
                                    {
                                        if($baseCode == '8633')
                                    {
                                        $query->whereIn('subjectCode', ["8633/LF", "8633/LH", "8633/RF", "8633/RH", "8633/SF", "8633/SH", "8633/WF", "8633/WH"]);
                                    }else
                                    {
                                         if($baseCode == '8678')
                                    {
                                        $query->whereIn('subjectCode', ["8678/LF", "8678/LH", "8678/RF", "8678/RH", "8678/SF", "8678/SH", "8678/WF", "8678/WH"]);
                                    }
                                    else
                                    {
                                         if($baseCode == '8683')
                                    {
                                        $query->whereIn('subjectCode', ["8683/LF", "8683/LH", "8683/RF", "8683/RH", "8683/SF", "8683/SH", "8683/WF", "8683/WH"]);
                                    }
                                    else
                                    {
                                         if($baseCode == '8688')
                                    {
                                        $query->whereIn('subjectCode', ["8688/SF", "8688/SH", "8688/LF", "8688/LH", "8688/RF", "8688/RH", "8688/WF", "8688/WH"]);
                                    }
                                    else
                                    {
                                         if($baseCode == '8692')
                                    {
                                        $query->whereIn('subjectCode', ["8692/LF", "8692/LH", "8692/RF", "8692/RH", "8692/SF", "8692/SH", "8692/WF", "8692/WH"]);
                                    }else
                                    {
                                        if($baseCode == '8382')
                                    {
                                        $query->whereIn('subjectCode', ["8382/1F", "8382/1H", "8382/2F", "8382/2H"]);
                                    }else
                                    {
                                        if($baseCode == '8648')
                                    {
                                        $query->whereIn('subjectCode', ["8648/LF", "8648/LH", "8648/RF", "8648/RH", "8648/SF", "8648/SH", "8648/WF", "8648/WH"]);
                                    }else
                                    {
                                         $query->whereRaw("REGEXP_REPLACE(subjectCode, '/.*|-.*| .*', '') = ?", [$baseCode])
                                    ->where('subjectCode', 'LIKE', '%' . $tier);
                                    }
                                       
                                    }
                                        
                                    }
                                         
                                    }
                                        
                                    }
                                        
                                    }
                                        
                                    }
                                           
                                    }
                                             
                                    }
                                        // dd($subjectCode,$baseCode);
                                        
                                    }
                                       
                                    }
                                        
                                            
                                    }
 
                                }


                            }


                                }

                        // dd($query->ge/t());
                    }
                } else {
                    // dd('');
                    // dd($baseCode, $tier, $examBoard, $courseType, $subjectCode);


                    //     if ($examBoard == 'Pearson' && $courseType == 'International A-Level' && $subjectCode == '')
                    //     {
                    //         dd($examBoard, $courseType, $subjectCode);
                    //         $this->process_Pearson_international_A_Level($examBoard, $courseType, $subjectCode);
                    //     }

                    // Pearson Papers IGCSE 4WAC logic start select paper based on frst 4 letters/chars
                    if ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WAC') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WBI') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WRS') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WCM') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WEC') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WES') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WEA') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WGE') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WET') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WHI') {
                        // dd('');
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 3) === '4WM') {
                        $query = $this->searchSubjectsByCodeMathsTier($examBoard, $courseType, $baseCode, $query);
                        // dd($query->first(),$examBoard, $courseType, $baseCode,);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WPH') {
                        // dd('');
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WSD') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4WCH') {
                        $query = $this->searchSubjectsByCode($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4IT1') {
                        $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4CP0') {
                        $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'Pearson' && $courseType === 'International GCSE' && substr($baseCode, 0, 4) === '4MA1') {
                        // in_array($lastChar = substr($subjectCode, -1), ['F', 'H']) ? $query->where('subjectCode', 'LIKE', '%' . $lastChar) : $query->where(function ($q) use ($baseCode) {
                        //     $baseCode = substr($baseCode, 0, 3);
                        //     $q->where('subjectCode', 'LIKE', $baseCode . '%')->orWhere('subjectCode', 'LIKE', $baseCode . '/%')->orWhere('subjectCode', 'LIKE', $baseCode . ' %')->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                        // });
                         $lastChar = substr(trim($subjectCode), -1); // F or H

    if ($lastChar === 'F') {
        // dd('1');
        // Only Foundation papers
        $query->whereIn('subjectCode', ['4MA1 1F', '4MA1 2F']);
    } elseif ($lastChar === 'H') {
        // Only Higher papers
        $query->whereIn('subjectCode', ['4MA1 1H', '4MA1 2H']);
    } else {
        // If no F/H, return nothing or skip
        $query->whereRaw('1 = 0'); // return no results
    }
                    } elseif ($examBoard === 'WJEC/EDUQAS' && $courseType === 'GCSE' && strlen($subjectCode) > 4) {
                        
                       
                        if($baseCode === 'C700U10')
                       {
                            $query->whereIn('subjectCode', ['C700U10-1', 'C700U20-1']);
                       }
                       if($baseCode === 'C300U10')
                       {
                            $query->whereIn('subjectCode', ['C300U10-1', 'C300U20-1']);
                       }
                       if($baseCode === 'C300UA0')
                       {
                            $query->whereIn('subjectCode', ['C300UA0-1', 'C300UB0-1']);
                       }
                        // $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'WJEC' && $courseType === 'A Level' && strlen($subjectCode) > 4) {
                        $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'WJEC' && $courseType === 'GCSE' && strlen($subjectCode) > 4) {
                        $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'WJEC' && $courseType === 'AS Level' && strlen($subjectCode) > 4) {
                        $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
                    } elseif ($examBoard === 'WJEC' && $courseType === 'A2' && strlen($subjectCode) > 4) {
                        $query = $this->SpecialDirectFunction($examBoard, $courseType, $baseCode, $query);
                    } else {
                        // dd('');

                        if ($examBoard == 'Pearson' && $courseType == 'GCSE' && in_array($subjectCode, ['1FR1H', '1FR1 2H', '1FR1 3H', '1FR1 4H'])) {
                            $query->whereIn('subjectCode', ['1FR1 2H', '1FR1 3H', '1FR1 4H']);
                            //    dd('1');
                        } elseif ($examBoard == 'WJEC' && $courseType == 'GCSE' && $baseCode == '3160U10') {

                            $query->whereIn('subjectCode', ['3160U10-1', '3160U30-1']);
                        } else {
                            // dd($examBoard,$courseType,$baseCode);

                                 
                           
                            $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                                // dd($query->get());

                            // dd('2');

                        }
                    }
                }
            });

            // dd($examBoard,$courseType,$baseCode);
 if ($examBoard === "AQA" && ($courseType === "A Level" || $courseType === "AS Level")) {
  $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
}

 if ($examBoard === "Pearson" && $courseType === "AS Level" && $baseCode === "8RS0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ['8RS0 01', '8RS0 02', '8RS0 03', '8RS0 4A-4F']);

            }   

 if ($examBoard === "Pearson" && $courseType === "A Level" && $baseCode === "9RS0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ['9RS0 01', '9RS0 02', '9RS0 03', '9RS0 4A-4F']);

            }    

             if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1AA0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1AA0 1F", "1AA0 1H", "1AA0 3F", "1AA0 3H", "1AA0 4F", "1AA0 4H"]);

            }    
                // dd($examBoard,$courseType,$baseCode);
             if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1BI0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1BIO 1F", "1BIO 1H", "1BIO 2F", "1BIO 2H"]);

            }    
            
            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1CN0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1CN0 1F", "1CN0 1H", "1CN0 3F", "1CN0 3H", "1CN0 4F", "1CN0 4H"]);

            }  
            
            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1SC0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1SC0 1BF", "1SC0 1BH", "1SC0 1CF", "1SC0 1CH", "1SC0 1PF", "1SC0 1PH", "1SC0 2BF", "1SC0 2BH", "1SC0 2CF", "1SC0 2CH", "1SC0 2PF", "1SC0 2PH"]);

            }  

            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1DT0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1DT0 1A", "1DT0 1B", "1DT0 1C", "1DT0 1D", "1DT0 1E", "1DT0 1F"]);

            }  


            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1FR1") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1FR1 2F", "1FR1 2H", "1FR1 3F", "1FR1 3H", "1FR1 4F", "1FR1 4H"]);

            } 
            
            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1GN1") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1GN1 2F", "1GN1 2H", "1GN1 3F", "1GN1 3H", "1GN1 4F", "1GN1 4H"]);

            } 


            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1GK0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1GK0 1F", "1GK0 1H", "1GK0 3F", "1GK0 3H", "1GK0 4F", "1GK0 4H"]);

            } 


            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1IN0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1IN0 1F", "1IN0 1H", "1IN0 3F", "1IN0 3H", "1IN0 4F", "1IN0 4H"]);

            } 


              if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1JA0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1JA0 1F", "1JA0 1H", "1JA0 3F", "1JA0 3H", "1JA0 4F", "1JA0 4H"]);

            } 
            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1PN0") {
                // dd($examBoard,$courseType,$baseCode);
              
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ["1PNO 1F", "1PNO 1H", "1PNO 3F", "1PNO 3H", "1PNO 4F", "1PNO 4H"]);

            } 

            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1PG0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode',["1PG0 1F", "1PG0 1H", "1PG0 3F", "1PG0 3H", "1PG0 4F", "1PG0 4H"]);

            } 

               if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1RU0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode',["1RU0 1F", "1RU0 1H", "1RU0 3F", "1RU0 3H", "1RU0 4F", "1RU0 4H"]);

            } 

            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1SP1") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode',["1SP1 2F", "1SP1 2H", "1SP1 3F", "1SP1 3H", "1SP1 4F", "1SP1 4H"]);

            } 

               if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1ST0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode',["1ST0 1F", "1ST0 1H", "1ST0 2F", "1ST0 2H"]);

            } 


             if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1TU0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode',["1TU0 1F", "1TU0 1H", "1TU0 3F", "1TU0 3H", "1TU0 4F", "1TU0 4H"]);

            } 


             if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1UR0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode',["1UR0 1F", "1UR0 1H", "1UR0 3F", "1UR0 3H", "1UR0 4F", "1UR0 4H"]);

            } 

          

             if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1GU0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode',["1GU0 1F", "1GU0 1H", "1GU0 3F", "1GU0 3H", "1GU0 4F", "1GU0 4H"]);

            } 

              

            // Pehla case: 1CH0 ke liye
            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1CH0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                  $query->whereIn('subjectCode', ['1CH0 1F', '1CH0 1H', '1CH0 2F', '1CH0 2H']);

            }

            // Doosra case: 1PH0 ke liye
            if ($examBoard === "Pearson" && $courseType === "GCSE" && $baseCode === "1PH0") {
                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');

                  $query->whereIn('subjectCode', ['1PH0 1F', '1PH0 1H', '1PH0 2F', '1PH0 2H']);
            }

            // Central static block: 4WM1 / 4WM2 Pearson International GCSE Maths — tier-wise only
            if ($examBoard === "Pearson" && $courseType === "International GCSE" && (strpos($subjectCode, '4WM1F') !== false || strpos($subjectCode, '4WM1H') !== false || strpos($subjectCode, '4WM2F') !== false || strpos($subjectCode, '4WM2H') !== false || $baseCode === '4WM1' || $baseCode === '4WM2')) {
                $hasF = strpos($subjectCode, 'F') !== false;
                $hasH = strpos($subjectCode, 'H') !== false;
                if ($hasF) {
                    $query->whereIn('subjectCode', ['4WM1F 01', '4WM2F 01']);
                } elseif ($hasH) {
                    $query->whereIn('subjectCode', ['4WM1H 01', '4WM2H 01']);
                }
            }

              if ($examBoard === 'Cambridge OCR' && $courseType === 'L3 CERTIFICATE') {

               
                 
                                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                            
                            
                    

                        // dd('');
                           

             }

            //  dd($examBoard,$courseType,$baseCode);
        
             if ($examBoard === 'Cambridge OCR' && $courseType === 'GCE') {
                

                  $query = Subjects::select('subjectName', 'subjectCode')
                    ->where('examBoard', $examBoard)
                    ->where('courseType', $courseType)
                    ->where('examSession', session('current_session'));
                        // dd($baseCode);
                        if($baseCode == 'Y101')
                        {
                             $query->whereIn('subjectCode', ['Y101/01', 'Y102/01', 'Y103/01', 'Y104/01', 'Y105/01', 'Y106/01', 'Y107/01', 'Y108/01', 'Y109/01', 'Y110/01', 'Y111/01', 'Y112/01', 'Y113/01', 'Y136/01', 'Y137/01', 'Y138/01', 'Y143/01', 'Y201/01', 'Y202/01', 'Y203/01', 'Y204/01', 'Y205/01', 'Y206/01', 'Y207/01', 'Y208/01', 'Y209/01', 'Y210/01', 'Y211/01', 'Y212/01', 'Y213/01', 'Y214/01', 'Y215/01', 'Y216/01', 'Y217/01', 'Y218/01', 'Y219/01', 'Y220/01', 'Y221/01', 'Y222/01', 'Y223/01', 'Y224/01', 'Y243/01', 'Y249/01', 'Y251/01', 'Y252/01', 'Y253/01', 'Y301/01', 'Y302/01', 'Y303/01', 'Y304/01', 'Y305/01', 'Y306/01', 'Y307/01', 'Y308/01', 'Y309/01', 'Y310/01', 'Y311/01', 'Y312/01', 'Y313/01', 'Y314/01', 'Y315/01', 'Y316/01', 'Y317/01', 'Y318/01', 'Y319/01', 'Y320/01', 'Y321/01']);
                        }else
                        {
                            if($baseCode == 'Y410')
                            {
                                 $query->whereIn('subjectCode', ['Y410/01', 'Y411/01', 'Y412/01', 'Y413/01', 'Y414/01', 'Y420/01', 'Y421/01', 'Y422/01', 'Y431/01', 'Y432/01', 'Y433/01', 'Y434/01', 'Y435/01', 'Y436/01', 'Y531/01', 'Y532/01', 'Y533/01', 'Y534/01', 'Y535/01', 'Y540/01', 'Y541/01', 'Y542/01', 'Y543/01', 'Y544/01', 'Y545/01']);

                            }
                            else{
                                 $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
                            }
                            
                        }
                        // dd('');
                           

             }

             if ($examBoard === 'Cambridge OCR' && $courseType === 'GCSE (9-1)') {
                        // dd('');
                            $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');

             }
            //  dd($courseType);
             if ($examBoard === 'Cambridge OCR' && $courseType === 'CAMBRIDGE NATI') {
                        // dd('');
                            $query->where('subjectCode', 'LIKE', $baseCode . '%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');

             }


            // if ($examBoard === 'WJEC/EDUQAS' && $courseType === 'GCSE') {
            //     // dd($subjectCode);
            //     // dd($activeSession->session_name);

            //     $query = Subjects::select('subjectName', 'subjectCode')
            //         ->where('examBoard', $examBoard)
            //         ->where('courseType', $courseType)
            //         ->where('examSession', session('current_session'));

            //     if ($subjectCode === 'C300H' || $subjectCode === 'C300UB0-1H' || $subjectCode === 'C300UA0-1H') {
            //         $query->where(function ($q) {
            //             $q->where('subjectCode', 'C300UA0-1H')
            //                 ->orWhere('subjectCode', 'C300UB0-1H');
            //         });
            //     } elseif ($subjectCode === 'C300F' || $subjectCode === 'C300U10-1F' || $subjectCode === 'C300U20-1F') {
            //         $query->where(function ($q) {
            //             $q->where('subjectCode', 'C300U10-1F')
            //                 ->orWhere('subjectCode', 'C300U20-1F');
            //         });
            //     } elseif ($subjectCode === 'C700U20-1') {
            //         $query->where(function ($q) {
            //             $q->where('subjectCode', 'C700U20-1');
            //         });
            //     } elseif ($subjectCode === 'C700U10-1') {
            //         $query->where(function ($q) {
            //             $q->where('subjectCode', 'C700U10-1');
            //         });
            //     }
            // }

             // for summer 2027 start
            // if($examBoard === 'AQA' && $courseType === 'A Level' ||  $courseType === 'AS Level' )
            //  {
            //       $query->where('subjectCode', 'LIKE', $baseCode . '%')
            //                      ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
            //                      ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
            //                      ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');

            //  }

             if ($examBoard === 'WJEC/EDUQAS' && $courseType === 'International GCSE') {
                // dd($subjectCode);
                // dd($activeSession->session_name);

                $query = Subjects::select('subjectName', 'subjectCode')
                    ->where('examBoard', $examBoard)
                    ->where('courseType', $courseType)
                    ->where('examSession', session('current_session'));

                if ($subjectCode === 'C700U10-1' || $subjectCode === 'C700U20-1' ) {
                    // dd('');
                    $query->where(function ($q) {
                        $q->where('subjectCode', 'C700U10-1')
                            ->orWhere('subjectCode', 'C700U20-1');
                    });
                    // dd($query->get());
                } elseif ($subjectCode === 'C300U10-1' || $subjectCode === 'C300U20-1') {
                    $query->where(function ($q) {
                        $q->where('subjectCode', 'C300U10-1')
                            ->orWhere('subjectCode', 'C300U20-1');
                    });
                } elseif ($subjectCode === 'C300UA0-1') {
                     $query->where(function ($q) {
                        $q->where('subjectCode', 'C300UA0-1')
                            ->orWhere('subjectCode', 'C300UB0-1');
                    });
                } 
            }


            $allowedCodesInitial = ['J560/01', 'J560/02', 'J560/03', 'J560F', 'J560/04', 'J560/05', 'J560/06', 'J560H'];
            if ($examBoard === 'OCR' && $courseType === 'GCSE' && in_array($subjectCode, $allowedCodesInitial)) {
                $query = Subjects::select('subjectName', 'subjectCode')
                    ->where('examBoard', $examBoard)
                    ->where('courseType', $courseType)
                    ->where('examSession', session('current_session'));

                // Define your grouped codes
                $group1 = ['J560/01', 'J560/02', 'J560/03', 'J560F'];
                $group2 = ['J560/04', 'J560/05', 'J560/06', 'J560H'];

                // Check which group the current subject belongs to and apply the filter
                if (in_array($subjectCode, $group1)) {
                    $query->whereIn('subjectCode', $group1);
                } elseif (in_array($subjectCode, $group2)) {
                    $query->whereIn('subjectCode', $group2);
                }
            }



            if ($examBoard === 'Pearson' && $courseType === 'Functional Skills') {
    
    // Base query setup
    $query = Subjects::select('subjectName', 'subjectCode')
        ->where('examBoard', $examBoard)
        ->where('courseType', $courseType)
        ->where('examSession', session('current_session'));
            // dd($subjectCode);
    // Separate check for each of the 10 subject codes
    if ($subjectCode === 'FSM EL1') {
        $query->where('subjectCode', 'FSM EL1');
    } elseif ($subjectCode === 'FSM EL2') {
        $query->where('subjectCode', 'FSM EL2');
    } elseif ($subjectCode === 'FSM EL3') {
        $query->where('subjectCode', 'FSM EL3');
    } elseif ($subjectCode === 'FSM L1') {
        $query->where('subjectCode', 'FSM L1');
    } elseif ($subjectCode === 'FSM L2') {
        $query->where('subjectCode', 'FSM L2');
    } elseif ($subjectCode === 'FSE EL1') {
        $query->where('subjectCode', 'FSE EL1');
    } elseif ($subjectCode === 'FSE EL2') {
        $query->where('subjectCode', 'FSE EL2');
    } elseif ($subjectCode === 'FSE EL3') {
        $query->where('subjectCode', 'FSE EL3');
    } elseif ($subjectCode === 'FSE L1') {
        $query->where('subjectCode', 'FSE L1');
    } elseif ($subjectCode === 'FSE L2') {
        $query->where('subjectCode', 'FSE L2');
    }
}
// dd($examBoard,$courseType,$baseCode);

            $exams = $query->get();
            // dd($exams);

            // Format the exams data for the dropdown
            $examsList = $exams->map(function ($exam) {
                return sprintf('%s ./. %s', $exam->subjectName, $exam->subjectCode);
            })->toArray();

            return response()->json([
                'exams' => $examsList
            ]);
        } else {
            return response()->json([
                'exams' => null
            ]);
        }
    }

    
    private function searchSubjectsByCodeMathsTier($examBoard, $courseType, $subjectCode, $query)
    {
        $lastChar = substr($subjectCode, -1);
        $baseCode = substr($subjectCode, 0, 3);
        if ($baseCode == '4WM' && $lastChar == 'F') {
            $query->whereIn('subjectCode', ['4WM1F 01', '4WM2F 01']);
        } elseif ($baseCode == '4WM' && $lastChar == 'H') {
            $query->whereIn('subjectCode', ['4WM1H 01', '4WM2H 01']);
        } else {
            if (in_array($lastChar, ['F', 'H'])) {
                $query->where('subjectCode', 'LIKE', '%' . $lastChar);
            } else {
                $baseCode = substr($subjectCode, 0, 3);
                $query->where('subjectCode', 'LIKE', $baseCode . '%')
                    ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                    ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                    ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');
            }
        }


        // dd($subjectCode, $query,$lastChar,$baseCode);


        return $query;
    }

    private function searchSubjectsByCode($examBoard, $courseType, $subjectCode, $query)
    {
        // Pearson Papers IGCSE 4WAC logic start
        $baseCode = substr($subjectCode, 0, 4);
        if ($baseCode == '4WRS') {
            $query->whereIn('subjectCode', ['4WRS1 01', '4WRS2 1A', '4WRS2 1B', '4WRS2 1C', '4WRS2 1D', '4WRS2 1E', '4WRS2 1F']);
        } else {
            $query->where('subjectCode', 'LIKE', $baseCode . '%')
                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');

            return $query;
        }


        // Pearson Papers IGCSE 4WAC logic end
    }

    private function SpecialDirectFunction($examBoard, $courseType, $subjectCode, $query)
    {
        
        // Pearson Papers IGCSE 4WAC logic start
        $baseCode = substr($subjectCode, 0, 4);
        if ($baseCode == '4IT1') {
            //  $query->whereIn('subjectCode', ['4IT1 01', '4IT1 02','4IT1 02','4IT1 02','4IT1 02','4IT1 02']);
            $query->whereIn('subjectCode', ['4IT1 01', '4IT1 02'])
                ->orderBy('examDate', 'asc')
                ->orderBy('examTime', 'asc');
        } elseif ($baseCode == '4CP0') {
            $query->whereIn('subjectCode', ['4CP0 01', '4CP0 02'])
                ->orderBy('examDate', 'asc')
                ->orderBy('examTime', 'asc');
        } else {
            $query->where('subjectCode', 'LIKE', $baseCode . '%')
                ->orWhere('subjectCode', 'LIKE', $baseCode . '/%')
                ->orWhere('subjectCode', 'LIKE', $baseCode . ' %')
                ->orWhere('subjectCode', 'LIKE', $baseCode . '-%');

            return $query;
        }


        // Pearson Papers IGCSE 4WAC logic end
    }



    public function approvedRequest()
    {
        $currentSession = session('current_session');

        $students = StudentDetail::where('exam_session', $currentSession)
            ->select(
                // select(
                'student_id',
                'surname',
                'first_name',
                'dob',
                'email',
                'gender',
                'exam_board',
                'exam_session',
                'photo_path',
                'id_document_path',
                'supporting_documents_path',
                'additional_docs_paths'
            )
            ->groupBy(
                'student_id',
                'surname',
                'first_name',
                'dob',
                'email',
                'gender',
                'exam_board',
                'exam_session',
                'photo_path',
                'id_document_path',
                'supporting_documents_path',
                'additional_docs_paths'
            )
            ->get();

        $students = $students->unique(function ($item) {
            return $item['student_id'];
        });
        // dd($students);

        return view('student.approvedList', compact('students'));
    }



    public function modifyApprovedStudent($id)
    {
        $restDetails = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)->first();
        $examDetails = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)->get();
        // dd($restDetails,$examDetails);
        if (!$restDetails) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Group by unit instead of exam_board and course_type
        $groupedExams = $examDetails->groupBy('unit')->map(function ($unitExams) {
            $firstExam = $unitExams->first();

            // Collect all exams for this unit
            $examList = $unitExams->map(function ($exam) {
                return $exam->subject_name . ' ./. ' . $exam->subject_code;
            })->unique()->values()->toArray();

            return [
                'unit' => $firstExam->unit,
                'exam_board' => $firstExam->exam_board,
                'course_type' => $firstExam->course_type,
                'subject_name' => $firstExam->subject_name,
                'subject_code' => $firstExam->subject_code,
                'tire' => $firstExam->tire ?? 'N/A',
                'nea' => $firstExam->nea ?? 'N/A',
                'exams' => $examList,
            ];
        })->values()->toArray();

        $subjectsData = Subjects::where('examSession', session('current_session'))->select('examBoard', 'courseType', 'subjectName', 'subjectCode', 'tier')
            ->orderBy('examBoard')
            ->orderBy('courseType')
            ->orderBy('subjectName')
            ->get();

        // Collect pre-selected subject codes for matching
        $preSelectedCodes = array_column($groupedExams, 'subject_code');

        // Initialize $data
        $data = [
            'boards' => $subjectsData->pluck('examBoard')->unique()->values(),
            'courseTypesByBoard' => $subjectsData->groupBy('examBoard')->map(function ($items) {
                return $items->pluck('courseType')->unique()->values();
            }),
            'subjectsByBoardAndCourse' => $subjectsData->groupBy(['examBoard', 'courseType'])->map(function ($courseTypes) use ($preSelectedCodes) {
                return $courseTypes->map(function ($subjects, $courseType) use ($preSelectedCodes) {
                    $subjectsByCode = [];

                    foreach ($subjects as $subject) {
                        $subjectName = $subject->subjectName;
                        $subjectCode = $subject->subjectCode;

                        // Extract main code (before / or space)
                        $delimiter = strpos($subjectCode, '/') !== false ? '/' : ' ';
                        $mainCode = strtok($subjectCode, $delimiter);

                        // Determine tier
                        $isHigher = stripos($subjectName, 'Higher') !== false || stripos($subjectCode, 'H') !== false;
                        $isFoundation = stripos($subjectName, 'Foundation') !== false || stripos($subjectCode, 'F') !== false;
                        $tier = $isHigher ? 'Higher' : ($isFoundation ? 'Foundation' : null);

                        // Group by main code and tier
                        if ($tier) {
                            $subjectsByCode[$mainCode][$tier][$subjectName] = $subjectCode;
                        } else {
                            $subjectsByCode[$mainCode]['NoTier'][$subjectName] = $subjectCode;
                        }
                    }

                    // Build new subjects array
                    $newSubjects = [];
                    foreach ($subjectsByCode as $mainCode => $tiers) {
                        // Handle AQA specifically to limit to one subject per tier
                        if (isset($tiers['Higher']) || isset($tiers['Foundation'])) {
                            // Prioritize pre-selected codes
                            $selectedHigher = null;
                            $selectedFoundation = null;

                            // Check Higher tier
                            if (isset($tiers['Higher'])) {
                                foreach ($tiers['Higher'] as $subjectName => $subjectCode) {
                                    if (in_array($subjectCode, $preSelectedCodes)) {
                                        $selectedHigher = [$subjectName => $subjectCode];
                                        break;
                                    }
                                }
                                // Fallback to first Higher subject if no pre-selected match
                                if (!$selectedHigher) {
                                    $subjectName = array_key_first($tiers['Higher']);
                                    $selectedHigher = [$subjectName => $tiers['Higher'][$subjectName]];
                                }
                                $newSubjects[key($selectedHigher)] = current($selectedHigher);
                            }

                            // Check Foundation tier
                            if (isset($tiers['Foundation'])) {
                                foreach ($tiers['Foundation'] as $subjectName => $subjectCode) {
                                    if (in_array($subjectCode, $preSelectedCodes)) {
                                        $selectedFoundation = [$subjectName => $subjectCode];
                                        break;
                                    }
                                }
                                // Fallback to first Foundation subject if no pre-selected match
                                if (!$selectedFoundation) {
                                    $subjectName = array_key_first($tiers['Foundation']);
                                    $selectedFoundation = [$subjectName => $tiers['Foundation'][$subjectName]];
                                }
                                $newSubjects[key($selectedFoundation)] = current($selectedFoundation);
                            }
                        } else {
                            // Non-tiered subjects (e.g., English Language)
                            $subjectName = array_key_first($tiers['NoTier']);
                            $newSubjects[$subjectName] = $tiers['NoTier'][$subjectName];
                        }
                    }

                    return $newSubjects;
                });
            })->toArray()
        ];

        // Merge subjects from $groupedExams into $data['subjectsByBoardAndCourse']
        foreach ($groupedExams as $exam) {
            $examBoard = $exam['exam_board'] ?? 'Unknown';
            $courseType = $exam['course_type'] ?? 'Unknown';
            $subjectName = $exam['subject_name'] ?? 'Unknown Subject';
            $subjectCode = $exam['subject_code'] ?? '';
            $tire = $exam['tire'] ?? 'N/A';

            // Normalize tier
            $tier = in_array($tire, ['N/A', 'Foundation Tier', 'Higher Tier']) ? str_replace(' Tier', '', $tire) : 'N/A';
            if ($tier === 'N/A') {
                if (stripos($subjectName, 'Higher') !== false || strpos($subjectCode, 'H') !== false) {
                    $tier = 'Higher';
                } elseif (stripos($subjectName, 'Foundation') !== false || strpos($subjectCode, 'F') !== false) {
                    $tier = 'Foundation';
                }
            }

            // Initialize exam board and course type if not present
            if (!isset($data['boards']) || !$data['boards']->contains($examBoard)) {
                $data['boards'][] = $examBoard;
            }
            if (!isset($data['courseTypesByBoard'][$examBoard])) {
                $data['courseTypesByBoard'][$examBoard] = collect([]);
            }
            if (!$data['courseTypesByBoard'][$examBoard]->contains($courseType)) {
                $data['courseTypesByBoard'][$examBoard][] = $courseType;
            }
            if (!isset($data['subjectsByBoardAndCourse'][$examBoard])) {
                $data['subjectsByBoardAndCourse'][$examBoard] = [];
            }
            if (!isset($data['subjectsByBoardAndCourse'][$examBoard][$courseType])) {
                $data['subjectsByBoardAndCourse'][$examBoard][$courseType] = [];
            }

            // Add subject if not already present
            if (!isset($data['subjectsByBoardAndCourse'][$examBoard][$courseType][$subjectName]) && $subjectCode) {
                $data['subjectsByBoardAndCourse'][$examBoard][$courseType][$subjectName] = $subjectCode;
            }
        }

        // Prepare pre-selected data
        $preSelectedSubjects = [];
        $preSelectedTiers = [];

        foreach ($groupedExams as $index => $exam) {
            $subjectCode = trim($exam['subject_code'], '[]');
            $examBoard = $exam['exam_board'] ?? null;
            $courseType = $exam['course_type'] ?? null;
            $tire = $exam['tire'] ?? 'N/A';

            // Normalize tier value
            $tier = in_array($tire, ['N/A', 'Foundation Tier', 'Higher Tier']) ? str_replace(' Tier', '', $tire) : 'N/A';
            if ($tier === 'N/A') {
                if (stripos($exam['subject_name'], 'Higher') !== false || strpos($subjectCode, 'H') !== false) {
                    $tier = 'Higher';
                } elseif (stripos($exam['subject_name'], 'Foundation') !== false || strpos($subjectCode, 'F') !== false) {
                    $tier = 'Foundation';
                }
            }
            $preSelectedTiers[$index] = $tier;

            // Initialize default values
            $matchingSubject = [
                'name' => $exam['subject_name'] ?? 'Unknown Subject',
                'code' => $subjectCode
            ];

            // Check if examBoard and courseType exist in $data
            if ($examBoard && $courseType && isset($data['subjectsByBoardAndCourse'][$examBoard][$courseType])) {
                $subjects = $data['subjectsByBoardAndCourse'][$examBoard][$courseType];

                // Exact match by subject code
                foreach ($subjects as $subjectName => $code) {
                    if ($code === $subjectCode) {
                        $matchingSubject = [
                            'name' => $subjectName,
                            'code' => $code
                        ];
                        break;
                    }
                }
            }

            $preSelectedSubjects[$index] = $matchingSubject;
        }

        // Pass existing exams per unit row
        $existingExams = array_map(function ($exam) {
            return $exam['exams'];
        }, $groupedExams);




         //////////////////////////////for result and send email module ////////////////////////////////////
        $activeSession = Session::where('status', 'active')->first();

        // Filter out "widthraw" at the database level (case-insensitive)
        $studentDetails = StudentDetail::where('exam_session', session('current_session'))

            ->where(function ($query) {
                $query->where('first_name', 'NOT LIKE', '%widthraw%')
                    ->where('surname', 'NOT LIKE', '%widthraw%');
            })
            ->get();

        $candidateAndUciNumbers = CandidateAndUciNumber::where('session', session('current_session'))->get();

        $selectedAttributes = $studentDetails->map(function ($student) {
            return [
                'student_id' => $student->student_id,
                'candidate_name' => $student->first_name . ' ' . $student->surname,
                'uci_number' => $student->uci_number,
                'email' => $student->email,
            ];
        })->unique('student_id');

        $selectedAttributesArray = $selectedAttributes->toArray();
        $candidateAndUciNumbersArray = $candidateAndUciNumbers->pluck('candidate_id')->toArray();

        $missingRecords = array_filter($selectedAttributesArray, function ($item) use ($candidateAndUciNumbersArray) {
            return !in_array($item['student_id'], $candidateAndUciNumbersArray);
        });

        foreach ($missingRecords as $record) {
            CandidateAndUciNumber::updateOrCreate(
                [
                    'candidate_id' => $record['student_id'],
                    'session' => session('current_session')
                ], // Search condition
                [
                    'candidate_name' => $record['candidate_name'],
                    'uci_number' => $record['uci_number'],
                    'email' => $record['email'],
                    'is_sent' => null,
                ]
            );
        }
        ///////////////////////////////////////////////////////////////////////////////////////////

        // For debugging
        // dd([
        //     'preSelectedSubjects' => $preSelectedSubjects,
        //     'data' => $data,
        //     'groupedExams' => $groupedExams
        // ]);
        // dd($existingExams,$preSelectedSubjects);




        ///////////////////////////////////////////
        // Process Chemistry and Physics for Pearson GCSE
        $data = $this->processPearsonChemistryPhysics($data, $subjectsData);

        $referrals = Referral::orderBy('name')->get();

        return view('student.modifyApprovedStudent', [
            'studentDataArray' => $groupedExams,
            'restDetails' => $restDetails,
            'examDetails' => $examDetails,
            'data' => $data,
            'existingExams' => $existingExams,
            'preSelectedSubjects' => $preSelectedSubjects,
            'preSelectedTiers' => $preSelectedTiers,
            'referrals' => $referrals,
        ]);
    }


    public function help()
    {
        return view('help.index');
    }


    public function updateApprovedStudent(Request $request, $studentId)
    {
        // Base path for file uploads
        $basePath = public_path('studentDocs');
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
        }

        // Fetch existing student details to handle file updates
        $existingDetails = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $studentId)->first();

        // Handle file uploads
        $photoPath = $existingDetails ? $existingDetails->photo_path : null;
        if ($request->hasFile('photo')) {
            if ($photoPath && file_exists(public_path($photoPath))) {
                unlink(public_path($photoPath));
            }
            $photo = $request->file('photo');
            $photoName = 'photo_' . time() . '.' . $photo->getClientOriginalExtension();
            $photo->move($basePath, $photoName);
            $photoPath = 'studentDocs/' . $photoName;
        }

        $idDocumentPath = $existingDetails ? $existingDetails->id_document_path : null;
        if ($request->hasFile('id_document')) {
            if ($idDocumentPath && file_exists(public_path($idDocumentPath))) {
                unlink(public_path($idDocumentPath));
            }
            $idDocument = $request->file('id_document');
            $idDocumentName = 'id_' . time() . '.' . $idDocument->getClientOriginalExtension();
            $idDocument->move($basePath, $idDocumentName);
            $idDocumentPath = 'studentDocs/' . $idDocumentName;
        }

        $supportingDocumentsPath = $existingDetails ? $existingDetails->supporting_documents_path : null;
        if ($request->hasFile('supporting_documents')) {
            if ($supportingDocumentsPath && file_exists(public_path($supportingDocumentsPath))) {
                unlink(public_path($supportingDocumentsPath));
            }
            $supportingDoc = $request->file('supporting_documents');
            $supportingDocName = 'support_' . time() . '.' . $supportingDoc->getClientOriginalExtension();
            $supportingDoc->move($basePath, $supportingDocName);
            $supportingDocumentsPath = 'studentDocs/' . $supportingDocName;
        }

        $additionalDocsPaths = $existingDetails ? $existingDetails->additional_docs_paths : [];
        if ($request->hasFile('additional_docs')) {
            if (!empty($additionalDocsPaths)) {
                foreach ($additionalDocsPaths as $oldDoc) {
                    if (file_exists(public_path($oldDoc))) {
                        unlink(public_path($oldDoc));
                    }
                }
            }
            $additionalDocsPaths = [];
            foreach ($request->file('additional_docs') as $key => $file) {
                $additionalDocName = 'additional_' . time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $file->move($basePath, $additionalDocName);
                $additionalDocsPaths[] = 'studentDocs/' . $additionalDocName;
            }
        }

        // Delete existing StudentDetail records for this student
        StudentDetail::where('exam_session', session('current_session'))->where('student_id', $studentId)->delete();
        $activeSession = Session::where('status', 'active')->first();

        // Process each exam row and create new StudentDetail records
        foreach ($request->examBoard as $index => $examBoard) {
            $examBoard = is_array($examBoard) ? $examBoard[0] : $examBoard;
            $courseType = $request->courseType[$index] ?? null;
            $tire = $request->tire[$index] ?? null;
            $nea = $request->nea[$index] ?? null;
            $selectedExams = $request->exams[$index] ?? [];

            $subjectName = $request->subjectName[$index] ?? null;
            $subjectCode = $request->subjectCode[$index] ?? null;

            // Fetch exam details from Subjects table
            $subjectDetails = Subjects::where('examSession', session('current_session'))->where('subjectCode', $subjectCode)
                ->select('examDay', 'examTime', 'examDate', 'duration')
                ->first();

            $examDay = $subjectDetails->examDay ?? null;
            $examTime = $subjectDetails->examTime ?? null;
            $examDate = $subjectDetails->examDate ?? null;
            $duration = $subjectDetails->duration ?? null;

            foreach ($selectedExams as $exam) {
                $examParts = explode(' ./. ', $exam, 2);
                $examName = isset($examParts[0]) ? trim($examParts[0]) : null;
                $examCode = isset($examParts[1]) ? trim($examParts[1]) : null;

                // Fetch specific exam details
                $examDetails = Subjects::where('examSession', session('current_session'))->where('subjectCode', $examCode)
                    ->select('examDay', 'examTime', 'examDate', 'duration')
                    ->first();

                $examDay = $examDetails->examDay ?? $subjectDetails->examDay ?? null;
                $examTime = $examDetails->examTime ?? $subjectDetails->examTime ?? null;
                $examDate = $examDetails->examDate ?? $subjectDetails->examDate ?? null;
                $duration = $examDetails->duration ?? $subjectDetails->duration ?? null;

                StudentDetail::create([
                    'current_domain' => $request->current_domain ?? null,
                    'student_id' => $studentId,
                    'student_request_id' => $existingDetails ? $existingDetails->student_request_id : null,
                    'surname' => $request->surname,
                    'first_name' => $request->first_name,
                    'dob' => $request->dob,
                    'gender' => $request->gender,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2,
                    'postcode' => $request->postcode,
                    'uci_number' => $request->uci_number,
                    'contact_number' => $request->contact_number,
                    'email' => $request->email,
                    'cc_email' => $request->cc_email ?: null,
                    'emergency_contact' => $request->emergency_contact,
                    'uln' => $request->uln,
                    'medical_condition' => $request->medical_condition,
                    'access_arrangement' => $request->access_arrangement === '1',
                    'access_arrangement_explanation' => $request->access_arrangement_explanation,
                    'practical_endorsement' => $request->practical_endorsement,
                    'need_predicted_grades' => $request->need_predicted_grades,
                    'need_mock_exams' => $request->need_mock_exams,
                    'course_work' => $request->course_work,
                    'course_work_uci' => $request->course_work_uci,
                    'exam_board' => $examBoard,
                    'course_type' => $courseType,
                    'subject_name' => $examName,
                    'subject_code' => $examCode,
                    'exams' => json_encode($request->exams),
                    'day' => $examDay,
                    'time' => $examTime,
                    'date' => $examDate,
                    'duration' => $duration,
                    'tire' => $tire,
                    'nea' => $nea,
                    'photo_path' => $photoPath,
                    'id_document_path' => $idDocumentPath,
                    'supporting_documents_path' => $supportingDocumentsPath,
                    'additional_docs_paths' => $additionalDocsPaths,
                    'unit' => "unit " . ($index + 1),
                    'exam_session' => session('current_session'), //$activeSession->session_name,
                    'form_fill_date' => $request->form_fill_date,
                    'sign' => $request->sign,
                    'referral' => $request->referral ?? null,
                ]);
            }
        }



         $activeSession =  session('current_session');

        // get unique student records for active session
        $students = StudentDetail::where('exam_session', $activeSession)
            ->select('student_id', 'first_name', 'surname', 'uci_number', 'email')
            ->groupBy('student_id', 'first_name', 'surname', 'uci_number', 'email')
            ->get();

        // update mismatched records only
        foreach ($students as $student) {

            CandidateAndUciNumber::where('candidate_id', $student->student_id)
                ->where('session', $activeSession)
                ->where(function ($q) use ($student) {
                    $q->where('candidate_name', '!=', $student->first_name.' '.$student->surname)
                    ->orWhere('email', '!=', $student->email)
                    ->orWhere('uci_number', '!=', $student->uci_number);
                })
                ->update([
                    'candidate_name' => $student->first_name.' '.$student->surname,
                    'email'          => $student->email,
                    'uci_number'     => $student->uci_number,
                    'updated_at'     => now(),
                ]);
        }

        return redirect()->route('approvedRequest')->with('success', 'Student updated successfully!');
    }


    public function generateTimetable(Request $request)
    {
        if ($request->has('student_id')) {
            $student_id = $request->input('student_id');
            $studentDetail = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $student_id)->first();
            $studentDetailz = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $student_id)->select('*')->get();

            if ($studentDetailz) {
                // dd($studentDetailz);
                $specials = []; //StudentDetail::where('student_id', $student_id)->where('special_paper', 'yes')->get();
                $language = 'No';
                $courseWork = 'No';
                $checks =   StudentDetail::where('exam_session', session('current_session'))->where('student_id', $student_id)->get();
                foreach ($studentDetailz as $check) {
                    if ($check->nea == 'language') {
                        $language = 'Yes';
                    }
                    if ($check->nea == 'course work') {
                        $courseWork = 'Yes';
                    }


                    if ($check->date) {
                        // Date format assume kar rahe hain: 2026/06/02 ya 2026-06-02
                        $dateStr = str_replace('/', '-', $check->date); // safety for both formats
                        $timestamp = strtotime($dateStr);

                        if ($timestamp !== false) {
                            $dayName = date('l', $timestamp); // Monday, Tuesday, etc.
                            $check->day = $dayName;
                        } else {
                            // $check->day = '-';
                        }
                    } else {
                        // $check->day = '-';
                    }
                }

                $email = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $student_id)->first();
                $studentEmail = $email->email ?? null;
                // dd($studentDetailz);

                return view('datesheet.index', compact('studentEmail', 'language', 'courseWork', 'studentDetailz', 'specials', 'studentDetail', 'student_id'))->with('success', 'Record found.');;
            } else {
                return view('datesheet.index')->with('error', 'Student not found.');
            }
        }
        $student_id = null;
        $specials = null;
        $studentEmail = null;

        return view('datesheet.index', compact('studentEmail', 'student_id', 'specials'));
    }

    public function viewDatesheet()
    {

        $subjects = Subjects::select('examBoard', 'courseType')->where('examSession', session('current_session'))->groupBy('examBoard', 'courseType')->get();
        $examBoards = $subjects->pluck('examBoard')->map(function ($item) {
            return trim($item);
        })->filter()->unique();

        return view('datesheet.view', compact('examBoards'));
    }

    public function getBoard(Request $request)
    {
        // dd($request->all());
        $examBoard = $request->examBoard;
        $date = $request->date;

        // Query subjects based on exam board
        $query = Subjects::where('examSession', session('current_session'))->where('examBoard', $examBoard);

        // If date is provided, filter by date
        if ($date) {
            // Convert the date from YYYY-MM-DD format (from input) to match database format
            $formattedDate = date('Y/m/d', strtotime($date));
            // dd($formattedDate);
            $query->where('examDate', $formattedDate);
        }
        // dd($query->get(),$formattedDate,$examBoard);
        // Get the results and format them
        $subjects = $query->get()->map(function ($subject) {
            // Format the date to DD/MM/YYYY for frontend display
            // $examDate = date('d/m/Y', strtotime($subject->examDate));
            $examDate = !empty($subject->examDate) ? date('d/m/Y', strtotime($subject->examDate)) : 'N/A';
            return [
                'paper_title' => $subject->subjectName,
                'exam_code' => $subject->subjectCode,
                'date' => $examDate ?? null,
                'duration' => $subject->duration,
                'exam_session' => $subject->examSession // Add this line
            ];
        });

        // dd($subjects);

        return response()->json($subjects);
    }


    // public function exportAllTimetable()
    // {
    //     $activeSession = session('current_session');

    //     $studentsData = [];
    //     $uniqueStudentIds = StudentDetail::select('student_id')
    //         ->where('exam_session', $activeSession)
    //         ->distinct()
    //         ->pluck('student_id');

    //     if ($uniqueStudentIds->isEmpty()) {
    //         return redirect()->back()->with('error', 'No records found for the current session: ' . $activeSession);
    //     }

    //     foreach ($uniqueStudentIds as $student_id) {
    //         $studentInfo = StudentDetail::where('student_id', $student_id)
    //             ->where('exam_session', $activeSession)
    //             ->select('student_id', 'first_name', 'surname', 'access_arrangement_explanation')
    //             ->first();

    //         $examEntries = StudentDetail::where('student_id', $student_id)
    //             ->where('exam_session', $activeSession)
    //             ->get();

    //         $exams = $examEntries->map(function ($item) {
    //             return (object)[
    //                 'examBoard' => $item->exam_board,
    //                 'courseType' => $item->course_type,
    //                 'subjectName' => $item->subject_name,
    //                 'tier' => $item->tire ?? $item->tier ?? '',
    //                 'subjectCode' => $item->subject_code,
    //                 'examSession' => $item->exam_session,
    //                 'examTime' => $item->time,
    //                 'examDate' => $item->date,
    //                 'examDay' => $item->day,
    //                 'duration' => $item->duration,
    //             ];
    //         });

    //         $studentsData[$student_id] = [
    //             'info' => $studentInfo,
    //             'exams' => $exams,
    //         ];
    //     }

    //     $data = [];

    //     foreach ($studentsData as $studentId => $details) {
    //         $info = $details['info'];
    //         foreach ($details['exams'] as $exam) {
    //             $data[] = [
    //                 'CANDIDATE NUMBER' => $studentId,
    //                 'CANDIDATE NAME' => trim(($info->first_name ?? '') . ' ' . ($info->surname ?? '')),
    //                 'QUALIFICATION' => $exam->courseType ?? '',
    //                 'EXAM BOARD' => $exam->examBoard ?? '',
    //                 'EXAM CODE' => $exam->subjectCode ?? '',
    //                 'PAPER TITLE' => $exam->subjectName ?? '',
    //                 'DATE' => $exam->examDate ? date('d/m/Y', strtotime($exam->examDate)) : '',
    //                 'DAY' => $exam->examDay ?? '',
    //                 'TIME' => $exam->examTime ?? '',
    //                 'DURATION' => $exam->duration ?? '',
    //                 'ACCESS ARRANGEMENTS' => $info->access_arrangement_explanation ?? 'NO',
    //             ];
    //         }
    //     }

    //     // Create spreadsheet and active sheet
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Define headers in desired order
    //     $headers = [
    //         'CANDIDATE NUMBER',
    //         'CANDIDATE NAME',
    //         'QUALIFICATION',
    //         'EXAM BOARD',
    //         'EXAM CODE',
    //         'PAPER TITLE',
    //         'DATE',
    //         'DAY',
    //         'TIME',
    //         'DURATION',
    //         'ACCESS ARRANGEMENTS'
    //     ];

    //     // Write headers to sheet
    //     foreach ($headers as $col => $header) {
    //         $columnLetter = Coordinate::stringFromColumnIndex($col + 1);
    //         $sheet->setCellValue($columnLetter . '1', $header);
    //     }

    //     // Write data rows
    //     $row = 2;
    //     foreach ($data as $record) {
    //         foreach ($headers as $col => $header) {
    //             $columnLetter = Coordinate::stringFromColumnIndex($col + 1);
    //             $sheet->setCellValue($columnLetter . $row, $record[$header]);
    //         }
    //         $row++;
    //     }

    //     // Generate and return the download response
    //     $writer = new Xlsx($spreadsheet);
    //     $filename = "Complete_Student_Report_{$activeSession}.xlsx";

    //     return new StreamedResponse(function () use ($writer) {
    //         $writer->save('php://output');
    //     }, 200, [
    //         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //         'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    //     ]);
    // }
    public function exportAllTimetable()
    {
        $activeSession = session('current_session');

        $studentsData = [];
        $uniqueStudentIds = StudentDetail::select('student_id')
            ->where('exam_session', $activeSession)
            ->distinct()
            ->pluck('student_id');

        if ($uniqueStudentIds->isEmpty()) {
            return redirect()->back()->with('error', 'No records found for the current session: ' . $activeSession);
        }

        foreach ($uniqueStudentIds as $student_id) {
            $studentInfo = StudentDetail::where('student_id', $student_id)
                ->where('exam_session', $activeSession)
                ->select('student_id', 'first_name', 'surname', 'access_arrangement_explanation')
                ->first();

            $examEntries = StudentDetail::where('student_id', $student_id)
                ->where('exam_session', $activeSession)
                ->get();

            $exams = $examEntries->map(function ($item) {
                return (object)[
                    'examBoard' => $item->exam_board,
                    'courseType' => $item->course_type,
                    'subjectName' => $item->subject_name,
                    'tier' => $item->tire ?? $item->tier ?? '',
                    'subjectCode' => $item->subject_code,
                    'examSession' => $item->exam_session,
                    'examTime' => $item->time,
                    'examDate' => $item->date,
                    'examDay' => $item->day,
                    'duration' => $item->duration,
                ];
            });

            $studentsData[$student_id] = [
                'info' => $studentInfo,
                'exams' => $exams,
            ];
        }

        $data = [];

        foreach ($studentsData as $studentId => $details) {
            $info = $details['info'];
            foreach ($details['exams'] as $exam) {
                $data[] = [
                    'DATE' => $exam->examDate ? date('d/m/Y', strtotime($exam->examDate)) : '',
                    'DAY' => $exam->examDay ?? '',
                    'TIME' => $exam->examTime ?? '',
                    'EXAM CODE' => $exam->subjectCode ?? '',
                    'PAPER TITLE' => $exam->subjectName ?? '',
                    'QUALIFICATION' => $exam->courseType ?? '',
                    'EXAM BOARD' => $exam->examBoard ?? '',
                    'DURATION' => $exam->duration ?? '',
                    'CANDIDATE NUMBER' => $studentId,
                    'CANDIDATE NAME' => trim(($info->first_name ?? '') . ' ' . ($info->surname ?? '')),
                    'ACCESS ARRANGEMENTS' => $info->access_arrangement_explanation ?? 'NO',
                ];
            }
        }

        // Create spreadsheet and active sheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define headers in desired order
        $headers = [
            'DATE',
            'DAY',
            'TIME',
            'EXAM CODE',
            'PAPER TITLE',
            'QUALIFICATION',
            'EXAM BOARD',
            'DURATION',
            'CANDIDATE NUMBER',
            'CANDIDATE NAME',
            'ACCESS ARRANGEMENTS'
        ];

        // Write headers to sheet
        foreach ($headers as $col => $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $sheet->setCellValue($columnLetter . '1', $header);
        }

        // Write data rows
        $row = 2;
        foreach ($data as $record) {
            foreach ($headers as $col => $header) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                $sheet->setCellValue($columnLetter . $row, $record[$header]);
            }
            $row++;
        }

        // Generate and return the download response
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Complete_Student_Report_{$activeSession}.xlsx";

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }


    public function createayments()
    {
        $session = Session::where('status', 'active')->first();

        return view('payments.create', compact('session'));
    }



    public function paymentStore(Request $request)
    {
        // dd($request->all//());
        try {
            $validated = $request->validate([
                'session' => 'required|string|max:255',
                'student_id' => 'required|string|max:255',
                'name' => 'nullable|string|max:255',
                'payment_received' => 'required|in:Yes,No',
                'amount_received' => 'required|numeric|min:0',
                'confirmed_by' => 'required|in:Receipt,Manual',
                'payment_method' => 'required|in:Bank Transfer,Cash,Card Machine,Online Transfer',
                'note' => 'nullable|string',
                'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('receipts');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $filename);
                $receiptPath = 'receipts/' . $filename;
            }

            $payment = Payment::create([
                'session' => $validated['session'],
                'student_id' => $validated['student_id'],
                'name' => $validated['name'],
                'payment_received' => $validated['payment_received'],
                'amount_received' => $validated['amount_received'],
                'confirmed_by' => $validated['confirmed_by'],
                'payment_method' => $validated['payment_method'],
                'note' => $validated['note'],
                'receipt_path' => $receiptPath,
            ]);

            return redirect()->back()->with('success', 'Payment recorded successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }


    public function getStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string'
        ]);

        $student = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $request->student_id)
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'name' => $student->first_name . ' ' . $student->surname,
            'student' => $student // You can include more data if needed
        ]);
    }


    public function viewPayment()
    {

        $payment = Payment::where('session', session('current_session'))->get();

        return view('payments.show', compact('payment'));
    }


    public function editPayment($id)
    {
        $payment = Payment::where('session', session('current_session'))->findOrFail($id);
        // dd($payment);
        return view('payments.edit', compact('payment')); // Assumes an edit view exists
    }

    public function destroyPayment($id)
    {
        $payment = Payment::where('session', session('current_session'))->findOrFail($id);
        $payment->delete();
        return redirect()->route('viewPayment')->with('success', 'Payment deleted successfully');
    }

    public function updatePayment(Request $request, $id)
    {
        $payment = Payment::where('session', session('current_session'))->findOrFail($id);

        // Validate the request
        $request->validate([
            'session' => 'required|string|max:255',
            'student_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'payment_received' => 'required|in:Yes,No',
            'amount_received' => 'required|numeric|min:0',
            'confirmed_by' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string',
            'receipt_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        // Handle file upload for receipt_path
        if ($request->hasFile('receipt_path')) {
            // Delete old receipt if it exists
            if ($payment->receipt_path) {
                Storage::delete($payment->receipt_path);
            }
            // Store new receipt
            $path = $request->file('receipt_path')->store('receipts', 'public');
            $payment->receipt_path = $path;
        }

        // Update other fields
        $payment->session = $request->session;
        $payment->student_id = $request->student_id;
        $payment->name = $request->name;
        $payment->payment_received = $request->payment_received;
        $payment->amount_received = $request->amount_received;
        $payment->confirmed_by = $request->confirmed_by;
        $payment->payment_method = $request->payment_method;
        $payment->note = $request->note;

        $payment->save();

        return redirect()->route('viewPayment')->with('success', 'Payment updated successfully');
    }


    public function createUser()
    {
        return view('user.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|integer|in:1,3',
            'password' => 'required|string|min:8',
            '_token' => 'required'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('view-users')->with('success', 'User created successfully');
    }

    public function viewUser()
    {
        $users = User::all();
        return view('user.show', compact('users'));
    }

    public function editUser(User $user)
    {
        // dd($user);
        return view('user.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required',
                'password' => 'nullable|string|min:8',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->is_admin = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect('view-users')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }

    public function deleteUser(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }


    public function allowPermission($id)
    {
        $user = User::findOrFail($id);
        $permission = AccessPermission::where('user_id', $id)->first();

        if ($permission && $permission->page_name) {
            $permission->page_name = json_decode($permission->page_name, true);
        }
        // dd($permission);
        return view('permission.manage', compact('user', 'permission'));
    }

    public function storePermission(Request $request)
    {
        $filteredData = $request->except(['user_id', '_token']);
        $jsonPermissions = json_encode($filteredData);

        AccessPermission::updateOrCreate(
            ['user_id' => $request->input('user_id')],
            [
                'page_name' => $jsonPermissions,
                'can_access' => true
            ]
        );

        return redirect()->route('allow.permission', ['id' => $request->input('user_id')])
            ->with('success', 'Permissions stored successfully.');
    }

    public function viewSheetData()
    {
        $candidates = CandidateAndUciNumber::where('session', session('current_session'))->get();
        // dd($candidates);
        return view('result.viewSheetData', compact('candidates'));
    }

    public function seatTag()
    {
        $classes = SeatingArrangement::all();
        return view('seatTag.seatTags', compact('classes'));
    }

    public function getClassData(Request $request)
    {
        $ampm       = strtoupper($request->input('ampm')); // Convert to uppercase
        $dateString = $request->input('date'); // Expected format: 'YYYY-MM-DD'
        $layoutId   = $request->input('layout_id'); // Get the layout_id from the request

        $activeSession = Session::where('status', 'active')->first();
        // Convert Y-m-d to match database format
        $dateTime      = \DateTime::createFromFormat('Y-m-d', $dateString);
        $formattedDate2 = $dateTime ? $dateTime->format('Y/m/d') : null; // YYYY-MM-DD

        // Fetch student details
        $getSpecial = StudentDetail::where('exam_session', session('current_session'))
            ->where('date', $formattedDate2)
            ->whereRaw('LOWER(TRIM(time)) = ?', [strtolower(trim($ampm))])
            ->get();
        // dd($getSpecial,$activeSession->session_name,$formattedDate2,$ampm);
        $specialresults = [];

        foreach ($getSpecial as $detail) {
            $specialresults[] = [
                'date' => $detail->date,
                'student_id' => $detail->student_id,
                'firstName' => $detail->first_name,
                'surname' => $detail->surname,
                'exam_board' => $detail->exam_board,
                'exam_code' => $detail->subject_code,
                'paper_title' => $detail->subject_name,
                'duration' => $detail->duration,
                'medical_condition' => $detail->medicalCondition,
            ];
        }

        // Convert date format to d/m/Y
        foreach ($specialresults as &$result) {
            $result['date'] = date('d/m/Y', strtotime($result['date']));
        }

        // Ensure unique results based on student_id, exam_code, and exam_board
        $uniqueResults = [];
        foreach ($specialresults as $key => $exam) {
            $uniqueKey = $exam['student_id'] . '-' . $exam['exam_code'] . '-' . $exam['exam_board'];
            if (!isset($uniqueResults[$uniqueKey])) {
                $uniqueResults[$uniqueKey] = $exam;
            }
        }

        // Re-index the unique results
        $uniqueResults = array_values($uniqueResults);

        // Filter out entries with firstName containing "widthraw"
        $filteredResults = array_filter($uniqueResults, function ($result) {
            return strpos($result['firstName'], 'widthraw') === false;
        });

        // Convert the filtered results back to a standard array
        $filteredResults = array_values($filteredResults);

        // Check if a layout_id is provided
        if ($layoutId) {
            // Fetch the layout from the database (assuming you have a Layout model)
            $layout = Layout::where('session_name', session('current_session'))->find($layoutId); // Adjust this based on your actual model/table name

            if ($layout) {
                return response()->json([
                    'special' => 'yes',
                    'entries' => $filteredResults,
                    'layout' => [
                        'id' => $layout->id,
                        'layout_name' => $layout->layout_name,
                        'position' => $layout->position, // Ensure this matches your DB column name
                    ]
                ]);
            } else {
                return response()->json([
                    'special' => 'yes',
                    'entries' => $filteredResults,
                    'layout' => null // Or handle missing layout case differently
                ], 404);
            }
        }

        // Default response when no layout_id is provided
        return response()->json([
            'entries' => $filteredResults,
        ]);
    }
    public function storeLayout(Request $request)
    {
        // dd($request->all());
        $cells = $request->input('cells');

        $cellsJson = json_encode($cells);
        // dd($cellsJson);
        $layout = Layout::updateOrCreate(
            [
                'date'  => $request->input('date'),
                'ampm'  => $request->input('ampm')
            ],
            [
                'layout_name'  => $request->input('layout_name'),
                'cell_id'      => $cellsJson,
                'position'     => $cellsJson,
                'session_name' => session('current_session')
            ]
        );
        return response()->json(['message' => 'Layouts saved successfully!']);
    }


    public function getLayout(Request $request)
    {
        $layouts = Layout::where('session_name', session('current_session'))->get();

        if ($layouts->isNotEmpty()) {
            return response()->json($layouts);
        } else {
            return response()->json(['message' => 'Layout not found'], 404);
        }
    }


    public function edit($id)
    {
        $candidate = CandidateAndUciNumber::where('session', session('current_session'))->findOrFail($id);
        return view('result.editCandidate', compact('candidate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'candidate_id' => 'required|string|max:255',
            'candidate_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'uci_number' => 'required|string|max:255',
            'session' => 'required|string|max:255',
        ]);

        $candidate = CandidateAndUciNumber::where('session', session('current_session'))->findOrFail($id);
        $candidate->update($request->all());

        return redirect()->route('view.sheet.data')
            ->with('success', 'Candidate updated successfully!');
    }

    public function destroy($id)
    {
        $activeSession = Session::where('status', 'active')->first();

        if (!$activeSession) {
            return redirect()->route('view.sheet.data')
                ->with('error', 'No active session found!');
        }

        // Find candidate matching both ID and active session
        $candidate = CandidateAndUciNumber::where('id', $id)
            ->where('session', session('current_session'))
            ->firstOrFail();

        // Delete corresponding results matching both candidate_id and session
        Result::where('candidate_id', $candidate->candidate_id)  // Changed from candidate_id to id
            ->where('session', session('current_session'))
            ->delete();

        // Delete the candidate
        $candidate->delete();

        return redirect()->route('view.sheet.data')
            ->with('success', 'Candidate and related results deleted successfully!');
    }


    public function examReport(Request $request)
    {
        // dd('');
        $currentSession = session('current_session'); // Get the current session from session data

        $paperCounts = \DB::table('student_details')
            ->select(
                'date',
                \DB::raw('SUM(CASE WHEN time = "am" THEN 1 ELSE 0 END) AS am_count'),
                \DB::raw('SUM(CASE WHEN time = "pm" THEN 1 ELSE 0 END) AS pm_count'),
                \DB::raw('COUNT(*) as total_count')
            )
            ->whereNotNull('date')
            ->where('exam_session', $currentSession) // Added session filter
            ->where(function ($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                    ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->groupBy('date')
            ->orderByDesc('total_count')
            ->limit(3)
            ->get();

        $highestRecords = [];

        foreach ($paperCounts as $paper) {
            $highestRecords[] = [
                'date' => $paper->date,
                'am_count' => $paper->am_count,
                'pm_count' => $paper->pm_count,
                'total_count' => $paper->total_count
            ];
        }

        return view('Reports.examReport', ['highestRecords' => $highestRecords]);
    }

    public function examReportDetail($year, $month, $day)
    {
      
        $currentSession = session('current_session');
        $formattedDate = $year . '/' . $month . '/' . $day;
        $records = \DB::table('student_details')
            ->where('date', $formattedDate)
            ->where('exam_session', $currentSession)
            ->where(function ($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                    ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->get();
        // dd( $year . '-' . $month . '-' . $day);

        // dd($records);
        return view('Reports.examReportDetail', compact('records'));
    }

    public function getDetails(Request $request)
    {
        // d('')
        $currentSession = session('current_session');
        $requestedDate = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y/m/d');
        $requestedTime = strtoupper($request->time);
        // dd($requestedDate,$requestedTime,$currentSession);
        // dd($requestedDate,$requestedTime);
        $entries = StudentDetail::where('date', $requestedDate)
            ->where('time', strtoupper($requestedTime))
            ->where('exam_session', $currentSession)
            ->where(function ($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                    ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->get();
        // dd($entries);
        $paperCounts = $entries->groupBy('subject_code')
            ->map(function ($group) {
                return [
                    'count'      => $group->count(),
                    'exam_board' => $group->first()->exam_board ?? '',
                ];
            });

        return response()->json($paperCounts);
    }

    // public function internalTimetable()
    // {
    //     // Get the current session
    //     $currentSession = session('current_session');

    //     // Get records filtered by current session
    //     $uniqueResults = StudentDetail::where('exam_session', $currentSession)
    //         ->get();

    //     $uniqueResults = $uniqueResults->map(function ($item) {
    //         if (!empty($item->date)) {
    //             $item->date = date('d/m/Y', strtotime($item->date));
    //         }
    //         return $item;
    //     });

    //     return view('admin.report.internalTimetable', compact('uniqueResults'));
    // }
    // Controller (app/Http/Controllers/YourController.php)
    public function internalTimetable()
    {
        $currentSession = session('current_session');
        $uniqueResults = StudentDetail::where('exam_session', $currentSession)
            ->where(function ($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                    ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->get();

        $uniqueResults = $uniqueResults->map(function ($item) {
            if (!empty($item->date)) {
                $item->date = date('d/m/Y', strtotime($item->date));
            }
            return $item;
        });

        $availableFields = [
            'student_id' => 'Candidate ID',
            'candidate_name' => 'Candidate Name',
            'exam_board' => 'Exam Board',
            'subject_code' => 'Exam Code',
            'subject_name' => 'Subject Name / Qualifications',
            'date' => 'Date',
            'day' => 'Day',
            'time' => 'Time',
            'duration' => 'Duration',
            'access_arrangement' => 'Access Arrangement',
            'spoken' => 'Spoken',
            'coursework' => 'Course Work'
        ];

        return view('admin.report.internalTimetable', [
            'uniqueResults' => $uniqueResults,
            'availableFields' => $availableFields
        ]);
    }

    public function spokenCourseWork()
    {
        // Get the current session
        $currentSession = session('current_session');

        // Query for language students filtered by current session
        $langStudents = StudentDetail::select('student_details.*')
            ->join(
                \DB::raw('(SELECT MIN(id) as min_id, student_id
                  FROM student_details
                  WHERE nea = "language"
                  AND exam_session = "' . $currentSession . '"
                  GROUP BY student_id) as grouped_students'),
                'student_details.id',
                '=',
                'grouped_students.min_id'
            )
            ->where(function($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                      ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->get();

        // Query for coursework students filtered by current session
        $courseworkStudents = StudentDetail::select('student_details.*')
            ->join(
                \DB::raw('(SELECT MIN(id) as min_id, student_id
                  FROM student_details
                  WHERE nea = "course work"
                  AND exam_session = "' . $currentSession . '"
                  GROUP BY student_id) as grouped_students'),
                'student_details.id',
                '=',
                'grouped_students.min_id'
            )
            ->where(function($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                      ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->get();

        // Return the view with filtered data
        return view('Reports.spokenCoursework', compact('langStudents', 'courseworkStudents'));
    }

    public function searchCandidate()
    {
        return view('Reports.searchStudent');
    }

    public function accessArrangementReport()
    {
        $currentSession = session('current_session');
        
        // Get unique students with access arrangement = Yes (true/1)
        $students = \DB::table('student_details')
            ->leftJoin('candidate_and_uci_numbers', function($join) use ($currentSession) {
                $join->on('student_details.student_id', '=', 'candidate_and_uci_numbers.candidate_id')
                     ->where('candidate_and_uci_numbers.session', '=', $currentSession);
            })
            ->select(
                \DB::raw('MAX(candidate_and_uci_numbers.candidate_id) as candidate_number'),
                'student_details.student_id',
                \DB::raw("CONCAT(MAX(student_details.first_name), ' ', MAX(student_details.surname)) as candidate_name"),
                \DB::raw('MAX(student_details.medical_condition) as medical_condition'),
                \DB::raw('MAX(student_details.access_arrangement_explanation) as access_arrangement')
            )
            ->where('student_details.access_arrangement', 1)
            ->where('student_details.exam_session', $currentSession)
            ->where(function($query) {
                $query->whereRaw('LOWER(student_details.first_name) NOT LIKE ?', ['%withdraw%'])
                      ->whereRaw('LOWER(student_details.surname) NOT LIKE ?', ['%withdraw%']);
            })
            ->groupBy('student_details.student_id')
            ->orderBy('candidate_name')
            ->get();

        return view('Reports.accessArrangementReport', compact('students', 'currentSession'));
    }

    public function examReportBySubject(Request $request)
    {
        // dd('');
        $currentSession = session('current_session');
        
        // Get date range from request (for filtering)
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        // Dynamic helper function to extract base subject name - works for any subject pattern
        $extractBaseSubjectName = function($subjectName) {
            if (empty($subjectName)) {
                return '';
            }
            
            $subjectName = trim($subjectName);
            $original = $subjectName;
            
            // Strategy 1: Remove text after common delimiters first (colon, dash, slash)
            $baseName = preg_split('/[:;,\-\/]/', $subjectName)[0];
            $baseName = trim($baseName);
            
            // Strategy 2: Remove common paper/assessment patterns
            $patterns = [
                '/\s*Paper\s+\d+/i',
                '/\s*Paper\s+[A-Z]\d*/i',
                '/\s*P\d+\s*/i',
                '/\s*Non-exam\s+assessment[^:]*:?\s*/i',
                '/\s*Non\s*exam\s*assessment[^:]*:?\s*/i',
                '/\s*NEA[^:]*:?\s*/i',
                '/\s*Assessment\s+\d+/i',
                '/\s*Assessment\s+[A-Z]/i',
                '/\s*Component\s+\d+/i',
                '/\s*Component\s+[A-Z]/i',
                '/\s*Unit\s+\d+/i',
                '/\s*Unit\s+[A-Z]/i',
                '/\s*Module\s+\d+/i',
                '/\s*Module\s+[A-Z]/i',
                '/\s*Part\s+\d+/i',
                '/\s*Part\s+[A-Z]/i',
                '/\s*Section\s+\d+/i',
                '/\s*Section\s+[A-Z]/i',
                '/\s*Practical\s+Endorsement/i',
                '/\s*Practical\s+Assessment/i',
                '/\s*Endorsement/i',
                '/\s*Higher\s+Tier/i',
                '/\s*Foundation\s+Tier/i',
                '/\s*Higher/i',
                '/\s*Foundation/i',
                '/\s+\d+\s*$/i',
                '/\s*Business\s+\d+/i',
                '/\s*\(.*?\)\s*$/i',
            ];
            
            $baseName = preg_replace($patterns, '', $baseName);
            $baseName = trim($baseName);
            
            // Strategy 3: Extract first 1-2 words as base subject name
            // Common two-word subjects: "English Literature", "Religious Studies", "Combined Science", "Computer Science", "Travel and Tourism", "First Language"
            $words = preg_split('/\s+/', $baseName);
            $twoWordSubjectStarters = [
                'english' => ['literature', 'language'],
                'religious' => ['studies'],
                'combined' => ['science'],
                'computer' => ['science'],
                'travel' => ['and', 'tourism'],
                'first' => ['language'],
                'further' => ['mathematics'],
                'international' => ['gcse'],
                'social' => ['context'],
                'applied' => ['science'],
            ];
            
            $baseWords = [];
            
            // Check if first word is part of a two-word subject
            if (count($words) > 0) {
                $firstWordLower = strtolower($words[0]);
                
                // Check if this is a known two-word subject
                if (isset($twoWordSubjectStarters[$firstWordLower]) && count($words) > 1) {
                    $secondWordLower = strtolower($words[1]);
                    if (in_array($secondWordLower, $twoWordSubjectStarters[$firstWordLower])) {
                        // Take first 2 words (e.g., "English Literature")
                        $baseWords[] = $words[0];
                        $baseWords[] = $words[1];
                    } else {
                        // Just take first word
                        $baseWords[] = $words[0];
                    }
                } else {
                    // Just take first word (e.g., "Chemistry", "Biology", "Economics")
                    $baseWords[] = $words[0];
                }
            }
            
            $baseName = implode(' ', $baseWords);
            $baseName = trim($baseName);
            
            // Strategy 4: Remove duplicate consecutive words
            $words = preg_split('/\s+/', $baseName);
            $uniqueWords = [];
            $lastWord = '';
            foreach ($words as $word) {
                $word = trim($word);
                if (!empty($word) && strcasecmp($word, $lastWord) !== 0) {
                    if (strlen($word) > 1 || preg_match('/[A-Z]/', $word)) {
                        $uniqueWords[] = $word;
                        $lastWord = $word;
                    }
                }
            }
            $baseName = implode(' ', $uniqueWords);
            
            // Strategy 5: Fallback - if empty, take first meaningful word
            if (empty($baseName) || strlen($baseName) < 3) {
                $words = preg_split('/\s+/', $original);
                foreach ($words as $word) {
                    $word = trim($word);
                    $wordLower = strtolower($word);
                    if (!empty($word) && !in_array($wordLower, $skipWords) && !preg_match('/^\d+$/', $word)) {
                        $baseName = $word;
                        break;
                    }
                }
                
                if (empty($baseName)) {
                    $baseName = $original;
                }
            }
            
            return trim($baseName);
        };
        
        // Helper function to find common base name from a group of subject names (for similarity matching)
        $findCommonBaseName = function($subjectNames) use ($extractBaseSubjectName) {
            if (empty($subjectNames)) {
                return '';
            }
            
            // Extract base names for all subjects
            $baseNames = array_map($extractBaseSubjectName, $subjectNames);
            $baseNames = array_filter($baseNames);
            
            if (empty($baseNames)) {
                return $extractBaseSubjectName($subjectNames[0]);
            }
            
            // Find the most common base name (or longest if multiple)
            $baseNameCounts = array_count_values($baseNames);
            arsort($baseNameCounts);
            
            // Return the most frequent base name, or longest if tie
            $mostCommon = array_keys($baseNameCounts)[0];
            $maxCount = $baseNameCounts[$mostCommon];
            
            // If multiple have same count, pick longest
            $candidates = array_keys(array_filter($baseNameCounts, function($count) use ($maxCount) {
                return $count === $maxCount;
            }));
            
            if (count($candidates) > 1) {
                usort($candidates, function($a, $b) {
                    return strlen($b) - strlen($a); // Longest first
                });
                return $candidates[0];
            }
            
            return $mostCommon;
        };
        
        // Base query
        $baseQuery = \DB::table('student_details')
            ->where('exam_session', $currentSession)
            ->whereNotNull('exam_board')
            ->whereNotNull('course_type')
            ->where(function($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                      ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->where(function($query) {
                $query->whereNotNull('subject_name')
                      ->orWhereNotNull('subject_code');
            });
        
        // Apply date range filter if provided
        if ($dateFrom && $dateTo) {
            $baseQuery->whereBetween(\DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $baseQuery->whereDate('created_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $baseQuery->whereDate('created_at', '<=', $dateTo);
        }
        
        // Get exam report data grouped by Exam Board, Qualification, and Subject
        $reportData = $baseQuery
            ->select(
                'exam_board',
                'course_type as qualification',
                'subject_name',
                'subject_code',
                \DB::raw('COUNT(DISTINCT student_id) as no_of_candidates')
            )
            ->groupBy('exam_board', 'course_type', 'subject_name', 'subject_code')
            ->orderBy('exam_board')
            ->orderBy('course_type')
            ->orderBy('subject_name')
            ->orderBy('subject_code')
            ->get();

        // Process the data to group by base subject name (dynamic grouping)
        $processedData = $reportData->groupBy(function($item) use ($extractBaseSubjectName) {
            // Extract base subject name dynamically
            $baseSubjectName = '';
            if (!empty(trim($item->subject_name ?? ''))) {
                $baseSubjectName = $extractBaseSubjectName($item->subject_name);
            } elseif (!empty($item->subject_code)) {
                // If no subject name, try to extract from code (e.g., "7402/1" -> "7402")
                $codeParts = explode('/', $item->subject_code);
                $baseSubjectName = trim($codeParts[0] ?? '');
            }
            
            // Normalize for grouping (case-insensitive, trimmed)
            $baseSubjectName = strtolower(trim($baseSubjectName));
            
            // Use base subject name for grouping
            return $item->exam_board . '|' . $item->qualification . '|' . $baseSubjectName;
        })->map(function($group) use ($currentSession, $dateFrom, $dateTo, $extractBaseSubjectName, $findCommonBaseName) {
            $first = $group->first();
            
            // Get all subject names in this group
            $allSubjectNames = $group->pluck('subject_name')->filter()->unique()->toArray();
            
            // Find the best common base name from all subjects in group
            $baseSubjectName = '';
            if (!empty($allSubjectNames)) {
                $baseSubjectName = $findCommonBaseName($allSubjectNames);
            } elseif (!empty($first->subject_code)) {
                // Fallback to code extraction
                $codeParts = explode('/', $first->subject_code);
                $baseSubjectName = trim($codeParts[0] ?? '');
            }
            
            // If still empty, use first subject name
            if (empty($baseSubjectName) && !empty($first->subject_name)) {
                $baseSubjectName = $extractBaseSubjectName($first->subject_name);
            }
            
            // Collect all subject codes for this group
            $allCodes = $group->pluck('subject_code')
                ->filter(function($code) {
                    return !empty($code);
                })
                ->unique()
                ->sort()
                ->values()
                ->toArray();
            
            // Concatenate codes
            $codesDisplay = !empty($allCodes) ? implode(', ', $allCodes) : '';
            
            // Build subject display: "Base Subject Name / Code1, Code2, Code3"
            if (!empty($baseSubjectName) && !empty($codesDisplay)) {
                $subjectDisplay = $baseSubjectName . ' / ' . $codesDisplay;
            } elseif (!empty($baseSubjectName)) {
                $subjectDisplay = $baseSubjectName;
            } elseif (!empty($codesDisplay)) {
                $subjectDisplay = $codesDisplay;
            } else {
                $subjectDisplay = 'N/A';
            }
            
            // Calculate total candidates for all papers in this subject
            $totalCandidates = 0;
            $allSubjectNames = $group->pluck('subject_name')->filter()->unique()->toArray();
            $allSubjectCodes = $group->pluck('subject_code')->filter()->unique()->toArray();
            
            // Build query to count all candidates for this subject group
            $countQuery = \DB::table('student_details')
                ->where('exam_session', $currentSession)
                ->where('exam_board', $first->exam_board)
                ->where('course_type', $first->qualification)
                ->where(function($query) {
                    $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                          ->where('surname', 'NOT LIKE', '%withdrawn%');
                });
            
            // Match by all subject names and codes in this group
            $countQuery->where(function($q) use ($allSubjectNames, $allSubjectCodes) {
                if (!empty($allSubjectNames)) {
                    $q->whereIn('subject_name', $allSubjectNames);
                }
                if (!empty($allSubjectCodes)) {
                    if (!empty($allSubjectNames)) {
                        $q->orWhereIn('subject_code', $allSubjectCodes);
                    } else {
                        $q->whereIn('subject_code', $allSubjectCodes);
                    }
                }
            });
            
            // Apply date range filter if provided
            if ($dateFrom && $dateTo) {
                $countQuery->whereBetween(\DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);
            } elseif ($dateFrom) {
                $countQuery->whereDate('created_at', '>=', $dateFrom);
            } elseif ($dateTo) {
                $countQuery->whereDate('created_at', '<=', $dateTo);
            }
            
            // Get count of distinct candidates
            $countData = $countQuery->select(
                \DB::raw('COUNT(DISTINCT student_id) as no_of_candidates')
            )->first();
            
            $totalCandidates = $countData->no_of_candidates ?? 0;
            
            return (object)[
                'exam_board' => $first->exam_board,
                'qualification' => $first->qualification,
                'subject_name_or_code' => $subjectDisplay,
                'no_of_candidates' => $totalCandidates
            ];
        })->values()->filter(function($item) {
            // Only include items with candidates
            return $item->no_of_candidates > 0;
        })->sortBy(function($item) {
            // Sort by exam board, qualification, and subject name
            return $item->exam_board . '|' . $item->qualification . '|' . $item->subject_name_or_code;
        })->values();

        // Calculate total unique candidates (count distinct student_id, not sum)
        // This ensures each candidate is counted only once, even if enrolled in multiple papers
        $totalCandidatesQuery = \DB::table('student_details')
            ->where('exam_session', $currentSession)
            ->whereNotNull('exam_board')
            ->whereNotNull('course_type')
            ->where(function($query) {
                $query->where('first_name', 'NOT LIKE', '%withdrawn%')
                      ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->where(function($query) {
                $query->whereNotNull('subject_name')
                      ->orWhereNotNull('subject_code');
            });
        
        // Apply same date range filter if provided
        if ($dateFrom && $dateTo) {
            $totalCandidatesQuery->whereBetween(\DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $totalCandidatesQuery->whereDate('created_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $totalCandidatesQuery->whereDate('created_at', '<=', $dateTo);
        }
        
        // Count distinct student_id using raw SQL for accuracy
        $totalCandidates = $totalCandidatesQuery->select(\DB::raw('COUNT(DISTINCT student_id) as total'))->first()->total ?? 0;

        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'reportData' => $processedData,
                'totalCandidates' => $totalCandidates
            ]);
        }

        return view('Reports.examReportBySubject', [
            'reportData' => $processedData,
            'totalCandidates' => $totalCandidates
        ]);
    }

    public function candidateApprovalReport()
    {
        $currentSession = session('current_session');
        
        // Get approved candidates with their approval dates
        // Use same base query logic as examReportBySubject to ensure consistent count
        // Approval date = oldest created_at from student_details (first paper/exam created for that student)
        $approvedCandidates = \DB::table('student_details')
            ->select(
                'student_details.student_id',
                \DB::raw("CONCAT(MAX(student_details.first_name), ' ', MAX(student_details.surname)) as candidate_name"),
                \DB::raw('MIN(student_details.created_at) as approval_date')
            )
            ->where('student_details.exam_session', $currentSession)
            ->whereNotNull('exam_board')
            ->whereNotNull('course_type')
            ->where(function($query) {
                $query->whereNotNull('subject_name')
                      ->orWhereNotNull('subject_code');
            })
            ->groupBy('student_details.student_id')
            ->orderBy('approval_date', 'desc')
            ->get();

        $paymentByStudent = Payment::where('session', $currentSession)
            ->orderByDesc('id')
            ->get()
            ->unique('student_id')
            ->keyBy('student_id');

        $approvedCandidates = $approvedCandidates->map(function ($candidate) use ($paymentByStudent) {
            $paymentRecord = $paymentByStudent->get($candidate->student_id);
            $candidate->fee_received = $paymentRecord->amount_received ?? null;
            return $candidate;
        });

        // Get total count for display (same logic as examReportBySubject)
        $totalCandidates = $approvedCandidates->count();

        return view('Reports.candidateApprovalReport', compact('approvedCandidates', 'currentSession', 'totalCandidates'));
    }

    public function getFilteredCandidateCount(Request $request)
    {
        $currentSession = session('current_session');
        
        // Get filter parameters
        $examBoard = $request->input('exam_board');
        $qualification = $request->input('qualification', []);
        $subject = $request->input('subject');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if (!is_array($qualification)) {
            $qualification = [$qualification];
        }

        $qualification = array_values(array_filter($qualification, function ($value) {
            return $value !== null && trim($value) !== '';
        }));
        
        // Base query
        $query = \DB::table('student_details')
            ->where('exam_session', $currentSession)
            ->whereNotNull('exam_board')
            ->whereNotNull('course_type')
            ->where(function($q) {
                $q->where('first_name', 'NOT LIKE', '%withdrawn%')
                  ->where('surname', 'NOT LIKE', '%withdrawn%');
            })
            ->where(function($q) {
                $q->whereNotNull('subject_name')
                  ->orWhereNotNull('subject_code');
            });
        
        // Apply filters
        if ($examBoard) {
            $query->where('exam_board', $examBoard);
        }
        
        if (!empty($qualification)) {
            $query->whereIn('course_type', $qualification);
        }
        
        if ($subject) {
            $query->where(function($q) use ($subject) {
                $q->where('subject_name', 'like', '%' . $subject . '%')
                  ->orWhere('subject_code', 'like', '%' . $subject . '%');
            });
        }
        
        // Apply date range filter if provided
        if ($dateFrom && $dateTo) {
            $query->whereBetween(\DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Count distinct student_id
        $filteredCount = $query->select(\DB::raw('COUNT(DISTINCT student_id) as total'))->first()->total ?? 0;
        
        return response()->json(['count' => $filteredCount]);
    }

    public function getCandidatedata($id)
    {
        // Get the current session
        $currentSession = session('current_session');

        // Get the student data filtered by student_id and current session
        $data = StudentDetail::where('student_id', $id)
            ->where('exam_session', $currentSession)
            ->first();

        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Candidate not found'], 404);
        }
    }

    public function uploadresultPDf()
    {

         $activeSession =  session('current_session');

        // get unique student records for active session
        $students = StudentDetail::where('exam_session', $activeSession)
            ->select('student_id', 'first_name', 'surname', 'uci_number', 'email')
            ->groupBy('student_id', 'first_name', 'surname', 'uci_number', 'email')
            ->get();

        // update mismatched records only
        foreach ($students as $student) {

            CandidateAndUciNumber::where('candidate_id', $student->student_id)
                ->where('session', $activeSession)
                ->where(function ($q) use ($student) {
                    $q->where('candidate_name', '!=', $student->first_name.' '.$student->surname)
                    ->orWhere('email', '!=', $student->email)
                    ->orWhere('uci_number', '!=', $student->uci_number);
                })
                ->update([
                    'candidate_name' => $student->first_name.' '.$student->surname,
                    'email'          => $student->email,
                    'uci_number'     => $student->uci_number,
                    'updated_at'     => now(),
                ]);
        }
        // ini_set('memory_limit', '1000M');
        // $activeSession = Session::where('status', 'active')->first();

        // $data = CandidateAndUciNumber::where('session',$activeSession->session_name)->unique('candidate_id');

        // $results = Result::all()->groupBy('candidate_id');
        // return view('result.uploadresultPDf', compact('data', 'results'));
        ini_set('memory_limit', '1000M');


        $activeSession = Session::where('status', 'active')->first();

        if (!$activeSession) {
            return back();
        }
        // where('exam_session',)->

        $data = CandidateAndUciNumber::where('session', session('current_session'))

            ->distinct('candidate_id')
            ->get();

        $results = Result::where('session', session('current_session'))
            ->get()
            ->groupBy('candidate_id');

        return view('result.uploadresultPDf', compact('data', 'results'));
    }





    public function uploadResults(Request $request)
    {
        // Debugging
        // dd($request->all());

        $request->validate([
            'candidate_id' => 'required',
            'candidate_name' => 'required',
            'result_file.*' => 'required',
        ]);

        ini_set('memory_limit', '1000M');
        $filePaths = [];

        $activeSession = Session::where('status', 'active')->first();
        foreach ($request->file('result_file') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('results'), $filename);
            $filePath = 'results/' . $filename;
            $filePaths[] = $filePath;

            Result::create([
                'candidate_id' => $request->candidate_id,
                'uci_number' => $request->uci_number,
                'candidate_name' => $request->candidate_name,
                'file' => $filePath,
                'session' => session('current_session'), //$activeSession->session_name,
            ]);
        }
        return redirect()->route('upload.result.pdf')->with('success', 'Files uploaded successfully!');
    }

    public function deletePdfData($id)
    {
        $activeSession = Session::where('status', 'active')->first();
        $results = Result::where('session', session('current_session'))->where('candidate_id', $id)->get();

        foreach ($results as $result) {
            $result->delete();

            $filePath = public_path($result->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        return redirect()->route('upload.result.pdf')->with('success', 'Records and associated files deleted successfully.');
    }


    public function emailLogs()
    {
        $currentSession = session('current_session');

        $emailLogs = EmailLog::where('session', $currentSession)
            ->orderByDesc('message_date')
            ->orderByDesc('id')
            ->paginate(100);

        return view('email.emailLogs', compact('emailLogs', 'currentSession'));
    }

    // ── Email Tracking Pixel Helper ──────────────────────────────────────────

    /**
     * Inject a 1×1 transparent PNG tracking pixel into an HTML email body.
     * Uses the email_log DB id so the trackEmailOpen route can find the record directly.
     */
    private function buildAndInjectPixel(string $html, int $logId): string
    {
        $base      = 'https://examcentre.londonexamcentre.co.uk';
        $cacheBust = now()->timestamp . '-' . md5($logId . microtime(true));
        $url       = $base . '/email-track/' . $logId . '.png?t=' . $cacheBust;

        $pixelTag = '<div style="font-size:1px;line-height:1px;">'
            . '<img src="' . $url . '" width="1" height="1" alt="" border="0"'
            . ' style="display:block;width:1px;height:1px;border:0;outline:none;text-decoration:none;" />'
            . '</div>';

        if (preg_match('/<\/body>/i', $html)) {
            return preg_replace('/<\/body>/i', $pixelTag . '</body>', $html, 1) ?? ($html . $pixelTag);
        }
        if (preg_match('/<\/html>/i', $html)) {
            return preg_replace('/<\/html>/i', $pixelTag . '</html>', $html, 1) ?? ($html . $pixelTag);
        }
        return $html . $pixelTag;
    }

    // ── Important Message Email Tracking ────────────────────────────────────

    /**
     * 1×1 transparent PNG pixel — records email open when loaded by the email client.
     * Uses numeric DB ID in the URL (same pattern as emailsender.biztechsols.com).
     * Route must NOT require auth so email clients can load it freely.
     */
    public function trackEmailOpen(Request $request, $id)
    {
        $log = EmailLog::find($id);

        if ($log) {
            $log->open_count = ((int) $log->open_count) + 1;
            if (is_null($log->opened_at)) {
                $log->opened_at = now();
            }
            $log->save();
        }

        // 1×1 transparent PNG (same as EmailSender project)
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Z6a4AAAAASUVORK5CYII=');

        return response($pixel, 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    /**
     * Report page: emails sent from /send-important-message with tracking data.
     * These are identified by having a non-null tracking_token.
     */
    public function importantMessageEmailReport()
    {
        $currentSession = session('current_session');

        $logs = EmailLog::whereNotNull('tracking_token')
            ->where('session', $currentSession)
            ->orderByDesc('message_date')
            ->paginate(50);

        return view('email.importantMessageReport', compact('logs', 'currentSession'));
    }

    public function sendResults()
    {
        // $candidates = CandidateAndUciNumber::whereNotNull('email')->where('email', '<>', '')->whereNull('is_sent')->take(50)->get();
        $activeSession = Session::where('status', 'active')->first();
        $candidates = CandidateAndUciNumber::whereNotNull('email')
            ->where('session', session('current_session'))
            // ->where('email', '<>', '')
            ->whereNull('is_sent')
            ->take(50)
            ->get();

        // Get all candidate IDs from the candidates collection
        $candidateIds = $candidates->pluck('candidate_id')->toArray();

        // Fetch all results for these candidates in one query
        $results = Result::whereIn('candidate_id', $candidateIds)
            ->where('session', session('current_session'))
            ->get()
            ->keyBy('candidate_id'); // Index by candidate_id for quick lookup

        return view('result.dispatch', compact('candidates', 'results'));
    }


    public function modifyRecords()
    {

        $activeSession = Session::where('status', 'active')->first();
        $candidates = CandidateAndUciNumber::where('session', session('current_session'))->get();
        return view('result.listRecords', compact('candidates'));
    }


    public function sendEmails(Request $request)
    {
        $batchSize = 50; // Process 50 records at a time
        $currentBatch = (int) $request->input('batch', 0);

        // Get the next batch of candidates

        $activeSession = Session::where('status', 'active')->first();

        $candidates = CandidateAndUciNumber::whereNotNull('email')
            ->where('email', '<>', '')
            ->where('is_sent', null)
            ->where('session', session('current_session'))
            ->skip($currentBatch * $batchSize)
            ->take($batchSize)
            ->get();

        if ($candidates->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No more candidates to process.']);
        }

        // Get all candidate IDs from this batch
        $candidateIds = $candidates->pluck('candidate_id')->toArray();

        // Find which of these candidates have results
        $results = Result::whereIn('candidate_id', $candidateIds)
            ->where('session', session('current_session'))
            ->get()
            ->groupBy('candidate_id');
        $processedCount = 0;
        $emailContent = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Exam Day Instructions</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
  <!-- Logo -->
  <div style="text-align: center; margin-bottom: 20px;">
    <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre Logo" style="max-width: 180px;">
  </div>
  <!-- Main Content -->
  <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    <p>Dear Student,</p>
    <p>I hope this message finds you well. Attached, you will find the results you requested.</p>
    <p>If you have any questions or need further information, please do not hesitate to contact me.</p>
    <p>Best regards,</p>
    <p>London Exam Centre</p>
    <p>londonexamcentre.co.uk</p>
  </div>
  <!-- Footer -->
  <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
    <p>
      140 Paul Street Stratford, London – E15 4FF<br>
      Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
      Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
    </p>

  </div>
</body>
</html>';

        foreach ($candidates as $candidate) {
            // Check if this candidate has results
            if (isset($results[$candidate->candidate_id])) {
                $candidateResults = $results[$candidate->candidate_id];

                $attachments = [];
                foreach ($candidateResults as $result) {
                    $filePath = public_path($result->file);
                    if (File::exists($filePath)) {
                        $attachments[] = [
                            'data' => File::get($filePath),
                            'name' => basename($result->file),
                            'options' => ['mime' => 'application/pdf']
                        ];
                    }
                }

                if (!empty($attachments)) {
                    try {
                        // ── Step 1: Log first to get DB id for tracking pixel ──
                        $logRecord = EmailLogHelper::log([
                            'candidate_name'  => $candidate->candidate_name,
                            'email_address'   => $candidate->email,
                            'candidate_id'    => $candidate->candidate_id,
                            'uci_number'      => $candidate->uci_number,
                            'subject_line'    => 'Result PDF Attachments',
                            'message'         => $emailContent,
                            'message_date'    => now(),
                            'session'         => session('current_session'),
                            'sent_status'     => 'sent',
                            'tracking_token'  => EmailLogHelper::generateTrackingToken(),
                        ]);

                        // ── Step 2: Inject tracking pixel ──────────────────
                        $trackedContent = $emailContent;
                        if ($logRecord) {
                            $trackedContent = $this->buildAndInjectPixel($emailContent, $logRecord->id);
                            $logRecord->message = $trackedContent;
                            $logRecord->save();
                        }

                        // ── Step 3: Send email ──────────────────────────────
                        Mail::send([], [], function (Message $message) use ($candidate, $trackedContent, $attachments) {
                            $message->to($candidate->email)
                                ->subject('Result PDF Attachments')
                                ->html($trackedContent);
                            foreach ($attachments as $attachment) {
                                $message->attachData(
                                    $attachment['data'],
                                    $attachment['name'],
                                    $attachment['options']
                                );
                            }
                        });

                        // Mark as sent only if email was successfully sent
                        $candidate->is_sent = 1;
                        $candidate->save();
                        $processedCount++;
                    } catch (\Exception $e) {
                        if (!empty($logRecord)) {
                            $logRecord->sent_status = 'failed';
                            $logRecord->save();
                        }
                        // Log error but continue with next candidate
                        $errors[] = [
                            'email' => $candidate->email,
                            'candidate_id' => $candidate->candidate_id,
                            'error' => $e->getMessage(),
                        ];
                        \Log::error("Failed to send email to {$candidate->email}: " . $e->getMessage());
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'processed' => $processedCount,
            'total_in_batch' => $candidates->count(),
            'next_batch' => $currentBatch + 1,
            'message' => "Processed batch {$currentBatch} with {$processedCount} emails sent."
        ]);
    }


    public function sendSingleMail($id)
    {
        $activeSession = Session::where('status', 'active')->first();
        $candidate = CandidateAndUciNumber::where('session', session('current_session'))->where('candidate_id', $id)->first();

        if (!$candidate || empty($candidate->email)) {
            return response()->json(['success' => false, 'message' => 'Candidate not found or email address missing.']);
        }

        $results = Result::where('session', session('current_session'))->where('candidate_id', $id)->get();

        if ($results->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No results found for this candidate.']);
        }

        // Email content with static text instead of Email::first()->message
        $htmlContent = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Exam Day Instructions</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
  <!-- Logo -->
  <div style="text-align: center; margin-bottom: 20px;">
    <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre Logo" style="max-width: 180px;">
  </div>
  <!-- Main Content -->
  <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    <p>Dear Student,</p>
    <p>I hope this message finds you well. Attached, you will find the results you requested.</p>
    <p>If you have any questions or need further information, please do not hesitate to contact me.</p>
    <p>Best regards,</p>
    <p>London Exam Centre</p>
    <p>londonexamcentre.co.uk</p>
  </div>
  <!-- Footer -->
  <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
    <p>
      140 Paul Street Stratford, London – E15 4FF<br>
      Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
      Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
    </p>

  </div>
</body>
</html>';

        $attachments = [];
        foreach ($results as $result) {
            $filePath = public_path($result->file);

            if (File::exists($filePath)) {
                $fileContents = File::get($filePath);

                $attachments[] = [
                    'data' => $fileContents,
                    'name' => basename($result->file),
                    'options' => ['mime' => 'application/pdf']
                ];
            }
        }

        if (!empty($attachments)) {
            // ── Step 1: Log first to get DB id for tracking pixel ──────────
            $logRecord = EmailLogHelper::log([
                'candidate_name'  => $candidate->candidate_name,
                'email_address'   => $candidate->email,
                'candidate_id'    => $candidate->candidate_id,
                'uci_number'      => $candidate->uci_number,
                'subject_line'    => 'Result PDF Attachments',
                'message'         => $htmlContent,
                'message_date'    => now(),
                'session'         => session('current_session'),
                'sent_status'     => 'sent',
                'tracking_token'  => EmailLogHelper::generateTrackingToken(),
            ]);

            // ── Step 2: Inject tracking pixel ──────────────────────────────
            $trackedContent = $htmlContent;
            if ($logRecord) {
                $trackedContent = $this->buildAndInjectPixel($htmlContent, $logRecord->id);
                $logRecord->message = $trackedContent;
                $logRecord->save();
            }

            // ── Step 3: Send email ──────────────────────────────────────────
            try {
                Mail::send([], [], function (Message $message) use ($candidate, $trackedContent, $attachments) {
                    $message->to($candidate->email)
                        ->subject('Result PDF Attachments')
                        ->html($trackedContent);

                    foreach ($attachments as $attachment) {
                        $message->attachData(
                            $attachment['data'],
                            $attachment['name'],
                            $attachment['options']
                        );
                    }
                });
            } catch (\Exception $e) {
                if ($logRecord) {
                    $logRecord->sent_status = 'failed';
                    $logRecord->save();
                }
                return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
            }

            // Update record
            $candidate->is_sent = 1;
            $candidate->save();

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully.',
                'candidate_id' => $candidate->id
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No attachments found for this candidate.']);
    }


    public function ignoreEmail($id)
    {
        $activeSession = Session::where('status', 'active')->first();
        $candidate = CandidateAndUciNumber::where('candidate_id', $id)->where('session', session('current_session'))->first();

        if ($candidate) {
            $candidate->is_sent = 3;
            $candidate->save();

            return response()->json(['success' => true, 'message' => 'Candidate ignored successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Candidate not found.'], 404);
        }
    }


    public function addCandidate(Request $request)
    {
        $candidate_id    = $request->input('candidate_id');
        $uci_number      = $request->input('uci_number');
        $candidate_name  = $request->input('candidateName') ?? null;
        $candidate_email = $request->input('candidateEmail') ?? null;

        $activeSession = Session::where('status', 'active')->first();
        $candidateAndUciNumber   = CandidateAndUciNumber::create([
            'candidate_id'   => $candidate_id,
            'uci_number'     => $uci_number,
            'candidate_name' => $candidate_name,
            'email'          => $candidate_email,
            'session'        => session('current_session'),
        ]);

        return back()->with('success', 'Candidate added successfully');
    }


    public function rejectEmail()
    {
        $activeSession = Session::where('status', 'active')->first();
        $candidates = CandidateAndUciNumber::where('session', session('current_session'))->where('is_sent', 3)->get();
        return view('result.rejectedEmail', compact('candidates'));
    }

    public function undoReject($candidateId)
    {
        $activeSession = Session::where('status', 'active')->first();
        $candidate = CandidateAndUciNumber::where('session', session('current_session'))->where('candidate_id', $candidateId)->first();

        if ($candidate) {
            $candidate->is_sent = null;
            $candidate->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function widthrawStudent($id)
    {
        // // dd($id);
        // $activeSession = Session::where('status', 'active')->first();
        // $students = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)
        //     // ->where('exam_session', session('current_session'))
        //     ->get();
        // $email_get = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)
        //     // ->where('exam_session', $activeSession->session_name)
        //     //  ->
        //     ->first();
        // // dd($students,$activeSession);

        // if ($students->isNotEmpty()) {
        //     foreach ($students as $student) {
        //         $student->first_name .= ' - widthraw';

        //         $student->save();
        //     }
        //     if ($email_get) {
        //         $email = $email_get->email;
        //         $candidateName = $email_get->first_name . ' ' . $email_get->surname;

        //         $emailContent =
        //             'Dear ' . $candidateName . ',<br><br>' .
        //             'I hope this message finds you well.<br><br>' .
        //             'This is to confirm that, as per your request, your withdrawal from the examination has been successfully processed. Please note that you will no longer be allowed to sit for the exam.<br><br>' .
        //             'If you have any further questions or require assistance, please feel free to contact us.<br><br>' .
        //             'Best regards,<br>' .
        //             'Exam Team';

        //         Mail::send([], [], function ($message) use ($email, $emailContent) {
        //             $message->to($email)
        //             ->cc('exams@londonexamcentre.co.uk') // Add CC email here
        //                 ->subject('Confirmation of Withdrawal from Examination')
        //                 ->html($emailContent);
        //         });
        //     }
        //     CandidateAndUciNumber::where('session', session('current_session'))->where('candidate_id', $id)->delete();
        // }

        // return redirect()->back()->with('success', 'Student widthraw successfully.');

        // Get active session
        $activeSession = Session::where('status', 'active')->first();
        $students = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)
            ->get();
        $email_get = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $id)
            ->first();

        if ($students->isNotEmpty()) {
            foreach ($students as $student) {
                $student->first_name .= ' - withdrawn';
                $student->save();
            }
            if ($email_get) {
                $email = $email_get->email;
                $candidateName = $email_get->first_name . ' ' . $email_get->surname;

                $emailContent = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Exam Withdrawal Confirmation</title>
            </head>
            <body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
                <!-- Logo -->
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre Logo" style="max-width: 180px;">
                </div>
                <!-- Main Content -->
                <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                    <h2 style="color: #2e6da4;">Dear ' . htmlspecialchars($candidateName) . ',</h2>
                    <p>I hope this message finds you well.</p>
                    <p>This is to confirm that, as per your request, your withdrawal from the examination has been successfully processed. Please note that you will no longer be allowed to sit for the exam.</p>
                    <p>If you have any further questions or require assistance, please feel free to contact us.</p>
                    <p>Best regards,<br>
                    <strong>Exams Team</strong><br>
                    London Exam Centre</p>
                </div>
                <!-- Footer -->
                <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
                    <p>
                        140 Paul Street Stratford, London – E15 4FF<br>
                        Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
                        Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
                    </p>
    
                </div>
            </body>
            </html>';

                try {
                    // ── Step 1: Log first to get DB id for tracking pixel ──
                    $logRecord = EmailLogHelper::log([
                        'candidate_name'  => trim($candidateName) ?: null,
                        'email_address'   => $email,
                        'student_id'      => $email_get->student_id ?? null,
                        'uci_number'      => $email_get->uci_number ?? null,
                        'subject_line'    => 'Confirmation of Withdrawal from Examination',
                        'message'         => $emailContent,
                        'message_date'    => now(),
                        'session'         => session('current_session'),
                        'sent_status'     => 'sent',
                        'tracking_token'  => EmailLogHelper::generateTrackingToken(),
                    ]);

                    // ── Step 2: Inject tracking pixel ──────────────────────
                    if ($logRecord) {
                        $emailContent = $this->buildAndInjectPixel($emailContent, $logRecord->id);
                        $logRecord->message = $emailContent;
                        $logRecord->save();
                    }

                    // ── Step 3: Send email ──────────────────────────────────
                    Mail::html($emailContent, function ($message) use ($email) {
                        $message->to($email)
                            ->cc('exams@londonexamcentre.co.uk')
                            ->subject('Confirmation of Withdrawal from Examination')
                            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    });
                } catch (\Exception $e) {
                    if (!empty($logRecord)) {
                        $logRecord->sent_status = 'failed';
                        $logRecord->save();
                    }
                    Log::error('Failed to send withdrawal confirmation email: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Failed to send withdrawal confirmation email: ' . $e->getMessage());
                }
            }
            CandidateAndUciNumber::where('session', session('current_session'))->where('candidate_id', $id)->delete();
        }

        return redirect()->back()->with('success', 'Student withdrawn successfully.');
    }


    public function undoStudent($id)
    {
        $activeSession = Session::where('status', 'active')->first();
        $students = StudentDetail::where('student_id', $id)->where('exam_session', session('current_session'))->get();
        // dd();
        if ($students->isNotEmpty()) {
            foreach ($students as $student) {
                if (str_ends_with($student->first_name, ' - withdrawn')) {
                    $student->first_name = rtrim(str_replace(' - withdrawn', '', $student->first_name));

                    $student->save();
                }
            }


            $studentDetails = StudentDetail::where('student_id', $id)->where('exam_session', session('current_session'))->get();
            // dd($students,$studentDetails);
            if ($studentDetails->isNotEmpty()) {
                $student = $studentDetails->first();
                // dd([
                //     'candidate_id' => $student->studenLt_id,
                //     'uci_number' => $student->uciNumber,
                //     'email' => $student->email,
                //     'candidate_name' => "$student->surname $student->firstName",
                //     'is_sent' => null,
                // ]);

                $candidateUciData = [
                    'candidate_id' => $student->student_id,
                    'uci_number' => $student->uci_number,
                    'email' => $student->email,
                    'candidate_name' => "{$student->first_name} {$student->surname}",
                    'is_sent' => null,
                    'session' => session('current_session'),
                ];

                $newCandidateUci = CandidateAndUciNumber::where('session', session('current_session'))
                    ->firstOrCreate($candidateUciData);
            }
        }

        return redirect()->back()->with('success', 'student enroll successfully.');
    }

    public function unapproveStudent($id)
    {
        $currentSession = session('current_session');
        
        // Get the student_request_id from student_details for this student_id and current session
        $studentDetail = StudentDetail::where('student_id', $id)
            ->where('exam_session', $currentSession)
            ->first();
        
        if ($studentDetail && $studentDetail->student_request_id) {
            // Update student_request status to 0
            StudentRequest::where('id', $studentDetail->student_request_id)
                ->update(['is_approved' => 0]);
        }
        
        // Delete all student_details records for this student_id in current session
        StudentDetail::where('student_id', $id)
            ->where('exam_session', $currentSession)
            ->delete();
        
        // Also delete from CandidateAndUciNumber if exists
        CandidateAndUciNumber::where('candidate_id', $id)
            ->where('session', $currentSession)
            ->delete();
        
        return redirect()->back()->with('success', 'Candidate unapproved successfully. All records deleted for current session.');
    }


    public function sendTimeTable(Request $request)
    {
        // Get the student ID from the request
        $student_id = $request->student_id;

        // Retrieve student details
        $student = StudentDetail::where('exam_session', session('current_session'))->where('student_id', $student_id)->first();
        // dd($student);
        // Check if student exists
        if (!$student || !$student->email) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or email not available.'
            ], 404);
        }

        $email = $student->email;

        // Get the HTML content for the PDF from the request
        $htmlContent = $request->input('dynamicLayoutContent');

        // Add styling to fit content on one page with increased font size for PDF
        $zoomOutStyle = '<style>
            body {
                transform: scale(0.80);
                transform-origin: top left;
                margin: 0;
                padding: 10px;
                font-family: "DejaVu Sans", sans-serif;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 8px;
            }
            th, td {
                border: 1px solid black !important;
                padding: 3px;
                text-align: left;
                font-size: 12px;
            }
            th {
                background-color: #f8f9fa;
                white-space: nowrap;
            }
            .table-responsive {
                overflow-x: auto;
            }
            h2 {
                font-size: 16px;
                margin: 4px 0;
            }
            .text-center {
                text-align: center !important;
            }
            img {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
        </style>';

        // Replace the asset() URL with a local file path for dompdf to load the logo properly
        $htmlContent = str_replace(asset('images/LondonExamCentre.png'), public_path('images/LondonExamCentre.png'), $htmlContent);
        // Inject the style into the HTML content for the PDF
        $htmlContent = $zoomOutStyle . $htmlContent;

        // Initialize Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isPhpEnabled', true);
        $options->set('chroot', public_path());

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Get the current timestamp
        $timestamp = date('YmdHisu');

        // Generate unique PDF file name
        $pdfFileName = 'timetable_' . $timestamp . '.pdf';

        // Save the PDF to the public folder
        $pdfPath = public_path('timetable/' . $pdfFileName);
        file_put_contents($pdfPath, $dompdf->output());

        // Define the HTML email template with placeholder for student's first name
        $emailTemplate = <<<EOD
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Exam Timetable</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">

  <!-- Logo -->
  <div style="text-align: center; margin-bottom: 20px;">
    <img src="https://examcentre.londonexamcentre.co.uk/images/LondonExamCentre.png" alt="London Exam Centre Logo" style="max-width: 180px;">
  </div>

  <!-- Main Content -->
  <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    <h2 style="color: #2e6da4;">Dear $student->first_name,</h2>

    <p>Please find attached your provisional timetable. We will issue your statement of entry later.</p>

    <p>Kindly make sure to cross-check the following information on your timetable:</p>
    <ul>
      <li>Your name should appear on the timetable as per the name on your passport/valid ID.</li>
      <li>There should be no difference in the date of birth on the timetable and the passport/ID.</li>
      <li>You should also verify the subject(s), unit code, and the Exam board(s) you want to sit the exam.</li>
      <li>Any clash of timing in your papers.</li>
    </ul>

    <p>In case of any error, kindly contact us at your earliest opportunity so we may remove the errors.</p>

    <p>Best Regards,<br>
    <strong>Exams Team</strong><br>
    London Exam Centre</p>
  </div>

  <!-- Footer -->
  <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
    <p>
      140 Paul Street Stratford, London – E15 4FF<br>
      Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
      Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
    </p>

  </div>

</body>
</html>
EOD;

        // Replace placeholder with student's first name
        $emailHtml = sprintf($emailTemplate, htmlspecialchars($student->firstName));

        // Send email with PDF attachment
        try {
            $subject = 'London Exam Centre Timetable';

            // ── Step 1: Log first to get DB id for tracking pixel ──────────
            $logRecord = EmailLogHelper::log([
                'candidate_name'  => trim(($student->first_name ?? '') . ' ' . ($student->surname ?? '')) ?: null,
                'email_address'   => $email,
                'student_id'      => $student->student_id ?? null,
                'uci_number'      => $student->uci_number ?? null,
                'subject_line'    => $subject,
                'message'         => $emailHtml,
                'message_date'    => now(),
                'session'         => session('current_session'),
                'sent_status'     => 'sent',
                'tracking_token'  => EmailLogHelper::generateTrackingToken(),
            ]);

            // ── Step 2: Inject tracking pixel ──────────────────────────────
            if ($logRecord) {
                $emailHtml = $this->buildAndInjectPixel($emailHtml, $logRecord->id);
                $logRecord->message = $emailHtml;
                $logRecord->save();
            }

            // ── Step 3: Send email with PDF attachment ──────────────────────
            $userCcEmail = trim($request->input('cc_email', ''));
            $staticCc    = 'exams@londonexamcentre.co.uk';

            // Build to() array — always student, add modal CC if provided
            $toAddresses = array_values(array_filter([$email, $userCcEmail], fn($e) => $e !== ''));

            Mail::html($emailHtml, function ($message) use ($toAddresses, $pdfPath, $subject, $staticCc) {
                $message->to($toAddresses)
                    ->cc($staticCc)
                    ->subject($subject)
                    ->attach($pdfPath);
            });

            // Delete the PDF file after sending the email
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Timetable sent successfully to ' . $email
            ], 200);
        } catch (\Exception $e) {
            // Mark log as failed if send threw
            if (!empty($logRecord)) {
                $logRecord->sent_status = 'failed';
                $logRecord->save();
            }

            // Delete the PDF file if it exists
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            // Log the error for debugging
            Log::error('Failed to send timetable: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to send timetable: ' . $e->getMessage()
            ], 500);
        }
    }

    public function truncateTables(Request $request)
    {
        // Secret key check for security
        $secretKey = 'LEC_TRUNCATE_2026';
        if ($request->input('key') !== $secretKey) {
            abort(403, 'Unauthorized. Provide correct key.');
        }

        // Tables to exclude from truncation
        $excludedTables = [
            'sessions',
            'users',
            'migrations',
            'access_permissions',
            'password_resets',
            'password_reset_tokens',
            'personal_access_tokens',
        ];

        try {
            // Disable foreign key checks to avoid truncation errors
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            // Get all table names from the database
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                
                // Skip excluded tables
                if (!in_array($tableName, $excludedTables)) {
                    DB::table($tableName)->truncate();
                }
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            return response()->json([
                'success' => true,
                'message' => 'All tables truncated successfully except: ' . implode(', ', $excludedTables),
            ]);
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            return response()->json([
                'success' => false,
                'message' => 'Error truncating tables: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getSubjectsCount()
    {
        $count = \App\Models\Subjects::count();
        return response()->json([
            'success' => true,
            'subjects_count' => $count
        ]);
    }

    public function insertAndListOfferedSubjects()
    {
        $offeredSubjectsData = [
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSM EL1', 'description' => 'Maths Entry Level 1', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSM EL2', 'description' => 'Maths Entry Level 2', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSM EL3', 'description' => 'Maths Entry Level 3', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSM L1', 'description' => 'Maths Level 1', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSM L2', 'description' => 'Maths Level 2', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSE EL1', 'description' => 'English Entry Level 1', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSE EL2', 'description' => 'English Entry Level 2', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSE EL3', 'description' => 'English Entry Level 3', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSE L1', 'description' => 'English Level 1', 'session' => 'Autumn 2026'],
            ['examboard' => 'Pearson', 'qualification' => 'Functional Skills', 'code' => 'FSE L2', 'description' => 'English Level 2', 'session' => 'Autumn 2026'],
        ];

        // Insert each subject (using updateOrCreate to avoid duplicates if run again)
        foreach ($offeredSubjectsData as $subjectData) {
            \App\Models\OfferedSubject::updateOrCreate(
                ['code' => $subjectData['code'], 'session' => $subjectData['session']],
                $subjectData
            );
        }

        // Get all offered subjects to display
        $allOfferedSubjects = \App\Models\OfferedSubject::orderBy('id')->get();

        // Create HTML table to display
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Offered Subjects</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>Offered Subjects - All Records</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Exam Board</th>
                <th>Qualification</th>
                <th>Code</th>
                <th>Description</th>
                <th>Session</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($allOfferedSubjects as $subject) {
            $html .= '<tr>
                <td>' . $subject->id . '</td>
                <td>' . $subject->examboard . '</td>
                <td>' . $subject->qualification . '</td>
                <td>' . $subject->code . '</td>
                <td>' . $subject->description . '</td>
                <td>' . $subject->session . '</td>
                <td>' . $subject->created_at . '</td>
                <td>' . $subject->updated_at . '</td>
            </tr>';
        }

        $html .= '</tbody>
    </table>
</body>
</html>';

        return response($html);
    }

    public function insertAndListSubjects()
    {
        $subjectsData = [
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'Maths Entry Level 1', 'tier' => null, 'subjectCode' => 'FSM EL1', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'Maths Entry Level 2', 'tier' => null, 'subjectCode' => 'FSM EL2', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'Maths Entry Level 3', 'tier' => null, 'subjectCode' => 'FSM EL3', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'Maths Level 1', 'tier' => null, 'subjectCode' => 'FSM L1', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'Maths Level 2', 'tier' => null, 'subjectCode' => 'FSM L2', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'English Entry Level 1', 'tier' => null, 'subjectCode' => 'FSE EL1', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'English Entry Level 2', 'tier' => null, 'subjectCode' => 'FSE EL2', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'English Entry Level 3', 'tier' => null, 'subjectCode' => 'FSE EL3', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'English Level 1', 'tier' => null, 'subjectCode' => 'FSE L1', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
            ['examBoard' => 'Pearson', 'courseType' => 'Functional Skills', 'subjectName' => 'English Level 2', 'tier' => null, 'subjectCode' => 'FSE L2', 'examSession' => 'Autumn 2026', 'examTime' => null, 'examDate' => null, 'examDay' => null, 'duration' => null, 'slot' => null, 'nea' => null],
        ];

        // Insert each subject (using updateOrCreate to avoid duplicates if run again)
        foreach ($subjectsData as $subjectData) {
            \App\Models\Subjects::updateOrCreate(
                ['subjectCode' => $subjectData['subjectCode'], 'examSession' => $subjectData['examSession']],
                $subjectData
            );
        }

        // Get all subjects to display
        $allSubjects = \App\Models\Subjects::orderBy('id')->get();

        // Create HTML table to display
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Subjects</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>Subjects - All Records</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Exam Board</th>
                <th>Course Type</th>
                <th>Subject Name</th>
                <th>Tier</th>
                <th>Subject Code</th>
                <th>Exam Session</th>
                <th>Exam Time</th>
                <th>Exam Date</th>
                <th>Exam Day</th>
                <th>Duration</th>
                <th>Slot</th>
                <th>NEA</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($allSubjects as $subject) {
            $html .= '<tr>
                <td>' . $subject->id . '</td>
                <td>' . $subject->examBoard . '</td>
                <td>' . $subject->courseType . '</td>
                <td>' . $subject->subjectName . '</td>
                <td>' . ($subject->tier ?? 'NULL') . '</td>
                <td>' . $subject->subjectCode . '</td>
                <td>' . $subject->examSession . '</td>
                <td>' . ($subject->examTime ?? 'NULL') . '</td>
                <td>' . ($subject->examDate ?? 'NULL') . '</td>
                <td>' . ($subject->examDay ?? 'NULL') . '</td>
                <td>' . ($subject->duration ?? 'NULL') . '</td>
                <td>' . ($subject->slot ?? 'NULL') . '</td>
                <td>' . ($subject->nea ?? 'NULL') . '</td>
                <td>' . $subject->created_at . '</td>
                <td>' . $subject->updated_at . '</td>
            </tr>';
        }

        $html .= '</tbody>
    </table>
</body>
</html>';

        return response($html);
    }


    public function sendEmailIndividualPendingCandidate(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $validated = $request->validate([
            'request_id' => 'required',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf',
        ]);

        // Extract validated data
        $email = $validated['email'];
        $subject = $validated['subject'];
        $message = $validated['message'];

        // Define the email template
        $fullTemplate = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($subject) . '</title>
        </head>
        <body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre Logo" style="max-width: 180px;">
            </div>
            <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                ' . nl2br(htmlspecialchars($message)) . '
            </div>
            <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
                <p>
                    140 Paul Street Stratford, London – E15 4FF<br>
                    Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
                    Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
                </p>

            </div>
        </body>
        </html>';

        try {
            // ── Step 1: Log first to get the DB id for the tracking pixel ──
            // request_id can be either a StudentRequest primary key (from /student-request)
            // or a student_id string like "0042" (from /approved-request).
            $requestId   = $validated['request_id'];
            $studentRequest = StudentRequest::find($requestId);

            // If not found as a StudentRequest PK, try StudentDetail by student_id
            $studentDetail = null;
            if (!$studentRequest) {
                $studentDetail = StudentDetail::where('student_id', $requestId)
                    ->where('exam_session', session('current_session'))
                    ->first();
            }

            // Resolve candidate name and identifiers
            if ($studentRequest) {
                $candidateName = EmailLogHelper::nameFromStudentRequest($studentRequest);
                $uciNumber     = $studentRequest->uci_number ?? null;
                $studentId     = null; // pending candidate — no student_id yet
            } elseif ($studentDetail) {
                $candidateName = trim(($studentDetail->first_name ?? '') . ' ' . ($studentDetail->surname ?? '')) ?: null;
                $uciNumber     = $studentDetail->uci_number ?? null;
                $studentId     = $studentDetail->student_id ?? null;
            } else {
                $candidateName = null;
                $uciNumber     = null;
                $studentId     = null;
            }

            $logRecord = EmailLogHelper::log([
                'candidate_name'  => $candidateName,
                'email_address'   => $email,
                'student_id'      => $studentId,
                'uci_number'      => $uciNumber,
                'subject_line'    => $subject,
                'message'         => $fullTemplate,
                'message_date'    => now(),
                'session'         => session('current_session'),
                'sent_status'     => 'sent',
                'tracking_token'  => EmailLogHelper::generateTrackingToken(),
            ]);

            // ── Step 2: Inject tracking pixel using the real DB id ──────────
            if ($logRecord) {
                $fullTemplate = $this->buildAndInjectPixel($fullTemplate, $logRecord->id);
                $logRecord->message = $fullTemplate;
                $logRecord->save();
            }

            // ── Step 3: Send the email ────────────────────────────────────
            Mail::html($fullTemplate, function ($mail) use ($email, $subject, $request) {
                $mail->to($email)
                    ->cc('exams@londonexamcentre.co.uk')
                    ->subject($subject)
                    ->from('exams@londonexamcentre.co.uk', 'London Exam Centre');

                // Attach multiple PDFs if provided
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $attachment) {
                        if ($attachment->isValid()) {
                            $mail->attach($attachment->getRealPath(), [
                                'as' => $attachment->getClientOriginalName(),
                                'mime' => $attachment->getClientMimeType(),
                            ]);
                        }
                    }
                }
            });

            // Return success response
            return response()->json([
                'message' => 'Email sent successfully!'
            ], 200);
        } catch (\Exception $e) {
            // Mark log as failed if send threw
            if (!empty($logRecord)) {
                $logRecord->sent_status = 'failed';
                $logRecord->save();
            }
            // Log the error
            Log::error('Email sending failed: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }


    // public function migrateStudent($studentId)
    // {
    //     // Step 1: Get current session
    //     $currentSession = session('current_session');

    //     // Step 2: Find upcoming next session from sessions table
    //     $nextSession = Session::where('status', 'inactive')
    //         ->where('session_name', '>', $currentSession) // ensure next wali session mile
    //         ->orderBy('session_name', 'asc')
    //         ->first();

    //     if (!$nextSession) {
    //         return response()->json(['error' => 'No upcoming session found'], 404);
    //     }

    //     // Step 3: Get all student details
    //     $studentDetails = StudentDetail::where('exam_session',session('current_session'))->where('student_id', $studentId)->get();

    //     if ($studentDetails->isEmpty()) {
    //         return response()->json(['error' => 'No student records found'], 404);
    //     }

    //     $migratedIds = [];
    //     $newRequestIds = [];

    //     foreach ($studentDetails as $studentDetail) {
    //         // Save in migrated_student_record with current session
    //         $migratedStudent = new MigratedStudentRecord();
    //         $migratedStudent->fill($studentDetail->getAttributes());
    //         $migratedStudent->exam_session = $currentSession; // ✅ current session
    //         $migratedStudent->save();

    //         $migratedIds[] = $migratedStudent->id;

    //         // Get related request
    //         $studentRequest = StudentRequest::find($studentDetail->student_request_id);

    //         if ($studentRequest) {
    //             // New request in next session
    //             $newStudentRequest = new StudentRequest();
    //             $newStudentRequest->fill($studentRequest->getAttributes());
    //             $newStudentRequest->exam_session = $nextSession->session_name; // ✅ upcoming session
    //             $newStudentRequest->is_approved = 0;
    //             $newStudentRequest->save();

    //             $newRequestIds[] = $newStudentRequest->id;
    //         }
    //     }


    //      StudentDetail::where('exam_session', $currentSession)
    //         ->where('student_id', $studentId)
    //         ->delete();



    //     return response()->json([
    //         'message' => 'Migration successful',
    //         'migrated_student_ids' => $migratedIds,
    //         'new_request_ids' => $newRequestIds,
    //         'current_session' => $currentSession,
    //         'next_session' => $nextSession->session_name,
    //     ]);
    // }

   public function migrateStudent($studentId)
    {


          $currentSession = trim(preg_replace('/\s+/', ' ', session('current_session')));

    list($currentSeason, $currentYear) = explode(' ', $currentSession);
    $currentYear = (int) $currentYear;

    // Season order
    $seasons = ["Summer", "Autumn"];
    $currentIndex = array_search($currentSeason, $seasons);

    if ($currentIndex === 0) {
        // Summer → Autumn (same year)
        $nextSeason = "Autumn";
        $nextYear   = $currentYear;
    } else {
        // Autumn → Summer (next year)
        $nextSeason = "Summer";
        $nextYear   = $currentYear + 1;
    }

    $nextSessionName = $nextSeason . " " . $nextYear;
    $nextSession = $nextSessionName;

      if (empty($nextSession)) {
    return response()->json(['error' => 'No upcoming session found'], 404);
}

        // Step 3: Get all student details
        $studentDetails = StudentDetail::where('exam_session', $currentSession)
            ->where('student_id', $studentId)
            ->get();

        if ($studentDetails->isEmpty()) {
            return response()->json(['error' => 'No student records found'], 404);
        }

        $migratedIds = [];
        $newRequestIds = [];
        $examDetails = [];

        // Step 4: Aggregate exam details for all papers
        foreach ($studentDetails as $studentDetail) {
            $examDetails[] = [
                'exam_board' => $studentDetail->exam_board,
                'course_type' => $studentDetail->course_type,
                'subject_name' => $studentDetail->subject_name,
                'subject_code' => $studentDetail->subject_code,
                'tire' => $studentDetail->tire,
                'nea' => $studentDetail->nea,
            ];
        }

        // Step 5: Prepare student data for emails and PDF (using first record for personal details)
        $firstStudentDetail = $studentDetails->first();
        $studentData = [
            'surname' => trim(preg_replace('/\s*-\s*withdrawn$/i', '', $firstStudentDetail->surname)),//$firstStudentDetail->surname,
            'first_name' => trim(preg_replace('/\s*-\s*withdrawn$/i', '', $firstStudentDetail->first_name)),//$firstStudentDetail->first_name,
            'dob' => Carbon::parse($firstStudentDetail->dob)->format('d/m/Y'),
            'gender' => $firstStudentDetail->gender,
            'address_line1' => $firstStudentDetail->address_line1,
            'address_line2' => $firstStudentDetail->address_line2,
            'postcode' => $firstStudentDetail->postcode,
            'uci_number' => $firstStudentDetail->uci_number,
            'contact_number' => $firstStudentDetail->contact_number,
            'email' => $firstStudentDetail->email,
            'emergency_contact' => $firstStudentDetail->emergency_contact,
            'uln' => $firstStudentDetail->uln,
            'medical_condition' => $firstStudentDetail->medical_condition,
            'access_arrangement' => $firstStudentDetail->access_arrangement,
            'access_arrangement_explanation' => $firstStudentDetail->access_arrangement_explanation,
            'practical_endorsement' => $firstStudentDetail->practical_endorsement,
            'need_predicted_grades' => $firstStudentDetail->need_predicted_grades,
            'need_mock_exams' => $firstStudentDetail->need_mock_exams,
            'exam_board' => array_column($examDetails, 'exam_board'),
            'course_type' => array_column($examDetails, 'course_type'),
            'subject_name' => array_column($examDetails, 'subject_name'),
            'subject_code' => array_column($examDetails, 'subject_code'),
            'tire' => array_column($examDetails, 'tire'),
            'nea' => array_column($examDetails, 'nea'),
            'photo_path' => $firstStudentDetail->photo_path,
            'id_document_path' => $firstStudentDetail->id_document_path,
            'supporting_documents_path' => $firstStudentDetail->supporting_documents_path,
            'additional_docs_paths' => $firstStudentDetail->additional_docs_paths,
            'exam_session' => $firstStudentDetail->exam_session,
            'signature' => $firstStudentDetail->sign,
            'form_filling_date' => Carbon::parse($firstStudentDetail->form_fill_date)->format('d/m/Y'),
        ];



        // Step 6: Send withdrawal confirmation email to the student (only once)
        try {
            $candidateName = $studentData['first_name'] . ' ' . $studentData['surname'];
            $emailContent = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Exam Withdrawal Confirmation</title>
    </head>
    <body style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 20px; color: #333;">
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="' . asset('images/LondonExamCentre.png') . '" alt="London Exam Centre Logo" style="max-width: 180px;">
        </div>
        <!-- Main Content -->
        <div style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
            <h2 style="color: #2e6da4;">Dear ' . htmlspecialchars($candidateName) . ',</h2>
            <p>I hope this message finds you well.</p>
            <p>This is to confirm that, as per your request, your withdrawal from the examination has been successfully processed. Please note that you will no longer be allowed to sit for the exam.</p>
            <p>If you have any further questions or require assistance, please feel free to contact us.</p>
            <p>Best regards,<br>
            <strong>Exams Team</strong><br>
            London Exam Centre</p>
        </div>
        <!-- Footer -->
        <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
            <p>
                140 Paul Street Stratford, London – E15 4FF<br>
                Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
                Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
            </p>
        
        </div>
    </body>
    </html>';

            Mail::html($emailContent, function ($message) use ($studentData) {
                $message->to($studentData['email']) // Use student email from studentData
                    ->subject('Exam Withdrawal Confirmation')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal confirmation email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send withdrawal confirmation email: ' . $e->getMessage()], 500);
        }

        // Step 6: Send confirmation email to the student (only once)
        try {
            $candidateName = $studentData['first_name'] . ' ' . $studentData['surname'];
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
                <p>Best regards,<br>
                <strong>Exams Team</strong><br>
                London Exam Centre</p>
            </div>
            <!-- Footer -->
            <div style="text-align: center; font-size: 13px; color: #777; margin-top: 30px;">
                <p>
                    140 Paul Street Stratford, London – E15 4FF<br>
                    Phone: <a href="tel:02089355931" style="color: #337ab7;">+44 7538 555089</a> |
                    Email: <a href="mailto:exams@londonexamcentre.co.uk" style="color: #337ab7;">exams@londonexamcentre.co.uk</a>
                </p>

            </div>
        </body>
        </html>';

            Mail::html($emailBody, function ($message) use ($studentData) {
                $message->to($studentData['email'])
                    // $message->to('inaam.chishti2@gmail.com')
                    ->subject('Exam Registration Confirmation')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send confirmation email: ' . $e->getMessage()], 500);
        }

        // Step 7: Generate single PDF with all exam details
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

        // Logo and Heading
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

        // Exam Session
        $pdfContent .= '
        <h3>Exam Session</h3>
        <table class="session-table">
            <tr>
                <th>Exam Session</th>
            </tr>
            <tr>
                <td>' . htmlspecialchars($nextSessionName ?? '') . '</td>
            </tr>
        </table>';

        // Student Information
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
            $value1 = $key1 ? (is_array($studentData[$key1]) ? implode(', ', $studentData[$key1]) : ($studentData[$key1] ?? '')) : '';
            $label2 = $key2 ? $simpleFields[$key2] : '';
            $value2 = $key2 ? (is_array($studentData[$key2]) ? implode(', ', $studentData[$key2]) : ($studentData[$key2] ?? '')) : '';

            $pdfContent .= '
            <tr>
                <td>' . htmlspecialchars($label1) . '</td>
                <td>' . htmlspecialchars($value1) . '</td>
                <td>' . htmlspecialchars($label2) . '</td>
                <td>' . htmlspecialchars($value2) . '</td>
            </tr>';
        }

        $pdfContent .= '</table>';

        // Exam Details (all papers)
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

        // Signature and Form Filling Date
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
            if (preg_match('/^data:image\/(png|jpg|jpeg);base64,/', $studentData['signature'])) {
                $pdfContent .= '<img src="' . $studentData['signature'] . '" style="max-width: 200px; max-height: 100px;" />';
            } elseif (file_exists(public_path($studentData['signature']))) {
                $pdfContent .= '<img src="' . public_path($studentData['signature']) . '" style="max-width: 200px; max-height: 100px;" />';
            } else {
                $pdfContent .= htmlspecialchars($studentData['signature'] ?? 'No signature provided');
            }
        } else {
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
        $studentName = trim($studentData['first_name'] . '_' . $studentData['surname']);
        $studentName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $studentName);
        $pdfPath = public_path('students/' . $studentName . '_' . time() . '.pdf');
        $pdf = Pdf::loadHtml($pdfContent);
        $pdf->save($pdfPath);

        // Step 8: Send PDF email to admin (only once)
        try {
            $adminEmailBody = '
            <html>
            <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <p>Dear Admin,</p>
                <p>A new student registration PDF is attached for your review.</p>
                <p>Student Email: ' . htmlspecialchars($studentData['email']) . '</p>
                <p>Best regards,<br>London Exam Centre</p>
            </body>
            </html>';

            Mail::html($adminEmailBody, function ($message) use ($pdfPath) {
                $message->to('exams@londonexamcentre.co.uk')
                    // $message->to('inaam.chishti2@gmail.com')
                    ->subject('New Student Registration PDF')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->attach($pdfPath);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send PDF email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send PDF email: ' . $e->getMessage()], 500);
        }

        // Step 9: Process migration for each student detail
        foreach ($studentDetails as $studentDetail) {
            // Save in migrated_student_record
            $migratedStudent = new MigratedStudentRecord();
            $migratedStudent->fill($studentDetail->getAttributes());
            $migratedStudent->exam_session = $currentSession;
            $migratedStudent->save();

            $migratedIds[] = $migratedStudent->id;
        }
        // Get related request

        $studentRequest = StudentRequest::where('exam_session', $currentSession)->find($studentDetail->student_request_id);

        if ($nextSession) {
            // Get current student request
            $studentRequest = StudentRequest::where('exam_session', $currentSession)
                ->where('id', $studentDetail->student_request_id)
                ->first();

            if ($studentRequest) {
                // Replicate existing record
                $newStudentRequest = $studentRequest->replicate();

                // Overwrite only required fields
                $newStudentRequest->exam_session = $nextSessionName;
                $newStudentRequest->is_approved  = 0;

                $newStudentRequest->save();

                $newRequestIds[] = $newStudentRequest->id;
            }
        }

        // Step 10: Delete student details after emails are sent
        StudentDetail::where('exam_session', $currentSession)
            ->where('student_id', $studentId)
            ->delete();

        return redirect()->back()->with('success', 'Student migrated successfully');
    }

    /**
     * Process Pearson GCSE Chemistry and Physics to ensure both Higher and Foundation tiers are shown
     */
    private function processPearsonChemistryPhysics($data, $subjectsData)
    {
        // Check if Pearson GCSE exists in data
        if (!isset($data['subjectsByBoardAndCourse']['Pearson']['GCSE'])) {
            return $data;
        }

        $pearsonGcseSubjects = &$data['subjectsByBoardAndCourse']['Pearson']['GCSE'];
        
        // Subject codes to check: 1CH0 (Chemistry) and 1PH0 (Physics)
        $targetCodes = ['1CH0', '1PH0'];
        
        // First, remove all existing Chemistry and Physics entries to avoid duplicates
        $filteredSubjects = [];
        foreach ($pearsonGcseSubjects as $subjectName => $subjectCode) {
            $codeDelimiter = strpos($subjectCode, '/') !== false ? '/' : ' ';
            $parts = explode($codeDelimiter, $subjectCode);
            $extractedCode = $parts[0] ?? '';
            
            if (!in_array($extractedCode, $targetCodes)) {
                $filteredSubjects[$subjectName] = $subjectCode;
            }
        }
        $pearsonGcseSubjects = $filteredSubjects;
        
        foreach ($targetCodes as $mainCode) {
            // Get all subjects for this code from original data
            $chemistryPhysicsSubjects = $subjectsData->filter(function ($subject) use ($mainCode) {
                $subjectCode = $subject->subjectCode;
                $delimiter = strpos($subjectCode, '/') !== false ? '/' : ' ';
                $code = strtok($subjectCode, $delimiter);
                return $code === $mainCode && 
                       $subject->examBoard === 'Pearson' && 
                       $subject->courseType === 'GCSE';
            });

            if ($chemistryPhysicsSubjects->isEmpty()) {
                continue;
            }

            // Get Higher tier subject - prefer Paper 1
            $higherSubs = $chemistryPhysicsSubjects->filter(function ($subject) {
                $hasHigherInName = stripos($subject->subjectName, 'Higher') !== false;
                $hasHInCode = strpos($subject->subjectCode, 'H') !== false;
                $noFInCode = strpos($subject->subjectCode, 'F') === false;
                return ($hasHigherInName || ($hasHInCode && $noFInCode));
            });
            
            // Prefer Paper 1, otherwise take first
            $higherSub = $higherSubs->filter(function ($subject) {
                return stripos($subject->subjectName, 'Paper 1') !== false || (strpos($subject->subjectCode, '1') !== false && strpos($subject->subjectCode, 'H') !== false);
            })->first();
            
            if (!$higherSub) {
                $higherSub = $higherSubs->first();
            }
            
            // Get Foundation tier subject - prefer Paper 1
            $foundationSubs = $chemistryPhysicsSubjects->filter(function ($subject) {
                $hasFoundationInName = stripos($subject->subjectName, 'Foundation') !== false;
                $hasFInCode = strpos($subject->subjectCode, 'F') !== false;
                $noHInCode = strpos($subject->subjectCode, 'H') === false;
                return ($hasFoundationInName || ($hasFInCode && $noHInCode));
            });
            
            // Prefer Paper 1, otherwise take first
            $foundationSub = $foundationSubs->filter(function ($subject) {
                return stripos($subject->subjectName, 'Paper 1') !== false || (strpos($subject->subjectCode, '1') !== false && strpos($subject->subjectCode, 'F') !== false);
            })->first();
            
            if (!$foundationSub) {
                $foundationSub = $foundationSubs->first();
            }
            
            // Add only one Foundation and one Higher (using subject name as key ensures no duplicates)
            if ($foundationSub) {
                $pearsonGcseSubjects[$foundationSub->subjectName] = $foundationSub->subjectCode;
            }
            if ($higherSub) {
                $pearsonGcseSubjects[$higherSub->subjectName] = $higherSub->subjectCode;
            }
        }

        // Reorder subjects: For Chemistry and Physics, place Foundation and Higher together (Foundation first, then Higher)
        $chemistryPhysicsItems = [];
        $otherItems = [];

        foreach ($pearsonGcseSubjects as $subjectName => $subjectCode) {
            $codeDelimiter = strpos($subjectCode, '/') !== false ? '/' : ' ';
            $parts = explode($codeDelimiter, $subjectCode);
            $extractedCode = $parts[0] ?? '';
            
            if (in_array($extractedCode, $targetCodes)) {
                $chemistryPhysicsItems[$subjectName] = $subjectCode;
            } else {
                $otherItems[$subjectName] = $subjectCode;
            }
        }

        // Sort Chemistry/Physics items: Foundation first, then Higher (grouped by subject code)
        $sortedChemistryPhysics = [];
        foreach ($targetCodes as $code) {
            $foundationItems = [];
            $higherItems = [];
            
            foreach ($chemistryPhysicsItems as $subjectName => $subjectCode) {
                $codeDelimiter = strpos($subjectCode, '/') !== false ? '/' : ' ';
                $parts = explode($codeDelimiter, $subjectCode);
                $extractedCode = $parts[0] ?? '';
                
                if ($extractedCode === $code) {
                    if (stripos($subjectName, 'Foundation') !== false || (strpos($subjectCode, 'F') !== false && strpos($subjectCode, 'H') === false)) {
                        $foundationItems[$subjectName] = $subjectCode;
                    } else {
                        $higherItems[$subjectName] = $subjectCode;
                    }
                }
            }
            
            // Add Foundation first, then Higher for this subject (only one of each)
            if (!empty($foundationItems)) {
                $sortedChemistryPhysics = array_merge($sortedChemistryPhysics, [array_key_first($foundationItems) => current($foundationItems)]);
            }
            if (!empty($higherItems)) {
                $sortedChemistryPhysics = array_merge($sortedChemistryPhysics, [array_key_first($higherItems) => current($higherItems)]);
            }
        }

        // Rebuild: other items first, then sorted Chemistry/Physics items
        $pearsonGcseSubjects = array_merge($otherItems, $sortedChemistryPhysics);

        return $data;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ONE-TIME: Migrate all student IDs from 0001-format to 1001-format
    // Route: GET /migrate-student-ids
    // Hit this route ONCE. After that, new approvals will auto-start from 1001.
    // ─────────────────────────────────────────────────────────────────────────
    public function migrateStudentIds()
    {
        // Safety: only run if IDs still need migration (any ID < 1000 exists)
        $alreadyMigrated = DB::table('student_details')
            ->whereRaw('CAST(student_id AS UNSIGNED) >= 1000')
            ->exists();

        if ($alreadyMigrated) {
            return response(
                "⚠️  Migration already done (IDs already >= 1000). No changes made.",
                200,
                ['Content-Type' => 'text/plain; charset=UTF-8']
            );
        }

        $log = [];

        try {
            DB::transaction(function () use (&$log) {

                // 1. student_details
                $count = DB::table('student_details')
                    ->whereRaw('CAST(student_id AS UNSIGNED) < 1000')
                    ->update([
                        'student_id' => DB::raw("LPAD(CAST(student_id AS UNSIGNED) + 1000, 4, '0')")
                    ]);
                $log[] = "student_details: {$count} rows updated";

                // 2. payments
                $count = DB::table('payments')
                    ->whereRaw('CAST(student_id AS UNSIGNED) < 1000')
                    ->update([
                        'student_id' => DB::raw("LPAD(CAST(student_id AS UNSIGNED) + 1000, 4, '0')")
                    ]);
                $log[] = "payments: {$count} rows updated";

                // 3. email_logs
                $count = DB::table('email_logs')
                    ->whereNotNull('student_id')
                    ->whereRaw('CAST(student_id AS UNSIGNED) < 1000')
                    ->update([
                        'student_id' => DB::raw("LPAD(CAST(student_id AS UNSIGNED) + 1000, 4, '0')")
                    ]);
                $log[] = "email_logs: {$count} rows updated";

                // 4. migrated_student_record
                $count = DB::table('migrated_student_record')
                    ->whereNotNull('student_id')
                    ->whereRaw('CAST(student_id AS UNSIGNED) < 1000')
                    ->update([
                        'student_id' => DB::raw("LPAD(CAST(student_id AS UNSIGNED) + 1000, 4, '0')")
                    ]);
                $log[] = "migrated_student_record: {$count} rows updated";

                // 5. candidate_and_uci_numbers  (stored as candidate_id)
                $count = DB::table('candidate_and_uci_numbers')
                    ->whereRaw('CAST(candidate_id AS UNSIGNED) < 1000')
                    ->update([
                        'candidate_id' => DB::raw("LPAD(CAST(candidate_id AS UNSIGNED) + 1000, 4, '0')")
                    ]);
                $log[] = "candidate_and_uci_numbers: {$count} rows updated";

                // 6. results  (stored as candidate_id)
                $count = DB::table('results')
                    ->whereNotNull('candidate_id')
                    ->whereRaw('CAST(candidate_id AS UNSIGNED) < 1000')
                    ->update([
                        'candidate_id' => DB::raw("LPAD(CAST(candidate_id AS UNSIGNED) + 1000, 4, '0')")
                    ]);
                $log[] = "results: {$count} rows updated";
            });

            $body = "✅ Migration completed successfully!\n\n" . implode("\n", $log);

        } catch (\Throwable $e) {
            $body = "❌ Migration FAILED — all changes rolled back.\n\nError: " . $e->getMessage();
        }

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    // ── Admin Operations ─────────────────────────────────────────────────────
    public function viewOfferedSubjects(Request $request)    {
        // Populate all dropdowns directly from offered_subjects table
        // so dropdown values always exactly match the stored data
        $sessions       = OfferedSubject::select('session')->distinct()
                            ->whereNotNull('session')
                            ->orderBy('session')
                            ->pluck('session');

        $boards         = OfferedSubject::select('examboard')->distinct()
                            ->orderBy('examboard')
                            ->pluck('examboard');

        $qualifications = OfferedSubject::select('qualification')->distinct()
                            ->orderBy('qualification')
                            ->pluck('qualification');

        // Build board → qualifications mapping for JS dependent dropdown
        $boardQualMap = OfferedSubject::select('examboard', 'qualification')
                            ->distinct()
                            ->orderBy('examboard')
                            ->orderBy('qualification')
                            ->get()
                            ->groupBy('examboard')
                            ->map(fn($rows) => $rows->pluck('qualification')->values());

        // Build session → boards mapping for JS dependent dropdown
        $sessionBoardMap = OfferedSubject::select('session', 'examboard')
                            ->distinct()
                            ->whereNotNull('session')
                            ->orderBy('session')
                            ->orderBy('examboard')
                            ->get()
                            ->groupBy('session')
                            ->map(fn($rows) => $rows->pluck('examboard')->values());

        // Build session → board → qualifications mapping for full cascade
        $sessionBoardQualMap = OfferedSubject::select('session', 'examboard', 'qualification')
                            ->distinct()
                            ->whereNotNull('session')
                            ->orderBy('session')
                            ->orderBy('examboard')
                            ->orderBy('qualification')
                            ->get()
                            ->groupBy('session')
                            ->map(fn($sessionRows) =>
                                $sessionRows->groupBy('examboard')
                                    ->map(fn($boardRows) => $boardRows->pluck('qualification')->values())
                            );

        $selectedSession  = $request->query('session', '');
        $selectedBoard    = $request->query('board', '');
        $selectedQual     = $request->query('qualification', '');

        $query = OfferedSubject::query();

        if ($selectedSession !== '')    $query->where('session',       $selectedSession);
        if ($selectedBoard   !== '')    $query->where('examboard',     $selectedBoard);
        if ($selectedQual    !== '')    $query->where('qualification', $selectedQual);

        $subjects = $query->orderBy('examboard')->orderBy('qualification')->orderBy('description')->get();

        return view('admin.offeredSubjects', compact(
            'sessions', 'boards', 'qualifications', 'boardQualMap',
            'sessionBoardMap', 'sessionBoardQualMap',
            'subjects', 'selectedSession', 'selectedBoard', 'selectedQual'
        ));
    }

    public function updateOfferedSubjectAmount(Request $request, $id)
    {
        $request->validate(['amount' => 'nullable|numeric|min:0']);

        $subject = OfferedSubject::findOrFail($id);
        $subject->amount = $request->input('amount') !== '' ? $request->input('amount') : null;
        $subject->save();

        return response()->json(['success' => true, 'amount' => $subject->amount]);
    }

    public function storeOfferedSubject(Request $request)
    {
        $data = $request->validate([
            'examboard'     => 'required|string|max:100',
            'qualification' => 'required|string|max:100',
            'code'          => 'required|string|max:50',
            'description'   => 'required|string|max:255',
            'session'       => 'nullable|string|max:100',
            'amount'        => 'nullable|numeric|min:0',
        ]);

        $subject = OfferedSubject::create($data);

        return response()->json(['success' => true, 'subject' => $subject]);
    }

    public function updateOfferedSubject(Request $request, $id)
    {
        $data = $request->validate([
            'examboard'     => 'required|string|max:100',
            'qualification' => 'required|string|max:100',
            'code'          => 'required|string|max:50',
            'description'   => 'required|string|max:255',
            'session'       => 'nullable|string|max:100',
            'amount'        => 'nullable|numeric|min:0',
        ]);

        $subject = OfferedSubject::findOrFail($id);
        $subject->update($data);

        return response()->json(['success' => true, 'subject' => $subject]);
    }

    public function destroyOfferedSubject($id)
    {
        $subject = OfferedSubject::findOrFail($id);
        $subject->delete();

        return response()->json(['success' => true]);
    }
}
