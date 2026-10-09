<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #0F172A;
            color: #F8FAFC;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 540px;
            margin: 0 auto;
            background-color: #1E293B;
            border-radius: 16px;
            border: 1px solid #334155;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
        .header {
            background-color: #1F3A5F;
            padding: 30px;
            text-align: center;
            border-bottom: 2px solid #3B82F6;
        }
        .header h1 {
            color: #FFFFFF;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #93C5FD;
            margin: 6px 0 0 0;
            font-size: 14px;
        }
        .content {
            padding: 36px 30px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #F1F5F9;
            margin-bottom: 12px;
            text-align: left;
        }
        .text {
            font-size: 15px;
            color: #94A3B8;
            line-height: 1.6;
            margin-bottom: 28px;
            text-align: left;
        }
        .otp-container {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            border: 2px dashed #3B82F6;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #60A5FA;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #F59E0B;
            text-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
            margin: 0;
            user-select: all;
        }
        .expiry-warning {
            font-size: 13px;
            color: #EF4444;
            margin-top: 12px;
            font-weight: 500;
        }
        .footer {
            background-color: #0F172A;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #64748B;
            border-top: 1px solid #334155;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🇵🇭 PhilNITS Prep</h1>
            <p>FE & AP Exam Review Platform</p>
        </div>
        <div class="content">
            <div class="greeting">Hello, {{ $userName }}!</div>
            <div class="text">
                Thank you for registering on PhilNITS Prep. To complete your account activation and verify your email address, please use the 6-digit verification code below:
            </div>

            <div class="otp-container">
                <div class="otp-label">Verification Code</div>
                <div class="otp-code">{{ $otpCode }}</div>
                <div class="expiry-warning">⏰ This code will expire in 10 minutes.</div>
            </div>

            <div class="text" style="font-size: 13px; color: #64748B; margin-bottom: 0;">
                If you did not request this verification code, please ignore this email or contact support if you have concerns.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} PhilNITS Prep. All rights reserved.
        </div>
    </div>
</body>
</html>
