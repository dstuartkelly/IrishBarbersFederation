/**
 * Form Handler JavaScript
 * Handles AJAX form submission for registration form
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const submitBtn = document.getElementById('submitBtn');
    const formMessage = document.getElementById('formMessage');

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Submitting...</span>';

        // Hide any previous messages
        formMessage.style.display = 'none';

        // Get form data
        const formData = new FormData(form);

        try {
            // Submit form via AJAX
            const response = await fetch('submit-form.php', {
                method: 'POST',
                body: formData
            });

            // Debug: log raw response
            const responseText = await response.text();
            console.log('Raw response:', responseText);

            // Try to parse as JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('JSON parse error. Full response was:', responseText);
                alert('Server Error: ' + responseText.substring(0, 200));
                throw new Error('Server returned invalid response');
            }

            // Display message
            formMessage.style.display = 'block';

            if (result.success) {
                // Success
                formMessage.style.background = 'linear-gradient(135deg, rgba(92, 114, 59, 0.2), rgba(36, 61, 23, 0.2))';
                formMessage.style.border = '1px solid #5c723b';
                formMessage.style.color = '#243d17';
                formMessage.textContent = result.message;

                // Reset form
                form.reset();

                // Optional: Redirect after success
                // setTimeout(() => {
                //     window.location.href = 'thank-you.html';
                // }, 2000);

            } else {
                // Error
                formMessage.style.background = 'linear-gradient(135deg, rgba(171, 50, 20, 0.2), rgba(240, 155, 64, 0.2))';
                formMessage.style.border = '1px solid #ab3214';
                formMessage.style.color = '#ab3214';
                formMessage.textContent = result.message || 'An error occurred. Please try again.';
            }

        } catch (error) {
            // Network or other error
            formMessage.style.display = 'block';
            formMessage.style.background = 'linear-gradient(135deg, rgba(171, 50, 20, 0.2), rgba(240, 155, 64, 0.2))';
            formMessage.style.border = '1px solid #ab3214';
            formMessage.style.color = '#ab3214';
            formMessage.textContent = 'Connection error. Please check your internet and try again.';

            console.error('Form submission error:', error);
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Submit</span>';
        }
    });

    // Optional: Real-time email validation
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.setCustomValidity('Please enter a valid email address');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Email validation helper
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});