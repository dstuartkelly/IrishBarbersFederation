        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form from submitting normally
            
            // Get form values
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const experience = document.getElementById('experience').value;
            
            // Build the alert message
            let message = 'Form Submitted!\n\n';
            message += 'Name: ' + name + '\n';
            message += 'Email: ' + email + '\n';
            message += 'Phone: ' + (phone || 'Not provided') + '\n';
            message += 'Years Experience: ' + (experience || 'Not provided');
            
            // Display the alert
            alert(message);
            
            // Optional: Reset the form after showing the alert
            // this.reset();
        });