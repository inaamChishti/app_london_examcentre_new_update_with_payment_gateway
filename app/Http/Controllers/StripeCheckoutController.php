<?php

namespace App\Http\Controllers;

use App\Models\ExamBooking;
use App\Models\OfferedSubject;
use App\Models\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StripeCheckoutController extends Controller
{
    public function start(int $studentRequestId, Request $request)
    {
        $student = StudentRequest::findOrFail($studentRequestId);

        if ($student->access_arrangement) {
            return response()->json([
                'manual_review' => true,
                'message' => 'Your request has been saved. The Exams Team will contact you about Access Arrangements before payment.'
            ]);
        }

        if (!config('services.stripe.secret')) {
            return response()->json([
                'message' => 'Online payment is not configured yet. Your registration has been saved; please contact the Exams Team.'
            ], 422);
        }

        $boards = (array) $student->exam_board;
        $qualifications = (array) $student->course_type;
        $subjects = (array) $student->subject_name;
        $codes = (array) $student->subject_code;
        $tiers = (array) $student->tire;
        $neas = (array) $student->nea;

        $count = count($codes);
        if (
            !$count ||
            count($boards) !== $count ||
            count($qualifications) !== $count ||
            count($subjects) !== $count ||
            count($tiers) !== $count ||
            count($neas) !== $count
        ) {
            return response()->json(['message' => 'Your exam basket is incomplete. Please check your selections and try again.'], 422);
        }

        $lineItems = [];
        $totalPence = 0;
        $seen = [];

        for ($i = 0; $i < $count; $i++) {
            $key = implode('|', [
                $student->exam_session,
                $boards[$i],
                $qualifications[$i],
                $subjects[$i],
                $codes[$i],
            ]);
            if (isset($seen[$key])) {
                return response()->json(['message' => 'Duplicate exam selections are not allowed.'], 422);
            }
            $seen[$key] = true;

            if (!in_array($tiers[$i], ['N/A', 'Foundation Tier', 'Higher Tier'], true)) {
                return response()->json(['message' => 'An exam has an invalid tier selection. Please review your basket.'], 422);
            }

            if (!in_array($neas[$i], ['N/A', 'Carry Forward', "Frobel's NEA"], true)) {
                return response()->json(['message' => 'An exam has an invalid NEA selection. Please review your basket.'], 422);
            }

            $offering = OfferedSubject::query()
                ->where('session', $student->exam_session)
                ->where('examboard', $boards[$i])
                ->where('qualification', $qualifications[$i])
                ->where('code', $codes[$i])
                ->where('description', $subjects[$i])
                ->first();

            if (!$offering || !is_numeric($offering->amount) || (float) $offering->amount <= 0) {
                return response()->json(['message' => 'A selected exam is unavailable or does not have an approved price. Please contact the Exams Team.'], 422);
            }

            if (($neas[$i] ?? 'N/A') === "Frobel's NEA") {
                return response()->json(['message' => 'This basket includes an NEA item requiring a separately agreed fee. The Exams Team will contact you before payment.'], 422);
            }

            $pence = (int) round(((float) $offering->amount) * 100);
            $totalPence += $pence;
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower(config('services.stripe.currency', 'gbp')),
                    'product_data' => [
                        'name' => $boards[$i] . ' — ' . $qualifications[$i] . ' — ' . $subjects[$i] . ' (' . $codes[$i] . ')',
                    ],
                    'unit_amount' => $pence,
                ],
                'quantity' => 1,
            ];
        }

        if ($totalPence < 1) {
            return response()->json(['message' => 'The checkout total is invalid.'], 422);
        }

        $booking = ExamBooking::create([
            'student_request_id' => $student->id,
            'booking_reference' => 'EXM-' . strtoupper(Str::random(10)),
            'amount_pence' => $totalPence,
            'currency' => strtolower(config('services.stripe.currency', 'gbp')),
            'payment_status' => 'pending',
            'metadata' => ['exam_session' => $student->exam_session],
        ]);

        $payload = [
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', ['reference' => $booking->booking_reference]),
            'customer_email' => $student->email,
            'billing_address_collection' => 'required',
            'client_reference_id' => $booking->booking_reference,
            'metadata[booking_reference]' => $booking->booking_reference,
            'payment_intent_data[metadata][booking_reference]' => $booking->booking_reference,
            // Explicitly offer cards and Klarna. Stripe still checks merchant,
            // customer country, currency and transaction eligibility at checkout.
            'payment_method_types[0]' => 'card',
        ];

        if (config('services.stripe.klarna_enabled', true)) {
            $payload['payment_method_types[1]'] = 'klarna';
        }

        foreach ($lineItems as $index => $item) {
            $payload["line_items[{$index}][price_data][currency]"] = $item['price_data']['currency'];
            $payload["line_items[{$index}][price_data][product_data][name]"] = $item['price_data']['product_data']['name'];
            $payload["line_items[{$index}][price_data][unit_amount]"] = $item['price_data']['unit_amount'];
            $payload["line_items[{$index}][quantity]"] = 1;
        }

        // Payment methods are selected dynamically by Stripe Dashboard settings.
        // Stripe filters Klarna based on merchant, buyer, currency and order eligibility.

        try {
            $response = Http::withToken(config('services.stripe.secret'))
                ->asForm()
                ->post('https://api.stripe.com/v1/checkout/sessions', $payload);

            if (!$response->successful() || !$response->json('url')) {
                Log::error('Stripe Checkout session creation failed', [
                    'booking_reference' => $booking->booking_reference,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                $booking->update(['payment_status' => 'checkout_failed']);
                return response()->json(['message' => 'We could not start secure checkout. No payment has been taken. Please try again or contact the Exams Team.'], 502);
            }

            $booking->update(['stripe_checkout_session_id' => $response->json('id')]);

            return response()->json([
                'checkout_url' => $response->json('url'),
                'booking_reference' => $booking->booking_reference,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Stripe Checkout exception: ' . $exception->getMessage());
            $booking->update(['payment_status' => 'checkout_failed']);
            return response()->json(['message' => 'Secure checkout is temporarily unavailable. No payment has been taken.'], 502);
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('public.page')->with('error', 'Missing payment session reference.');
        }

        $booking = ExamBooking::where('stripe_checkout_session_id', $sessionId)->first();
        if (!$booking) {
            return redirect()->route('public.page')->with('error', 'We could not find this booking. Please contact the Exams Team.');
        }

        // The success redirect is not proof of payment. The signed webhook changes the status.
        return response()->view('student.payment-pending', compact('booking'));
    }

    public function cancel(string $reference)
    {
        $booking = ExamBooking::where('booking_reference', $reference)->firstOrFail();
        if ($booking->payment_status === 'pending') {
            $booking->update(['payment_status' => 'cancelled']);
        }

        return redirect()->route('public.page')->with('error',
            'Checkout was cancelled. Your registration details have been saved, but payment was not completed.');
    }

    public function webhook(Request $request)
    {
        $secret = config('services.stripe.webhook_secret');
        $signature = $request->header('Stripe-Signature');
        $rawBody = $request->getContent();

        if (!$secret || !$signature || !$this->validSignature($rawBody, $signature, $secret)) {
            return response('Invalid Stripe signature', 400);
        }

        $event = json_decode($rawBody, true);
        if (!is_array($event)) {
            return response('Invalid payload', 400);
        }

        if (in_array(($event['type'] ?? ''), ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
            $session = $event['data']['object'] ?? [];
            $reference = $session['metadata']['booking_reference'] ?? $session['client_reference_id'] ?? null;

            if ($reference && ($session['payment_status'] ?? '') === 'paid') {
                $booking = ExamBooking::where('booking_reference', $reference)->first();
                if ($booking && $booking->payment_status !== 'paid_awaiting_review') {
                    $expectedAmount = (int) $booking->amount_pence;
                    if ((int) ($session['amount_total'] ?? 0) !== $expectedAmount ||
                        strtolower($session['currency'] ?? '') !== strtolower($booking->currency)) {
                        Log::critical('Stripe amount/currency mismatch for booking ' . $reference);
                        return response('Amount mismatch', 400);
                    }

                    $booking->update([
                        'payment_status' => 'paid_awaiting_review',
                        'stripe_payment_intent_id' => $session['payment_intent'] ?? null,
                        'payment_method' => implode(',', $session['payment_method_types'] ?? ['stripe']),
                        'paid_at' => now(),
                    ]);

                    try {
                        $student = $booking->studentRequest;
                        $subject = 'Frobel exam booking payment received - ' . $booking->booking_reference;
                        $message = "Dear {$student->first_name} {$student->surname},\n\n"
                            . "We have received your payment for exam booking {$booking->booking_reference}.\n"
                            . "Amount: " . number_format($booking->amount_pence / 100, 2) . ' ' . strtoupper($booking->currency) . "\n\n"
                            . "Your booking is now PAID — AWAITING REVIEW. Payment does not mean your exam entry is confirmed. "
                            . "The Exams Team will check your details, availability and exam components before confirming entry.\n\n"
                            . "Frobel Education Exams Team";
                        \Illuminate\Support\Facades\Mail::raw($message, function ($mail) use ($student, $subject) {
                            $mail->to($student->email)->subject($subject);
                        });
                    } catch (\Throwable $mailException) {
                        Log::error('Paid booking email failed: ' . $mailException->getMessage());
                    }
                }
            }
        }

        return response('ok', 200);
    }

    private function validSignature(string $payload, string $header, string $secret): bool
    {
        $timestamp = null;
        $signatures = [];
        foreach (explode(',', $header) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, null);
            if ($key === 't') $timestamp = $value;
            if ($key === 'v1') $signatures[] = $value;
        }

        if (!$timestamp || abs(time() - (int) $timestamp) > 300) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
        foreach ($signatures as $signature) {
            if (is_string($signature) && hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }
}
