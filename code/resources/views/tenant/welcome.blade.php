<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->id ?? 'Tenant' }} - ComandaFlow</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        .tenant-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            backdrop-filter: blur(10px);
        }
        .tenant-name {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-transform: capitalize;
        }
        .domain {
            font-size: 1.2em;
            opacity: 0.8;
            margin-bottom: 20px;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .feature {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        .feature h3 {
            margin-top: 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="tenant-info">
            <h1 class="tenant-name">{{ $tenant->id ?? 'Welcome' }}</h1>
            <p class="domain">{{ $domain }}</p>
            <p>Your dedicated ComandaFlow workspace is ready!</p>
        </div>

        <div class="features">
            <div class="feature">
                <h3>🕒 Time Tracking</h3>
                <p>Track your team's working hours efficiently</p>
            </div>
            <div class="feature">
                <h3>📊 Analytics</h3>
                <p>Get insights into your productivity</p>
            </div>
            <div class="feature">
                <h3>👥 Team Management</h3>
                <p>Manage your team members and permissions</p>
            </div>
            <div class="feature">
                <h3>🔒 Secure</h3>
                <p>Your data is isolated and secure</p>
            </div>
        </div>

        <div>
            <a href="/dashboard" class="btn">Go to Dashboard</a>
            <a href="http://comandaflow.local" class="btn">Back to Central</a>
        </div>
    </div>
</body>
</html>
