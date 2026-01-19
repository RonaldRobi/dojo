<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }
        .content {
            background-color: #ffffff;
            padding: 40px 30px;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e5e7eb;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .link-text {
            word-break: break-all;
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #6b7280;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#667eea" stroke-width="2">
                <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
        </div>
        <h1>Welcome to Droplets Dojo!</h1>
    </div>

    <div class="content">
        <h2>Complete Your Parent Registration</h2>
        
        <p>Thank you for your interest in joining Droplets Dojo! We're excited to have you as part of our martial arts community.</p>
        
        <p>To complete your registration as a parent, please click the button below. This link will be valid for <strong>24 hours</strong>.</p>
        
        <center>
            <a href="{{ $url }}" class="button">Complete Registration</a>
        </center>
        
        <div class="warning">
            <strong>⚠️ Important:</strong> If you did not request this registration, please ignore this email or contact us immediately.
        </div>
        
        <p>If the button above doesn't work, you can copy and paste this link into your browser:</p>
        <div class="link-text">
            {{ $url }}
        </div>
        
        <p>After completing your registration, you'll be able to:</p>
        <ul>
            <li>Link your children to your account</li>
            <li>View class schedules and attendance</li>
            <li>Track your children's progress</li>
            <li>Receive notifications and announcements</li>
            <li>Manage payments and memberships</li>
        </ul>
        
        <p>If you have any questions, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>
        <strong>Droplets Dojo Team</strong></p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Droplets Dojo. All rights reserved.</p>
        <p>This email was sent to you as part of your registration process.</p>
    </div>
</body>
</html>

