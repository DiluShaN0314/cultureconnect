<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper {

    /**
     * Send a welcome email to a newly created user
     *
     * @param string $to Recipient email
     * @param string $name Recipient name
     * @param string $role User role (resident, sme, admin)
     * @return bool Success or failure
     */
    public static function sendWelcomeEmail($to, $name, $role) {

        $mail = new PHPMailer(true);

        try {

            $subject = "Welcome to CultureConnect!";

            $role_label = ucfirst($role);
            if ($role === 'user') {
                $role_label = "Resident";
            }

            $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/cultureconnect";

            $message = "
            <html>
            <head>
                <style>
                    .container { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333; }
                    .header { background: #3498db; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { padding: 30px; border: 1px solid #eee; border-top: none; border-radius: 0 0 8px 8px; line-height: 1.6; }
                    .btn { display: inline-block; padding: 12px 25px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
                    .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>CultureConnect</h1>
                    </div>
                    <div class='content'>
                        <h2>Hello " . htmlspecialchars($name) . ",</h2>
                        <p>Welcome to <strong>CultureConnect</strong>! We are thrilled to have you join our community as a <strong>" . $role_label . "</strong>.</p>
                        <p>Our platform connects residents and small businesses to build a stronger cultural community.</p>

                        <center>
                            <a href='{$base_url}/login' class='btn'>Log In to Your Account</a>
                        </center>

                        <p>If you have any questions, feel free to reply to this email.</p>
                        <p>Regards,<br>The CultureConnect Team</p>
                    </div>
                    <div class='footer'>
                        &copy; " . date('Y') . " CultureConnect. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com';
            $mail->Password   = 'your_app_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your_email@gmail.com', 'CultureConnect');
            $mail->addAddress($to, $name);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            return $mail->send();

        } catch (Exception $e) {
            error_log("Email Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send a password reset email
     *
     * @param string $to Recipient email
     * @param string $name Recipient name
     * @param string $token Reset Token
     * @return bool Success or failure
     */
    public static function sendPasswordResetEmail($to, $name, $token) {
        $mail = new PHPMailer(true);

        try {
            $subject = "CultureConnect - Password Reset Request";
            $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/cultureconnect";
            $reset_link = "{$base_url}/reset-password?token={$token}";

            $message = "
            <html>
            <head>
                <style>
                    .container { font-family: 'Segoe UI', Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333; }
                    .header { background: #3498db; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                    .content { padding: 30px; border: 1px solid #eee; border-top: none; border-radius: 0 0 8px 8px; line-height: 1.6; }
                    .btn { display: inline-block; padding: 12px 25px; background: #e74c3c; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
                    .footer { text-align: center; font-size: 12px; color: #777; margin-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>CultureConnect</h1>
                    </div>
                    <div class='content'>
                        <h2>Hello " . htmlspecialchars($name) . ",</h2>
                        <p>We received a request to reset your password for your CultureConnect account.</p>
                        <p>Click the button below to set a new password. This link will expire in 1 hour.</p>

                        <center>
                            <a href='{$reset_link}' class='btn'>Reset My Password</a>
                        </center>

                        <p>If you did not request a password reset, you can safely ignore this email.</p>
                        <p>Regards,<br>The CultureConnect Team</p>
                    </div>
                    <div class='footer'>
                        &copy; " . date('Y') . " CultureConnect. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ";

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com';
            $mail->Password   = 'your_app_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;


            $mail->setFrom('your_email@gmail.com', 'CultureConnect Security');
            $mail->addAddress($to, $name);


            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            return $mail->send();

        } catch (Exception $e) {
            error_log("Reset Email Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}