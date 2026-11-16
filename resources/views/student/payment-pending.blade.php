<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment received - Frobel Education</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f5f7fb;color:#263247;margin:0;padding:35px 15px}
        .card{max-width:620px;margin:40px auto;background:#fff;padding:32px;border-radius:12px;box-shadow:0 8px 30px #172b4d14}
        h1{font-size:25px;margin-top:0;color:#203b69}.ref{font-weight:700;color:#e97816}
        p{line-height:1.65;color:#596579}
    </style>
</head>
<body>
<div class="card">
    <h1>We are verifying your payment</h1>
    <p>Your booking reference is <span class="ref">{{ $booking->booking_reference }}</span>.</p>
    <p>Stripe has returned you to Frobel Education. Your booking will show as <strong>PAID — AWAITING REVIEW</strong> only after our server verifies Stripe's signed payment notification.</p>
    <p>Payment received does not mean your exam entry is confirmed. The Exams Team will review your details, exam availability and any required components.</p>
</div>
</body>
</html>
