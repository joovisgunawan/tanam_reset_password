<?php
include 'dbconnect.php';

$email = isset($_GET['email']) ? $_GET['email'] : '';
$errorMessage = '';

if (empty($email)) {
    $errorMessage = 'Error: Email parameter is missing or empty.';
} else {
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $errorMessage = 'Error: Invalid email format.';
    }
}

// Output the email securely
echo htmlspecialchars($email);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-message {
            color: #FF0000;
            background-color: #FFDDDD;
            border: 1px solid #FF0000;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($errorMessage)) : ?>
            <div class="error-message">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <h1>Tanam Reset Password</h1>
        <form id="resetPasswordForm">
            <div class="input-group">
                <input type="password" placeholder="New Password" name="password" required>
                <button type="button" id="show1" onclick="togglePasswordVisibility()">Show</button>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Confirm New Password" name="confirmPassword" required>
                <button type="button" id="show2" onclick="toggleConfirmPasswordVisibility()">Show</button>
            </div>
            <button type="submit" id="submitButton">Reset Password</button>
        </form>
    </div>

    <script>
        let showPassword = false;
        let showConfirmPassword = false;
        let password;
        let confirmPassword;

        function togglePasswordVisibility() {
            showPassword = !showPassword;
            const passwordField = document.querySelector('input[name="password"]');
            passwordField.type = showPassword ? 'text' : 'password';
            const button = document.getElementById('show1');
            button.textContent = showPassword ? 'Hide' : 'Show';
        }

        function toggleConfirmPasswordVisibility() {
            showConfirmPassword = !showConfirmPassword;
            const confirmPasswordField = document.querySelector('input[name="confirmPassword"]');
            confirmPasswordField.type = showConfirmPassword ? 'text' : 'password';
            const button = document.getElementById('show2');
            button.textContent = showConfirmPassword ? 'Hide' : 'Show';
        }

        function validateForm() {
            password = document.querySelector('input[name="password"]').value;
            confirmPassword = document.querySelector('input[name="confirmPassword"]').value;

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = '<?php echo addslashes($errorMessage); ?>';
            const submitButton = document.getElementById('submitButton');
            if (errorMessage !== '') {
                submitButton.disabled = true;
            }
        });

        document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
            event.preventDefault();

            if (!validateForm()) {
                return;
            }

            const formData = new FormData();
            formData.append('password', password);
            formData.append('confirmPassword', confirmPassword);
            formData.append('email', '<?php echo addslashes($email); ?>');

            fetch('reset.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('resetPasswordForm').reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    </script>
</body>
</html>
