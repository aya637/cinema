<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Movie Booking</title>
    <link rel="stylesheet" href="LoginPage.css"> 
     <link rel="stylesheet" href="HomePage.css"><!-- Or login.css if separate -->
</head>
<body class="login-page"> <!-- Optional class for page-specific styles -->
    <header>
        <nav>
            <ul>
                <li><a href="HomePage.html">Home</a></li>
                <li><a href="movies.html">Movies</a></li>
                <li><a href="LoginPage.html">Login</a></li>
                <li><a href="RegistrationPage.html">Register</a></li>
                <li><a href="contact.html">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-wrapper">
            <h1>Login</h1>
            <form id="loginForm">
                <input type="email" id="email" placeholder="Email" required>
                <input type="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p><a href="#">Forgot Password?</a></p>
            <p>Don't have an account? <a href="RegistrationPage.html">Register new account</a></p>
            <p id="message"></p>
        </div>
    </main>
    <footer>&copy; 2023 Movie Booking</footer>
    <script src="utils.js"></script>
    <script src="login.js"></script>
</body>
</html>