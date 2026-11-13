<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GCSE Crash Course Flyer – London Exam Centre</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px 48px;
        }

        /* ── Top toolbar ── */
        .toolbar {
            width: 100%;
            max-width: 880px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .toolbar h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #002147;
        }
        .btn-send {
            background: #002147;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 7px;
            font-family: 'Poppins', sans-serif;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background .2s;
        }
        .btn-send:hover { background: #003580; }

        /* ── Alert banners ── */
        .alert {
            width: 100%;
            max-width: 880px;
            padding: 12px 16px;
            border-radius: 7px;
            margin-bottom: 14px;
            font-size: .85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── Flyer wrapper ── */
        .flyer-card {
            width: 100%;
            max-width: 880px;
            background: #fff;
            box-shadow: 0 10px 40px rgba(0,0,0,.13);
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        /* colours */
        .bg-navy  { background-color: #002147; }
        .bg-gold  { background-color: #E5A93C; }
        .text-navy { color: #002147; }
        .text-gold { color: #E5A93C; }

        /* clip shapes */
        .clip-slanted-right { clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%); }
        .clip-slanted-left  { clip-path: polygon(15% 0, 100% 0, 100% 100%, 0 100%); }
        .clip-register      { clip-path: polygon(8% 0,  100% 0, 100% 100%, 0 100%); }

        /* ── ROW 1 ── */
        .row1 {
            display: grid;
            grid-template-columns: 7fr 5fr;
            gap: 1rem;
            padding: 2rem 2rem .5rem;
        }
        .logo-box {
            width: 300px; height: 80px;
            background: transparent;
            border-radius: 0;
            border: none;
            display: flex; align-items: center; justify-content: flex-start;
            position: relative; overflow: hidden;
            margin-bottom: 1rem;
        }
        .logo-box img {
            height: 100%;
            width: auto;
            object-fit: contain;
            display: block;
        }
        .logo-box span {
            font-size: 10px; color: #9ca3af;
            font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        }
        .headline-sub  { font-size: 1.5rem;  font-weight: 600; color: #002147; text-transform: uppercase; line-height: 1; }
        .headline-main { font-size: 4rem;    font-weight: 700; color: #002147; letter-spacing: -.03em; text-transform: uppercase; line-height: 1; margin: .2rem 0; }
        .headline-gold { font-size: 2rem;    font-weight: 700; color: #E5A93C; text-transform: uppercase; letter-spacing: .05em; line-height: 1; margin-bottom: 1rem; }
        .session-badge {
            background: #002147; color: #fff;
            font-size: .85rem; font-weight: 700;
            padding: .45rem 1.4rem; border-radius: 6px;
            display: inline-block; text-transform: uppercase; letter-spacing: .08em;
            margin-bottom: 1rem;
        }
        .tagline { color: #1f2937; font-style: italic; font-weight: 600; font-size: .92rem; margin-top: .4rem; }
        .img-placeholder {
            position: relative; min-height: 380px;
            background: #e5e7eb;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; overflow: hidden; border: 1px solid #d1d5db;
        }
        .img-placeholder span { color: #9ca3af; font-weight: 700; font-size: .8rem; text-align: center; padding: 0 1rem; }
        .img-placeholder img  { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* ── ROW 2 ── */
        .row2 {
            display: grid;
            grid-template-columns: 4fr 5fr 3fr;
            gap: .75rem;
            padding: .75rem 2rem .5rem;
        }
        .price-box {
            background: #002147; color: #fff;
            border-radius: 12px; padding: 1.5rem;
            display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
        }
        .price-box .only  { font-size: .85rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #d1d5db; }
        .price-box .price { font-size: 3.75rem; font-weight: 900; color: #E5A93C; line-height: 1; margin: .25rem 0; }
        .price-box .week  { font-size: .85rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }

        .inclusions-box {
            background: rgba(251,191,36,.1);
            border-radius: 12px; padding: 1rem;
            border: 1px solid #fde68a;
        }
        .inclusions-title {
            background: #E5A93C; color: #002147;
            font-weight: 900; text-align: center;
            padding: .25rem .5rem; border-radius: 4px;
            text-transform: uppercase; letter-spacing: .05em;
            margin-bottom: .75rem; font-size: .68rem;
        }
        .inclusions-list { list-style: none; display: flex; flex-direction: column; gap: .4rem; }
        .inclusions-list li { display: flex; align-items: flex-start; font-size: .68rem; font-weight: 700; color: #002147; }
        .inclusions-list li .tick { color: #E5A93C; margin-right: .4rem; flex-shrink: 0; }

        .icon-cards { display: flex; flex-direction: column; gap: .4rem; }
        .icon-card {
            display: flex; align-items: center; gap: .5rem;
            background: #fff; padding: .5rem .6rem;
            border-radius: 4px; border: 1px solid #f3f4f6;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .icon-circle {
            background: #002147; color: #E5A93C;
            border-radius: 50%; font-size: .75rem;
            width: 32px; height: 32px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .icon-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; color: #002147; line-height: 1.3; }
        .icon-label span { color: #E5A93C; display: block; font-weight: 700; }

        /* ── ROW 3 : science banner ── */
        .row3 {
            display: grid;
            grid-template-columns: 1fr;
            background: #fff;
            margin-top: 0;
            position: relative;
            min-height: 120px;
            overflow: hidden;
            line-height: 0;
        }
        .science-overlay {
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 62%;
            background: linear-gradient(to right, rgba(0,33,71,0.98) 60%, rgba(0,33,71,0.75) 85%, transparent 100%);
            display: flex; align-items: center; gap: .75rem;
            padding: 1rem 4rem 1rem 1.25rem;
            z-index: 2;
            overflow: hidden;
        }
        .science-icon-circle {
            border: 2px solid #fff; border-radius: 50%;
            width: 48px; height: 48px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
            color: #fff;
        }
        .science-full-img {
            width: 100%;
            display: block;
            object-fit: cover;
            vertical-align: top;
        }

        /* ── ROW 4 ── */
        .row4 { display: grid; grid-template-columns: 7fr 5fr; gap: .5rem; padding: .5rem 1rem; margin-top: 0; align-items: stretch; }
        .photo-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .5rem; }
        .photo-ph {
            background: #e5e7eb; border-radius: 6px; min-height: 160px;
            display: flex; align-items: center; justify-content: center;
            font-size: .625rem; color: #9ca3af; font-weight: 700;
            text-align: center; padding: .5rem; border: 1px solid #d1d5db;
            overflow: hidden;
        }
        .photo-ph img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 6px; }
        .register-box {
            background: #002147; color: #fff;
            padding: 1rem 1rem 1rem 2rem;
            display: flex; flex-direction: column; justify-content: center;
            position: relative;
            clip-path: polygon(8% 0, 100% 0, 100% 100%, 0 100%);
            border-left: 4px solid #E5A93C;
        }
        .register-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .register-cal {
            border: 2px solid #E5A93C; border-radius: 50%; color: #E5A93C;
            width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        }
        .limited-badge {
            background: #E5A93C; color: #002147;
            font-weight: 900; text-align: center; font-size: .68rem;
            padding: .4rem .75rem; border-radius: 4px;
            text-transform: uppercase; letter-spacing: .1em; margin-top: .5rem;
        }

        /* ── Notice ── */
        .notice {
            margin: .75rem 1rem;
            background: #F2F4F7; padding: 1rem;
            display: flex; align-items: center; gap: 1rem; border-radius: 6px;
        }
        .notice-icon { color: #E5A93C; font-size: 2.25rem; flex-shrink: 0; padding-left: .5rem; }
        .notice h5 { font-size: .68rem; font-weight: 900; color: #002147; text-transform: uppercase; letter-spacing: .08em; margin-bottom: .2rem; }
        .notice p  { font-size: .68rem; color: #374151; font-weight: 700; letter-spacing: -.01em; }

        /* ── Footer ── */
        .footer-bar {
            background: #002147; color: #fff;
            padding: 1rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
            border-top: 1px solid rgba(255,255,255,.1);
            gap: .75rem;
        }
        .footer-divider { width: 1.5px; height: 2rem; background: rgba(255,255,255,.3); flex-shrink: 0; }
        .footer-contact { display: flex; gap: 2rem; flex-grow: 1; font-size: .68rem; font-weight: 600; color: rgba(255,255,255,.9); }
        .footer-col { display: flex; flex-direction: column; gap: .4rem; }
        .footer-col a { color: inherit; }
        .footer-col .dim { color: #d1d5db; font-size: .65rem; line-height: 1.3; }
        .fi { color: #E5A93C; width: 14px; text-align: center; margin-right: .4rem; }
        .qr-box {
            border: 2px solid #E5A93C; padding: 4px;
            background: #fff; border-radius: 6px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            width: 64px; height: 64px; flex-shrink: 0;
        }
        .qr-box span { font-size: .38rem; color: #000; font-weight: 900; text-transform: uppercase; letter-spacing: .05em; margin-top: 2px; }
    </style>
</head>
<body>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <h4><i class="fa-solid fa-file-image me-1" style="color:#002147;"></i> GCSE Crash Course Flyer</h4>
        <button class="btn-send" onclick="sendFlyerEmail()">
            <i class="fa-regular fa-envelope"></i> Send Flyer via Email
        </button>
    </div>

    {{-- ── Flash alerts ── --}}
    @if(session('flyer_success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i> {{ session('flyer_success') }}
    </div>
    @endif
    @if(session('flyer_error'))
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-xmark"></i> {{ session('flyer_error') }}
    </div>
    @endif

    {{-- ══════════════════ FLYER CARD ══════════════════ --}}
    <div class="flyer-card">

        {{-- ROW 1 : Headline + image --}}
        <div class="row1">
            <div>
                <div class="logo-box">
                    <img src="{{ asset('crash_course/Screenshot 2026-06-18 162406.jpg') }}" alt="Logo" onerror="this.style.display='none'">
                </div>
                <p class="headline-sub">GCSE Maths &amp; English</p>
                <h1 class="headline-main">Resit</h1>
                <h2 class="headline-gold">Crash Course</h2>
                <div class="session-badge">November 2026 Exam Series</div>
                <p class="tagline">
                    <span style="color:#0056B3;font-weight:900;">Boost</span> Your Grades.
                    <span style="color:#002147;font-weight:900;">Build</span> Confidence.
                    <span style="color:#002147;font-weight:900;">Pass</span> Your Resits.
                </p>
            </div>
            <div class="img-placeholder">
                <img src="{{ asset('crash_course/Top iamge 1.png') }}" alt="Top Student Studying">
                <div class="gold-left-border" style="position:absolute;inset:0;border-left:4px solid #E5A93C;pointer-events:none;"></div>
            </div>
        </div>

        {{-- ROW 2 : Price + Inclusions + Icons --}}
        <div class="row2">
            {{-- Price --}}
            <div class="price-box">
                <p class="only">Only</p>
                <p class="price">£40</p>
                <p class="week">Per Week</p>
            </div>

            {{-- What's included --}}
            <div class="inclusions-box">
                <div class="inclusions-title">What's Included?</div>
                <ul class="inclusions-list">
                    @foreach([
                        'Weekly GCSE Maths &amp; English Tuition',
                        'Free Worksheets',
                        'Free Past Papers',
                        'Free Full Mock Exam Under Real Exam Conditions',
                        'Free November 2026 GCSE Resit Exam Entry',
                        'Experienced Tutors',
                        'Structured Revision Programme',
                        'Exam Techniques &amp; Time Management Support',
                    ] as $item)
                    <li><span class="tick">✔</span>{!! $item !!}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Icon cards --}}
            <div class="icon-cards">
                @foreach([
                    ['fa-solid fa-file-lines',      'FREE', 'Worksheets'],
                    ['fa-solid fa-book-open',        'FREE', 'Past Papers'],
                    ['fa-solid fa-clipboard-list',   'FREE', 'Mock Exam'],
                    ['fa-solid fa-graduation-cap',   'FREE', 'Exam Entry'],
                ] as $ic)
                <div class="icon-card">
                    <div class="icon-circle"><i class="{{ $ic[0] }}"></i></div>
                    <div class="icon-label"><span>{{ $ic[1] }}</span>{{ $ic[2] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ROW 3 : iGCSE Science banner — full width image with text overlay --}}
        <div class="row3">
            {{-- Full-width image only --}}
            <img src="{{ asset('crash_course/Image 3.png') }}" alt="Students Group Studying" class="science-full-img">
        </div>

        {{-- ROW 4 : Photos + Register Today --}}
        <div class="row4">
            <div class="photo-grid">
                <div class="photo-ph"><img src="{{ asset('crash_course/Top image 2.png') }}" alt="Boy Writing"></div>
                <div class="photo-ph"><img src="{{ asset('crash_course/image 4.png') }}" alt="Classroom Studying"></div>
                <div class="photo-ph"><img src="{{ asset('crash_course/image 5.png') }}" alt="Girl with Headphone Laptop"></div>
            </div>
            <div class="register-box">
                <div class="register-top">
                    <div>
                        <h3 style="font-size:1.25rem;font-weight:900;text-transform:uppercase;letter-spacing:.05em;line-height:1;">Register</h3>
                        <h1 style="color:#E5A93C;font-size:3rem;font-weight:900;text-transform:uppercase;letter-spacing:-.02em;line-height:1.1;margin:.25rem 0;">Today</h1>
                    </div>
                    <div class="register-cal"><i class="fa-regular fa-calendar-days"></i></div>
                </div>
                <div class="limited-badge">Limited Spaces Available</div>
            </div>
        </div>

        {{-- Important Notice --}}
        <div class="notice">
            <div class="notice-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <h5>Important Notice</h5>
                <p>Candidate must remain enrolled on the course until the November 2026 examination series to qualify for the free exam entry.</p>
            </div>
        </div>

        {{-- Footer / Contact --}}
        <div class="footer-bar">
            <div style="flex-shrink:0;">
                <h4 style="color:#E5A93C;font-weight:800;font-size:.88rem;letter-spacing:.05em;text-transform:uppercase;white-space:nowrap;">CONTACT US TODAY</h4>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-contact">
                <div class="footer-col">
                    <div><i class="fa-solid fa-phone fi"></i>+44 7538 555089</div>
                    <div><i class="fa-regular fa-envelope fi"></i>exams@londonexamcentre.co.uk</div>
                </div>
                <div class="footer-col">
                    <div style="display:flex;align-items:flex-start;">
                        <i class="fa-solid fa-location-dot fi" style="margin-top:2px;"></i>
                        <span class="dim">140 Paul Street Stratford,<br>London – E15 4FF</span>
                    </div>
                </div>
            </div>
            <div class="footer-divider"></div>
            <div class="qr-box">
                <i class="fa-solid fa-qrcode" style="color:#000;font-size:1.5rem;"></i>
                <span>SCAN ME</span>
            </div>
        </div>

    </div>{{-- /flyer-card --}}

    {{-- ── Email Send Script ── --}}
    <script>
    function sendFlyerEmail() {
        Swal.fire({
            title: 'Send Flyer Email?',
            html: `Send the GCSE Crash Course flyer to <strong>inaam.chishti2@gmail.com</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#002147',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-regular fa-envelope"></i> Yes, Send It!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Sending…',
                text: 'Please wait while we send the email.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('marketing.flyer.send-email') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Sent!', text: data.message, confirmButtonColor: '#002147' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Something went wrong.', confirmButtonColor: '#002147' });
                }
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect to the server.', confirmButtonColor: '#002147' });
            });
        });
    }
    </script>

</body>
</html>
