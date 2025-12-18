<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Movie Booking</title>
    <link rel="stylesheet" href="RegistrationPage.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="HomePage.html">Home</a></li>
                <li><a href="movies.html">Movies</a></li>
                <li><a href="login.html">Login</a></li>
                <li><a href="register.html">Register</a></li>
                <li><a href="contact.html">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container"> 
            
            
        <form id="registerForm">
            <h1>Register</h1>
            <input type="text" id="fullName" placeholder="Full Name" required>
            <input type="email" id="email" placeholder="Email" required>
            <input type="password" id="password" placeholder="Password" required>
            <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
            <input type="tel" id="phone" placeholder="Phone Number" required>
            <button type="submit">Register</button>
        </form>
        <p id="message"></p>
        <script>
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                if (password !== confirmPassword) {
                    document.getElementById('message').innerHTML = '<span class="error">Passwords do not match!</span>';
                    return;
                }
                document.getElementById('message').innerHTML = '<span class="success">Account created successfully!</span>';
                // In real app: Send to backend
                setTimeout(() => window.location.href = 'login.html', 2000);
            });
        </script></div>
       
    </main>
    <footer>&copy; 2023 Movie Booking</footer>
</body>
</html>
