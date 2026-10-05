/**
 * Form Handler JavaScript
 * Handles AJAX form submission for registration form
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const submitBtn = document.getElementById('submitBtn');
    const formMessage = document.getElementById('formMessage');

    if (!form) return;

    const submitLabel = submitBtn.innerHTML;

    function showMessage(type, text) {
        formMessage.className = 'form-message ' + type;
        formMessage.textContent = text;
        formMessage.hidden = false;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        // Hide any previous messages
        formMessage.hidden = true;

        // Get form data
        const formData = new FormData(form);

        try {
            // Submit form via AJAX
            const response = await fetch('submit-form.php', {
                method: 'POST',
                body: formData
            });
            const responseText = await response.text();

            // Try to parse as JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('JSON parse error. Full response was:', responseText);
                showMessage('error', 'Something went wrong on our side. Please try again later.');
                return;
            }

            if (result.success) {
                showMessage('success', result.message);
                form.reset();
            } else {
                showMessage('error', result.message || 'An error occurred. Please try again.');
            }

        } catch (error) {
            // Network or other error
            showMessage('error', 'Connection error. Please check your internet and try again.');
            console.error('Form submission error:', error);
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitLabel;
        }
    });

    // Real-time email validation
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
