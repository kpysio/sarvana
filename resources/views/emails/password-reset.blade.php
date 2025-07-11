<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset - Sarvana</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f97316;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .password-box {
            background-color: #e5e7eb;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            color: #374151;
        }
        .warning {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #92400e;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sarvana - Password Reset</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>Your password has been reset by an administrator. Here is your new password:</p>
        
        <div class="password-box">
            {{ $password }}
        </div>
        
        <div class="warning">
            <strong>Important:</strong> For security reasons, please change your password immediately after logging in.
        </div>
        
        <p>You can log in to your account using this new password. We recommend changing it to something you can remember.</p>
        
        <p>If you did not request this password reset, please contact our support team immediately.</p>
        
        <p>Best regards,<br>The Sarvana Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from Sarvana. Please do not reply to this email.</p>
        <p>If you have any questions, please contact our support team.</p>
    </div>
</body>
</html> 