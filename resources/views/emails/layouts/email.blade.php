<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SmartHR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 30px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .button {
            display: inline-block;
            background-color: #2563EB;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #2563EB;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏢 SmartHR</h1>
            <p>Solutions RH innovantes</p>
        </div>

        <div class="content">
            @yield('email-content')
        </div>

        <div class="footer">
            <p>SmartHR - Lomé, Togo</p>
            <p>📧 emileanalene03@gmail.com | 📞 +228 99951693</p>
            <p style="margin-top: 10px; font-size: 11px;">
                Cet email est un message automatique, merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>