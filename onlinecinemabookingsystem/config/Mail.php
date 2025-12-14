<?php
// config/Mail.php
// Simple email sending class for XAMPP

class Mail {
    /**
     * Send email using XAMPP sendmail
     */
    public static function send($to, $subject, $message) {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . APP_NAME . " <noreply@cinebook.local>\r\n";
        $headers .= "Reply-To: noreply@cinebook.local\r\n";
        
        return mail($to, $subject, $message, $headers);
    }

    /**
     * Send password reset email
     */
    public static function sendPasswordReset($email, $name, $resetToken) {
        $resetLink = BASE_URL . '/public/index.php?url=resetPassword&token=' . $resetToken;
        
        $subject = 'Password Reset - ' . APP_NAME;
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; border-radius: 8px; }
                .header { background: linear-gradient(to right, #4f46e5, #7c3aed); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { padding: 30px; }
                .button { display: inline-block; background: #4f46e5; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { background: #f9fafb; padding: 15px; text-align: center; font-size: 12px; color: #6b7280; border-radius: 0 0 8px 8px; }
                .link { word-break: break-all; color: #4f46e5; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>" . APP_NAME . "</h1>
                    <p>Password Reset Request</p>
                </div>
                <div class='content'>
                    <p>Hi $name,</p>
                    <p>We received a request to reset your password. Click the button below to proceed:</p>
                    <center>
                        <a href='$resetLink' class='button'>Reset Your Password</a>
                    </center>
                    <p>Or copy and paste this link in your browser:</p>
                    <p class='link'>$resetLink</p>
                    <p><strong>Important:</strong> This link will expire in 1 hour.</p>
                    <p>If you didn't request a password reset, you can safely ignore this email.</p>
                    <p>Best regards,<br>" . APP_NAME . " Team</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " " . APP_NAME . ". All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        return self::send($email, $subject, $message);
    }
}
?>