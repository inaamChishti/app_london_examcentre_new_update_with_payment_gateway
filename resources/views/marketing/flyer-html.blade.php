<style>
.flyer-wrap, .flyer-wrap * { box-sizing: border-box; font-family: 'Poppins', Arial, sans-serif; }
.flyer-card { width: 100%; max-width: 880px; background: #fff; box-shadow: 0 10px 40px rgba(0,0,0,.13); overflow: hidden; border: 1px solid #e5e7eb; }
.f-row1 { display: grid; grid-template-columns: 7fr 5fr; gap: 1rem; padding: 2rem 2rem 0; }
.f-logo { height: 80px; width: auto; object-fit: contain; display: block; margin-bottom: 1rem; max-width: 300px; }
.f-h-sub  { font-size: 1.5rem; font-weight: 600; color: #002147; text-transform: uppercase; line-height: 1; }
.f-h-main { font-size: 5rem; font-weight: 700; color: #002147; letter-spacing: -.03em; text-transform: uppercase; line-height: 1; margin: .2rem 0; }
.f-h-gold { font-size: 2rem; font-weight: 700; color: #E5A93C; text-transform: uppercase; letter-spacing: .05em; line-height: 1; margin-bottom: 1rem; }
.f-badge { background: #002147; color: #fff; font-size: .85rem; font-weight: 700; padding: .45rem 1.4rem; border-radius: 6px; display: inline-block; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 1rem; }
.f-tagline { color: #1f2937; font-style: italic; font-weight: 600; font-size: .92rem; margin-top: .4rem; }
.f-img-wrap { position: relative; min-height: 380px; background: #fff; overflow: hidden; }
.f-img-wrap img { width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; clip-path: polygon(12% 0, 100% 0, 100% 100%, 0 100%); }
.f-row2 { display: grid; grid-template-columns: 4fr 5fr 3fr; gap: .75rem; padding: .75rem 2rem 0; }
.f-price { background: #002147; color: #fff; border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
.f-price .only  { font-size: 1.4rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #d1d5db; }
.f-price .amt   { font-size: 7rem; font-weight: 900; color: #E5A93C; line-height: 1; margin: .25rem 0; }
.f-price .week  { font-size: 1.4rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #fff; }
.f-incl { background: rgba(251,191,36,.1); border-radius: 12px; padding: 1rem; border: 1px solid #fde68a; }
.f-incl-title { background: #E5A93C; color: #002147; font-weight: 900; text-align: center; padding: .25rem .5rem; border-radius: 4px; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .75rem; font-size: .68rem; }
.f-incl ul { list-style: none; display: flex; flex-direction: column; gap: .4rem; }
.f-incl li { display: flex; align-items: flex-start; font-size: .68rem; font-weight: 700; color: #002147; }
.f-incl li .tick { color: #E5A93C; margin-right: .4rem; flex-shrink: 0; }
.f-icons { display: flex; flex-direction: column; gap: .4rem; }
.f-icon-card { display: flex; align-items: center; gap: .5rem; background: #fff; padding: .5rem .6rem; border-radius: 4px; border: 1px solid #f3f4f6; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.f-icon-circle { background: #002147; color: #E5A93C; border-radius: 50%; font-size: .75rem; width: 32px; height: 32px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
.f-icon-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; color: #002147; line-height: 1.3; }
.f-icon-label span { color: #E5A93C; display: block; font-weight: 700; }
.f-row3 { line-height: 0; overflow: hidden; }
.f-row3 img { width: 100%; display: block; object-fit: cover; }
.f-row4 { display: grid; grid-template-columns: 7fr 5fr; gap: .5rem; padding: 0 1rem .5rem; align-items: stretch; }
.f-photos { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .5rem; }
.f-photo { border-radius: 6px; min-height: 160px; overflow: hidden; border: 1px solid #d1d5db; }
.f-photo img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 6px; }
.f-register { background: #002147; color: #fff; padding: 1rem 1rem 1rem 2rem; display: flex; flex-direction: column; justify-content: center; clip-path: polygon(8% 0, 100% 0, 100% 100%, 0 100%); border-left: 4px solid #E5A93C; text-decoration: none; }
.f-reg-top { display: flex; justify-content: space-between; align-items: flex-start; }
.f-reg-cal { border: 2px solid #E5A93C; border-radius: 50%; color: #E5A93C; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
.f-limited { background: #E5A93C; color: #002147; font-weight: 900; text-align: center; font-size: .68rem; padding: .4rem .75rem; border-radius: 4px; text-transform: uppercase; letter-spacing: .1em; margin-top: .5rem; }
.f-notice { margin: .75rem 1rem; background: #F2F4F7; padding: 1rem; display: flex; align-items: center; gap: 1rem; border-radius: 6px; }
.f-notice-icon { color: #E5A93C; font-size: 2.25rem; flex-shrink: 0; padding-left: .5rem; }
.f-notice h5 { font-size: .68rem; font-weight: 900; color: #002147; text-transform: uppercase; letter-spacing: .08em; margin-bottom: .2rem; }
.f-notice p  { font-size: .68rem; color: #374151; font-weight: 700; }
.f-footer { background: #002147; color: #fff; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255,255,255,.1); gap: .75rem; }
.f-footer-div { width: 1.5px; height: 2rem; background: rgba(255,255,255,.3); flex-shrink: 0; }
.f-footer-contact { display: flex; gap: 2rem; flex-grow: 1; font-size: .68rem; font-weight: 600; color: rgba(255,255,255,.9); }
.f-footer-col { display: flex; flex-direction: column; gap: .4rem; }
.f-footer-col a { color: inherit; text-decoration: none; }
.f-footer-col .dim { color: #d1d5db; font-size: .65rem; line-height: 1.3; }
.f-fi { color: #E5A93C; width: 14px; text-align: center; margin-right: .4rem; }
.f-qr { border: 2px solid #E5A93C; padding: 4px; background: #fff; border-radius: 6px; width: 64px; height: 64px; flex-shrink: 0; overflow: hidden; }
.f-qr img { width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px; }
</style>

<div class="flyer-wrap">
<div class="flyer-card">

    <!-- ROW 1 -->
    <div class="f-row1">
        <div>
            <img src="{{ asset('images/LondonExamCentre.png') }}" alt="London Exam Centre Logo" class="f-logo" onerror="this.style.display='none'">
            <p class="f-h-sub">GCSE Maths &amp; English</p>
            <h1 class="f-h-main">Resit</h1>
            <h2 class="f-h-gold">Crash Course</h2>
            <div class="f-badge">November 2026 Exam Series</div>
            <p class="f-tagline">
                <span style="color:#0056B3;font-weight:900;">Boost</span> Your Grades.
                <span style="color:#002147;font-weight:900;">Build</span> Confidence.
                <span style="color:#002147;font-weight:900;">Pass</span> Your Resits.
            </p>
        </div>
        <div class="f-img-wrap">
            <img src="https://examcentre.efrobel.com/crash_course/Top iamge 1.jpg" alt="Student Studying">
        </div>
    </div>

    <!-- ROW 2 -->
    <div class="f-row2">
        <div class="f-price">
            <p class="only">Only</p>
            <p class="amt">£40</p>
            <p class="week">Per Week</p>
        </div>
        <div class="f-incl">
            <div class="f-incl-title">What's Included?</div>
            <ul>
                <li><span class="tick">✔</span>Weekly GCSE Maths &amp; English Tuition</li>
                <li><span class="tick">✔</span>Free Worksheets</li>
                <li><span class="tick">✔</span>Free Past Papers</li>
                <li><span class="tick">✔</span>Free Full Mock Exam Under Real Exam Conditions</li>
                <li><span class="tick">✔</span>Free November 2026 GCSE Resit Exam Entry</li>
                <li><span class="tick">✔</span>Experienced Tutors</li>
                <li><span class="tick">✔</span>Structured Revision Programme</li>
                <li><span class="tick">✔</span>Exam Techniques &amp; Time Management Support</li>
            </ul>
        </div>
        <div class="f-icons">
            <div class="f-icon-card"><div class="f-icon-circle"><i class="fa-solid fa-file-lines"></i></div><div class="f-icon-label"><span>FREE</span>Worksheets</div></div>
            <div class="f-icon-card"><div class="f-icon-circle"><i class="fa-solid fa-book-open"></i></div><div class="f-icon-label"><span>FREE</span>Past Papers</div></div>
            <div class="f-icon-card"><div class="f-icon-circle"><i class="fa-solid fa-clipboard-list"></i></div><div class="f-icon-label"><span>FREE</span>Mock Exam</div></div>
            <div class="f-icon-card"><div class="f-icon-circle"><i class="fa-solid fa-graduation-cap"></i></div><div class="f-icon-label"><span>FREE</span>Exam Entry</div></div>
        </div>
    </div>

    <!-- ROW 3 : Full width image -->
    <div class="f-row3">
        <img src="https://examcentre.efrobel.com/crash_course/Image 3.jpg" alt="Students Group">
    </div>

    <!-- ROW 4 -->
    <div class="f-row4">
        <div class="f-photos">
            <div class="f-photo"><img src="https://examcentre.efrobel.com/crash_course/Top image 2.png" alt="Boy Writing"></div>
            <div class="f-photo"><img src="https://examcentre.efrobel.com/crash_course/image 4.png" alt="Classroom"></div>
            <div class="f-photo"><img src="https://examcentre.efrobel.com/crash_course/image 5.png" alt="Girl Laptop"></div>
        </div>
        <a href="https://londonexamcentre.co.uk/register" target="_blank" class="f-register" style="text-decoration:none;">
            <div class="f-reg-top">
                <div>
                    <h3 style="font-size:1.25rem;font-weight:900;text-transform:uppercase;letter-spacing:.05em;line-height:1;color:#fff;">Register</h3>
                    <h1 style="color:#E5A93C;font-size:3rem;font-weight:900;text-transform:uppercase;letter-spacing:-.02em;line-height:1.1;margin:.25rem 0;">Today</h1>
                </div>
                <div class="f-reg-cal"><i class="fa-regular fa-calendar-days"></i></div>
            </div>
            <div class="f-limited">Limited Spaces Available</div>
        </a>
    </div>

    <!-- Notice -->
    <div class="f-notice">
        <div class="f-notice-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div>
            <h5>Important Notice</h5>
            <p>Candidate must remain enrolled on the course until the November 2026 examination series to qualify for the free exam entry.</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="f-footer">
        <div style="flex-shrink:0;">
            <h4 style="color:#E5A93C;font-weight:800;font-size:.88rem;letter-spacing:.05em;text-transform:uppercase;white-space:nowrap;">CONTACT US TODAY</h4>
        </div>
        <div class="f-footer-div"></div>
        <div class="f-footer-contact">
            <div class="f-footer-col">
                <div><i class="fa-solid fa-phone f-fi"></i>+44 7538 555089</div>
                <div><i class="fa-regular fa-envelope f-fi"></i>exams@londonexamcentre.co.uk</div>
            </div>
            <div class="f-footer-col">
                <div style="display:flex;align-items:flex-start;">
                    <i class="fa-solid fa-location-dot f-fi" style="margin-top:2px;"></i>
                    <span class="dim">140 Paul Street Stratford,<br>London – E15 4FF</span>
                </div>
            </div>
        </div>
        <div class="f-footer-div"></div>
        <div class="f-qr">
            <img src="https://examcentre.efrobel.com/crash_course/QR.jpg" alt="QR Code">
        </div>
    </div>

</div><!-- /flyer-card -->
</div><!-- /flyer-wrap -->
