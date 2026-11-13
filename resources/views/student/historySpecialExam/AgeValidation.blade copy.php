<script>
   $(document).ready(function() {
    const dobMessage = $(
        '<div id="dobMessage" style="margin-top: 8px; font-size: 14px; color: #721c24;"></div>');
    $('#dob').after(dobMessage);

    function calculateAge(dob, targetDate) {
        const dobDate = new Date(dob);
        let age = targetDate.getFullYear() - dobDate.getFullYear();
        const monthDiff = targetDate.getMonth() - dobDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && targetDate.getDate() < dobDate.getDate())) {
            age--;
        }
        return age;
    }

    function toggleFormFields(disable) {
        $('#examForm :input').not('#session, #dob').prop('disabled', disable);
        $('.add-row-button, #addDoc, .btn-success').prop('disabled', disable);
        $('.appendSec :input').prop('disabled', disable);
        $('#additionalDocsContainer :input').prop('disabled', disable);
    }

    function validateDOB() {
        const session = $('#session').val();
        const dob = $('#dob').val();
        const currentYear = new Date().getFullYear();
        dobMessage.text('');

        if (session && session.includes('Autumn')) {
            if (dob) {
                const targetDate = new Date(currentYear, 7, 30);
                const age = calculateAge(dob, targetDate);
                if (age < 16) {
                    toggleFormFields(true);
                    dobMessage.css('color', '#721c24');
                    dobMessage.text(
                        `Candidate must be at least 16 years old on or before August 30th, ${currentYear}. Current age: ${age}.`
                    );
                } else {
                    toggleFormFields(false);
                    dobMessage.css('color', '#155724');
                    dobMessage.text(`Age is valid: ${age} years on August 30th, ${currentYear}.`);
                }
            } else {
                toggleFormFields(false); // Keep fields enabled if DOB is empty
                dobMessage.css('color', '#721c24');
                dobMessage.text('Please select a date of birth.');
            }
        } else {
            toggleFormFields(false);
            dobMessage.text('');
        }
    }

    $('#session').on('change', validateDOB);
    $('#dob').on('change', validateDOB);
    validateDOB();
});
</script>
