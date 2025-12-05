        document.getElementById('userForm').addEventListener('submit', function(f) 
        {
            // get values from the form
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const experience = document.getElementById('experience').value;
            
            // make the alert message
            let message = 'Form Submitted!\n\n';
            message += 'Name: ' + name + '\n';
            message += 'Email: ' + email + '\n';
            message += 'Phone: ' + phone + '\n';
            message += 'Years Experience: ' + experience ;
            
            // display alert
            alert(message);
        });